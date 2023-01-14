<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlternativeLink extends Model
{
    use HasFactory;

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
        'replaceable_link',
        'link'
    ];

    /**
     * @return BelongsTo
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
