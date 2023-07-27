<?php

declare(strict_types=1);

namespace App\Models;

use App\Orchid\Presenters\SitePresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Ilike;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Screen\AsSource;

class Site extends Model
{
    use HasFactory, SoftDeletes, AsSource, Filterable, Searchable;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'link',
        'home_link',
        'title',
        'description',
        'fed_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'     => 'string',
        'fed_at' => 'datetime',
    ];

    protected array $allowedFilters = [
        'link'        => Ilike::class,
        'home_link'   => Ilike::class,
        'title'       => Ilike::class,
        'description' => Ilike::class,
        'fed_at'      => WhereDateStartEnd::class,
        'created_at'  => WhereDateStartEnd::class,
        'updated_at'  => WhereDateStartEnd::class,
        'deleted_at'  => WhereDateStartEnd::class,
    ];

    protected array $allowedSorts = [
        'id',
        'title',
        'description',
        'fed_at',
        'feeds_count',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the presenter for the model.
     *
     * @return SitePresenter
     */
    public function presenter(): SitePresenter
    {
        return new SitePresenter($this);
    }

    /**
     * @return HasMany
     */
    public function feeds(): HasMany
    {
        return $this->hasMany(Feed::class);
    }

    /**
     * @return HasMany
     */
    public function authentications(): HasMany
    {
        return $this->hasMany(SiteAuthentication::class);
    }

    /**
     * @return HasMany
     */
    public function alternativeLinks(): HasMany
    {
        return $this->hasMany(AlternativeLink::class);
    }
}
