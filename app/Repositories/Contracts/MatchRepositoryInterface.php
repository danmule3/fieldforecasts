<?php

namespace App\Repositories\Contracts;

use App\Models\Sport;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Abstracts match reads behind an interface so Module 7 (external Fixture
 * API integration) can introduce a decorating/composite implementation
 * — e.g. one that merges locally stored matches with live results
 * fetched from a provider and cached — without any controller, view,
 * or service in this module changing.
 */
interface MatchRepositoryInterface
{
    public function today(?Sport $sport = null): Collection;

    public function live(?Sport $sport = null): Collection;

    public function upcoming(?Sport $sport = null, int $limit = 10): Collection;

    public function featured(int $limit = 5): Collection;

    public function paginateForLeague(int $leagueId, int $perPage = 20): LengthAwarePaginator;

    public function paginateFiltered(array $filters, int $perPage = 20): LengthAwarePaginator;
}
