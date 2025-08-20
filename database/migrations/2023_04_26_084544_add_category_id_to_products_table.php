<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Menambahkan kolom category_id
            $table->unsignedBigInteger('category_id')->nullable()->after('id');

            // Menambahkan foreign key ke categories
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Menghapus foreign key terlebih dahulu sebelum drop kolom
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
