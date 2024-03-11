&lt;?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Filament\Resources\GedcomResource;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ExportGedCom;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;

class GedcomResourceTest extends TestCase
{
    use WithFaker;

    public function testExportActionTriggersExportProcess()
    {
        Bus::fake();

        $user = Auth::loginUsingId($this->faker->randomDigitNotNull);

        $expectedFileName = now()->format('Y-m-d_His') . '_family_tree.ged';

        GedcomResource::exportGedcom();

        Bus::assertDispatched(ExportGedCom::class, function ($job) use ($user, $expectedFileName) {
            return $job->file === $expectedFileName && $job->user->id === $user->id;
        });
    }
}
