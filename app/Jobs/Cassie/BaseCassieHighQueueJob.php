<?php

namespace App\Jobs\Cassie;

use App\DTOs\ShopifyUserDto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class BaseCassieHighQueueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(public ShopifyUserDto $shopifyUserDto)
    {
        $this->onQueue(config('queue.cassie.cassie_high'));
    }
}
