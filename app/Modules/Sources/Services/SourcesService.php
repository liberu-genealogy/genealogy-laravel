<?php

namespace App\Modules\Sources\Services;

use App\Models\Source;
use App\Models\Repository;
use App\Models\Author;
use App\Models\Publication;
use App\Models\SourceData;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SourcesService
{
    /**
     * Create a new source.
     */
    public function createSource(array $data): Source
    {
        return Source::create([
            'title' => $data['title'],
            'author' => $data['author'] ?? null,
            'publication_date' => $data['publication_date'] ?? null,
            'publisher' => $data['publisher'] ?? null,
            'repository_id' => $data['repository_id'] ?? null,
            'call_number' => $data['call_number'] ?? null,
            'description' => $data['description'] ?? null,
            'url' => $data['url'] ?? null,
        ]);
    }

    /**
     * Update source information.
     */
    public function updateSource(Source $source, array $data): Source
    {
        $source->update($data);
        return $source->fresh();
    }

    /**
     * Search for sources.
     */
    public function searchSources(string $query, int $limit = 50): Collection
    {
        return Source::where('title', 'LIKE', "%{$query}%")
            ->orWhere('author', 'LIKE', "%{$query}%")
            ->orWhere('publisher', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get();
    }

    /**
     * Get sources with pagination.
     */
    public function getSourcesPaginated(int $perPage = 20): LengthAwarePaginator
    {
        return Source::with(['repository'])
            ->orderBy('title')
            ->paginate($perPage);
    }

    /**
     * Get sources by repository.
     */
    public function getSourcesByRepository(Repository $repository): Collection
    {
        return Source::where('repository_id', $repository->id)
            ->orderBy('title')
            ->get();
    }

    /**
     * Get sources by author.
     */
    public function getSourcesByAuthor(string $author): Collection
    {
        return Source::where('author', 'LIKE', "%{$author}%")
            ->orderBy('title')
            ->get();
    }

    /**
     * Format source citation.
     */
    public function formatCitation(Source $source, string $format = 'chicago'): string
    {
        return match ($format) {
            'chicago' => $this->formatChicagoCitation($source),
            'mla' => $this->formatMLACitation($source),
            'apa' => $this->formatAPACitation($source),
            default => $this->formatChicagoCitation($source),
        };
    }

    /**
     * Format Chicago style citation.
     */
    protected function formatChicagoCitation(Source $source): string
    {
        $parts = [];

        if ($source->author) {
            $parts[] = $source->author;
        }

        if ($source->title) {
            $parts[] = "\"{$source->title}\"";
        }

        if ($source->publisher) {
            $parts[] = $source->publisher;
        }

        if ($source->publication_date) {
            $parts[] = $source->publication_date;
        }

        if ($source->url) {
            $parts[] = "accessed " . now()->format('F j, Y') . ", {$source->url}";
        }

        return implode(', ', $parts) . '.';
    }

    /**
     * Format MLA style citation.
     */
    protected function formatMLACitation(Source $source): string
    {
        $parts = [];

        if ($source->author) {
            $parts[] = $source->author;
        }

        if ($source->title) {
            $parts[] = "\"{$source->title}\"";
        }

        if ($source->publisher) {
            $parts[] = $source->publisher;
        }

        if ($source->publication_date) {
            $parts[] = $source->publication_date;
        }

        if ($source->url) {
            $parts[] = "Web. " . now()->format('j M Y');
        }

        return implode(', ', $parts) . '.';
    }

    /**
     * Format APA style citation.
     */
    protected function formatAPACitation(Source $source): string
    {
        $parts = [];

        if ($source->author) {
            $parts[] = $source->author;
        }

        if ($source->publication_date) {
            $parts[] = "({$source->publication_date})";
        }

        if ($source->title) {
            $parts[] = $source->title;
        }

        if ($source->publisher) {
            $parts[] = $source->publisher;
        }

        if ($source->url) {
            $parts[] = "Retrieved from {$source->url}";
        }

        return implode('. ', $parts) . '.';
    }

    /**
     * Get source statistics.
     */
    public function getSourceStatistics(): array
    {
        $total = Source::count();
        $withRepositories = Source::whereNotNull('repository_id')->count();
        $withUrls = Source::whereNotNull('url')->count();

        return [
            'total_sources' => $total,
            'with_repositories' => $withRepositories,
            'without_repositories' => $total - $withRepositories,
            'with_urls' => $withUrls,
            'most_cited_sources' => $this->getMostCitedSources(10),
            'sources_by_type' => $this->getSourcesByType(),
        ];
    }

    /**
     * Get most cited sources.
     */
    protected function getMostCitedSources(int $limit = 10): Collection
    {
        // This would need to be implemented based on actual citation tracking
        return Source::orderBy('title')
            ->limit($limit)
            ->get();
    }

    /**
     * Get sources grouped by type.
     */
    protected function getSourcesByType(): array
    {
        // This would need to be implemented based on source type classification
        return [
            'books' => Source::where('publisher', '!=', null)->count(),
            'websites' => Source::where('url', '!=', null)->count(),
            'documents' => Source::where('call_number', '!=', null)->count(),
        ];
    }

    /**
     * Validate source data.
     */
    public function validateSource(array $data): array
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors[] = 'Title is required';
        }

        if (!empty($data['url']) && !filter_var($data['url'], FILTER_VALIDATE_URL)) {
            $errors[] = 'Invalid URL format';
        }

        if (!empty($data['publication_date']) && !strtotime($data['publication_date'])) {
            $errors[] = 'Invalid publication date format';
        }

        return $errors;
    }

    /**
     * Merge duplicate sources.
     */
    public function mergeSources(Source $primarySource, Source $duplicateSource): Source
    {
        // Update citations to use primary source
        // This would need to be implemented based on actual citation relationships

        // Merge source data
        if (empty($primarySource->author) && !empty($duplicateSource->author)) {
            $primarySource->author = $duplicateSource->author;
        }

        if (empty($primarySource->publisher) && !empty($duplicateSource->publisher)) {
            $primarySource->publisher = $duplicateSource->publisher;
        }

        if (empty($primarySource->publication_date) && !empty($duplicateSource->publication_date)) {
            $primarySource->publication_date = $duplicateSource->publication_date;
        }

        $primarySource->save();

        // Delete duplicate
        $duplicateSource->delete();

        return $primarySource;
    }

    /**
     * Export source data.
     */
    public function exportSourceData(Source $source): array
    {
        return [
            'id' => $source->id,
            'title' => $source->title,
            'author' => $source->author,
            'publisher' => $source->publisher,
            'publication_date' => $source->publication_date,
            'description' => $source->description,
            'url' => $source->url,
            'call_number' => $source->call_number,
            'repository' => $source->repository ? [
                'id' => $source->repository->id,
                'name' => $source->repository->name,
            ] : null,
            'citations' => [
                'chicago' => $this->formatCitation($source, 'chicago'),
                'mla' => $this->formatCitation($source, 'mla'),
                'apa' => $this->formatCitation($source, 'apa'),
            ],
        ];
    }
}
