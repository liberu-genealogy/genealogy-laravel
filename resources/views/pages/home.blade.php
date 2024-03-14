/**
 * View file for the home page.
 *
 * This file renders the home page of the genealogy application.
 * It extends the default layout and includes a content section
 * that provides an overview of the application and its features.
 */
@extends('layouts.default')

@section('content')
    /**
     * The content section of the home page.
     *
     * This section displays the main content of the home page.
     * It includes information about the genealogy application and its features.
     * Users can navigate through the website and access various functionalities from this section.
     */
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Welcome to the Genealogy Application</h1>
        <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla facilisi. Sed euismod, nunc id aliquet ultrices, nisl nunc tincidunt nunc, id lacinia nunc nunc vitae nunc. Sed id leo auctor, tincidunt nunc id, aliquet nunc. Nulla facilisi. Sed euismod, nunc id aliquet ultrices, nisl nunc tincidunt nunc, id lacinia nunc nunc vitae nunc. Sed id leo auctor, tincidunt nunc id, aliquet nunc.</p>
        <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla facilisi. Sed euismod, nunc id aliquet ultrices, nisl nunc tincidunt nunc, id lacinia nunc nunc vitae nunc. Sed id leo auctor, tincidunt nunc id, aliquet nunc. Nulla facilisi. Sed euismod, nunc id aliquet ultrices, nisl nunc tincidunt nunc, id lacinia nunc nunc vitae nunc. Sed id leo auctor, tincidunt nunc id, aliquet nunc.</p>
    </div>
@endsection
