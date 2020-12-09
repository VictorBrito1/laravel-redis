<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class User extends Model
{
    public $timestamps = false;
    protected $table = 'usuarios';
    protected $primaryKey = 'cpf';
    public $incrementing = false;
}
