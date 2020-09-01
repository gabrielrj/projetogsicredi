<?php

namespace App\Models;

class Banco extends ModelComSoftDeletes
{
    protected $fillable = ['codigo', 'nome'];
}
