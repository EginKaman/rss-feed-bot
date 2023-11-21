<?php

declare(strict_types=1);

namespace App\Models;

use App\Orchid\Presenters\SitePresenter;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Ilike;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Screen\AsSource;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Site extends Model
{
    use AsSource;
    use Filterable;
    use HasFactory;
    use HasUuids;
    use LogsActivity;
    use Searchable;
    use SoftDeletes;

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
        'is_disabled',
        'fed_at',
        'pauses_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var string[]
     */
    protected $casts = [
        'id'           => 'string',
        'is_disabled'  => 'boolean',
        'fed_at'       => 'datetime',
        'pauses_at'    => 'datetime',
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
     */
    public function presenter(): SitePresenter
    {
        return new SitePresenter($this);
    }

    public function feeds(): HasMany
    {
        return $this->hasMany(Feed::class);
    }

    public function authentications(): HasMany
    {
        return $this->hasMany(SiteAuthentication::class);
    }

    public function alternativeLinks(): HasMany
    {
        return $this->hasMany(AlternativeLink::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontLogIfAttributesChangedOnly(['fed_at', 'updated_at'])
            ->logOnlyDirty();
    }
}
