# Service Dropdown Improvements

## Changes Made

### 1. **Service Dropdown with Search**
- Added Select2 plugin to service dropdowns for searchable selection
- Each service row gets its own Select2-enabled dropdown
- Dropdown shows: Service Name (Category) - Unit

**Example Display:**
```
Normal Wash (Normal Wash & Dry Services) - Kg
Express Wash (Normal Wash & Dry Services) - Kg
Comforter/Toto (Normal Wash & Dry Services) - Pc
```

### 2. **Unit Indicators (Badges)**
Added unit badges next to quantity input showing:
- **Kg** - Kilogram (allows decimals)
- **Pc** - Piece (whole numbers only)
- **Set** - Set (whole numbers only)
- **Sq.ft** - Square Feet (allows decimals)
- **Pair** - Pair (whole numbers only)

### 3. **Smart Quantity Input**
Quantity input automatically adjusts based on selected service unit:

#### Decimal Units (Kg, Sq.ft):
- `step="0.01"` - Allows decimals like 2.5 kg
- `min="0.01"` - Minimum 0.01
- Can enter: 1.5, 2.75, 10.25, etc.

#### Whole Number Units (Pc, Set, Pair):
- `step="1"` - Only whole numbers
- `min="1"` - Minimum 1
- Automatically rounds decimals to nearest integer
- Can only enter: 1, 2, 3, 10, etc.

## Files Modified

### 1. Controller
**File:** `app/Http/Controllers/LaundryController.php`
```php
// Added 'unit' to eager loading
$services = Product::where('store_id', $storeId)
    ->where('is_active', true)
    ->with(['category', 'unit'])  // Added unit
    ->get();
```

### 2. View
**File:** `resources/views/laundry/create-order.blade.php`

#### Changes:
1. **Service Dropdown Enhancement:**
   - Added unit information to dropdown options
   - Initialized Select2 for each service dropdown
   - Added unique IDs for each dropdown

2. **Quantity Input with Unit Badge:**
```html
<div class="input-group input-group-sm">
    <input type="number" class="form-control item-qty" 
           name="items[${itemIndex}][quantity]" 
           value="1" min="0.01" step="1">
    <span class="input-group-text item-unit-badge" id="unit_${itemIndex}">Pc</span>
</div>
```

3. **JavaScript Functions Updated:**

**`addItem()`:**
- Builds dropdown with service name, category, and unit
- Initializes Select2 with search functionality
- Sets unit badge for each row

**`setServicePrice()`:**
- Updates unit badge when service is selected
- Dynamically changes quantity input attributes:
  - For Kg/Sq.ft: `step="0.01"`, `min="0.01"`
  - For Pc/Set/Pair: `step="1"`, `min="1"`
- Rounds existing decimals to integers for whole-number units

**`calculateItemSubtotal()`:**
- Validates quantity based on step attribute
- Automatically rounds to integer if step=1

### 3. Styling
Added CSS for unit badges and Select2 sizing:
```css
.item-unit-badge {
    min-width: 50px;
    background-color: #e9ecef;
    font-weight: 600;
    color: #495057;
}
```

## How It Works

### User Flow:
1. **Click "+ Add Item"** button
2. **Search and select service** from dropdown (with search bar)
3. **Unit badge automatically appears** (Kg, Pc, Set, etc.)
4. **Quantity input adjusts:**
   - If Kg or Sq.ft: Can enter decimals (e.g., 2.5)
   - If Pc, Set, or Pair: Only whole numbers (e.g., 3)
5. **Price auto-fills** from service
6. **Subtotal calculates** automatically

### Example Scenarios:

**Scenario 1: Normal Wash (Kg)**
- Select: "Normal Wash - Kg"
- Unit badge shows: **Kg**
- Quantity: Can enter 2.5, 3.75, etc.
- Price: MYR 4.00/kg
- Subtotal: 2.5 × 4.00 = MYR 10.00

**Scenario 2: Comforter (Pc)**
- Select: "Comforter/Toto - Pc"
- Unit badge shows: **Pc**
- Quantity: Can only enter 1, 2, 3 (whole numbers)
- If user enters 2.7, it auto-rounds to 3
- Price: MYR 21.50/pc
- Subtotal: 3 × 21.50 = MYR 64.50

## Debugging

Added console log to check services loading:
```javascript
console.log('Services loaded:', services.length);
```

Check browser console to verify services are loaded correctly.

## Requirements

### Database:
- Services must have `unit_id` linked to `units` table
- Units table must have records: Kg, Pc, Set, Sq.ft, Pair

### Frontend Libraries:
- ✅ Select2 (already included in layout)
- ✅ jQuery (already included)
- ✅ Feather Icons (already included)

## Testing Checklist

- [ ] Service dropdown shows all services with search
- [ ] Unit badges display correctly (Kg, Pc, Set, Sq.ft, Pair)
- [ ] Kg services allow decimal quantities (e.g., 2.5)
- [ ] Sq.ft services allow decimal quantities (e.g., 10.75)
- [ ] Pc services only allow whole numbers (1, 2, 3)
- [ ] Set services only allow whole numbers
- [ ] Pair services only allow whole numbers
- [ ] Entering 2.7 for Pc auto-rounds to 3
- [ ] Price auto-fills when service selected
- [ ] Subtotal calculates correctly
- [ ] Search in dropdown works
- [ ] Multiple items can be added
- [ ] Delete button removes items

## Notes

- Unit validation happens on both change and blur events
- Decimal rounding uses `Math.round()` for whole-number units
- Select2 dropdowns are scoped to their parent to avoid z-index issues
- Each service dropdown is independently searchable


