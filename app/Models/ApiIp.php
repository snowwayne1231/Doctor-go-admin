<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use ApiKey;

class ApiIp extends Model
{
    protected $fillable = ['api_key_id', 'ip', 'token'];

    public function key() {
        return $this->belongsTo('App\Models\ApiKey', 'api_key_id');
    }
}
