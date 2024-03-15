<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->jsonb('sections');
            $table->string('url');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement(
            "ALTER TABLE pages ADD searchable tsvector generated always as(jsonb_to_tsvector('english', sections, '[\"all\"]')) stored"
        );

        DB::statement("CREATE EXTENSION pg_trgm");
        DB::statement("CREATE INDEX pages_searchable_index ON pages USING GIN (searchable)");
        DB::statement("CREATE INDEX titles_trgm_index ON pages USING GIN (title gin_trgm_ops)");
    }

    public function down(): void
    {
        Schema::drop('pages');
    }
};
