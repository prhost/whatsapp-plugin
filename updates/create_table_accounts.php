<?php

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::create('getbyte_whatsapp_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('whatsapp_type');
            $table->string('secret_key');
            $table->string('status')->nullable();
            $table->timestamp('connected_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('getbyte_whatsapp_accounts');
    }
};
