<?php

namespace App\Console\Commands;

use App\Models\Meilisearch;
use App\Models\Typesense;
use Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CopyAdsToPagesCommand extends Command
{
    protected $signature = 'copy:ads-to-pages
    {--driver= : The driver to be used: pgsql, meilisearch or typesense}';

    protected $description = 'Command description';

    public function handle(): void
    {
        $faker = fake();
        $chunk = 1000;
        $bar   = $this->output->createProgressBar(500_000);
        $bar->start();

        match ($this->option('driver')) {
            'meilisearch' => $this->meilisearchInsert($bar, $chunk),
            'typesense'   => $this->typesenseInsert($bar, $chunk),
            'pgsql'       => $this->pgsqlInsert($faker, $bar, $chunk),
        };

        $bar->finish();
    }

    private function meilisearchInsert($bar, $chunk): void
    {
        Artisan::call('scout:flush', ["model" => Meilisearch\Page::class]);

        Meilisearch\Page::chunk($chunk, function (Collection $pages) use ($chunk, $bar) {
            [, $duration] = Benchmark::value(fn() => $pages->searchable());
            $bar->advance($chunk);

            $this->info("Searchable {$bar->getProgress()} pages in $duration seconds");
        });
    }

    private function typesenseInsert($bar, $chunk): void
    {
        Artisan::call('scout:flush', ["model" => Typesense\Page::class]);

        Typesense\Page::chunk($chunk, function (Collection $pages) use ($chunk, $bar) {
            [, $duration] = Benchmark::value(fn() => $pages->searchable());
            $bar->advance($chunk);

            $this->info("Searchable {$bar->getProgress()} pages in $duration seconds");
        });
    }

    private function pgsqlInsert($faker, $bar, $chunk): void
    {
        DB::table('ads')->orderBy('id')->chunk($chunk, function (Collection $ads) use ($faker, $bar, $chunk) {
            $durations = collect();
            foreach ($ads as $ad) {
                [, $duration] = Benchmark::value(fn() => DB::table('pages')->insert([
                    'title'    => $faker->title,
                    'url'      => $faker->url,
                    'sections' => $ad->json,
                ]));

                $durations->push($duration);
            }

            $bar->advance($chunk);

            $this->info("Inserted {$bar->getProgress()} pages in {$durations->avg()} seconds");
        });
    }
}
