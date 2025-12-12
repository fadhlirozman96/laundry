<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class StoreFrontController extends Controller
{
    /**
     * Get categories for a store
     */
    protected function getStoreCategories($store)
    {
        return Category::whereHas('products', function($query) use ($store) {
            $query->where('store_id', $store->id)
                  ->where('is_active', true);
        })->where('is_active', true)->get();
    }

    /**
     * Get theme for a store
     */
    protected function getStoreTheme($store)
    {
        return $store->theme ?? null;
    }

    /**
     * Display the store homepage
     */
    public function index($slug)
    {
        $store = Store::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get theme settings
        $theme = $this->getStoreTheme($store);

        // Get featured products (active products with stock)
        $featuredProducts = Product::where('store_id', $store->id)
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->with(['category', 'brand'])
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get();

        // Get categories
        $categories = $this->getStoreCategories($store);

        return view('storefront.index', compact('store', 'featuredProducts', 'categories', 'theme'));
    }

    /**
     * Display all products for the store
     */
    public function products($slug, Request $request)
    {
        $store = Store::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $query = Product::where('store_id', $store->id)
            ->where('is_active', true)
            ->with(['category', 'brand']);

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by brand
        if ($request->has('brand') && $request->brand) {
            $query->where('brand_id', $request->brand);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        // Sort
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::whereHas('products', function($q) use ($store) {
            $q->where('store_id', $store->id)->where('is_active', true);
        })->where('is_active', true)->get();
        $brands = Brand::whereHas('products', function($q) use ($store) {
            $q->where('store_id', $store->id)->where('is_active', true);
        })->where('is_active', true)->get();

        $theme = $this->getStoreTheme($store);

        return view('storefront.products', compact('store', 'products', 'categories', 'brands', 'theme'));
    }

    /**
     * Display a single product
     */
    public function product($slug, $productSlug)
    {
        $store = Store::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $product = Product::where('store_id', $store->id)
            ->where('slug', $productSlug)
            ->where('is_active', true)
            ->with(['category', 'brand', 'unit'])
            ->firstOrFail();

        // Get related products (same category)
        $relatedProducts = Product::where('store_id', $store->id)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        $categories = $this->getStoreCategories($store);
        $theme = $this->getStoreTheme($store);
        
        return view('storefront.product', compact('store', 'product', 'relatedProducts', 'categories', 'theme'));
    }

    /**
     * Display products by category
     */
    public function category($slug, $categorySlug)
    {
        $store = Store::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $category = Category::where('slug', $categorySlug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = Product::where('store_id', $store->id)
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->with(['category', 'brand'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = $this->getStoreCategories($store);
        $theme = $this->getStoreTheme($store);
        
        return view('storefront.category', compact('store', 'category', 'products', 'categories', 'theme'));
    }
}

