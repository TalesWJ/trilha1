<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="icon" type="image/png" href="/public/Misc/Images/Icons/wj.jpeg">
    @yield('head')
</head>
<body>
<header>
    <div class="navbar">
        <nav>
            <ul>
                <li><a href="/">Home</a></li>
                @yield('navbaritems')
            </ul>
        </nav>
        @yield('saldo')
        <img src="../../public/Misc/Images/logo-webjump-footer.png" alt="" class="logowj" width="200px">
    </div>
    @yield('alert')
    @yield('header')
</header>

<section class="sectionDash">
    @yield('main')
</section>

@section('footer')
    <footer>
    </footer>
@show
</body>
</html>