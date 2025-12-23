# POS Features Added to Laundry Order Creation

## Summary
Added complete POS-style features to the Create Laundry Order page, including:
- Order Tax (customizable percentage)
- Shipping charges
- Discount
- Coupon code system
- Payment method selection (Cash, Debit Card, QR)
- Grand total display
- Action buttons (Hold, Void, Payment)

## Files Modified

### 1. Migration Created
**File:** `database/migrations/2025_12_23_100000_add_pos_fields_to_laundry_orders_table.php`
- Added `payment_method` (string, nullable)
- Added `shipping` (decimal)
- Added `coupon_code` (string, nullable)
- Added `coupon_discount` (decimal)
- Added `order_tax_percent` (decimal, default 6%)

### 2. Model Updated
**File:** `app/Models/LaundryOrder.php`
- Added new fields to `$fillable` array
- Added new fields to `$casts` array for proper type casting

### 3. Controller Updated
**File:** `app/Http/Controllers/LaundryController.php`
- Updated `store()` method to handle:
  - Dynamic tax percentage calculation
  - Shipping charges
  - Coupon discount
  - Payment method
  - Updated total calculation formula: `subtotal + tax + shipping - discount - coupon_discount`

### 4. View Updated
**File:** `resources/views/laundry/create-order.blade.php`

#### New UI Components Added:
1. **Order Calculation Card** - Contains:
   - Order Tax (%) input field
   - Shipping input field
   - Discount input field
   - Coupon code input with Apply button
   
2. **Totals Summary Display**:
   - Sub Total
   - Tax (with dynamic percentage display)
   - Shipping
   - Discount (in red)
   - Coupon Discount (in green, shows only when applied)
   - Total (highlighted in primary color)

3. **Payment Method Selection**:
   - Cash (default)
   - Debit Card
   - QR Payment
   - Radio button style with icons

4. **Grand Total Display**:
   - Dark background banner showing final total

5. **Action Buttons**:
   - **Hold** (Info/Blue) - Save order as draft
   - **Void** (Danger/Red) - Clear all data
   - **Payment** (Success/Green) - Submit order

#### JavaScript Functions Added:
- `calculateTotals()` - Enhanced to handle all new fields
- `applyCoupon()` - Validate and apply coupon codes
- `holdOrder()` - Save order as draft (placeholder)
- `voidOrder()` - Clear form and reload page

## Database Schema Changes

Run the migration to add new fields:
```bash
php artisan migrate
```

### New Columns in `laundry_orders` table:
| Column | Type | Default | Description |
|--------|------|---------|-------------|
| payment_method | string | null | cash, debit_card, qr |
| shipping | decimal(10,2) | 0.00 | Shipping/delivery charges |
| coupon_code | string | null | Applied coupon code |
| coupon_discount | decimal(10,2) | 0.00 | Discount from coupon |
| order_tax_percent | decimal(5,2) | 6.00 | Tax percentage used |

## Calculation Formula

```
Subtotal = Sum of all (item quantity × item price)
Tax = Subtotal × (order_tax_percent / 100)
Total = Subtotal + Tax + Shipping - Discount - Coupon Discount
```

## Features to Implement Later

### 1. Coupon System
Currently the `applyCoupon()` function has a placeholder. You need to:
- Create a `coupons` table
- Create `Coupon` model
- Implement coupon validation API endpoint
- Add coupon types (percentage, fixed amount, free shipping)
- Add expiry dates and usage limits

### 2. Hold Order Functionality
- Create a `held_orders` or add `is_held` flag to orders
- Store held orders in session or database
- Add page to view and resume held orders

### 3. Payment Processing
- Integrate with payment gateways for card/QR payments
- Add payment confirmation modal
- Record payment transactions
- Print receipt after payment

## Usage

1. **Navigate** to Create Laundry Order page
2. **Add customer** information
3. **Add items** using the "+ Add Item" button
4. **Select services** from dropdown
5. **Adjust** tax, shipping, and discount as needed
6. **Apply coupon** if available
7. **Select payment method** (Cash/Debit/QR)
8. **Review** grand total
9. **Click Payment** button to create order

## UI Improvements
- Matches POS system design from reference image
- Clean, organized layout
- Real-time calculation updates
- Color-coded totals (discount in red, coupon in green)
- Professional payment method selection
- Prominent grand total display
- Clear action buttons

## Notes
- All monetary calculations use 2 decimal places
- Tax percentage is customizable per order
- Negative totals are prevented (minimum 0)
- Coupon validation needs backend implementation
- Hold/Void features need additional development


