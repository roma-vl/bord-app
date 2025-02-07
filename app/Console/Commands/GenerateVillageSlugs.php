<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LocatedVillage;
use Illuminate\Support\Str;

class GenerateVillageSlugs extends Command
{
    protected $signature = 'generate:slugs:village';
    protected $description = 'Генерує slug для всіх сіл у базі даних';

    public function handle()
    {
        $villages = LocatedVillage::whereNull('slug')->orWhere('slug', '')->get();
        $this->info("Знайдено записів без slug: " . $villages->count());

        foreach ($villages as $village) {
            $slug = Str::slug($village->village, '-');

            // Унікальність slug
            $count = LocatedVillage::where('slug', 'LIKE', $slug . '%')->count();
            if ($count) {
                $slug .= '-' . ($count + 1);
            }

            $village->slug = $slug;
            $village->save();
            $this->info("Оновлено: {$village->village} -> {$slug}");
        }

        $this->info("✅ Генерація slug завершена!");
    }
}
