<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Addr;
use App\Models\Author;
use App\Models\Chan;
use App\Models\Citation;
use App\Models\Dna;
use App\Models\DnaMatching;
use App\Models\Event;
use App\Models\Family;
use App\Models\FamilyEvent;
use App\Models\FamilySlgs;
use App\Models\Geneanum;
use App\Models\ImportJob;
use App\Models\MediaObject;
use App\Models\Note;
use App\Models\Person;
use App\Models\PersonAlia;
use App\Models\PersonAnci;
use App\Models\PersonAsso;
use App\Models\PersonEvent;
use App\Models\PersonLds;
use App\Models\PersonName;
use App\Models\PersonNameFone;
use App\Models\PersonNameRomn;
use App\Models\PersonSubm;
use App\Models\Place;
use App\Models\Publication;
use App\Models\Refn;
use App\Models\Repository;
use App\Models\Source;
use App\Models\SourceData;
use App\Models\SourceDataEven;
use App\Models\SourceRef;
use App\Models\SourceRefEven;
use App\Models\SourceRepo;
use App\Models\Subm;
use App\Models\Subn;
use App\Models\Tree;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(1)->create();
        //  Addr::factory(1)->create();
        // Author::factory(1)->create();
        // CalendarEvent::factory(1)->create();
        // Chan::factory(1)->create();
        // Chat::factory(1)->create();
        // ChatMessage::factory(1)->create();
        //Citation::factory(1)->create();
        // Company::factory(1)->create();
        // Dna::factory(1)->create();
        // DnaMatching::factory(1)->create();
        // Event::factory(1)->create();
        //Type::factory(4)->create();
        //Person::factory(2)->create();
        //FamilyEvent::factory(1)->create();
        //Family::factory(1)->create();
        // FamilySlgs::factory(1)->create();
        // ForumCategory::factory(1)->create();
        // ForumPostComment::factory(1)->create();
        // ForumPost::factory(1)->create();
        // ForumTopic::factory(1)->create();
        // Geneanum::factory(1)->create(); // skipping because of missing migration
        // ImportJob::factory(1)->create();
        // MediaObject::factory(1)->create();
        // MediaObjectFile::factory(1)->create();
        //  Note::factory(1)->create();
        // PersonAlia::factory(1)->create();
        //   PersonAnci::factory(1)->create();
        //   PersonAsso::factory(1)->create();
        // PersonDesi::factory(1)->create(); // missing model
        //   PersonEvent::factory(1)->create();
    //    PersonLds::factory(1)->create();
    //    PersonName::factory(1)->create();
    //    PersonNameFone::factory(1)->create();
   //     PersonNameRomn::factory(1)->create();
   //     PersonSubm::factory(1)->create();
   //     Place::factory(1)->create();
        // Provider::factory(1)->create();
    //    Publication::factory(1)->create();
    //    Refn::factory(1)->create();
        //   Repository::factory(1)->create();
   //     SourceDataEven::factory(1)->create();
        //   SourceData::factory(1)->create();
        //   Source::factory(1)->create();
        //  SourceRefEven::factory(1)->create();
        //   SourceRef::factory(1)->create();
        //   SourceRepo::factory(1)->create();
        //   Subm::factory(1)->create();
    //    Subn::factory(1)->create();
        //   Tree::factory(1)->create();
    }
}
