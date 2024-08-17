<?php

namespace App\Models;

use App\Traits\HasFilters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorpReserveTag extends Model
{
    use HasFactory, HasFilters;
    protected $guarded = [];
    /**
     * @var bool
     */
    public $timestamps = false;

    public function corpTagList() {
        return $this->belongsTo(CorpTagList::class,'corp_tag_list_id');
    }
}
