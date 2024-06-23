<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\UserPermission
 *
 * @property int $id
 * @property int $user_id
 * @property int $permission_id
 * @property int $permission_type_id
 * @property int $customised_permissions
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Permission $permission
 * @property-read PermissionType $type
 * @method static Builder|UserPermission newModelQuery()
 * @method static Builder|UserPermission newQuery()
 * @method static Builder|UserPermission query()
 * @method static Builder|UserPermission whereCreatedAt($value)
 * @method static Builder|UserPermission whereId($value)
 * @method static Builder|UserPermission wherePermissionId($value)
 * @method static Builder|UserPermission wherePermissionTypeId($value)
 * @method static Builder|UserPermission whereUpdatedAt($value)
 * @method static Builder|UserPermission whereUserId($value)
 * @property-read User $user
 * @mixin Eloquent
 */
class UserPermission extends BaseModel
{

    use HasFactory;

    protected $guarded = ['id'];
    protected $fillable = ['user_id', 'permission_id', 'permission_type_id'];
    
    public function type(): BelongsTo
    {
        return $this->belongsTo(PermissionType::class, 'permission_type_id');
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
