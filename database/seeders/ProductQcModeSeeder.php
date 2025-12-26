<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductQcModeSeeder extends Seeder
{
    public function run(): void
    {
        // Get all products
        $products = Product::all();
        
        foreach ($products as $product) {
            $name = strtolower($product->name);
            
            // Detect unit type and QC mode based on product name
            if (str_contains($name, 'set') || 
                str_contains($name, 'bedsheet') || 
                str_contains($name, 'curtain')) {
                // SET-BASED
                $product->update([
                    'unit_type' => 'set',
                    'qc_mode' => 'completeness',
                    'set_components' => $this->getSetComponents($name)
                ]);
            } 
            elseif (str_contains($name, 'kg') || 
                    str_contains($name, 'load') || 
                    str_contains($name, 'wash') ||
                    str_contains($name, 'mixed')) {
                // KG-BASED
                $product->update([
                    'unit_type' => 'kg',
                    'qc_mode' => 'integrity'
                ]);
            }
            elseif (str_contains($name, 'carpet') || 
                    str_contains($name, 'sqft') ||
                    str_contains($name, 'rug')) {
                // SQFT-BASED
                $product->update([
                    'unit_type' => 'sqft',
                    'qc_mode' => 'identity'
                ]);
            }
            else {
                // PIECE-BASED (default)
                $product->update([
                    'unit_type' => 'piece',
                    'qc_mode' => 'count'
                ]);
            }
        }
        
        $this->command->info('Product QC modes updated successfully!');
    }
    
    private function getSetComponents($name)
    {
        if (str_contains($name, 'bedsheet')) {
            return ['Bedsheet', 'Pillowcase', 'Duvet Cover'];
        }
        elseif (str_contains($name, 'curtain')) {
            return ['Curtain Panel', 'Tiebacks'];
        }
        elseif (str_contains($name, 'comforter')) {
            return ['Comforter', 'Pillowcase'];
        }
        
        return null;
    }
}

