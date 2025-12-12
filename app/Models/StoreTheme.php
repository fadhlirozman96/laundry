<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreTheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
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

    protected $casts = [
        'layout_settings' => 'array',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}

