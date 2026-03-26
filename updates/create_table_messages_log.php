<?php

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::create('getbyte_whatsapp_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('to_phone_number')->nullable();
            $table->string('from_device_token')->nullable();
            $table->string('message_type')->nullable();
            $table->json('content')->nullable();
            $table->text('error')->nullable();
            $table->timestamp('sent_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('getbyte_whatsapp_logs');
    }
};
