<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SmsKing;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;


class SmsController extends BasicController
{
    //

    private static $sms_url = 'https://api.kotsms.com.tw/kotsmsapi-1.php';

    public function send(Request $request, $dstaddr)
    {
        $result = [];
        $smsKingSettings = SmsKing::all();

        if (isset($dstaddr)) {

            $numbers = [0,1,2,3,4,5,6,7,8,9];
            $randoms = Arr::random($numbers, 6);
            $randomCode = join('', $randoms);
            $kmsgid = 0;
            

            if ($smsKingSettings->count() > 0) {
                $smsKingData = $smsKingSettings->first();
                $body = str_replace("%CODE%", $randomCode, $smsKingData['template']);
    
                $data = [
                    'username' => $smsKingData['account'],
                    'password' => $smsKingData['password'],
                    'smbody' => iconv("UTF-8","big5//TRANSLIT",$body),
                    'dstaddr' => $dstaddr,
                    'dlvtime' => 0,
                    // 'response' => '',
                ];
                
                $url = self::$sms_url.'?'.http_build_query($data);
    
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
                $response = curl_exec($ch);
                // $response = 'id=5278';
                $res_array = explode('=', $response);
                $kmsgid = trim(array_pop($res_array));
    
                if (intval($kmsgid) < 0) {
    
                    $mapping = [
                        '-1' => '系統維護中或其他錯誤 ,帶入的參數異常,伺服器異常',
                        '-2' => '授權錯誤(帳號/密碼錯誤)',
                        '-4' => 'A Number違反規則 發送端 870短碼VCSN 設定異常',
                        '-5' => 'B Number違反規則 接收端 門號錯誤',
                        '-6' => 'Closed User 接收端的門號停話異常090 094 099 付費代號等',
                        '-20' => 'Schedule Time錯誤 預約時間錯誤 或時間已過',
                        '-21' => 'Valid Time錯誤 有效時間錯誤',
                        '-1000' => '發送內容違反NCC規範 -59999 帳務系統異常 簡訊無法扣款送出',
                        '-60002' => '您帳戶中的點數不足',
                        '-60014' => '該用戶已申請 拒收簡訊平台之簡訊 ( 2010 NCC新規)',
                        '-999959999' => '在12 小時內，相同容錯機制碼',
                        '-999969999' => '同秒, 同門號, 同內容簡訊',
                        '-999979999' => '鎖定來源IP',
                        '-999989999' => '簡訊為空',
                    ];
    
                    $wording = isset($mapping[$kmsgid]) ? $mapping[$kmsgid] : '未知錯誤 請聯繫客服 : '.$kmsgid;
                    throw new \Exception($wording);
                }
                
            }

            


            $cacheCodes = Cache::get('smsCodes', '');
                
            if (strpos($cacheCodes, $randomCode) == false) {
                $cacheCodes = $cacheCodes.','.$randomCode;
                Cache::put('smsCodes', $cacheCodes, 30);
            }

            $result['kmsgid'] = $kmsgid;
            $result['code'] = $randomCode;
            $result['codes'] = $cacheCodes;


        } else {
            throw new \Exception('錯誤,參數不足');
        }
        
        return $this->basicJSON($result);
    }

}



