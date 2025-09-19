<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // make sure storage dir exists
        Storage::disk('public')->makeDirectory('products');

        $imagesDir = base_path('database/seeders/product_images');

        // gather image files in folder
        $files = array_values(array_filter(scandir($imagesDir), function ($f) use ($imagesDir) {
            $full = $imagesDir . DIRECTORY_SEPARATOR . $f;
            return is_file($full) && preg_match('/\.(jpe?g|png|gif|webp)$/i', $f);
        }));

        if (empty($files)) {
            $this->command->warn("No sample images found in {$imagesDir}. Add files named by SKU or slug (e.g. WD-DSL-001.jpg).");
            return;
        }

        $products = [
            [
                'title' => 'Satin Slip Dress',
                'sku' => 'WD-DSL-001',
                'price' => 79.99,
                'brand' => 'Maison',
                'description' => 'Elegant satin slip dress for evenings.',
                'category_slugs' => ['women', 'sale'],
            ],
            [
                'title' => 'Denim Jacket',
                'sku' => 'MN-JCK-001',
                'price' => 99.00,
                'brand' => 'UrbanTailor',
                'description' => 'Classic denim jacket with relaxed fit.',
                'category_slugs' => ['men', 'outerwear'],
            ],
            [
                'title' => 'Leather Chelsea Boots',
                'sku' => 'SH-CHS-002',
                'price' => 149.50,
                'brand' => 'Northfield',
                'description' => 'Premium leather Chelsea boots with rubber sole.',
                'category_slugs' => ['men', 'shoes', 'new'],
            ],
            [
                'title' => 'Cashmere Scarf',
                'sku' => 'AC-SCF-003',
                'price' => 59.00,
                'brand' => 'Loft & Lane',
                'description' => 'Soft cashmere scarf — lightweight and warm.',
                'category_slugs' => ['women', 'accessories'],
            ],
            [
                'title' => 'Sport Running Sneakers',
                'sku' => 'SH-RUN-004',
                'price' => 129.99,
                'brand' => 'Fleet',
                'description' => 'Cushioned running sneakers for road and trail.',
                'category_slugs' => ['men', 'women', 'shoes'],
            ],
            [
                'title' => 'Organic Cotton T-Shirt',
                'sku' => 'UN-TSH-005',
                'price' => 24.00,
                'brand' => 'FieldBasics',
                'description' => 'Breathable organic cotton tee — everyday essential.',
                'category_slugs' => ['unisex', 'basics'],
            ],
            [
                'title' => 'Mini Crossbody Bag',
                'sku' => 'AC-CBX-006',
                'price' => 45.00,
                'brand' => 'PetiteRoute',
                'description' => 'Compact crossbody bag with adjustable strap.',
                'category_slugs' => ['women', 'accessories', 'sale'],
            ],
            [
                'title' => 'Kids Denim Overalls',
                'sku' => 'KD-OVR-007',
                'price' => 39.99,
                'brand' => 'PlayPatch',
                'description' => 'Durable denim overalls made for playtime.',
                'category_slugs' => ['kids', 'children', 'clothing'],
            ],
            [
                'title' => 'Aromatherapy Candle - Lavender',
                'sku' => 'HM-CND-008',
                'price' => 18.50,
                'brand' => 'Calm & Co',
                'description' => 'Soy wax candle with soothing lavender scent.',
                'category_slugs' => ['home', 'beauty'],
            ],
            [
                'title' => 'Slim Fit Chinos',
                'sku' => 'MN-CHN-009',
                'price' => 65.00,
                'brand' => 'CoveWear',
                'description' => 'Smart-casual slim fit chinos with stretch.',
                'category_slugs' => ['men', 'bottoms', 'new'],
            ],
            [
                'title' => 'Silk Pillowcase',
                'sku' => 'HM-PIL-010',
                'price' => 34.75,
                'brand' => 'LunaSilk',
                'description' => 'Mulberry silk pillowcase for hair and skin care.',
                'category_slugs' => ['home', 'beauty', 'gift'],
            ],
            [
                'title' => 'Waterproof Parka',
                'sku' => 'WN-PRK-011',
                'price' => 199.00,
                'brand' => 'Boreal',
                'description' => 'Heavy-duty waterproof parka with insulated lining.',
                'category_slugs' => ['women', 'outerwear', 'winter'],
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($products as $i => $p) {
                $slug = Str::slug($p['title']);

                $product = Product::updateOrCreate(
                    ['sku' => $p['sku']],
                    [
                        'title' => $p['title'],
                        'slug' => $slug,
                        'description' => $p['description'],
                        'price' => $p['price'],
                        'brand' => $p['brand'],
                        'meta' => ['seeded' => true],
                        'active' => true,
                        'position' => $i + 1,
                        // optionally set discount_percent here if needed
                    ]
                );

                // attach categories by slug
                if (!empty($p['category_slugs'])) {
                    $categoryIds = Category::whereIn('slug', $p['category_slugs'])->pluck('id')->toArray();
                    if ($categoryIds) {
                        $product->categories()->syncWithoutDetaching($categoryIds);
                    }
                }

                // ---------- SINGLE IMAGE LOGIC ----------
                // Try to find a matching file by SKU (preferred), then slug, else fallback to rotate.
                $matchedFile = null;
                $skuPattern = strtolower($p['sku']);
                $slugPattern = strtolower($slug);

                foreach ($files as $f) {
                    $name = pathinfo($f, PATHINFO_FILENAME);
                    if (strtolower($name) === $skuPattern) {
                        $matchedFile = $f;
                        break;
                    }
                }

                if (!$matchedFile) {
                    foreach ($files as $f) {
                        $name = pathinfo($f, PATHINFO_FILENAME);
                        if (strtolower($name) === $slugPattern) {
                            $matchedFile = $f;
                            break;
                        }
                    }
                }

                // fallback: rotate through files deterministically by index
                if (!$matchedFile) {
                    $matchedFile = $files[$i % count($files)];
                    $this->command->warn("No exact-matching image for SKU {$p['sku']} or slug {$slug}. Using fallback {$matchedFile}.");
                }

                $srcFile = $imagesDir . DIRECTORY_SEPARATOR . $matchedFile;
                $ext = pathinfo($srcFile, PATHINFO_EXTENSION);
                $fileHash = substr(md5_file($srcFile), 0, 8);
                // stable filename in storage so re-running doesn't create duplicates
                $destFilename = "{$product->sku}.{$ext}";
                $destPath = 'products/' . $destFilename;

                if (!Storage::disk('public')->exists($destPath)) {
                    Storage::disk('public')->put($destPath, file_get_contents($srcFile));
                    $this->command->info("Copied image to storage: {$destPath}");
                } else {
                    $this->command->info("Image already exists in storage: {$destPath}");
                }

                // remove any old images for this product (if you want to ensure single image)
                ProductImage::where('product_id', $product->id)->delete();

                // create the ProductImage row (single image)
                ProductImage::create([
                    'product_id' => $product->id,
                    'variant_id' => null,
                    'path' => $destPath,
                    'alt' => $product->title . ' - primary',
                    'position' => 1,
                    'is_primary' => true,
                ]);

                $this->command->info("Seeded product: {$product->title} with image {$destPath}");
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->command->error("Product seeder failed: " . $e->getMessage());
            throw $e;
        }
    }
}
