<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the existing foreign key
            $table->dropForeign(['product_id']);

            // Add the new foreign key with the desired constraints
            $table->foreign('product_id')
                  ->references('id')->on('products')
                  ->onDelete('cascade') // Adjust this to 'set null' if you prefer
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
             // Drop the modified foreign key
             $table->dropForeign(['product_id']);

             // Restore the original foreign key
             $table->foreign('product_id')
                   ->references('id')->on('products')
                   ->onDelete('restrict') // Restore the original policy
                   ->onUpdate('cascade');
        });
    }
};
