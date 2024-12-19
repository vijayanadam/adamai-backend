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
        Schema::create('table_name', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('user_id')->nullable();
            $table->string('bezeichnung')->nullable();
            $table->string('license_Key')->nullable();
            $table->string('status')->nullable();
            $table->string('product_name')->nullable();
            $table->string('sip_benutzername')->nullable();
            $table->string('sip_passwd')->nullable();
            $table->string('sip_registername')->nullable();
            $table->string('sip_phonenumber')->nullable();
            $table->string('sip_server')->nullable();
            $table->string('sip_port')->nullable();
            $table->string('e_mail_server')->nullable();
            $table->string('e_mail_user')->nullable();
            $table->string('e_mail_passwd')->nullable();
            $table->string('e_mail_server_port')->nullable();
            $table->string('e_mail_server_from')->nullable();
            $table->string('mail_certifikate_validation')->nullable();
            $table->text('ansagetext')->nullable();
            $table->text('prompt')->nullable();
            $table->string('chatmodel')->nullable();
            $table->string('chatmodel_api')->nullable();
            $table->string('sttmodel')->nullable();
            $table->string('sttmodel_api')->nullable();
            $table->string('ttsmodel')->nullable();
            $table->string('ttsmodel_api')->nullable();
            $table->string('attendedtransfer')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_name');
    }
};
