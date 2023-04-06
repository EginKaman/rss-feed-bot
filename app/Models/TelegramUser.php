<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TelegramUser extends Model
{
    use HasFactory;
    use Notifiable;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'is_bot',
        'first_name',
        'last_name',
        'username',
        'language_code',
        'can_join_groups',
        'can_read_all_group_messages',
        'support_inline_queries',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_bot'                      => 'boolean',
        'first_name'                  => 'string',
        'last_name'                   => 'string',
        'username'                    => 'string',
        'language_code'               => 'string',
        'can_join_groups'             => 'boolean',
        'can_read_all_group_messages' => 'boolean',
        'support_inline_queries'      => 'boolean',
    ];
}
