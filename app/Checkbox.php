<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checkbox extends Model
{
    protected $table = 'checkboxes';
    protected $fillable = ['content','checked'];

    public $timestamps = false;
}
