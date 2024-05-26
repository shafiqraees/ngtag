<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtpProcess extends Model
{
    use SoftDeletes, HasFactory;
    protected $guarded = [];
    public $timestamps = false;
}
