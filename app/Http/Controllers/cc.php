<?php


namespace App\Http\Controllers\Country;


use App\Country;
use App\Http\Controllers\Controller;
use App\Rol;
use Illuminate\Http\Request;

class cc extends Controller
{

    /**
     * cc constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getAllCountries (Request $request) {

    }


    public function getCountry (Request $request, int $id) {
        if ($request->user()->rol->name == Rol::ADMIN) {
            $country = Country::query()->find($id);
            return \response()->json(['country' => $country], 200);
        }
        return \response(['error' => 'Unhautorized'], 401);
    }

}