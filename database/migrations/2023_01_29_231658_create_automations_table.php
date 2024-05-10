<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('automations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(User::class);
            $table->string('title');
            $table->string('code');
            $table->integer('type');
            $table->string('product_type');
            $table->integer('side');
            $table->float('limit_price', 8, 2);
            $table->float('stop_price', 8, 2);
            $table->float('stop_loss', 8, 2);
            $table->float('take_profif', 8, 2);
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('automations');
    }
};
