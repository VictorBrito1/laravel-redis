@extends('base_layout')
@section('title', 'Listagem de usuários')

@section('content')
    <div class="d-flex justify-content-between mb-5">
        <h2>Listagem de usuários</h2>
        <a href="{{ route('users.create') }}" class="btn btn-success">Adicionar usuário</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                <h5>
                    <i class="icon fas fa-ban"></i>
                    Ocorreu um erro!
                </h5>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table class="table table-bordered table-hover">
        <tr class="thead-dark">
            <th>CPF</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>Ações</th>
        </tr>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->cpf }}</td>
            <td>{{ $user->nome }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->telefone }}</td>
            <td class="d-flex">
                <a href="{{ route('users.edit', ['user' => $user->cpf]) }}" class="btn btn-primary btn-sm mr-2">Editar</a>

                <form action="{{ route('users.destroy', ['user' => $user->cpf]) }}" onsubmit="return confirm('Tem certeza que deseja excluir?')" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Excluir</button>
                </form>
            </td>
        </tr>
    @endforeach
    </table>
@endsection
