<nav class="bg-gray-900 fixed w-full z-10 mb-8">
    <div class="container mx-auto flex justify-between items-center py-4">
        <a class="navbar-brand flex items-center" href="/">
            <img src="{{ asset('/build/images/logo1.svg') }}" alt="Logo" class="h-8">
            <span class="ml-2 text-white">{{ config('app.name') }}</span>
        </a>
        <button class="navbar-toggler lg:hidden text-white focus:outline-none">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="hidden lg:flex lg:items-center lg:w-auto">
            <ul class="navbar-nav ml-auto flex space-x-4">
                <li class="nav-item">
                    <a class="nav-link text-white hover:bg-transparent" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white hover:bg-transparent" href="#">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white hover:bg-transparent" href="#">Pricing</a>
                </li>
                <li class="nav-item dropdown" id="moreDropdown">
                    <a class="nav-link dropdown-toggle text-white hover:bg-transparent" href="#" id="navbarDropdownMenuLink" role="button" aria-expanded="false" onclick="toggleDropdown()">
                        More
                    </a>
                    <ul class="dropdown-menu hidden" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="#">About Us</a></li>
                        <li><a class="dropdown-item" href="/contact">Contact</a></li>
                    </ul>
                </li>
                @if(auth()->check())
                    <li class="nav-item">
                        <span class="text-white">
                            Welcome, {{ auth()->user()->name }}
                        </span>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link text-white hover:bg-transparent" href="/admin/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white hover:bg-transparent" href="/admin/register">Register</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<script>
    function toggleDropdown() {
        var dropdownMenu = document.getElementById("moreDropdown").querySelector(".dropdown-menu");
        dropdownMenu.classList.toggle("hidden");
    }
</script>
