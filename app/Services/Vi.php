<?php

namespace App\Services;

use Cache;
use Illuminate\Support\Facades\Http;

class Vi 
{
 
    private const BASE_URI = 'https://cts.myvi.in:8443/Solutions/api/';

    private const USER = 'ctc_ap';

    private const PASSWORD = '17d37bb3d7c8641fe85e3422dd42b7b1';

    public function getAuthToken()
    {
        return  Cache::remember('vi_token', now()->addHours(24), function () {
            $response = Http::post(static::BASE_URI.'AuthJwt/Authenticate',[
                'username' => static::USER,
                'password' => static::PASSWORD,
            ])->json();
            
            return  isset($response['Message']) ? null : $response;
        });
    } 

    public function call( $no1, $no2 )
    {
        // echo $no1.'<br/>';
        // echo $no2;
        if( $token = $this->getAuthToken())
        {
            return Http::withHeaders([
                'Authorization' => "Bearer $token",
            ])->get(self::BASE_URI.'CTC/Get', [
                'username'=> 'prajanaa',
                'password' => 'pwpl@123',
                'apartyno' => $no1,
                'bpartyno'=> $no2
            ]);
            
        }
        
    }

}