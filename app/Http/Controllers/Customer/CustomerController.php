<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\BasicController;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Image;
use App\Models\SettingPointGive;

class CustomerController extends BasicController
{
    //
    
    public function register(Request $request)
    {
        // return response($request->customer, 400);
        $inputs = $this->validate($request, [
            'firstname' => 'required|between:1,32',
            'lastname' => 'required|between:1,32',
            'email' => 'required|email|unique:customers|max:90',
            'telephone' => 'required|numeric|unique:customers',
            'password' => 'required|alpha_dash|between:4,32',

            'country' => 'required|numeric',
            'postCode' => 'required|numeric',
            'address_1' => 'required|max:128',
            'address_2' => 'required|max:128',

            'doctorProfile' => 'required|max:64',
            'doctorClinic' => 'max:64',

            'doctorProfileImage' => 'image',
            'doctorClinicImage' => 'image',
        ]);

        $customer = $this->makeNewCustomer($inputs);
        $address = $this->makeNewAddress($inputs, $customer->id);

        
        if (isset($inputs['doctorProfileImage'])) {
            $profileImage = $inputs['doctorProfileImage'];
            $profileName = $profileImage->getClientOriginalName();
            $image_profile = Image::saveByImageFile($profileName, $profileImage);
            $customer->doctor_profile_image_id = $image_profile->id;
        }

        
        if (isset($inputs['doctorClinicImage'])) {
            $clinicImage = $inputs['doctorClinicImage'];
            $clinicName = $clinicImage->getClientOriginalName();
            $image_clinic = Image::saveByImageFile($clinicName, $clinicImage);
            $customer->doctor_clinic_image_id = $image_clinic->id;
        }
        
        $customer->save();
        $uuid = $request->uuid;
        $token = $this->makeToken($customer, $uuid);

        $result = $customer->toArray();
        $result['token'] = $token;
        
        return $this->basicJSON($result);
    }


    public function login(Request $request) {

        $inputs = $this->validate($request, [
            'account' => 'required|between:1,90',
            'password' => 'required|between:4,32',
        ]);

        $whereAccount = is_numeric($inputs['account'])
            ? 'telephone'
            : 'email';

        $customer = Customer::where($whereAccount, $inputs['account'])->first();

        if (empty($customer)) {
            throw new \Exception('錯誤的EMail或手機號');
        }

        if (!\Hash::check($inputs['password'], $customer->password)) {
            throw new \Exception('錯誤的EMail/手機號或是密碼');
        }

        if ($customer->status == 4) {
            throw new \Exception('帳號被停用');
        }

        $result = $customer->toArray();

        $result['token'] = $this->makeToken($customer, $request->uuid);

        return $this->basicJSON($result);
    }


    public function logout(Request $request) {
        if (isset($request->customer) && isset($request->customer['id'])) {
            $id = $request->customer['id'];
            \Cache::forget("customer_$id");
        }
        return $this->basicJSON('done');
    }


    public function getInfo(Request $request) {
        if ($request->customer) {
            return $this->basicJSON($request->customer);
        } else {
            return $this->basicJSON([
                'redirect' => '/',
                'logout' => true,
            ]);
        }
    }


    public function updateInfo(Request $request) {
        $inputs = $this->validate($request, [
            'id' => 'required|numeric',
            'firstname' => 'between:1,32',
            'lastname' => 'between:1,32',
            'email' => 'email|max:90',
            'telephone' => 'required|numeric',

            'postcode' => 'numeric',
            'address_1' => 'max:128',
            'address_2' => 'max:128',

            'doctor_profile' => 'max:64',
            'doctor_clinic' => 'max:64',

            'doctor_profile_image' => 'image',
            'doctor_clinic_image' => 'image',
            'avatar_url' => 'max:255',
            'avatar_image' => 'image',
        ]);

        $customer = Customer::find($inputs['id']);

        if ($customer->telephone != $inputs['telephone']) {
            throw new \Exception('錯誤的參數');
        }

        foreach ($inputs as $key => $val) {
            switch ($key) {
                case 'email':
                case 'firstname':
                case 'lastname':
                case 'doctor_profile':
                case 'doctor_clinic':
                    $customer->$key = $val;
                break;
                case 'postcode':
                case 'address_1':
                case 'address_2':
                    if ($customer->address) {
                        $customer->address->$key = $val;
                    } else {
                        $address = $this->makeNewAddress($inputs, $customer->id);
                        $customer = Customer::find($inputs['id']);
                    }
                    
                break;
                case 'doctor_profile_image':
                case 'doctor_clinic_image':
                    $fileName = $val->getClientOriginalName();
                    $image = Image::saveByImageFile($fileName, $val);
                    $property = $key.'_id';

                    $old_image_id = $customer->$property;
                    $old_image = Image::find($old_image_id);
                    if ($old_image) {
                        $old_image->deleteImage();
                    }

                    $customer->$property = $image->id;
                break;
                case 'avatar_image':
                    $fileName = $val->getClientOriginalName();
                    $image = Image::saveByImageFile($fileName, $val);

                    $old_image_id = $customer->avatar_url;
                    $old_image = Image::find($old_image_id);
                    if ($old_image) {
                        $old_image->deleteImage();
                    }
                    
                    $customer->avatar_url = $image->id;
                break;
                default:
                    // throw new \Exception('錯誤的參數:2');
            }
        }

        // return $this->basicJSON($customer);
        $result = $this->updateCustomer($request, $customer->toArray());
        
        return $this->basicJSON($result);
    }


    public function resetpassword(Request $request) {
        $inputs = $this->validate($request, [
            'telephone' => 'required|numeric',
            'password' => 'required|alpha_dash|between:4,32',
            'sms_token' => 'required|between:4,6',
        ]);

        $customer = Customer::where('telephone', $inputs['telephone'])->first();
        
        if (empty($customer)) {
            throw new \Exception('會員不存在此手機號');
        }

        $customer->password = bcrypt($inputs['password']);
        $customer->save();

        return $this->basicJSON('done');
    }


    public static function updateCustomer(Request $request, $nextCustomer) {
        $customer = $request->customer;
        
        $id = $customer['id'];
        
        $model_customer = Customer::find($id);

        if (empty($model_customer->address) || empty($customer['address']) || empty($nextCustomer['address'])) {
            $address = self::makeNewAddress([], $id);
            $customer['address'] = $address->toArray();
            $model_customer = Customer::find($id);
            // dd($customer);
            // $model_customer->address = $address;
        }

        foreach ($nextCustomer as $key => $val) {
            if ($key == 'login_time') { continue; }
            if (is_array($val)) {
                
                foreach ($val as $k => $v) {
                    $model_customer->$key->$k = $v;
                    $customer[$key][$k] = $v;
                }
            } else if ($val) {
                $model_customer->$key = $val;
                $customer[$key] = $val;
            }
        }
        
        $model_customer->save();
        $model_customer->address->save();
        
        \Cache::put("customer_$id", $customer, now()->addWeekdays(2));

        // $customer['request_token'] = $request->token;

        return $customer;
    }


    private function makeToken($customer, $uuid) {
        $id = $customer->id;
        $telephone = $customer->telephone;
        $time = time();
        // $token = \Crypt::encrypt("$id,$telephone,$time,$uuid");
        $token = \Crypt::encryptString("$id,$telephone,$time,$uuid");
        $customer->token = $token;
        $customer->save();

        $array = $customer->toArray();
        $array['token'] = $token;
        $array['login_time'] = $time;

        // dd($array);

        \Cache::put("customer_$id", $array, now()->addWeekdays(2));
        
        return $token;
    }


    private static function makeNewCustomer($inputs) {
        $newCustomer = new Customer();
        $newCustomer->status = 0;
        $newCustomer->firstname = $inputs['firstname'];
        $newCustomer->lastname = $inputs['lastname'];
        $newCustomer->email = $inputs['email'];
        $newCustomer->telephone = $inputs['telephone'];
        $newCustomer->password = bcrypt($inputs['password']);
        $newCustomer->doctor_profile = $inputs['doctorProfile'];
        if (isset($inputs['doctorClinic'])) {
            $newCustomer->doctor_clinic = $inputs['doctorClinic'];
        }
        
        $newEventActivePoint = SettingPointGive::find(1)->json['register'];
        $newCustomer->point = intval($newEventActivePoint);

        $newCustomer->save();
        return $newCustomer;
    }


    private static function makeNewAddress($inputs, $customer_id) {
        $newAddress = new Address();
        $newAddress->country_id = isset($inputs['country']) ? $inputs['country'] : 205;
        $newAddress->customer_id = $customer_id;
        if (isset($inputs['address_1'])) { $newAddress->address_1 = $inputs['address_1']; }
        if (isset($inputs['address_2'])) { $newAddress->address_2 = $inputs['address_2']; }
        if (isset($inputs['postCode'])) { $newAddress->postcode = $inputs['postCode']; }
        $newAddress->save();
        return $newAddress;
    }

}

