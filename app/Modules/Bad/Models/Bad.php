<?php

namespace App\Modules\Bad\Models;

use App\Modules\Cpu\Models\Cpu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\LignesMarchandise\Models\LignesMarchandise;
use Illuminate\Database\Eloquent\Model;

class Bad extends Model
{
    use HasFactory;

    protected $fillable = [
        'badId',
        'consignee',
        'creationDate',
        'holder',
        'holderName',
        'issuer',
        'issuerName',
        'numCpu',
        'owner',
        'OwnerName',
        'connaissementReference',
        'port',
        'dateReceptionDouane',
        'dateReceptionEai',
        'numeroVoyage',
        'nomNavire',
        'dateVoyage',
        'etd',
        'imoNumber',
        'armateurProprietaire',
    ];

    public function lignes_marchandise(){
        return $this->hasMany(LignesMarchandise::class);
    }
    public function cpus(){
        return $this->hasMany(Cpu::class);
    }
}
