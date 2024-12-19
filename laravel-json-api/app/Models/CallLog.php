<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallLog extends Model
{
    use HasFactory;

    // The attributes that are mass assignable
    protected $fillable = [
        'phonebot_id',
       
        'caller_number',
        'text',
        'action',
        
        'timestamp',
        'priority',
        'status',
        'notification',
        'response'
    ];

    // Define the relationship with Phonebot
    public function phonebot()
    {
        return $this->belongsTo(Vbot::class);
    }
}
