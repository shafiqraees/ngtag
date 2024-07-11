<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class CorpCustomerAccount extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * @var array
     */
    protected $guarded = [];
    /**
     * @var bool
     */
    public $timestamps = false;

    protected $hidden = [
        'password'
    ];

    /**
     * Get the route key name for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'customer_account_id';
    }
    public function getAuthIdentifierName()
    {
        return 'id'; // Assuming 'id' is the name of your primary key column
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }
    public function corpReserveTag() {
        return $this->hasMany(CorpReserveTag::class,'corp_customer_account_id','id');
    }
}
