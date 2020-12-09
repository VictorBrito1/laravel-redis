<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redis - @yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="container">
    <header class="headerP">
        @section('header')
            <nav class="navbar navbar-light navbar-expand-md fixed-top" style="background-color: #fd5c5c;">
                <a class="navbar-brand" href="/"  style="color: #FFFFFF" >BD2 - Redis</a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav" >
                        <li class="nav-item">
                            <a class="nav-link" href="/" style="color: #FFFFFF" >Home</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" style="color: #FFFFFF" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Usuários
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('users.index') }}">Listagem de usuários</a>
                                <a class="dropdown-item" href="{{ route('users.create') }}">Cadastrar usuário</a>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" style="color: #FFFFFF" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Posts
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('posts.index') }}">Listagem de posts</a>
                                <a class="dropdown-item" href="{{ route('posts.create') }}">Criar post</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        @show
    </header>

    @yield('content')

    <footer class="footer">
        <p class="footerP">&copy; 2020 - BD2 Redis.</p>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
</div>
</body>
</html>
