<?php

namespace App\Models;

use App\Traits\HasFilters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoiceMail extends Model
{
    use HasFactory, HasFilters;
    protected $guarded = [];

}
