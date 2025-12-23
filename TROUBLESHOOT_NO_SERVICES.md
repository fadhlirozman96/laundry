# Troubleshooting: No Services Showing in Dropdown

## Issue
Service dropdown shows "No results found"

## Diagnosis Steps

### 1. Check Browser Console
Open browser DevTools (F12) and check Console tab for:
```javascript
Services loaded: 0  // ❌ Problem: No services in database
Services loaded: 25 // ✅ Good: Services are loaded
```

### 2. Check Database

Run this query to check if services exist:
```sql
SELECT COUNT(*) FROM products WHERE is_active = 1;
```

Or check in Laravel:
```bash
php artisan tinker
>>> App\Models\Product::where('is_active', true)->count()
```

## Solutions

### Solution 1: Run Database Seeders

If services count is 0, run the seeder:

```bash
# Run all seeders (includes LaundryServiceSeeder)
php artisan db:seed

# Or run specific seeder
php artisan db:seed --class=LaundryServiceSeeder
```

### Solution 2: Check Store Assignment

Services must belong to the current store. Check if you have a store selected:

```php
// In browser console, check:
console.log('Current Store ID:', '{{ session("store_id") }}');
```

If no store ID, you need to select a store first.

### Solution 3: Verify Migration

Make sure all migrations have run:

```bash
php artisan migrate:status
```

Check that these tables exist:
- ✅ `products` table
- ✅ `categories` table  
- ✅ `units` table
- ✅ `stores` table

### Solution 4: Manual Service Creation

If seeders fail, create a test service manually:

```bash
php artisan tinker
```

```php
// Get current store
$store = App\Models\Store::first();

// Get or create a unit
$unit = App\Models\Unit::firstOrCreate(
    ['short_name' => 'Kg'],
    ['name' => 'Kilogram']
);

// Get or create a category
$category = App\Models\Category::firstOrCreate(
    ['name' => 'Laundry Services'],
    ['slug' => 'laundry-services']
);

// Create a test service
App\Models\Product::create([
    'store_id' => $store->id,
    'name' => 'Normal Wash',
    'slug' => 'normal-wash-' . $store->id,
    'sku' => 'SVC-001-' . $store->id,
    'category_id' => $category->id,
    'unit_id' => $unit->id,
    'price' => 4.00,
    'cost' => 2.40,
    'quantity' => 9999,
    'is_active' => true,
    'created_by' => $store->created_by
]);
```

## Verification

After fixing, reload the page and:

1. **Check Console Output:**
   ```
   Services loaded: X  // Should be > 0
   Services data: [{...}]  // Should show array of services
   ```

2. **Check Dropdown:**
   - Click service dropdown
   - Should see list of services
   - Search should work

3. **Test Service Selection:**
   - Select a service
   - Unit badge should appear (Kg, Pc, etc.)
   - Price should auto-fill
   - Quantity input should adjust

## Common Issues

### Issue: "Services loaded: 0"
**Cause:** No services in database for current store
**Fix:** Run `php artisan db:seed --class=LaundryServiceSeeder`

### Issue: Services exist but dropdown empty
**Cause:** JavaScript error or Select2 initialization issue
**Fix:** Check browser console for errors

### Issue: "No store selected"
**Cause:** No store_id in session
**Fix:** Login and select a store from the store selector

### Issue: Services show but no unit badge
**Cause:** Services don't have unit_id
**Fix:** Update services to include unit:
```php
DB::table('products')->whereNull('unit_id')->update([
    'unit_id' => DB::table('units')->where('short_name', 'Pc')->value('id')
]);
```

## Quick Fix Command

Run this to set up everything:

```bash
# Fresh start with seeders
php artisan migrate:fresh --seed

# Or just reseed without dropping tables
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=TestAccountsSeeder
php artisan db:seed --class=LaundryServiceSeeder
```

## Debugging Checklist

- [ ] Browser console shows services loaded > 0
- [ ] Database has products with is_active = 1
- [ ] Products have valid store_id matching current store
- [ ] Products have unit_id (not null)
- [ ] Products have category_id (not null)
- [ ] Store is selected (check session store_id)
- [ ] No JavaScript errors in console
- [ ] Select2 library is loaded
- [ ] Page refreshed after database changes

## Still Not Working?

Check the full data structure in console:
```javascript
console.table(services.map(s => ({
    id: s.id,
    name: s.name,
    store_id: s.store_id,
    unit: s.unit?.short_name,
    category: s.category?.name,
    price: s.price
})));
```

This will show exactly what's missing from the service data.

