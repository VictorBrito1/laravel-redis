<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $users = User::all();
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

        return redirect()->route('users.index');
    }

    /**
     * @param $cpf
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($cpf)
    {
        $user = User::find($cpf);

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
        }

        return redirect()->route('users.index');
    }
}
