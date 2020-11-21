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

<a href="{{ route('user.create') }}">Adicionar novo</a>
<table>
    <tr>
        <th>CPF</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Telefone</th>
        <th>Ações</th>
    </tr>
@foreach($users as $user)
    <tr>
        <td>{{ $user->cpf }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->phone }}</td>
        <td>
            <form action="{{ route('user.delete', ['cpf' => $user->cpf]) }}" onsubmit="return confirm('Tem certeza que deseja excluir?')" method="POST">
                @csrf
                @method('DELETE')
                <button>Excluir</button>
            </form>

            <a href="{{ route('user.edit', ['cpf' => $user->cpf]) }}">Editar</a>
        </td>
    </tr>
@endforeach
</table>
