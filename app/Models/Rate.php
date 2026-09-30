<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    // Specify the table (optional, Laravel assumes 'rates')
    protected $table = 'rates';

    // Mass assignable fields
    protected $fillable = [
        'today22',
        'today24',
        'silver_cost',
    ];

    // Optional: if you want to disable timestamps
    // public $timestamps = false;
}
