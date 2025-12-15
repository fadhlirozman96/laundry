<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'group',
        'description',
    ];

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_permissions',
            'permission_id',
            'role_id'
        )->withTimestamps();
    }

    public static function getByRole($role)
    {
        if ($role instanceof Role) {
            $roleId = $role->id;
        } elseif (is_numeric($role)) {
            $roleId = $role;
        } else {
            $roleModel = Role::where('name', $role)->first();
            if (!$roleModel) {
                return collect([]);
            }
            $roleId = $roleModel->id;
        }
        
        return self::whereHas('roles', function ($query) use ($roleId) {
            $query->where('roles.id', $roleId);
        })->get();
    }
}

