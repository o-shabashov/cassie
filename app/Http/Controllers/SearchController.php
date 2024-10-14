<?php

namespace App\Http\Controllers;

use App\Auth;
use App\Models\Meilisearch\MeilisearchPage;
use App\Models\Meilisearch\MeilisearchProduct;
use App\Models\Pgsql\PgsqlPage;
use App\Models\Pgsql\PgsqlProduct;
use App\Models\Typesense\TypesensePage;
use App\Models\Typesense\TypesenseProduct;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $settings = Auth::user()->settings;

        $query = $request->validated('q');
        $type  = $request->validated('type');

        $results = match ($type) {
            'page' => match ($settings->search_engine) {
                'typesense'   => TypesensePage::search($query)->get(),
                'meilisearch' => MeilisearchPage::search($query)->get(),
                default       => PgsqlPage::search($query)->get(),
            },

            'product' => match ($settings->search_engine) {
                'typesense'   => TypesenseProduct::search($query)->get(),
                'meilisearch' => MeilisearchProduct::search($query)->get(),
                default       => PgsqlProduct::search($query)->get(),
            },
        };

        return $results->paginate();
    }
}
