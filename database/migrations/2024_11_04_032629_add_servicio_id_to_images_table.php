<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('images', function (Blueprint $table) {
        $table->unsignedBigInteger('servicio_id')->nullable()->after('id'); // Opción de posición de la columna
        $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('images', function (Blueprint $table) {
        $table->dropForeign(['servicio_id']);
        $table->dropColumn('servicio_id');
    });
}

};
