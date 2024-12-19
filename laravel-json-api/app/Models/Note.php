<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['call_id', 'text'];  // Allow mass assignment for call_id and text

    // Define the relationship between the Note and Call model
    public function call()
    {
        return $this->belongsTo(Call::class);
    }
}
