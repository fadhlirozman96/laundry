# 🧪 Test Accounts & Permissions

## 📋 All Test Accounts

### 🔑 Super Admin (System Administrator)
**Email:** `superadmin@dreampos.com`  
**Password:** `superadmin123`  
**Role:** `super_admin`  
**Access:** ALL stores from ALL business owners

**Can Do:**
- ✅ View all business owners in the system
- ✅ Access ANY store from ANY business owner
- ✅ View all users in the system
- ✅ System settings and configuration
- ✅ Override any permission (full system access)

---

### 👔 Business Owner A - John's Restaurants
**Email:** `owner.john@business.com`  
**Password:** `owner123`  
**Role:** `business_owner`  
**Stores:** 
- John's Restaurant Downtown
- John's Restaurant Mall

**Can Do:**
- ✅ Create new stores
- ✅ View/Edit/Delete HIS stores only
- ✅ Create users for his stores
- ✅ Assign users to his stores
- ✅ Manage products in his stores
- ✅ View reports for his stores
- ❌ Cannot see Emma's stores or data

#### 📌 Mike Manager (Store Admin - Downtown)
**Email:** `mike.manager@johns.com`  
**Password:** `admin123`  
**Role:** `admin`  
**Assigned Store:** John's Restaurant Downtown only

**Can Do:**
- ✅ Manage products in Downtown store
- ✅ Create/Edit/Delete categories, brands
- ✅ Manage orders and customers
- ✅ View reports for Downtown store
- ✅ Adjust inventory
- ❌ Cannot access Mall store
- ❌ Cannot create users

#### 📌 Sarah Cashier (Staff - Downtown)
**Email:** `sarah.cashier@johns.com`  
**Password:** `staff123`  
**Role:** `staff`  
**Assigned Store:** John's Restaurant Downtown only

**Can Do:**
- ✅ View products
- ✅ Create orders (POS operations)
- ✅ View/Create customers
- ✅ View inventory
- ❌ Cannot edit/delete products
- ❌ Cannot access reports
- ❌ Cannot adjust inventory

#### 📌 Tom Sales (Staff - Mall)
**Email:** `tom.sales@johns.com`  
**Password:** `staff123`  
**Role:** `staff`  
**Assigned Store:** John's Restaurant Mall only

**Same permissions as Sarah but for Mall store**

---

### 👔 Business Owner B - Emma's Boutique
**Email:** `owner.emma@retail.com`  
**Password:** `owner123`  
**Role:** `business_owner`  
**Stores:** 
- Emma's Boutique Central

**Can Do:**
- ✅ Create new stores
- ✅ View/Edit/Delete HER stores only
- ✅ Create users for her stores
- ✅ Manage products in her stores
- ✅ View reports for her stores
- ❌ Cannot see John's stores or data

#### 📌 Lisa Manager (Store Admin - Central)
**Email:** `lisa.manager@emmas.com`  
**Password:** `admin123`  
**Role:** `admin`  
**Assigned Store:** Emma's Boutique Central only

**Can Do:**
- ✅ Manage products in Central store
- ✅ Create/Edit/Delete categories, brands
- ✅ Manage orders and customers
- ✅ View reports for Central store
- ✅ Adjust inventory
- ❌ Cannot create users

#### 📌 Bob Cashier (Staff - Central)
**Email:** `bob.cashier@emmas.com`  
**Password:** `staff123`  
**Role:** `staff`  
**Assigned Store:** Emma's Boutique Central only

**Can Do:**
- ✅ View products
- ✅ Create orders (POS operations)
- ✅ View/Create customers
- ✅ View inventory
- ❌ Cannot edit/delete products
- ❌ Cannot access reports

---

## 🎯 Permission Matrix

| Permission Group | Super Admin | Business Owner | Store Admin | Store Staff |
|-----------------|-------------|----------------|-------------|-------------|
| **System Settings** | ✅ Full | ❌ No | ❌ No | ❌ No |
| **View All Data** | ✅ Yes | ❌ Only theirs | ❌ Only assigned | ❌ Only assigned |
| **Create Store** | ✅ Yes | ✅ Yes | ❌ No | ❌ No |
| **Edit Store** | ✅ Any | ✅ Their stores | ❌ No | ❌ No |
| **Delete Store** | ✅ Any | ✅ Their stores | ❌ No | ❌ No |
| **Create User** | ✅ Yes | ✅ Yes | ❌ No | ❌ No |
| **Assign User to Store** | ✅ Yes | ✅ Yes | ❌ No | ❌ No |
| **View Products** | ✅ All | ✅ Their stores | ✅ Assigned stores | ✅ Assigned stores |
| **Create Product** | ✅ Yes | ✅ Yes | ✅ Yes | ❌ No |
| **Edit Product** | ✅ Any | ✅ Their stores | ✅ Assigned stores | ❌ No |
| **Delete Product** | ✅ Any | ✅ Their stores | ✅ Assigned stores | ❌ No |
| **Manage Categories** | ✅ Yes | ✅ Yes | ✅ View/Create/Edit | ✅ View only |
| **Manage Brands** | ✅ Yes | ✅ Yes | ✅ View/Create/Edit | ✅ View only |
| **View Orders** | ✅ All | ✅ Their stores | ✅ Assigned stores | ✅ Assigned stores |
| **Create Order** | ✅ Yes | ✅ Yes | ✅ Yes | ✅ Yes |
| **Edit Order** | ✅ Any | ✅ Their stores | ✅ Assigned stores | ❌ No |
| **Delete Order** | ✅ Any | ✅ Their stores | ❌ No | ❌ No |
| **Cancel Order** | ✅ Any | ✅ Their stores | ✅ Assigned stores | ❌ No |
| **Manage Customers** | ✅ Yes | ✅ Yes | ✅ View/Create/Edit | ✅ View/Create only |
| **View Reports** | ✅ All | ✅ Their stores | ✅ Assigned stores | ❌ No |
| **Export Reports** | ✅ Yes | ✅ Yes | ❌ No | ❌ No |
| **Adjust Inventory** | ✅ Yes | ✅ Yes | ✅ Yes | ❌ No |
| **Transfer Inventory** | ✅ Yes | ✅ Yes | ❌ No | ❌ No |

---

## 🧪 Testing Scenarios

### Scenario 1: Test Data Isolation
1. Login as `owner.john@business.com`
2. Go to Product List → Should see products from Downtown & Mall stores only
3. Go to Add Product → Store dropdown shows: Downtown, Mall only
4. Logout
5. Login as `owner.emma@retail.com`
6. Go to Product List → Should see products from Central store only
7. Go to Add Product → Store dropdown shows: Central only
8. **Result:** ✅ John cannot see Emma's data, Emma cannot see John's data

### Scenario 2: Test Store User Access
1. Login as `mike.manager@johns.com` (Store Admin)
2. Go to Product List → Should see products from Downtown store only
3. Go to Add Product → Store dropdown shows: Downtown only
4. Cannot access Mall store
5. Logout
6. Login as `sarah.cashier@johns.com` (Staff)
7. Can view products but cannot edit/delete
8. Can create orders
9. **Result:** ✅ Users only access assigned stores

### Scenario 3: Test Super Admin Access
1. Login as `superadmin@dreampos.com`
2. Go to Product List → Should see products from ALL stores (John's + Emma's)
3. Go to Store List → Should see ALL 3 stores from both owners
4. Can access any product, any store, any user
5. **Result:** ✅ Super admin has full system access

### Scenario 4: Test Permission Enforcement
1. Login as `sarah.cashier@johns.com` (Staff)
2. Try to edit a product → Should be blocked or show error
3. Try to delete a product → Should be blocked
4. Try to view reports → Should be blocked
5. **Result:** ✅ Permissions are enforced

---

## 💻 Using Permissions in Code

### Check Permission
```php
// In Controller
if (!auth()->user()->hasPermission('create-product')) {
    abort(403, 'Unauthorized action.');
}

// In Blade
@if(auth()->user()->hasPermission('create-product'))
    <button>Add Product</button>
@endif
```

### Check Multiple Permissions
```php
// Has ANY of these permissions
if (auth()->user()->hasAnyPermission(['create-product', 'edit-product'])) {
    // Show product management
}

// Has ALL of these permissions
if (auth()->user()->hasAllPermissions(['create-product', 'edit-product'])) {
    // Show full product management
}
```

### Check Role
```php
if (auth()->user()->isSuperAdmin()) {
    // Super admin logic
}

if (auth()->user()->isBusinessOwner()) {
    // Business owner logic
}

if (auth()->user()->isAdmin()) {
    // Store admin logic
}

if (auth()->user()->isStaff()) {
    // Staff logic
}
```

### Get User Permissions
```php
$permissions = auth()->user()->permissions();
// Returns collection of all permissions for user's role
```

---

## 🗄️ Database Structure

### permissions table
- `id`, `name`, `display_name`, `group`, `description`

### role_permissions table (pivot)
- `role`, `permission_id`

**Total Permissions Created:** 51 permissions across 10 groups
- System (6)
- Stores (5)
- Users (5)
- Products (7)
- Categories (4)
- Brands (4)
- Orders (5)
- Customers (4)
- Reports (5)
- Inventory (3)

---

## 🚀 Quick Start

1. **Login as Super Admin** to see everything:
   ```
   Email: superadmin@dreampos.com
   Password: superadmin123
   ```

2. **Login as Business Owner** to manage your stores:
   ```
   Email: owner.john@business.com
   Password: owner123
   ```

3. **Login as Store Admin** to manage store products:
   ```
   Email: mike.manager@johns.com
   Password: admin123
   ```

4. **Login as Staff** for POS operations:
   ```
   Email: sarah.cashier@johns.com
   Password: staff123
   ```

---

## 📝 Notes

- All passwords are simple for testing: `superadmin123`, `owner123`, `admin123`, `staff123`
- Super Admin does NOT own stores, they can just VIEW and MANAGE all stores
- Business Owners create and OWN stores
- Data isolation is enforced at the query level
- Permissions are cached per role for performance
- Super Admin always bypasses permission checks (can do everything)

