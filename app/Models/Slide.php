<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;

    public const PLACEMENT_HOMEPAGE_HERO = 'homepage_hero';
    public const PLACEMENT_HOMEPAGE_BANNER = 'homepage_banner';

    protected $fillable = ['placement', 'title', 'subtitle', 'image_path', 'link_url', 'display_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function scopeForPlacement(Builder $query, string $placement): Builder
    {
        return $query->where('placement', $placement)->where('is_active', true)->orderBy('display_order');
    }
}
