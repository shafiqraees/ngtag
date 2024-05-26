<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class AdminPortal extends Model
{
    use HasFactory, HasApiTokens;
    protected $guarded = [];

    /**
     * @var bool
     */
    public $timestamps = false;

    protected $hidden = [
        'password'
    ];

    protected $guard = 'admin';

    // Ensure the primary key is treated as a string if necessary
    // protected $keyType = 'string';

    // Override method if necessary
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }
    public function getAuthIdentifierName()
    {
        return 'id'; // Assuming 'id' is the name of your primary key column
    }
}
