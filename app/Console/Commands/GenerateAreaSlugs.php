<?php

namespace App\Console\Commands;

use App\Models\LocatedArea;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateAreaSlugs extends Command
{
    protected $signature = 'generate:slugs:areas';

    protected $description = 'Генерує slug для всіх записів у located_area';

    public function handle()
    {
        $areas = LocatedArea::whereNull('slug')->get();
        $this->info('Знайдено записів без slug: '.$areas->count());

        foreach ($areas as $area) {
            $slug = Str::slug($area->area, '-');
            if (LocatedArea::where('slug', $slug)->exists()) {
                $slug .= '-'.$area->id;
            }

            $area->slug = $slug;
            $area->save();

            $this->info("Згенеровано slug: {$slug} для {$area->area}");
        }

        $this->info('Генерація slug завершена!');
    }
}
