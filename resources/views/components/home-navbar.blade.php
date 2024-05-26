<style>
    .btn-nav:hover {
        color: #f7f7f7; /* Couleur du texte au survol */
    }
</style>

    <nav class="bg-green-900 fixed w-full z-10">
        <div class="container mx-auto flex justify-between items-center py-4">
            <a class="navbar-brand flex items-center" href="/">
                <img src="{{ asset('/build/images/logo1.svg') }}" alt="Logo" class="h-8">
            </a>
            <button class="lg:hidden text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6 fill-current">
                    <path fill-rule="evenodd" d="M4 6h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 5h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 5h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"></path>
                </svg>
            </button>
            <div class="hidden lg:flex lg:items-center lg:w-auto">
                <a href="/" class="btn-nav">Home</a>
                <span class="mx-2"></span>
                <a href="/contact" class="btn-nav">Contact</a>
                <span class="mx-2"></span>
                <a href="/about" class="btn-nav">About</a>
                <span class="mx-2"></span>
                @if(auth()->check())
                    <div class="relative inline-block text-left">
                        <button onclick="toggleDropdown()" class="btn-nav">
                            <span class="text-white">
                                <strong>Welcome, {{ auth()->user()->name }}</strong>
                            </span>
                        </button>
                    </div>
                    <div class="ml-3">
                        <a href="{{ route('filament.admin.tenant')}}" class="btn-nav">Dashboard</a>
                    </div>
                @else
                    <a href="/login" class="btn-nav">Login</a>
                    <span class="mx-2"></span>
                    <a href="/register" class="btn-nav">Register</a>
                @endif
            </div>
        </div>
    </nav>

    <script>
        function toggleDropdown() {
            var dropdownMenu = document.getElementById("moreDropdown");
            dropdownMenu.classList.toggle("hidden");
        }
    </script>
