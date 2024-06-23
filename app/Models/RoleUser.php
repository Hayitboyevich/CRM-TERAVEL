<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\RoleUser
 *
 * @property int $user_id
 * @property int $role_id
 * @property-read mixed $icon
 * @property-read Role $role
 * @property-read User $user
 * @method static Builder|RoleUser newModelQuery()
 * @method static Builder|RoleUser newQuery()
 * @method static Builder|RoleUser query()
 * @method static Builder|RoleUser whereRoleId($value)
 * @method static Builder|RoleUser whereUserId($value)
 * @mixin Eloquent
 */
class RoleUser extends BaseModel
{

    public $timestamps = false;
    protected $table = 'role_user';
    protected $fillable = [
        'role_id',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'user_id');
    }

}
