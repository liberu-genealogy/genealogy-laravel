<?php

namespace App\Modules\Notes\Services;

use App\Models\Note;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class NotesService
{
    /**
     * Create a new note.
     */
    public function createNote(array $data): Note
    {
        return Note::create([
            'title' => $data['title'] ?? null,
            'content' => $data['content'],
            'category' => $data['category'] ?? null,
            'is_private' => $data['is_private'] ?? false,
            'user_id' => $data['user_id'] ?? auth()->id(),
        ]);
    }

    /**
     * Update note information.
     */
    public function updateNote(Note $note, array $data): Note
    {
        $note->update($data);
        return $note->fresh();
    }

    /**
     * Search notes.
     */
    public function searchNotes(string $query, int $limit = 50): Collection
    {
        return Note::where('title', 'LIKE', "%{$query}%")
            ->orWhere('content', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get();
    }

    /**
     * Get notes with pagination.
     */
    public function getNotesPaginated(int $perPage = 20): LengthAwarePaginator
    {
        return Note::orderBy('updated_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get notes by category.
     */
    public function getNotesByCategory(string $category): Collection
    {
        return Note::where('category', $category)
            ->orderBy('title')
            ->get();
    }

    /**
     * Get note categories.
     */
    public function getNoteCategories(): Collection
    {
        return Note::whereNotNull('category')
            ->distinct('category')
            ->pluck('category');
    }

    /**
     * Get note statistics.
     */
    public function getNoteStatistics(): array
    {
        $total = Note::count();
        $private = Note::where('is_private', true)->count();
        $categories = $this->getNoteCategories()->count();

        return [
            'total_notes' => $total,
            'private_notes' => $private,
            'public_notes' => $total - $private,
            'categories' => $categories,
            'recent_notes' => $this->getRecentNotes(5),
        ];
    }

    /**
     * Get recent notes.
     */
    protected function getRecentNotes(int $limit = 5): Collection
    {
        return Note::orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Delete note.
     */
    public function deleteNote(Note $note): bool
    {
        return $note->delete();
    }

    /**
     * Export note data.
     */
    public function exportNoteData(Note $note): array
    {
        return [
            'id' => $note->id,
            'title' => $note->title,
            'content' => $note->content,
            'category' => $note->category,
            'is_private' => $note->is_private,
            'created_at' => $note->created_at?->toISOString(),
            'updated_at' => $note->updated_at?->toISOString(),
        ];
    }
}