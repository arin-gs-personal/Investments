<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
    'user_id',
    'student_name',
    'dob',
    'kind',
    'quantity',
    'cost',
    'reminder',
    'metal',
    'phone',
    'address',
];


    protected $appends = ['profit'];

    public function getProfitAttribute()
    {
        $rate = \App\Models\rate::latest()->first();  //$rate helps to get the latest entered values 

        // $rate = \App\Models\rate::oldest()->first(); //this one for taking the oldest value

        if (!$rate || $this->quantity == 0) {  //if empty rate or empty qy=uantity means 0 (zero)
            return 0;
        }

        if ($this->kind == 22) {
            return ($rate->today22 * $this->quantity) - $this->cost; //$this helps to get focuss on a single row which is get $rate
        }

        if ($this->kind == 24) {
            return ($rate->today24 * $this->quantity) - $this->cost;
        }
        if ($this->metal == "SS") {
            return ($rate->silver_cost * $this->quantity) - $this->cost;
        }

        return 0;
    }
}


