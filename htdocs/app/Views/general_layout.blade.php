<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="icon" type="image/png" href="/public/Misc/Images/Icons/wj.jpeg">
    <link rel="stylesheet" href="/public/Misc/CSS/style.css">
    @yield('head')
</head>
<body>
<header>
    <div class="navbar">
        <nav>
            <ul>
                <li><a href="/">Home</a></li>
                @yield('navbar')
            </ul>
        </nav>
        <img src="../../public/Misc/Images/logo-webjump-footer.png" alt="" class="logowj" width="200px">
    </div>
    @yield('header')
</header>


@section('footer')
    <footer>
    </footer>
@show
</body>
</html>