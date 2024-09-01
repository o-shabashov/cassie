<?php

namespace App\Http\Controllers;

use App\Jobs\DoFullReindexJob;
use Illuminate\Http\Request;

class IndexationController extends Controller
{
    public function fullReindex(Request $request)
    {
        DoFullReindexJob::dispatch($request->user()->shopify_session);
    }
}
