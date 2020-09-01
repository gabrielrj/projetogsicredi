<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FiltroCidade extends Model
{
    protected $fillable = ['config_id', 'ufs_id', 'cidade'];

    public function estado(){
        return $this->belongsTo(Uf::class, 'ufs_id');
    }
}
