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
            config: null,
            table: 'role_permissions',
            foreignPivotKey: 'permission_id',
            relatedPivotKey: 'role',
            parentKey: 'id',
            relatedKey: 'role'
        );
    }

    public static function getByRole($role)
    {
        return self::whereHas('roles', function ($query) use ($role) {
            $query->where('role', $role);
        })->get();
    }
}

