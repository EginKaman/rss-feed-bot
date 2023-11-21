<?php

declare(strict_types=1);

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Access\RoleAccess;
use Orchid\Access\RoleInterface;
use Orchid\Access\StatusAccess;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Metrics\Chartable;
use Orchid\Platform\Models\User;
use Orchid\Screen\AsSource;

class Role extends Model implements RoleInterface
{
    use AsSource, Chartable, Filterable, RoleAccess, StatusAccess;

    /**
     * @var string
     */
    protected $table = 'roles';

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
        'slug',
        'permissions',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'permissions' => 'array',
    ];

    protected array $allowedFilters = [
        'id'          => Like::class,
        'name'        => Like::class,
        'slug'        => Like::class,
        'permissions' => Like::class,
    ];

    /**
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'slug',
        'updated_at',
        'created_at',
    ];

    /**
     * @return int
     */
    public function getRoleId()
    {
        return $this->getKey();
    }

    public function getRoleSlug(): string
    {
        return $this->getAttribute('slug');
    }

    /**
     * @return Collection
     */
    public function getUsers()
    {
        return $this->users()->get();
    }

    /**
     * The Users relationship.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_users', 'role_id', 'user_id');
    }

    /**
     * @throws Exception
     */
    public function delete(): ?bool
    {
        $isSoftDeleted = array_key_exists(SoftDeletes::class, class_uses($this));
        if ($this->exists && ! $isSoftDeleted) {
            $this->users()->detach();
        }

        return parent::delete();
    }
}
