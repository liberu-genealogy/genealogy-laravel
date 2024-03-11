&lt;?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Queue;
use App\Jobs\ExportGedCom;
use App\Models\User;
use App\Models\Person;
use App\Models\Family;
use Illuminate\Database\Eloquent\Collection;

class ExportGedComJobTest extends TestCase
{
    use RefreshDatabase;

    public function testHandleMethodProcessesDataCorrectly()
    {
        Storage::fake('local');
        Queue::fake();

        $user = User::factory()->create();
        $people = Person::factory()->count(5)->make();
        $families = Family::factory()->count(3)->make();

        Person::shouldReceive('all')->once()->andReturn(new Collection($people));
        Family::shouldReceive('all')->once()->andReturn(new Collection($families));

        $job = new ExportGedCom('test_file.ged', $user);
        $job->handle();

        Storage::disk('local')->assertExists('test_file.ged');
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function testHandleMethodWithNoData()
    {
        Storage::fake('local');
        Queue::fake();

        $user = User::factory()->create();

        Person::shouldReceive('all')->once()->andReturn(new Collection());
        Family::shouldReceive('all')->once()->andReturn(new Collection());

        $job = new ExportGedCom('empty_file.ged', $user);
        $job->handle();

        Storage::disk('local')->assertExists('empty_file.ged');
        $this->assertEmpty(Storage::disk('local')->get('empty_file.ged'));
    }

    public function testHandleMethodWithError()
    {
        Storage::fake('local');
        Queue::fake();

        $user = User::factory()->create();

        Person::shouldReceive('all')->andThrow(new \Exception('Database error'));
        Family::shouldReceive('all')->andReturn(new Collection());

        $job = new ExportGedCom('error_file.ged', $user);

        try {
            $job->handle();
            $this->fail("The expected exception was not thrown.");
        } catch (\Exception $e) {
            $this->assertEquals('Database error', $e->getMessage());
        }

        Storage::disk('local')->assertMissing('error_file.ged');
    }
}
