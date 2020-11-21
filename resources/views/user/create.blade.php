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

<form method="POST" action="{{ route('user.store') }}">
    @csrf
    <input type="text" name="cpf" placeholder="CPF"/>
    <input type="text" name="name" placeholder="Nome"/>
    <input type="email" name="email" placeholder="Email"/>
    <input type="text" name="phone" placeholder="Telefone"/>
    <input type="submit" value="Criar"/>
</form>
