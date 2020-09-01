<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelComSoftDeletes extends Model
{
    use SoftDeletes;
}
