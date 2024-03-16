<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CopyAdsToPagesCommand extends Command
{
    protected $signature = 'copy:ads-to-pages';

    protected $description = 'Command description';

    public function handle(): void
    {
        $faker = fake();
        $chunk = 10_000;
        $bar = $this->output->createProgressBar(500_000);
        $bar->start();

        DB::table('ads')->orderBy('id')->chunk($chunk, function (Collection $ads) use ($faker, $bar, $chunk) {
//            DB::table('pages')->truncate();
            $durations = collect();
            foreach ($ads as $ad) {
                [$count, $duration] = Benchmark::value(fn () => DB::table('pages')->insert([
                    'title'      => $faker->title,
                    'url'        => $faker->url,
                    'sections'   => $ad->json,
                ]));

                $durations->push($duration);
            }

            $bar->advance($chunk);
            $this->info("Inserted {$bar->getProgress()} pages in {$durations->avg()} seconds");
        });

        $bar->finish();
    }
}
