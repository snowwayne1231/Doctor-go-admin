<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingPointGive extends Model
{
    //
    protected $table = 'setting_point_give';

    public function setJsonAttribute($val)
    {
        if (is_array($val)) {
            $this->attributes['json'] = json_encode($val);
        }
    }

    public function getJsonAttribute($val)
    {
        return json_decode($val, true);
    }
}
