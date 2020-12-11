<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /** @var \Illuminate\Redis\Connections\Connection $redis */
    private $redis;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->redis = Redis::connection();
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $keys = $this->redis->keys('user:*');
        $users = [];

        foreach ($keys as $key) {
            $stored = $this->redis->hgetall($key);

            $user = new User();
            $user->cpf = $stored['cpf'];
            $user->nome = $stored['nome'];
            $user->email = $stored['email'];
            $user->telefone = $stored['telefone'];
            $users[] = $user;
        }

        if ($users) {
            error_log('Usuários carregados do Redis.');
        } else {
            $users = User::all();

            if ($users) {
                foreach ($users as $user) {
                    $data['cpf'] = $user->cpf;
                    $data['nome'] = $user->nome;
                    $data['email'] = $user->email;
                    $data['telefone'] = $user->telefone;
                    $this->insertOrUpdateRedisUser($data);
                }

                error_log('Usuários inseridos no Redis.');
            }
        }

        return view('user.index', ['users' => $users]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->only(['cpf', 'nome', 'email', 'telefone']);

        $validator = Validator::make($data, [
            'nome' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'max:14', 'unique:usuarios'],
            'telefone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'email'],
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.create')
                ->withErrors($validator)
                ->withInput();
        }

        $user = new User();
        $user->cpf = $data['cpf'];
        $user->nome = $data['nome'];
        $user->telefone = $data['telefone'];
        $user->email = $data['email'];
        $user->save();

        $this->insertOrUpdateRedisUser($data, 'Usuário inserido com sucesso no Redis!');

        return redirect()->route('users.index');
    }

    /**
     * @param $cpf
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($cpf)
    {
        $stored = $this->redis->hgetall("user:{$cpf}");

        if ($stored) {
            $user = new User();
            $user->cpf = $stored['cpf'];
            $user->nome = $stored['nome'];
            $user->email = $stored['email'];
            $user->telefone = $stored['telefone'];

            error_log("Usuário '{$stored['nome']}' recuperado do Redis!");
        } else {
            $user = User::find($cpf);
        }

        if ($user) {
            return view('user.edit', ['user' => $user]);
        }

        return redirect()->route('users.index');
    }

    /**
     * @param Request $request
     * @param $cpf
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $cpf)
    {
        $user = User::find($cpf);

        if ($user) {
            $data = $request->only(['cpf', 'nome', 'email', 'telefone']);

            $validator = Validator::make($data, [
                'nome' => ['required', 'string', 'max:255'],
                'cpf' => ['required', 'string', 'max:14'],
                'telefone' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'max:255', 'email'],
            ]);

            if ($validator->fails()) {
                return redirect()->route('users.edit', ['user' => $cpf])->withInput()->withErrors($validator);
            }

            if ($user->cpf !== $data['cpf']) {
                $validatorCpf = Validator::make(['cpf' => $data['cpf']], ['cpf' => ['unique:usuarios']]);

                if ($validatorCpf->fails()) {
                    $validator->errors()->add('cpf', __('validation.unique', ['attribute' => 'cpf']));
                }

                $user->cpf = $data['cpf'];
            }

            $user->nome = $data['nome'];
            $user->telefone = $data['telefone'];
            $user->email = $data['email'];
            $user->save();

            $stored = $this->redis->hgetall("user:{$cpf}");

            if ($stored) {
                $this->insertOrUpdateRedisUser($data, "Usuário {$cpf} encontrado e alterado no Redis!");
            }
        }

        return redirect()->route('users.index');
    }

    /**
     * @param $cpf
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($cpf)
    {
        $user = User::find($cpf);

        if ($user) {
            $user->delete();

            $redisUser = $this->redis->hgetall("user:{$cpf}");

            if ($redisUser) {
                $this->redis->del("user:{$cpf}");
                error_log("Usuário '{$cpf}' removido do Redis.");
            }
        }

        return redirect()->route('users.index');
    }

    /**
     * @param $data
     * @param string $message
     */
    private function insertOrUpdateRedisUser($data, $message = '')
    {
        $this->redis->hmset("user:{$data['cpf']}", $data);

        if ($message) {
            error_log($message);
        }
    }
}
