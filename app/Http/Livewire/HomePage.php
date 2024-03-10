&lt;?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Post;

class HomePage extends Component
{
    public $recentPosts;

    public function mount()
    {
        $this->fetchRecentPosts();
    }

    public function fetchRecentPosts()
    {
        $this->recentPosts = Post::latest()->take(5)->get();
    }

    public function render()
    {
        return view('livewire.home-page', [
            'recentPosts' => $this->recentPosts,
        ]);
    }
}
