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

<form method="POST" action="{{ route('user.update') }}">
    @csrf
    @method('PUT')
    <input type="text" name="cpf" placeholder="CPF" value="{{ $user->cpf }}" readonly/>
    <input type="text" name="name" placeholder="Nome" value="{{ $user->name }}"/>
    <input type="email" name="email" placeholder="Email" value="{{ $user->email }}"/>
    <input type="text" name="phone" placeholder="Telefone" value="{{ $user->phone }}"/>
    <input type="submit" value="Salvar"/>
</form>
