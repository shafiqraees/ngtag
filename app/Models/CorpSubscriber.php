<?php

namespace App\Models;

use App\Traits\HasFilters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorpSubscriber extends Model
{
    use HasFactory, HasFilters;
    protected $guarded = [];

    public $timestamps = false;

    public function getRouteKeyName()
    {
        return 'corp_subscriber_id';
    }
    public function getAuthIdentifierName()
    {
        return 'id'; // Assuming 'id' is the name of your primary key column
    }
}
