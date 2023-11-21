<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use App\Orchid\Filters\RoleFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class UserFiltersLayout extends Selection
{
    /**
     * @return Filter[]|string[]
     */
    public function filters(): array
    {
        return [
            RoleFilter::class,
        ];
    }
}
