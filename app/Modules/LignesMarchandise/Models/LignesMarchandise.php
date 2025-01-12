<?php

namespace App\Modules\LignesMarchandise\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Modules\Bad\Models\Bad;
use Illuminate\Database\Eloquent\Model;

class LignesMarchandise extends Model
{
    use HasFactory;
    protected $table="lignes_marchandises";
    protected $fillable = [
        'ctr_id',
        'isoType',
        'ctr_length',
        'linesite_id',
        'ctr_fe_ind',
        'bad_id',
    ];
    public function bad(){
        return $this->belongsTo(Bad::class);
    }
}
