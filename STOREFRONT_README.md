# Store Frontend Website

Each store in the system now has its own public-facing website, styled similar to The Laundress website.

## URL Structure

- **Homepage**: `laundry3.test/{store_slug}`
- **All Products**: `laundry3.test/{store_slug}/products`
- **Product Detail**: `laundry3.test/{store_slug}/product/{product_slug}`
- **Category Page**: `laundry3.test/{store_slug}/category/{category_slug}`

## Example URLs

If you have a store with slug `johns-restaurant-downtown`:

- Homepage: `laundry3.test/johns-restaurant-downtown`
- Products: `laundry3.test/johns-restaurant-downtown/products`
- Product: `laundry3.test/johns-restaurant-downtown/product/product-name`
- Category: `laundry3.test/johns-restaurant-downtown/category/category-name`

## Features

### 1. **Homepage**
- Hero section with call-to-action
- Featured products grid
- Category showcase
- Newsletter subscription

### 2. **Products Page**
- Filterable product listing
- Category and brand filters
- Search functionality
- Sort options (newest, price, name)
- Pagination

### 3. **Product Detail Page**
- Large product images
- Product information
- Related products
- Add to cart functionality
- Stock status

### 4. **Category Page**
- Products filtered by category
- Pagination

## Design Features

- Clean, minimalist design inspired by The Laundress
- Responsive layout (mobile-friendly)
- Sticky navigation
- Search functionality
- Shopping cart icon with count
- Modern typography using Inter font

## Files Created

### Controllers
- `app/Http/Controllers/StoreFrontController.php` - Handles all storefront routes

### Views
- `resources/views/storefront/layout.blade.php` - Main layout
- `resources/views/storefront/index.blade.php` - Homepage
- `resources/views/storefront/products.blade.php` - Products listing
- `resources/views/storefront/product.blade.php` - Product detail
- `resources/views/storefront/category.blade.php` - Category page

### Assets
- `public/css/storefront.css` - Storefront styles
- `public/js/storefront.js` - Storefront JavaScript

### Routes
- Added storefront routes in `routes/web.php`

## How It Works

1. Each store has a unique `slug` field
2. The storefront routes use this slug to identify which store's website to display
3. Products are automatically filtered by `store_id`
4. Only active stores and active products are shown
5. Categories and brands are filtered to show only those with products for the current store

## Testing

1. Make sure you have stores with slugs in your database
2. Visit `laundry3.test/{your-store-slug}` to see the storefront
3. Example: If you seeded test data, try:
   - `laundry3.test/johns-restaurant-downtown`
   - `laundry3.test/johns-restaurant-mall`
   - `laundry3.test/emmas-boutique-central`

## Notes

- The cart functionality is currently a placeholder (shows alert)
- Newsletter subscription is a placeholder
- Images should be stored in `public/uploads/products/` and `public/uploads/categories/`
- The design is responsive and works on mobile devices
- Store routes are protected from conflicting with admin routes

## Future Enhancements

- Implement actual shopping cart functionality
- Add checkout process
- Implement newsletter subscription
- Add product reviews/ratings
- Add wishlist functionality
- Add product image zoom/lightbox
- Add more filtering options
- Add product comparison

