<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'account_owner_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function accountOwner()
    {
        return $this->belongsTo(User::class, 'account_owner_id');
    }

    public function subUsers()
    {
        return $this->hasMany(User::class, 'account_owner_id');
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_user')->withTimestamps();
    }

    public function ownedStores()
    {
        return $this->hasMany(Store::class, 'created_by');
    }

    // Helper methods
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isBusinessOwner()
    {
        return $this->role === 'business_owner';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function getAccessibleStoresQuery()
    {
        if ($this->isSuperAdmin()) {
            // Super admin sees ALL stores from ALL business owners
            return Store::query();
        } elseif ($this->isBusinessOwner()) {
            // Business owner sees all stores they created
            return Store::where('created_by', $this->id);
        } else {
            // Staff/Admin sees only their assigned stores
            return $this->stores();
        }
    }

    public function getAccessibleStores()
    {
        return $this->getAccessibleStoresQuery()->where('is_active', true)->get();
    }

    public function getBusinessOwner()
    {
        if ($this->isBusinessOwner()) {
            return $this;
        }
        return $this->accountOwner;
    }

    // Permission methods
    public function permissions()
    {
        return Permission::whereHas('roles', function ($query) {
            $query->where('role', $this->role);
        })->get();
    }

    public function hasPermission($permissionName)
    {
        return $this->permissions()->contains('name', $permissionName);
    }

    public function hasAnyPermission($permissions)
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    public function hasAllPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    public function can($ability, $arguments = [])
    {
        // Override Laravel's can method to use our permission system
        if ($this->isSuperAdmin()) {
            return true; // Super admin can do everything
        }
        
        return $this->hasPermission($ability);
    }
}
