<?php

namespace App\Http\Controllers;

use App\Models\Meilisearch;
use App\Models\Pgsql;
use App\Models\Typesense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $settings = Auth::user()->settings;

        $query = $request->validated('q');
        $type  = $request->validated('type');

        $results = match ($type) {
            'page' => match ($settings->search_engine) {
                'typesense'   => Typesense\Page::search($query)->get(),
                'meilisearch' => Meilisearch\Page::search($query)->get(),
                default       => Pgsql\PgsqlPage::search($query)->get(),
            },

            'product' => match ($settings->search_engine) {
                'typesense'   => Typesense\Product::search($query)->get(),
                'meilisearch' => Meilisearch\Product::search($query)->get(),
                default       => Pgsql\PgsqlProduct::search($query)->get(),
            },
        };

        return $results->paginate();
    }
}
