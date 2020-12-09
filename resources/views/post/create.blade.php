@extends('base_layout')
@section('title', 'Criação de post')

@section('content')
    <h2>Criação de post</h2>

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

    <form method="POST" action="{{ route('posts.store') }}">
        @csrf
        <div class="row">
            <div class="form-group col-6">
                <label for="titulo">Título</label>
                <input type="text" name="titulo" id="titulo" class="form-control" placeholder="Título" required/>
            </div>

            <div class="form-group col-6">
                <label for="usuario">Usuário</label>
                <select required name="usuario" id="usuario" class="form-control">
                    @foreach($users as $user)
                        <option value="{{ $user->cpf }}">{{ $user->nome }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-12">
                <label for="corpo">Corpo</label>
                <textarea class="form-control" id="corpo" name="corpo" required></textarea>
            </div>
        </div>

        <input type="submit" value="Criar" class="btn btn-success"/>
    </form>
@endsection
