<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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

        // Only for the database and pgsql drivers
        DB::statement('ALTER TABLE pages ADD searchable tsvector NULL');
        DB::statement('CREATE INDEX posts_searchable_index ON pages USING GIN (searchable)');

        // Or alternatively
//        DB::statement('ALTER TABLE pages ADD searchable tsvector NULL');
//        DB::statement('CREATE INDEX posts_searchable_index ON posts USING GIST (searchable)');
    }
};
