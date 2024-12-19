<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Vbot extends Model
{
    protected $fillable = [
        'bezeichnung',
        'status',
        'product_name',
        'sip_benutzername',
        'sip_passwd',
        'sip_registername',
        'sip_phonenumber',
        'sip_server',
        'sip_port',
        'e_mail_server',
        'e_mail_user',
        'e_mail_passwd',
        'e_mail_server_port',
        'e_mail_server_from',
        'ansagetext',
        'prompt',
        'chatmodel',
        'chatmodel_api',
        'sttmodel',
        'sttmodel_api',
        'ttsmodel',
        'ttsmodel_api',
        'attendedTransfer',
        'mail_certifikate_validation',
        'license_Key',
        'user_id',
        'apen_ai_key',
        'prompturl',
        'transmit_type',
        'stun_server'
        

    ];

    // Method to generate a unique license key
    public static function generateUniqueLicenseKey()
    {
        do {
            $licenseKey = Str::random(32); // Generate a random string of 10 characters
        } while (self::where('license_key', $licenseKey)->exists()); // Ensure uniqueness

        return $licenseKey;
    }
}
