<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Post extends Model
{
    public $timestamps = false;
    protected $table = 'posts';
    protected $dates = ['data_publicacao'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_cpf');
    }
}
