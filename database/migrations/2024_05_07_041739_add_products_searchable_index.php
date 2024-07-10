<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(
            "ALTER TABLE products ADD searchable tsvector generated always as(jsonb_to_tsvector('english', fields, '[\"all\"]')) stored"
        );
        DB::statement('CREATE INDEX products_searchable_index ON products USING GIN (searchable)');
        DB::statement('CREATE INDEX products_titles_trgm_index ON products USING GIN (title gin_trgm_ops)');
    }
};
