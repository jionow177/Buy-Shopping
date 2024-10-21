<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('express_checkout', function (Blueprint $table) {
            $table->id();
            $table->string('variant_name')->nullable();
            $table->string('product_id');
            $table->string('quantity');
            $table->string('url');
            $table->string('store_id');
            $table->string('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('express_checkout');
    }
};
