<div class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
            <img src="/build/images/logo1.svg" alt="Logo" style="height: 30px;">
Family Tree 365
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown navbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link navbar-button" aria-current="page" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link navbar-button" href="#">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link navbar-button" href="#">Pricing</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        More
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="#">About Us</a></li>
                        <li><a class="dropdown-item" href="/contact">Contact</a></li>
                    </ul>
                </li>
            </ul>
            @if(Auth::check())
                <span class="navbar-text">
                    Welcome, {{ Auth::user()->name }}
                </span>
            @else
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/register">Register</a>
                    </li>
                </ul>
            @endif
        </div>
    </div>
<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
