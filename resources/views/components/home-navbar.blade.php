<div class="navbar bg-gray-900">
    <div class="container mx-auto">
        <a class="navbar-brand flex items-center" href="/">
            <img src="{{ asset('/build/images/logo1.svg') }}" alt="Logo" class="h-8">
            <span class="ml-2 text-white">{{ config('app.name') }}</span>
        </a>
        <button class="navbar-toggler" type="button" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Pricing</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" aria-expanded="false">
                        More
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="#">About Us</a></li>
                        <li><a class="dropdown-item" href="/contact">Contact</a></li>
                    </ul>
                </li>
            </ul>
            @if(auth()->check())
                <span class="text-white">
                    Welcome, {{ auth()->user()->name }}
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
</div>
