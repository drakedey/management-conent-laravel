<?php

namespace App\Http\Controllers;

use App\Agreement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgreementController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('client.admincredentials');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agreements = Agreement::all();
        return response(['agreements' => $agreements], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'id_contact' => 'required|unique:agreements',
            'person_contact' => 'required'
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        }

        $data['user_id'] = $request->user()->id;
        $agreement = Agreement::query()->create($data);
        return response(['success' => 'agreement created succesfully', 'agreement' => $agreement], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $agreement = Agreement::query()->findOrFail($id);
        return response(['agreement' => $agreement]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $agreement = Agreement::query()->findOrFail($id);
        $rules = [
            'id_contact' => 'required',
            'person_contact' => 'required'
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        }

        $agreement->update($data);
        return response(['success' => 'agreement updated succesfully', 'agreement' => $agreement], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $agreement = Agreement::query()->findOrFail($id);
        $agreement->delete();
        return response(['success'=>'agreement deleted', 'agreement' => $agreement]);

    }

    public function getByContactId($contactId){
        $agreements = Agreement::where('id_contact', '=', $contactId)->get();
        return (count($agreements) > 0) ?
            response(['agreements' => $agreements], 200) :
            response(['error' => 'not agreements found'], 404);
    }

}
