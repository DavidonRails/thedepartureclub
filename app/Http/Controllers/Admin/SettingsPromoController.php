<?php

namespace App\Http\Controllers\Admin;

use App\Models\Promo;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Response\Response;
use App\Http\Controllers\Controller;

class SettingsPromoController extends Controller
{


    public function get(  ) 
    {

        $images = Promo::where('status', '=', 1)->get();

        $return  = [];

        foreach ( $images as $image ) {
            $return[] = [
                'image_id' => $image->image_id,
                'img' => md5($image->image_id)
            ];
        }

        
    }


    public function move( Request $request )
    {

        $data = $request->only(['direction', 'image_id']);
        
        echo '<pre>';
        print_r($data);
        die();




    }
    
    
}
