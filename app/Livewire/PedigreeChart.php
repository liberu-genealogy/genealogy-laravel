<?php

namespace App\Livewire;

use Throwable;
use App\Models\Person;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use App\Filament\App\Resources\PersonResource;

class PedigreeChart extends Component
{
    public ?int $rootPersonId = null;

    public int $generations = 4;

    public bool $showDates = true;

    /**
     * The tree data used to render the chart.
     * @var array<mixed>
     */
    public array $tree = [];

    /**
     * In-request cache of people with loaded parents to reduce queries
     * @var array<int, Person>
     */
    protected array $personCache = [];

    public function mount(?int $rootPersonId = null, int $generations = 4): void
    {
        $this->rootPersonId = $rootPersonId ?? Person::query()->value('id');
        $this->generations = max(3, min(8, $generations));
        $this->hydrateTree();
    }

    public function setRootPerson(int $personId): void
    {
        $this->rootPersonId = $personId;
        $this->hydrateTree();
        $this->dispatch('refreshChart');
    }

    public function setGenerations(int $generations): void
    {
        $this->generations = max(3, min(8, $generations));
        $this->hydrateTree();
        $this->dispatch('refreshChart');
    }

    public function toggleDates(): void
    {
        $this->showDates = ! $this->showDates;
        $this->dispatch('refreshChart');
    }

    public function expandPerson(int $personId): void
    {
        $this->setRootPerson($personId);
    }

    protected function hydrateTree(): void
    {
        if (! $this->rootPersonId) {
            $this->tree = [];
            return;
        }

        $root = $this->fetchPersonWithParents($this->rootPersonId);

        if (! $root) {
            $this->tree = [];
            return;
        }

        $this->tree = $this->buildTree($root, 1, $this->generations);
    }

    protected function buildTree(Person $person, int $currentGen, int $maxGen): array
    {
        $node = [
            'id' => $person->id,
            'name' => $person->fullname(),
            'sex' => $person->sex,
            'birth' => optional($person->birthday)?->format('Y-m-d'),
            'death' => optional($person->deathday)?->format('Y-m-d'),
            'image' => method_exists($person, 'profileImageUrl') ? $person->profileImageUrl() : asset('images/default-avatar.svg'),
            'father' => null,
            'mother' => null,
        ];

        if ($currentGen < $maxGen) {
            $family = $person->childInFamily()->with(['husband', 'wife'])->first();
            if ($family?->husband) {
                $father = $this->fetchPersonWithParents($family->husband->id);
                $node['father'] = $this->buildTree($father, $currentGen + 1, $maxGen);
            }
            if ($family?->wife) {
                $mother = $this->fetchPersonWithParents($family->wife->id);
                $node['mother'] = $this->buildTree($mother, $currentGen + 1, $maxGen);
            }
        }

        return $node;
    }

    /**
     * Return HTML for the pedigree tree.
     */
    public function renderPedigreeTree(array $node, int $generation = 1): string
    {
        if (empty($node)) {
            return '<div class="empty-person-box">No data</div>';
        }

        $sexClass = $node['sex'] === 'F' ? 'female' : 'male';
        $datesHtml = '';
        if ($this->showDates) {
            $birth = $node['birth'] ? htmlspecialchars($node['birth'], ENT_QUOTES, 'UTF-8') : '';
            $death = $node['death'] ? htmlspecialchars($node['death'], ENT_QUOTES, 'UTF-8') : '';
            $datesHtml = "<div class=\"person-dates\">{$birth}" . ($birth && $death ? ' – ' : ($death ? ' – ' : '')) . "{$death}</div>";
        }

        $name = htmlspecialchars($node['name'] ?? 'Unknown', ENT_QUOTES, 'UTF-8');
        $imageSrc = htmlspecialchars($node['image'] ?? asset('images/default-avatar.svg'), ENT_QUOTES, 'UTF-8');
        $imageAlt = htmlspecialchars($node['name'] ?? 'Person', ENT_QUOTES, 'UTF-8');
        $editUrl = htmlspecialchars($this->personEditUrl($node['id']), ENT_QUOTES, 'UTF-8');
        // include thumbnail image in person box
        $personHtml = "<div class=\"person-box {$sexClass}\" onclick=\"expandPerson({$node['id']})\">".
            "<div class=\"person-thumb\"><img src=\"{$imageSrc}\" alt=\"{$imageAlt}\" loading=\"lazy\"/></div>".
            "<div class=\"person-name\"><a href=\"{$editUrl}\" class=\"hover:underline\" target=\"_blank\" rel=\"noopener\">{$name}</a></div>".
            $datesHtml.
            "<button class=\"expand-btn\" title=\"Set as root\" onclick=\"event.stopPropagation(); expandPerson({$node['id']});\">+</button>".
            "</div>";

        $parentsHtml = '';
        if (!empty($node['father']) || !empty($node['mother'])) {
            $fatherHtml = !empty($node['father'])
                ? $this->renderPedigreeTree($node['father'], $generation + 1)
                : '<div class="empty-person-box">Father unknown</div>';
            $motherHtml = !empty($node['mother'])
                ? $this->renderPedigreeTree($node['mother'], $generation + 1)
                : '<div class="empty-person-box">Mother unknown</div>';

            $parentsHtml = "<div class=\"parents-container\">".
                "<div class=\"parent-branch father-branch\">{$fatherHtml}</div>".
                "<div class=\"parent-branch mother-branch\">{$motherHtml}</div>".
                "</div>";
        }

        return "<div class=\"generation-level\">{$personHtml}{$parentsHtml}</div>";
    }

    public function render(): View
    {
        return view('livewire.pedigree-chart');
    }

    protected function fetchPersonWithParents(int $id): ?Person
    {
        if (isset($this->personCache[$id])) {
            return $this->personCache[$id];
        }
        $person = Person::with(['childInFamily.husband', 'childInFamily.wife'])->find($id);
        if ($person) {
            $this->personCache[$id] = $person;
        }
        return $person;
    }

    public function getPeopleListProperty()
    {
        return Person::getListOptimized();
    }

    protected function personEditUrl(int $id): string
    {
        try {
            return PersonResource::getUrl('edit', ['record' => $id]);
        } catch (Throwable $e) {
            return url('/');
        }
    }
}
