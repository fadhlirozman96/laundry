<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permissions',
            'role_id',
            'permission_id'
        )->withTimestamps();
    }

    // Helper methods
    public static function getByName($name)
    {
        return self::where('name', $name)->first();
    }

    public static function superAdmin()
    {
        return self::getByName('super_admin');
    }

    public static function businessOwner()
    {
        return self::getByName('business_owner');
    }

    public static function admin()
    {
        return self::getByName('admin');
    }

    public static function staff()
    {
        return self::getByName('staff');
    }
}







