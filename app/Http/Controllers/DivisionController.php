<?php

namespace App\Http\Controllers;

use App\Country;
use App\Division;
use App\DivisionContent;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DivisionController extends Controller
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
        $divisions = Division::all();
        return response(['divisions' => $divisions], 200);
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
            'content' => 'required',
            'countries' => 'required'
        ];

        // Validating object
        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        //Validating Countries
        $countries = $data['countries'];
        foreach ($countries as $country) {
            try {
                Country::findOrFail($country);
            } catch (\Exception $e) {
                return response(['error' => 'country with the id '. $country .' not found'], 400);
            }
        }

        //Validating that at content there's the spanish body (the minimun required)
        $content = $data['content'];
        $contentRules = [Language::SPANISH => 'required'];
        $contentValidator = Validator::make($content, $contentRules);
        if($contentValidator->fails()) {
            return response(['error' => $contentValidator->errors()], 400);
        }

        //Validating that the language exists on DB and the content is not empty and accomplish with the minimun data
        foreach ($content as $language => $language_content) {
            //Validating thar exists in other case returns 400
            try {
                Language::query()->where('name', '=', $language)->firstOrFail();
                $rules = [
                    'name' => 'required',
                ];
                //Spanish is mandatory, if language content is not empty the validate
                if($language == Language::SPANISH || !empty($language_content)) {
                    $innerContentValidator = Validator::make($language_content, $rules);
                    if($innerContentValidator->fails()) {
                        return response(['error' => $innerContentValidator->errors()], 400);
                    }
                }
            } catch (\Exception $exception) {
                return response(['error' => 'language with name: '. $language .' not found'], 400);
            }
        }

        //Creating
        $division = Division::create(['user_id' => $request->user()->id]);
        $division->countries()->sync($data['countries']);
        foreach ($content as $language => $language_content) {
            $languageRegister = Language::where('name', '=', $language)->firstOrFail();
            $divisionContent = [
                'name' => $language_content['name'],
                'division_id' => $division->id,
                'language_id' => $languageRegister->id
            ];
            DivisionContent::create($divisionContent);
        }

        return response(['success' => 'division created succesfully', 'division' => $division], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $division = Division::query()->findOrFail($id);
            return response(["division" => $division], 200);
        } catch (\Exception $exception) {
            return response(["error" => "division with id ". $id ." not found"], 404);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $division = null;

        try {
            $division = Division::findOrFail($id);
        } catch (\Exception $e){
            return response(['error' => "division with $id not found"], 404);
        }

        $data = $request->all();

        //If has content then validate it
        if($request->has('content')) {
            $content = $data['content'];
            //Validating that the language exists on DB and the content is not empty and accomplish with the minimun data
            foreach ($content as $language => $language_content) {
                //Validating thar exists in other case returns 404
                Language::where('name', '=', $language)->firstOrFail();
                $rules = [
                    'name' => 'required',
                ];
                $innerContentValidator = Validator::make($language_content, $rules);
                if($innerContentValidator->fails()) {
                    return response(['error' => $innerContentValidator->errors()], 400);
                }
            }

            foreach ($content as $language => $language_content) {
                $languageTarget = Language::where('name', '=', $language)->first();
                $contentTarget = DivisionContent::where('division_id', '=', $division->id)->where('language_id','=', $languageTarget->id)->first();
                $contentLanguage = [
                    'name' => $language_content['name'],
                ];
                if($contentTarget) {
                    $contentTarget->update($contentLanguage);
                } else {
                    $contentLanguage['division_id'] = $division->id;
                    $contentLanguage['language_id'] = $languageTarget->id;
                    DivisionContent::create($contentLanguage);
                }
            }
        }

        //If gonna add any country then sync with the existing countries
        if($request->has('addCountries')) {
            try{
                $removeCountries = $data['addCountries'];
                $division->countries()->sync($removeCountries);
            } catch (\Exception $e) {
                return response(['error' => 'error at adding new country to the relation']);
            }
        }

        //If gonna remove any country then delete the pivote table
        if($request->has('removeCountries')) {
            try{
                $removeCountries = $data['removeCountries'];
                $division->countries()->detach($removeCountries);
            } catch (\Exception $e) {
                return response(['error' => 'error at removing existing country to the relation']);
            }
        }

        return response(['success' => 'division updated succesfully', 'division' => $division], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $division = Division::query()->findOrFail($id);
            $division->delete();
            return response(['success' => 'division deleted succesfully', 'division' => $division], 200);
        } catch (\Exception $e) {
            return response(['error' => "division with id: $id not found"], 404);
        }
    }
}
