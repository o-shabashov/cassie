<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSessionsUserIdType extends Migration
{
    public function up()
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->bigInteger('user_id')->nullable()->change();
        });
    }

    public function down()
    {
        //
    }
}
