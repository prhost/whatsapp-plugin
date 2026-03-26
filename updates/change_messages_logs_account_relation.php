<?php

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::table('getbyte_whatsapp_logs', function (Blueprint $table) {
            $table->dropColumn('from_device_token');
            $table->bigInteger('account_id')->nullable()->after('id')->index();
        });
    }

    public function down()
    {
        Schema::table('getbyte_whatsapp_logs', function (Blueprint $table) {
            $table->string('from_device_token')->nullable();
            $table->dropColumn('account_id');
        });
    }
};
