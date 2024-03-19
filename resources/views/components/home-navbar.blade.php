<nav class="bg-green-900 fixed w-full z-10 mb-8">
    <div class="container mx-auto flex justify-between items-center py-4">
        <a class="navbar-brand flex items-center" href="/">
            <img src="{{ asset('/build/images/logo1.svg') }}" alt="Logo" class="h-8">
        </a>
        <button class="navbar-toggler lg:hidden text-white focus:outline-none">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="hidden lg:flex lg:items-center lg:w-auto">

    <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full"><a href="/">Home</a></button></li>
    <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full"><a href="/contact">Contact</a></button></li>
    <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full"><a href="/about">About</a></button></li>
                @if(auth()->check())
                    <li class="nav-item">
                        <span class="text-white">
                            Welcome, {{ auth()->user()->name }}
                        </span>
                    </li>
                @else
    <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full"><a href="/admin/login">Login</a></button>
    <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full"><a href="/admin/register">Register</a></button>
                @endif
        </div>
    </div>
</nav>

<script>
    function toggleDropdown() {
        var dropdownMenu = document.getElementById("moreDropdown").querySelector(".dropdown-menu");
        dropdownMenu.classList.toggle("hidden");
    }
</script>
