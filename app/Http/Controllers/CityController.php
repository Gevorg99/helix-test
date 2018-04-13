<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class CityController
 * @package App\Http\Controllers
 */
class CityController extends Controller
{
    /**
     * method CitiesSearch
     *
     * @functionality SELECT all cities by name
     * @param Request $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function CitiesSearch(Request $request)
    {
        $query = $request->get('query','');

        if(!empty($query)){

            $cities = City::select('name','latitude','longitude')->where('name','LIKE','%'.$query.'%')->get();

            return response()->json($cities);
        }

        return false;
    }

    /**
     * method CitiesNearest
     *
     * @functionality SELECT 20 nearest cities of the current city
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function CitiesNearest(Request $request)
    {
        $this->validate($request,[
            'latitude'=>'required',
            'longitude'=>'required',
        ]);

        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $cities = City::select(DB::raw('latitude,longitude,name, ( 9800 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
            ->having('distance', '<', 500)
            ->orderBy('distance')
            ->limit(20)
            ->get();

        return response()->json($cities);
    }
}
