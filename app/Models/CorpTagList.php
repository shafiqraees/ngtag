<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CorpTagList extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $guarded = [];
    public $timestamps = false;
}
