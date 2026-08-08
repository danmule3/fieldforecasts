<?php

namespace App\Services;

use App\Models\Sport;
use App\Repositories\Contracts\MatchRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MatchService
{
    public function __construct(private readonly MatchRepositoryInterface $matches)
    {
    }

    public function homepageSections(?Sport $sport = null): array
    {
        return [
            'live' => $this->matches->live($sport),
            'today' => $this->matches->today($sport),
            'upcoming' => $this->matches->upcoming($sport, 8),
            'featured' => $this->matches->featured(5),
        ];
    }

    public function forLeague(int $leagueId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->matches->paginateForLeague($leagueId, $perPage);
    }

    public function filtered(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        return $this->matches->paginateFiltered($filters, $perPage);
    }

    public function liveTicker(?Sport $sport = null): Collection
    {
        return $this->matches->live($sport);
    }
}
