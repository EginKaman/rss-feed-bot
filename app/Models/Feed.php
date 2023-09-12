<?php

declare(strict_types=1);

namespace App\Models;

use App\Orchid\Presenters\FeedPresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Ilike;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Screen\AsSource;

class Feed extends Model
{
    use AsSource, Filterable, HasFactory, Searchable, SoftDeletes;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'photo',
        'link',
        'description',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id'           => 'string',
        'title'        => 'string',
        'published_at' => 'datetime',
    ];

    protected array $allowedFilters = [
        'link'         => Ilike::class,
        'site_id'      => Where::class,
        'title'        => Ilike::class,
        'description'  => Ilike::class,
        'published_at' => WhereDateStartEnd::class,
        'created_at'   => WhereDateStartEnd::class,
        'updated_at'   => WhereDateStartEnd::class,
        'deleted_at'   => WhereDateStartEnd::class,
    ];

    protected array $allowedSorts = [
        'id',
        'title',
        'description',
        'published_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the presenter for the model.
     *
     * @return FeedPresenter
     */
    public function presenter(): FeedPresenter
    {
        return new FeedPresenter($this);
    }

    /**
     * @return BelongsTo
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class)->withTrashed();
    }

    public function toSearchableArray(): array
    {
        return [
            'id'           => $this->getKey(),
            'link'         => $this->link,
            'title'        => $this->title,
            'description'  => $this->description,
            'published_at' => $this->published_at,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
            'deleted_at'   => $this->deleted_at,
            'photo'        => $this->photo,
        ];
    }
}
