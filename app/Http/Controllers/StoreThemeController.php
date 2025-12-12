<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreTheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreThemeController extends Controller
{
    /**
     * Display the CMS/Theme editor
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get accessible stores
        if ($user->isSuperAdmin()) {
            $stores = Store::where('is_active', true)->get();
        } elseif ($user->isBusinessOwner()) {
            $stores = Store::where('created_by', $user->id)->where('is_active', true)->get();
        } else {
            $stores = $user->getAccessibleStores();
        }
        
        // Require store selection - if no stores available, show error
        if ($stores->isEmpty()) {
            return redirect()->route('store-list')->with('error', 'No stores available. Please create a store first.');
        }
        
        // Get selected store - prioritize request parameter, then session, then first available
        $selectedStoreId = $request->get('store_id') ?? session('selected_store_id');
        
        // If no store selected, redirect to select first store
        if (!$selectedStoreId) {
            $firstStore = $stores->first();
            return redirect()->route('storefront-cms', ['store_id' => $firstStore->id]);
        }
        
        $selectedStore = Store::find($selectedStoreId);
        
        // Validate store access
        if (!$selectedStore) {
            return redirect()->route('storefront-cms', ['store_id' => $stores->first()->id])->with('error', 'Store not found.');
        }
        
        // Check if user has access to this store
        if (!$user->isSuperAdmin()) {
            if ($user->isBusinessOwner() && $selectedStore->created_by !== $user->id) {
                return redirect()->route('storefront-cms', ['store_id' => $stores->first()->id])->with('error', 'You do not have access to this store.');
            }
            if (!$user->isBusinessOwner() && !$user->stores->contains($selectedStore->id)) {
                return redirect()->route('storefront-cms', ['store_id' => $stores->first()->id])->with('error', 'You do not have access to this store.');
            }
        }
        
        // Get or create theme for selected store
        $theme = StoreTheme::firstOrCreate(
            ['store_id' => $selectedStore->id],
            [
                'primary_color' => '#1a2a2a',
                'secondary_color' => '#f5f5f0',
                'text_color' => '#1a2a2a',
                'background_color' => '#ffffff',
                'accent_color' => '#1a2a2a',
                'heading_font' => 'Playfair Display',
                'body_font' => 'Inter',
                'heading_size' => 36,
                'body_size' => 16,
            ]
        );
        
        return view('storefront-cms', compact('stores', 'selectedStore', 'theme'));
    }

    /**
     * Update theme settings
     */
    public function update(Request $request, $storeId)
    {
        $store = Store::findOrFail($storeId);
        
        // Check permissions
        $user = auth()->user();
        if (!$user->isSuperAdmin() && $store->created_by !== $user->id) {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
            'background_color' => 'nullable|string|max:7',
            'accent_color' => 'nullable|string|max:7',
            'heading_font' => 'nullable|string|max:255',
            'body_font' => 'nullable|string|max:255',
            'heading_size' => 'nullable|integer|min:12|max:72',
            'body_size' => 'nullable|integer|min:10|max:24',
            'hero_title' => 'nullable|string',
            'hero_subtitle' => 'nullable|string',
            'promo_banner_text' => 'nullable|string',
            'footer_text' => 'nullable|string',
            'newsletter_text' => 'nullable|string',
            'custom_css' => 'nullable|string',
            'layout_settings' => 'nullable|array',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,svg|max:512',
            'hero_background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'hero_image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'hero_image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'hero_image_3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_showcase_image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_showcase_image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_showcase_image_3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'scent_indulgent_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'scent_beauty_sleep_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'scent_classic_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'scent_marine_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'article_image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'article_image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'article_image_3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'footer_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $data = $request->only([
            'primary_color',
            'secondary_color',
            'text_color',
            'background_color',
            'accent_color',
            'heading_font',
            'body_font',
            'heading_size',
            'body_size',
            'hero_title',
            'hero_subtitle',
            'promo_banner_text',
            'footer_text',
            'newsletter_text',
            'custom_css',
            'layout_settings',
        ]);
        
        // Handle image uploads
        $imageFields = [
            'logo_image',
            'favicon',
            'hero_background_image',
            'hero_image_1',
            'hero_image_2',
            'hero_image_3',
            'category_showcase_image_1',
            'category_showcase_image_2',
            'category_showcase_image_3',
            'scent_indulgent_image',
            'scent_beauty_sleep_image',
            'scent_classic_image',
            'scent_marine_image',
            'article_image_1',
            'article_image_2',
            'article_image_3',
            'footer_logo',
        ];
        
        $oldTheme = StoreTheme::where('store_id', $storeId)->first();
        
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = 'store_' . $storeId . '_' . $field . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Create directory if it doesn't exist
                $uploadPath = public_path('uploads/storefront');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $file->move($uploadPath, $filename);
                $data[$field] = $filename;
                
                // Delete old image if exists
                if ($oldTheme && $oldTheme->$field) {
                    $oldImagePath = public_path('uploads/storefront/' . $oldTheme->$field);
                    if (file_exists($oldImagePath)) {
                        @unlink($oldImagePath);
                    }
                }
            } elseif ($request->has($field . '_remove') && $request->input($field . '_remove')) {
                // Remove image if remove checkbox is checked
                if ($oldTheme && $oldTheme->$field) {
                    $oldImagePath = public_path('uploads/storefront/' . $oldTheme->$field);
                    if (file_exists($oldImagePath)) {
                        @unlink($oldImagePath);
                    }
                }
                $data[$field] = null;
            }
        }
        
        $theme = StoreTheme::updateOrCreate(
            ['store_id' => $storeId],
            $data
        );
        
        return redirect()->route('storefront-cms', ['store_id' => $storeId])->with('success', 'Theme settings updated successfully!');
    }

    /**
     * Preview website with theme
     */
    public function preview($storeId)
    {
        $store = Store::findOrFail($storeId);
        return redirect()->route('storefront.index', $store->slug);
    }
}

