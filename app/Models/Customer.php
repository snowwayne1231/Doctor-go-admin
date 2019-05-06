<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $with = ['address'];
    protected $guarded = ['password'];

    protected $hidden = ['password'];

    public function address()
    {
        return $this->hasOne('App\Models\Address');
    }

    public function increasePoint($increase) {
        $this->point = $this->point + $increase;
        $id = $this->id;
        $key = "customer_$id";
        $customer = \Cache::get($key);

        if (!empty($customer)) {
            $customer['point'] = $this->point;
            \Cache::put($key, $customer, now()->addWeekdays(2));
        }
        
        $this->save();
        return $this;
    }

    public function removeCache() {
        $id = $this->id;
        $key = "customer_$id";
        $customer = \Cache::pull($key);
        return $this;
    }

}
