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
        'company_name',
        'company_address',
        'company_phone',
        'current_plan_id',
        'plan_expires_at',
        'account_owner_id',
        'department_id',
        'designation_id',
        'shift_id',
        'employee_id',
        'joining_date',
        'salary',
        'phone',
        'address',
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
        'plan_expires_at' => 'datetime',
    ];

    // Relationships
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id')->withTimestamps();
    }

    // Get the primary role (assuming one role per user)
    // Accessor to get first role (for backward compatibility)
    public function getRoleAttribute()
    {
        if (!$this->relationLoaded('roles')) {
            $this->load('roles');
        }
        return $this->roles->first();
    }
    
    // Helper method (same as attribute accessor)
    public function role()
    {
        return $this->getRoleAttribute();
    }

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

    // SaaS Relationships
    public function currentPlan()
    {
        return $this->belongsTo(Plan::class, 'current_plan_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->where('status', 'active')->latest();
    }

    public function payments()
    {
        return $this->hasMany(SubscriptionPayment::class);
    }

    // HRM Relationships
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    // Helper methods
    public function isSuperAdmin()
    {
        $roleObj = $this->role();
        return $roleObj && ($roleObj->name === 'super_admin' || $roleObj->name === 'superadmin');
    }

    public function isBusinessOwner()
    {
        $roleObj = $this->role();
        return $roleObj && $roleObj->name === 'business_owner';
    }

    public function isAdmin()
    {
        $roleObj = $this->role();
        return $roleObj && ($roleObj->name === 'admin' || $roleObj->name === 'store_manager');
    }

    public function isStaff()
    {
        $roleObj = $this->role();
        return $roleObj && $roleObj->name === 'staff';
    }

    // SaaS Helper Methods
    public function hasPlan()
    {
        return !is_null($this->current_plan_id);
    }

    public function onPlan($planSlug)
    {
        if (!$this->currentPlan) {
            return false;
        }
        return $this->currentPlan->slug === $planSlug;
    }

    public function canAddStore()
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        if (!$this->currentPlan) {
            return false;
        }
        
        $storeCount = $this->ownedStores()->count();
        $maxStores = $this->currentPlan->max_stores;
        
        return $maxStores === -1 || $storeCount < $maxStores;
    }

    public function hasFeatureAccess($feature)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        if (!$this->currentPlan) {
            return false;
        }
        
        return $this->currentPlan->hasFeature($feature);
    }

    public function getQcLevel()
    {
        if (!$this->currentPlan) {
            return 'basic';
        }
        return $this->currentPlan->qc_level;
    }

    public function hasStoreSwitcher()
    {
        if ($this->isSuperAdmin()) {
            return false; // Superadmin doesn't need store switcher
        }
        
        if (!$this->currentPlan) {
            return false;
        }
        
        return $this->currentPlan->has_store_switcher;
    }

    public function hasAllStoresView()
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        if (!$this->currentPlan) {
            return false;
        }
        
        return $this->currentPlan->has_all_stores_view;
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

    public function getAccessibleStores($includeInactive = false)
    {
        $query = $this->getAccessibleStoresQuery();
        
        // Super Admin and Business Owner can see inactive stores
        // Staff/Admin can only see active stores
        if (!$includeInactive && !$this->isSuperAdmin() && !$this->isBusinessOwner()) {
            $query = $query->where('is_active', true);
        }
        
        return $query->get();
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
        $role = $this->role();
        if (!$role) {
            return collect([]);
        }
        return $role->permissions;
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
