<?php


namespace App\Http\Controllers\Country;


use App\Country;
use App\Http\Controllers\Controller;
use App\Rol;
use Illuminate\Http\Request;

class CountryController extends Controller
{

    /**
     * CountryController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getAllCountries (Request $request) {
        if ($request->user()->rol->name == Rol::ADMIN) {
            return \response()->json(['countries' => Country::all()], 200);
        }
        return \response(['error' => 'Unhautorized'], 401);
    }

}