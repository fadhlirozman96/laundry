# Multi-Tenant POS System Documentation

## 🏗️ Correct System Architecture

### User Roles Hierarchy

```
┌─────────────────────────────────────────────────────┐
│          Super Admin (System Administrator)         │
│  - Can see ALL business owners                      │
│  - Can see ALL stores from ALL business owners      │
│  - Can see ALL users in the system                  │
│  - System settings & configuration                  │
│  - Does NOT own stores                              │
└─────────────────────────────────────────────────────┘
                        │
                        ├──────────────────────────────────┬──────────────────────────────
                        ▼                                  ▼
        ┌───────────────────────────┐      ┌───────────────────────────┐
        │  Business Owner A         │      │  Business Owner B         │
        │  (Tenant 1)               │      │  (Tenant 2)               │
        │  - Registers themselves   │      │  - Registers themselves   │
        │  - Creates stores         │      │  - Creates stores         │
        │  - Creates users          │      │  - Creates users          │
        │  - Sees only THEIR data   │      │  - Sees only THEIR data   │
        └───────────────────────────┘      └───────────────────────────┘
                │                                      │
        ┌───────┴───────┐                      ┌──────┴──────┐
        ▼               ▼                      ▼             ▼
    ┌────────┐    ┌────────┐            ┌────────┐    ┌────────┐
    │Store A1│    │Store A2│            │Store B1│    │Store B2│
    └────────┘    └────────┘            └────────┘    └────────┘
        │               │                    │             │
    ┌───┴──┐        ┌──┴──┐            ┌───┴──┐      ┌──┴──┐
    ▼      ▼        ▼     ▼            ▼      ▼      ▼     ▼
  User   User    User  User         User   User   User  User
  A1-1   A1-2    A2-1  A2-2         B1-1   B1-2   B2-1  B2-2
```

## 👥 Role Definitions

### 1. Super Admin (role: `super_admin`)
**System-level administrator**
- ✅ Access to ALL data across ALL business owners
- ✅ Can view all registered business owners
- ✅ Can view all stores from all business owners
- ✅ Can view all users in the system
- ✅ System settings and configuration
- ✅ Can manage any store/user (override permissions)
- ❌ Does NOT own stores
- ❌ Does NOT create stores (business owners do)

**Use case:** 
- Platform administrator
- Technical support
- System maintenance

### 2. Business Owner (role: `business_owner`)
**Tenant/Account owner**
- ✅ Registers their own account
- ✅ Creates multiple stores
- ✅ Creates users for their stores
- ✅ Assigns users to specific stores
- ✅ Can access ALL their stores
- ✅ Can see ALL their users
- ❌ Cannot see other business owners' data
- ❌ Cannot see other business owners' stores
- ❌ Cannot access system settings

**Use case:**
- Restaurant chain owner
- Retail business owner
- Franchise owner

### 3. Store Admin (role: `admin`)
**Store manager**
- ✅ Created by Business Owner
- ✅ Assigned to specific store(s)
- ✅ Can manage products in assigned stores
- ✅ Can view store reports
- ❌ Cannot create new stores
- ❌ Cannot see other stores (only assigned ones)
- ❌ Cannot create users

**Use case:**
- Store manager
- Branch manager

### 4. Store Staff (role: `staff`)
**Store employee**
- ✅ Created by Business Owner
- ✅ Assigned to specific store(s)
- ✅ Can manage products in assigned stores
- ✅ Limited permissions
- ❌ Cannot create stores
- ❌ Cannot see other stores
- ❌ Cannot create users

**Use case:**
- Cashier
- Sales staff
- Inventory staff

## 🗄️ Database Structure

### Users Table
```
id, name, email, password
role: super_admin | business_owner | admin | staff
account_owner_id: Links to business_owner (NULL for business_owner and super_admin)
```

### Stores Table
```
id, name, slug, email, phone, address
created_by: Links to business_owner who created the store
is_active, timestamps
```

### Store_User Table (Pivot)
```
id, store_id, user_id, timestamps
Unique constraint: (store_id, user_id)
```

### Products Table
```
...existing fields...
store_id: Links product to specific store
```

## 🔐 Access Control Matrix

| Role            | See All Stores | Create Store | Create Users | Assign Users | System Settings |
|-----------------|----------------|--------------|--------------|--------------|-----------------|
| Super Admin     | ✅ Yes (ALL)   | ❌ No        | ✅ Yes       | ✅ Yes       | ✅ Yes          |
| Business Owner  | ✅ Yes (THEIRS)| ✅ Yes       | ✅ Yes       | ✅ Yes       | ❌ No           |
| Store Admin     | ❌ No (ASSIGNED)| ❌ No       | ❌ No        | ❌ No        | ❌ No           |
| Store Staff     | ❌ No (ASSIGNED)| ❌ No       | ❌ No        | ❌ No        | ❌ No           |

## 🔄 Typical Workflows

### Workflow 1: Business Owner Registration
1. User visits website
2. Clicks "Register"
3. Fills: Name, Email, Password
4. System automatically sets:
   - `role = 'business_owner'`
   - `account_owner_id = NULL`
5. User logs in → Sees empty stores list
6. Creates their first store

### Workflow 2: Business Owner Creates Store
1. Business Owner logs in
2. Goes to "Store List"
3. Clicks "Add Store"
4. Enters: Name, Address, Phone, Email
5. System automatically sets:
   - `created_by = business_owner.id`
6. Store appears in dropdown for product creation

### Workflow 3: Business Owner Creates Store User
1. Business Owner opens a store
2. Clicks "Create User for this Store"
3. Enters: Name, Email, Password, Role (Admin/Staff)
4. System automatically:
   - Creates user with `role = admin/staff`
   - Sets `account_owner_id = business_owner.id`
   - Assigns user to this store (store_user table)
5. User receives credentials
6. User logs in → Sees only assigned store(s)

### Workflow 4: Store User Adds Product
1. Store User logs in
2. Goes to "Add Product"
3. Store dropdown shows ONLY assigned store(s)
4. Selects store, adds product details
5. Product is linked to that store
6. Business Owner can see this product
7. Other business owners CANNOT see this product

### Workflow 5: Super Admin Monitoring
1. Super Admin logs in
2. Sees dashboard with:
   - Total business owners
   - Total stores (from all owners)
   - Total users
   - System health
3. Can search any business owner
4. Can view any store
5. Can access any product
6. Cannot create stores (only business owners can)

## 🎯 Current System State

After running migrations and seeders:

1. **Your Account**: Business Owner (role: `business_owner`)
2. **Your Stores**: 
   - Main Store
   - Downtown Branch
   - Shopping Mall Store
3. **Access**: You can see only YOUR 3 stores

## 🚀 How to Use the System

### As Business Owner (Your Current Role)

**Create New Store:**
```
1. Go to Store List
2. Click "Add Store"
3. Enter store details
4. Store is automatically yours
```

**Create User for Store:**
```
1. Go to Store List
2. Find your store
3. Click "Create User"
4. Enter: Name, Email, Password, Role
5. User is automatically:
   - Linked to you (account_owner_id)
   - Assigned to that store
```

**Add Products:**
```
1. Go to Add Product
2. Store dropdown shows only YOUR stores
3. Select store
4. Add product → Linked to selected store
```

**View Products:**
```
- You see products from ALL your stores
- Other business owners don't see your products
```

### As Super Admin (To Be Created)

**View All Business Owners:**
```
- Dashboard shows all registered business owners
- Can search/filter business owners
```

**View All Stores:**
```
- Store List shows stores from ALL business owners
- Shows owner name for each store
```

**Access Any Data:**
```
- Can view any product
- Can see any user
- Can access system settings
```

## 📝 API Methods

### User Model

```php
// Check role
$user->isSuperAdmin();       // System admin
$user->isBusinessOwner();    // Account owner
$user->isAdmin();            // Store admin
$user->isStaff();            // Store staff

// Get accessible stores
$stores = $user->getAccessibleStores();
// Super Admin → ALL stores
// Business Owner → Their stores
// Admin/Staff → Assigned stores

// Get business owner
$owner = $user->getBusinessOwner();
```

### Store Model

```php
// Get store owner (business owner who created it)
$owner = $store->owner;

// Get assigned users
$users = $store->users;

// Get products
$products = $store->products;
```

## 🎨 Multi-Tenant Features

### Data Isolation

**Business Owner A:**
- Creates: Store A1, Store A2
- Creates: User A1, User A2
- Products belong to Store A1, A2
- **CANNOT** see Store B1, B2
- **CANNOT** see User B1, B2
- **CANNOT** see products from Store B

**Business Owner B:**
- Creates: Store B1, Store B2
- Creates: User B1, User B2
- Products belong to Store B1, B2
- **CANNOT** see Store A1, A2
- **CANNOT** see User A1, A2
- **CANNOT** see products from Store A

**Super Admin:**
- **CAN** see Store A1, A2, B1, B2
- **CAN** see User A1, A2, B1, B2
- **CAN** see ALL products
- **CAN** access system settings

### Product Filtering

```php
// Automatic filtering in ProductController
if ($user->isSuperAdmin()) {
    // Show ALL products from ALL stores
    $products = Product::all();
} elseif ($user->isBusinessOwner()) {
    // Show products from business owner's stores only
    $storeIds = $user->ownedStores()->pluck('id');
    $products = Product::whereIn('store_id', $storeIds)->get();
} else {
    // Show products from assigned stores only
    $storeIds = $user->stores()->pluck('id');
    $products = Product::whereIn('store_id', $storeIds)->get();
}
```

## ⚙️ Creating Super Admin

To create a Super Admin account:

```php
// Run in tinker or seeder
User::create([
    'name' => 'System Admin',
    'email' => 'superadmin@system.com',
    'password' => Hash::make('securepassword'),
    'role' => 'super_admin',
    'account_owner_id' => null,
]);
```

Or create a registration route specifically for super admin (protected).

## 🔒 Security Considerations

1. **Multi-Tenant Isolation**: Business owners cannot access other owners' data
2. **Store Assignment**: Users can only access assigned stores
3. **Super Admin Override**: Super admin can access everything (for support)
4. **Cascade Delete**: Deleting business owner deletes their stores and users
5. **Store Validation**: Products can only be assigned to accessible stores

## 📊 Business Use Cases

### Use Case 1: Restaurant Chain
- **Business Owner**: Restaurant chain owner
- **Stores**: Branch A, Branch B, Branch C
- **Users**:
  - Manager A → Branch A
  - Manager B → Branch B
  - Cashier A1, A2 → Branch A
  - Cashier B1 → Branch B

### Use Case 2: Retail Franchise
- **Business Owner**: Franchise owner
- **Stores**: Mall Store, Downtown Store
- **Users**:
  - Store Manager → Mall Store
  - Sales Staff 1, 2, 3 → Mall Store
  - Store Manager → Downtown Store
  - Sales Staff 1, 2 → Downtown Store

### Use Case 3: Multi-Business Platform
- **Super Admin**: Platform owner
- **Business Owner A**: Clothing brand
  - Store A1, A2
- **Business Owner B**: Electronics store
  - Store B1, B2, B3
- Each business is completely isolated

## 🎯 Summary

**This is a Multi-Tenant SaaS POS System where:**
- ✅ Multiple business owners can register
- ✅ Each business owner has their own stores
- ✅ Data is completely isolated between business owners
- ✅ Super Admin can access everything for support
- ✅ Store users can only access assigned stores
- ✅ Perfect for: Restaurant chains, retail franchises, multi-location businesses

