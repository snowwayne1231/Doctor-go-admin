<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use App\Models\ApiIp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApiKeyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ApiKey  $apiKey
     * @return \Illuminate\Http\Response
     */
    public function show(ApiKey $apiKey)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ApiKey  $apiKey
     * @return \Illuminate\Http\Response
     */
    public function edit(ApiKey $apiKey)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ApiKey  $apiKey
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ApiKey $apiKey)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ApiKey  $apiKey
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApiKey $apiKey)
    {
        //
    }

    public function touch(Request $request) {
        $super_ips = config('app.SUPER_IP');
        $client_ip = $request->ip();
        $location = \Location::get($client_ip);
        $uuid = $request->header('X-Device-UUID');
        $countryCode = $location->countryCode;
        if (isset($uuid)) {
            dd($uuid);
        } else {
            $lvName = 'lv_05';
            $api_key = ApiKey::where('name', $lvName)->first();
            $api_ip = ApiIp::where('ip', $client_ip)->where('api_key_id', $api_key->id)->get();
            if ($api_ip->isEmpty()) {
                $new_token = str_random(48);
                $api_ip = ApiIp::create([
                    'api_key_id' => $api_key->id,
                    'ip' => $client_ip,
                    'token' => $new_token,
                ]);
            }
            dd($api_ip->getRelations());
            $qq = Cache::get('qqq');
        }
        
        
        // dd($key);
        return $api_ip;
    } 
}
