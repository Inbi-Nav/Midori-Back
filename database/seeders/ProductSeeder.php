<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
      
        $products = [

            [
              "name"=> "Incense Holder",
              "description"=> "Elegant minimalist incense holder featuring a small black cat figurine.",
              "price"=> 8.99,
              "stock"=> 25,
              "material"=> "ceramic",
              "color"=> "black",
              "image_url"=> "images/products/incense-holder.png",
              "category_id"=> 6
            ],
            [
              "name"=> "Cutlery Set",
              "description"=> "Reusable bamboo cutlery set for sustainable daily dining.",
              "price"=> 12.55,
              "stock"=> 20,
              "material"=> "bamboo",
              "color"=> "light wood brown",
              "image_url"=> "images/products/bamboo-cutlery-set-1.png",
              "category_id"=> 1
            ],
            [
              "name"=> "Bamboo Lunch Box",
              "description"=> "Minimalist bamboo bento box with compartments.",
              "price"=> 9.90,
              "stock"=> 18,
              "material"=> "bamboo",
              "color"=> "light wood brown",
              "image_url"=> "images/products/bento-1.png",
              "category_id"=> 1
            ],
            [
              "name"=> "Glass Water Bottle",
              "description"=> "Reusable glass bottle with lucky cat illustration.",
              "price"=> 6.29,
              "stock"=> 30,
              "material"=> "glass",
              "color"=> "transparent",
              "image_url"=> "images/products/bottle-water-1.png",
              "category_id"=> 1
            ],
            [
              "name"=> " Napkin Set",
              "description"=> "Reusable fabric napkins with Japanese-style illustration.",
              "price"=> 6.17,
              "stock"=> 24,
              "material"=> "fabric",
              "color"=> "white and black",
              "image_url"=> "images/products/napkin-set.png",
              "category_id"=> 1
            ],
            [
              "name"=> "Ceramic Plate Set",
              "description"=> "Ceramic plate set in neutral tones.",
              "price"=> 9.99,
              "stock"=> 14,
              "material"=> "ceramic",
              "color"=> "neutral tones",
              "image_url"=> "images/products/Plate-set-1.png",
              "category_id"=> 1
            ],
            [
              "name"=> "Ceramic Plate Set – Earth Tones",
              "description"=> "Ceramic plates in earth tones.",
              "price"=> 11.89,
              "stock"=> 12,
              "material"=> "ceramic",
              "color"=> "earth tones",
              "image_url"=> "images/products/Plate-set-2.png",
              "category_id"=> 1
            ],

            [
              "name"=> "Thermos Bottle",
              "description"=> "Thermos bottle with traditional wave illustration.",
              "price"=> 9.19,
              "stock"=> 22,
              "material"=> "stainless steel",
              "color"=> "off white and blue",
              "image_url"=> "images/products/thermos-bottle.png",
              "category_id"=> 1
            ],
            [
              "name"=> "Bamboo Travel Cutlery Set",
              "description"=> "Reusable bamboo travel cutlery with fabric case.",
              "price"=> 22.9,
              "stock"=> 28,
              "material"=> "bamboo and fabric",
              "color"=> "light wood brown and green",
              "image_url"=> "images/products/wooden-cutlery.png",
              "category_id"=> 6
            ],
            
            [
              "name"=> "Bamboo Matcha Whisk (Chasen)",
              "description"=> "Traditional bamboo whisk for matcha preparation.",
              "price"=> 19.9,
              "stock"=> 40,
              "material"=> "bamboo",
              "color"=> "light wood brown",
              "image_url"=> "images/products/chasen-1.png",
              "category_id"=> 2
            ],
            [
              "name"=> "Matcha Utensil Set",
              "description"=> "Complete matcha preparation utensil set.",
              "price"=> 24.9,
              "stock"=> 22,
              "material"=> "bamboo and metal",
              "color"=> "light wood brown",
              "image_url"=> "images/products/matcha-utensil-set.png",
              "category_id"=> 6
            ],
            
            [
              "name"=> "Hand-Painted Ceramic Tea Bowl",
              "description"=> "Tea bowl with Japanese mountain illustration.",
              "price"=> 15.49,
              "stock"=> 18,
              "material"=> "ceramic",
              "color"=> "off white and blue",
              "image_url"=> "images/products/tea-pot.png",
              "category_id"=> 2
            ],
            [
              "name"=> "Ceramic Tea Set",
              "description"=> "Traditional japanese style ceramic teapot with matching cups.",
              "price"=> 20.9,
              "stock"=> 7,
              "material"=> "ceramic",
              "color"=> "dark green",
              "image_url"=> "images/products/tea-set-2.png",
              "category_id"=> 2
            ],
            
            [
              "name"=> "Ceramic Vase",
              "description"=> "Decorative rustic ceramic vase.",
              "price"=> 39.9,
              "stock"=> 12,
              "material"=> "ceramic",
              "color"=> "dark brown",
              "image_url"=> "images/products/decorative-vase.png",
              "category_id"=> 3
            ],
            [
              "name"=> "Wooden Desk Shelf Organizer",
              "description"=> "Three-tier wooden desk shelf.",
              "price"=> 29.59,
              "stock"=> 10,
              "material"=> "wood",
              "color"=> "wood brown",
              "image_url"=> "images/products/desk-organizer-2.png",
              "category_id"=> 3
            ],
            [
              "name"=> "Paper Lantern Set",
              "description"=> "Decorative Japanese-style paper lanterns.",
              "price"=> 8.99,
              "stock"=> 8,
              "material"=> "paper",
              "color"=> "multicolor",
              "image_url"=> "images/products/paper-lantern.png",
              "category_id"=> 3
            ],
            [
              "name"=> "Paper Lantern Set – Cherry Blossom",
              "description"=> "Japanese Paper lanterns with cherry blossom illustration.",
              "price"=> 9.99,
              "stock"=> 6,
              "material"=> "paper",
              "color"=> "white and red",
              "image_url"=> "images/products/paper-lantern-2.png",
              "category_id"=> 3
            ],
            [
              "name"=> "Calligraphy Table Lamp",
              "description"=> "Table lamp with Japanese calligraphy shade.",
              "price"=> 47.9,
              "stock"=> 9,
              "material"=> "wood and paper",
              "color"=> "light wood brown and off white",
              "image_url"=> "images/products/table-lamp.png",
              "category_id"=> 3
            ],
            [
              "name"=> "Table Lamp",
              "description"=> "Ambient lamp with cherry blossom artwork.",
              "price"=> 24.9,
              "stock"=> 11,
              "material"=> "glass and wood",
              "color"=> "pink and off white",
              "image_url"=> "images/products/table-lamp-2.png",
              "category_id"=> 3
            ],
            [
              "name"=> "Yin Yang Wall Art",
              "description"=> "Decorative wall art featuring Yin Yang and elements.",
              "price"=> 20.9,
              "stock"=> 6,
              "material"=> "fabric",
              "color"=> "earth tones",
              "image_url"=> "images/products/wall-decoration-2.png",
              "category_id"=> 3
            ],
            [
              "name"=> "Landscape Wall Hanging",
              "description"=> "Textile wall hanging with Japanese landscape.",
              "price"=> 21.9,
              "stock"=> 9,
              "material"=> "fabric",
              "color"=> "soft pink and off white",
              "image_url"=> "images/products/wall-decoration.png",
              "category_id"=> 3
            ],
            
            [
              "name"=> "No-Face Desk Organizer",
              "description"=> "Desk organizer inspired by No-Face from Studio Ghibli.",
              "price"=> 27.9,
              "stock"=> 15,
              "material"=> "ceramic",
              "color"=> "black and white",
              "image_url"=> "images/products/desk.organizer.png",
              "category_id"=> 4
            ],
            [
              "name"=> "Ceramic Mug",
              "description"=> "Ceramic mug with Soot Sprite design.",
              "price"=> 16.9,
              "stock"=> 35,
              "material"=> "ceramic",
              "color"=> "white",
              "image_url"=> "images/products/mug-1.png",
              "category_id"=> 4
            ],
            [
              "name"=> "Planter Pot",
              "description"=> "Planter inspired by Ghibli character.",
              "price"=> 15.9,
              "stock"=> 14,
              "material"=> "ceramic",
              "color"=> "multicolor",
              "image_url"=> "images/products/Pot-1.png",
              "category_id"=> 4
            ],
            [
              "name"=> "Totoro Inspired Planter Set",
              "description"=> "Totoro-inspired planter pots set.",
              "price"=> 15.9,
              "stock"=> 16,
              "material"=> "ceramic",
              "color"=> "grey and beige",
              "image_url"=> "images/products/Pot-2.png",
              "category_id"=> 4
            ],
            [
              "name"=> "Studio Ghibli Figurine Set",
              "description"=> "Ghibli-inspired collectible figurines.",
              "price"=> 16.9,
              "stock"=> 17,
              "material"=> "resin",
              "color"=> "multicolor",
              "image_url"=> "images/products/toy-set.png",
              "category_id"=> 4
            ],
            [
              "name"=> "Totoro Mini Figurine Collection",
              "description"=> "Mini Totoro figurines collection.",
              "price"=> 12.9,
              "stock"=> 12,
              "material"=> "resin",
              "color"=> "multicolor",
              "image_url"=> "images/products/toy-set-2.png",
              "category_id"=> 4
            ],
            
            [
              "name"=> "Funko Pop! Gen Narumi",
              "description"=> "Official Funko Pop! Animation Gen Narumi.",
              "price"=> 14.9,
              "stock"=> 25,
              "material"=> "vinyl",
              "color"=> "light brown and black",
              "image_url"=> "images/products/funko-1.png",
              "category_id"=> 5
            ],
            
            [
              "name"=> "Bamboo Cutlery Set – Compact",
              "description"=> "Compact bamboo cutlery set for everyday meals.",
              "price"=> 17.9,
              "stock"=> 15,
              "material"=> "bamboo",
              "color"=> "light wood brown",
              "image_url"=> "images/products/bamboo-cutlery-set-2.png",
              "category_id"=> 1
            ],
            [
              "name"=> "Incense Holder – Ceramic",
              "description"=> "Minimal ceramic incense holder with modern design.",
              "price"=> 9.99,
              "stock"=> 18,
              "material"=> "ceramic",
              "color"=> "black",
              "image_url"=> "images/products/incense-holder-2.png",
              "category_id"=> 1
            ],          

            [
              "name"=> "Matcha Ceremonial Set",
              "description"=> "Complete ceremonial matcha set for traditional tea rituals.",
              "price"=> 35.9,
              "stock"=> 6,
              "material"=> "ceramic and bamboo",
              "color"=> "earth tones",
              "image_url"=> "images/products/matcha-ceremonial-set.png",
              "category_id"=> 2
            ],
            [
              "name"=> "Matcha Tea Set – Minimal",
              "description"=> "Minimal matcha tea set for everyday preparation.",
              "price"=> 49.9,
              "stock"=> 12,
              "material"=> "ceramic",
              "color"=> "light green",
              "image_url"=> "images/products/matcha-set-1.png",
              "category_id"=> 2
            ],
            [
              "name"=> "Matcha Tea Set – Dark",
              "description"=> "Dark ceramic matcha set with traditional finish.",
              "price"=> 54.9,
              "stock"=> 10,
              "material"=> "ceramic",
              "color"=> "dark green",
              "image_url"=> "images/products/matcha-set-2.png",
              "category_id"=> 2
            ],
            [
              "name"=> "Matcha Tea Set – Natural",
              "description"=> "Natural tone matcha set inspired by Japanese tea rooms.",
              "price"=> 52.9,
              "stock"=> 9,
              "material"=> "ceramic",
              "color"=> "beige",
              "image_url"=> "images/products/matcha-set-3.png",
              "category_id"=> 2
            ],          

            [
              "name"=> "Ghibli Inspired Plush Toy",
              "description"=> "Soft plush toy inspired by Studio Ghibli characters.",
              "price"=> 8.74,
              "stock"=> 20,
              "material"=> "fabric",
              "color"=> "black",
              "image_url"=> "images/products/ghibli-plushie.png",
              "category_id"=> 4
            ],
            [
              "name"=> "Ghibli Figurine Collection",
              "description"=> "Collection of Studio Ghibli inspired figurines.",
              "price"=> 33.69,
              "stock"=> 8,
              "material"=> "resin",
              "color"=> "multicolor",
              "image_url"=> "images/products/funko-collection.png",
              "category_id"=> 6
            ],          

            [
              "name"=> "Funko Pop! Anime Figure",
              "description"=> "Official Funko Pop! anime collectible figure.",
              "price"=> 14.9,
              "stock"=> 20,
              "material"=> "vinyl",
              "color"=> "multicolor",
              "image_url"=> "images/products/funko-2.png",
              "category_id"=> 5
            ],
            [
              "name"=> "Funko Pop! Anime Figure – Variant",
              "description"=> "Funko Pop! anime figure variant edition.",
              "price"=> 15.9,
              "stock"=> 18,
              "material"=> "vinyl",
              "color"=> "multicolor",
              "image_url"=> "images/products/funko-3.png",
              "category_id"=> 5
            ],
            [
              "name"=> "Funko Pop! Anime Figure – Special",
              "description"=> "Special edition Funko Pop! anime figure.",
              "price"=> 17.9,
              "stock"=> 12,
              "material"=> "vinyl",
              "color"=> "multicolor",
              "image_url"=> "images/products/funko-4.png",
              "category_id"=> 5
            ],          
        ];
        foreach ($products as $product) {
        Product::create(array_merge($product, ['user_id' => 3]));
    }
        $this->command->info('Productos creados y asignados al proveedor ID 3');
  }
}