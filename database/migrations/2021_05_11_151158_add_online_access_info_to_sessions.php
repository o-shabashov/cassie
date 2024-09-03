<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOnlineAccessInfoToSessions extends Migration
{
    public function up()
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->integer('user_id')->nullable();
            $table->string('user_first_name')->nullable();
            $table->string('user_last_name')->nullable();
            $table->string('user_email')->nullable();
            $table->boolean('user_email_verified')->nullable();
            $table->boolean('account_owner')->nullable();
            $table->string('locale')->nullable();
            $table->boolean('collaborator')->nullable();
        });
    }

    public function down()
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('user_first_name');
            $table->dropColumn('user_last_name');
            $table->dropColumn('user_email');
            $table->dropColumn('user_email_verified');
            $table->dropColumn('account_owner');
            $table->dropColumn('locale');
            $table->dropColumn('collaborator');
        });
    }
}
