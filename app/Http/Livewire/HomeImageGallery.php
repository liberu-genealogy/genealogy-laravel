<?php

namespace App\Http\Livewire;

use Livewire\Component;

class HomeImageGallery extends Component
{
    public $images = [
        'https://example.com/image1.jpg',
        'https://example.com/image2.jpg',
        'https://example.com/image3.jpg',
    ];

    public function render()
    {
        return view('livewire.home-image-gallery', ['images' => $this->images]);
    }
}
