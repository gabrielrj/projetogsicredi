<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FiltroCargo extends Model
{
    protected $fillable = ['config_id', 'esfera', 'cargo', 'situacao'];
}
