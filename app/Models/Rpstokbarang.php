<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rpstokbarang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rpstokbarang';
    protected $guarded = [];

    public static function deleteBeforeInsert()
    {
        //
    }
}
