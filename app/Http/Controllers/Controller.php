<?php

namespace App\Http\Controllers;

use App\Models\UserTokens;
use App\Models\BillingHistory;
use App\Models\BillingPackages;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function http($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_TIMEOUT, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_USERAGENT, 'HoboJet PHP Wrapper');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, []);

        try {
            $result = curl_exec($curl);

            if($result)
            {
                curl_close($curl);
                return json_decode($result, TRUE);
            }
            else
            {
                return [];
            }
        }catch (\Exception $e){

        }
    }

    public function profileImage( $hash ) 
    {
        if(file_exists(base_path() . '/public/data/images/profile/' . $hash . '.jpg'))
            $avatar = url('data/images/profile/' . $hash . '.jpg');
        else
            $avatar = url('images/user-avatar.png');
        
        return  $avatar;
    }


    public function getUser()
    {

        $return = [
            'user_id' => null,
            'first_name' => null,
            'last_name' => null,
            'avatar' => null
        ];

        $cookie = \Cookie::get('token');

        if($cookie)
        {
            $user = UserTokens::checkToken($cookie);

            if(!$user)
            {
                \Cookie::queue(\Cookie::forget( 'token' ));
                \Auth::logout();
                return  \Redirect::to('/');
            }

            \Auth::loginUsingId($user->user_id, TRUE);
            $user = \Auth::getUser();

            $avatar = $this->profileImage($user->avatar);
			$return = $user;
            $return['user_id'] = $user->user_id;
            $return['first_name'] = $user->first_name;
            $return['last_name'] = $user->last_name;
            $return['avatar'] = $avatar;
			
			$billing = BillingHistory::where('user_id', '=', $user->user_id)->first(['payment_agrement_id']);
			$package_id = BillingHistory::where('user_id', '=', $user->user_id)->first(['package_id']);
			
            $return['membership'] = $billing->payment_agrement_id;
			
			if($package_id) {
				$package = BillingPackages::where('package_id', '=', $package_id->package_id)->first();
				$return['package'] = $package;
			}

	}
        return $return;

    }


    public function searchCity($city)
    {

        $url = "https://maps.googleapis.com/maps/api/geocode/json?";
        $url .= "address=".urlencode($city);
        $url .= "&libraries=places";
        $url .= "&types=(cities)";
        $url .= "&components=country:US";
        $url .= "&sensor=false";
        $url .= "&key=" . env('GOOGLE_CLOUD_API_ID');

        $geocode = $this->http($url);

        if(!isset($geocode['status']) && $geocode['status'] != 'OK')
            return Response::success([]);


        $return = [];
        foreach($geocode['results'] as $location)
        {
            $return[] = [
                'formatted_address' => $location['formatted_address'],
                'geometry' => [
                    'lat' => $location['geometry']['location']['lat'],
                    'lng' => $location['geometry']['location']['lng']
                ]
            ];
        }


        if(count($return))
        {
            return $return[0];
        }
        else
            return [];
    }


}
