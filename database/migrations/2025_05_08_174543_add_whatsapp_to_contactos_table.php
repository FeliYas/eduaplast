<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contactos', function (Blueprint $table) {
            $table->string('whatsapp')->nullable()->after('email');
        });
    }

    public function down()
    {
        Schema::table('contactos', function (Blueprint $table) {
            $table->dropColumn('whatsapp');
        });
    }
};
