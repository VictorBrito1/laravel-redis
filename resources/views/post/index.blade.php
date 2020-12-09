@extends('base_layout')
@section('title', 'Listagem de posts')

@section('content')
    <div class="d-flex justify-content-between mb-5">
        <h2>Listagem de posts</h2>
        <a href="{{ route('posts.create') }}" class="btn btn-success">Adicionar post</a>
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
            <th>Id</th>
            <th>Título</th>
            <th>Corpo</th>
            <th>Usuário</th>
            <th>Data de publicação</th>
            <th>Ações</th>
        </tr>
    @foreach($posts as $post)
        <tr>
            <td>{{ $post->id }}</td>
            <td>{{ $post->titulo }}</td>
            <td>{{ $post->corpo }}</td>
            <td>{{ $post->usuario->nome }}</td>
            <td>{{ $post->data_publicacao->format('d/m/Y H:i:s') }}</td>
            <td class="d-flex">
                <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary btn-sm mr-2">Editar</a>

                <form action="{{ route('posts.destroy', ['post' => $post->id]) }}" onsubmit="return confirm('Tem certeza que deseja excluir?')" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Excluir</button>
                </form>
            </td>
        </tr>
    @endforeach
    </table>
@endsection
