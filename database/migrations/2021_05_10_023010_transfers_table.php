<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->float('value',20,2);
            $table->timestamps();
            $table->enum('status', [
                'success',
                'failed',
                'refused',
                'refunded'
            ])->default('success');

            $table->foreignId('payer_id')->constrained('users', 'id');
            $table->foreignId('payee_id')->constrained('users', 'id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfers');
    }
}
