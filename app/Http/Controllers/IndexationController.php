<?php

namespace App\Http\Controllers;

use App\Jobs\Cassie\DoFullReindexJob;
use Illuminate\Http\Request;

class IndexationController extends Controller
{
    public function fullReindex(Request $request)
    {
        // TODO get this settings from the `session` table based on the Bearer access_token in header
        $session = new \Shopify\Auth\Session(
            id      : 'offline_quickstart-dbca5a72.myshopify.com',
            shop    : 'quickstart-dbca5a72.myshopify.com',
            isOnline: false,
            state   : ''
        );
        $session->setAccessToken('shpua_912c33220eca761ae9b9c3fa15fc062b');

        DoFullReindexJob::dispatch($session)->onQueue('cassie_high');
    }
}
