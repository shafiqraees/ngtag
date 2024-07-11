<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorpReserveTag extends Model
{
    use HasFactory;
    protected $guarded = [];
    /**
     * @var bool
     */
    public $timestamps = false;

    public function corpTagList() {
        return $this->belongsTo(CorpTagList::class,'corp_tag_list_id');
    }
}
