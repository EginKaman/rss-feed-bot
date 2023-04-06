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
use Orchid\Screen\AsSource;

class Feed extends Model
{
    use HasFactory, SoftDeletes, AsSource, Filterable, Searchable;

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
        'home_link',
        'description',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id'    => 'string',
        'title' => 'string',
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
        return $this->belongsTo(Site::class);
    }
}
