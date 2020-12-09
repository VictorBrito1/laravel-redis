@extends('base_layout')
@section('title', 'Edição de usuário')

@section('content')
    <h2>Edição de usuário</h2>

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

    <form method="POST" action="{{ route('users.update', ['user' => $user->cpf]) }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="form-group col-4">
                <label for="cpf">CPF</label>
                <input type="text" name="cpf" id="cpf" class="form-control" placeholder="CPF" maxlength="11" value="{{ $user->cpf }}" required readonly/>
            </div>

            <div class="form-group col-8">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" class="form-control" placeholder="Nome" value="{{ $user->nome }}" required/>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-9">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="E-mail" value="{{ $user->email }}" required/>
            </div>

            <div class="form-group col-3">
                <label for="telefone">Telefone</label>
                <input type="text" name="telefone" id="telefone" class="form-control" placeholder="Telefone" maxlength="15" value="{{ $user->telefone }}" required/>
            </div>
        </div>

        <input type="submit" value="Salvar" class="btn btn-success"/>
    </form>
@endsection
