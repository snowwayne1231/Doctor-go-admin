<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Exceptions\AuthException;
use App\Models\Customer;

class AuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $uuid = $request->header('X-UUID');
        $token = $request->header('X-Auth-Token');

        $request->request->add([ 'uuid' => $uuid, 'token' => $token ]);

        if (!empty($token)) {
            
            // $decrypt = \Crypt::decrypt($token);
            $decrypt = \Crypt::decryptString($token);
            $decrypt_array = explode(',', $decrypt);
            $decrypt_id = $decrypt_array[0] ?? '';
            $decrypt_telephone = $decrypt_array[1] ?? '';
            $decrypt_time = $decrypt_array[2] ?? '';
            $decrypt_uuid = $decrypt_array[3] ?? '';

            $key = "customer_$decrypt_id";

            if (\Cache::has($key)) {

                if ($uuid != $decrypt_uuid) {
                    // \Cache::forget($key);
                    // throw new AuthException('登錄裝置與金鑰不匹配 請重新登入');
                    $customer = ['error' => '登錄裝置與金鑰不匹配 請重新登入'];
                } else {
                    $customer = \Cache::get($key);
                }

                // dd($customer);
                if (!isset($customer['login_time'])) {
                    $customer = ['error' => '登錄資訊錯誤'];
                } else if ($decrypt_time != $customer['login_time']) {
                    // \Cache::forget($key);
                    // throw new AuthException('已有其他地方登入此帳號');
                    $customer = ['error' => '已有其他地方登入此帳號'];
                }

            } else {

                $customer = ['error' => '請重新登入'];
                // throw new AuthException('請重新登入');
            }

            $request->request->add(['customer' => $customer]);
        }

        return $next($request);
    }
}
