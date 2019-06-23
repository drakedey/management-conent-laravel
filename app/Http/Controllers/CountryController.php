<?php

namespace App\Http\Controllers;

use App\Country;
use App\Rol;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;


class CountryController extends Controller
{

    /**
     * cc constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->user()->rol->name == Rol::ADMIN) {
            $country = Country::all();
            return response()->json(['countries' => $country], 200);
        }
        return response()->json(['error' => 'Unhautorized'], 401);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:countries',
            'uri_param' => 'required|unique:countries',
            'user_id' => 'required'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        }

        if ($request->user()->rol->name == Rol::ADMIN) {
            $country = Country::query()->create($data);
            return response()->json(['country' => $country], 200);
        }
        return response()->json(['error' => 'Unhautorized'], 401);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(Request $request, int $id)
    {
        if ($request->user()->rol->name == Rol::ADMIN) {
            $country = Country::query()->findOrFail($id);
            return response(['country' => $country], 200);
        }
        return response()->json(['error' => 'Unhautorized'], 401);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $country = Country::query()->findOrFail($id);

        $rules = [
            'name' => 'unique:countries',
            'uri_param' => 'max:3',
//            'user_id' => 'required'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        if ($request->has('name')) {
            $country->name = $request->name;
        }

        if ($request->has('uri_param')) {
            $country->uri_param = $request->uri_param;
        }

        if ($request->has('user_id')) {
            $country->user_id = $request->user_id;
        }

        if (!$country->isDirty()) {
            return response()->json(['error' => 'You must specify at least one different value to update', 'code' => 422], 422);
        }

        $country->save();

        return response()->json(['success' => 'country updated succesfully', 'country' => $country], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public function destroy(int $id, Request $request)
    {
        $country = Country::query()->findOrFail($id);
        $country->update(['user_id' => $request->user()->id]);
        $country->delete();
        return response()->json(['country' => $country], 200);
    }
}
