<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveNotarisColumnFromAkadTable extends Migration
{
    public function up()
    {
        Schema::table('akad', function (Blueprint $table) {
            $table->dropColumn('notaris');
        });
    }

    public function down()
    {
        Schema::table('akad', function (Blueprint $table) {
            $table->string('notaris')->nullable();
        });
    }
}
