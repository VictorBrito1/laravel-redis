<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
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
            $cpf = explode(':', $key)[1];
            $stored = $this->redis->hgetall($key);
            $user = new User($cpf, $stored['name'], $stored['email'], $stored['phone']);
            $users[] = $user;
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
        $data = $request->only(['cpf', 'name', 'email', 'phone']);
        $user = $this->redis->hgetall("user:{$data['cpf']}");

        if ($user) {
            return Redirect::back()->withErrors(['CPF já cadastrado!']);
        }

        Redis::hmset('user:' . $data['cpf'], [
            'name'    => $data['name'],
            'email'   => $data['email'],
            'phone' => $data['phone'],
        ]);

        return redirect()->route('user.index');
    }

    /**
     * @param $cpf
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($cpf)
    {
        $stored = $this->redis->hgetall("user:{$cpf}");

        if (!$stored) {
            return redirect()->route('user.index')->withErrors(['Usuário não encontrado.']);
        }

        $user = new User($cpf, $stored['name'], $stored['email'], $stored['phone']);

        return view('user.edit', ['user' => $user]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $data = $request->only(['cpf', 'name', 'email', 'phone']);

        Redis::hmset('user:' . $data['cpf'], [
            'name'    => $data['name'],
            'email'   => $data['email'],
            'phone' => $data['phone'],
        ]);

        return redirect()->route('user.index');
    }

    public function find($id)
    {
//        $key = "user:{$id}";
//        $stored = Redis::hgetall($key);
//
//        if (!empty($stored)) {
//            return new User($stored['id'], $stored['name'], $stored['email'], $stored['phone']);
//        }
//
//        return false;
    }

    /**
     * @param $cpf
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($cpf)
    {
        $user = $this->redis->hgetall("user:{$cpf}");

        if ($user) {
            $this->redis->del("user:{$cpf}");
        }

        return redirect()->route('user.index');
    }
}
