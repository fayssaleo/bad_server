<?php

namespace App\Modules\Cpu\Models;

use App\Modules\Bad\Models\Bad;
use Illuminate\Database\Eloquent\Model;

class Cpu extends Model
{
    protected $fillable = [
        'bad_id',
        'cpuId',
        'driver',
        'flatTruck',
        'holder',
        'nounce',
        'objectType',
        'owner',
        'securityCode',
        'status',
        'termAuthAssoc',
        'terminal',
        'transpAuthAssoc',
        'truck',
        'unit',
    ];
    public function bad(){
        return $this->belongsTo(Bad::class);
    }
}
