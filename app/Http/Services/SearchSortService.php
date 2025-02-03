<?php

namespace App\Http\Services;

use Illuminate\Database\Eloquent\Builder;

class SearchSortService
{
    protected array $allowedSortFields = ['id', 'name', 'email'];
    protected int $searchMinLength = 1;

    public function applySearch(Builder $query, ?string $search): void
    {
        if ($search && strlen($search) >= $this->searchMinLength) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
    }

    public function applySorting(Builder $query, string $sortBy, string $sortOrder): void
    {
        if (in_array($sortBy, $this->allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }
    }
}
