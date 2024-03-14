<footer>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex justify-between items-center">
            <div>
                <a href="/" class="text-lg font-semibold">{{ config('app.name') }}</a>
            </div>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="/about" class="hover:text-gray-300">About Us</a></li>
                    <li><a href="/services" class="hover:text-gray-300">Services</a></li>
                    <li><a href="/contact" class="hover:text-gray-300">Contact</a></li>
                    <li><a href="https://wa.me/447706007407" class="hover:text-gray-300">Contact on WhatsApp</a></li>
                </ul>
            </nav>
        </div>
        <div class="text-center py-4">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</footer>
