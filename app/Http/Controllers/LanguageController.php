<?php

namespace App\Http\Controllers;

use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class LanguageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('client.admincredentials');
    }

    public function index() {
        $languages = Language::all();
        return response(['languages' => $languages], 200);
    }

    public function store(Request $request) {
        $rules = [
            'name' => 'required|unique:languages',
            'user_id' => 'required'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        }

        $language = Language::create($data);

        return response(['success' => 'language created succesfully', 'language' => $language], 200);
    }

    public function update(int $id, Request $request)
    {
        $language = Language::findOrFail($id);

        $rules = [
            'name' => 'required|unique:languages',
            'user_id' => 'required'
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        }

        $language->update($data);

        return response(['success' => 'language updated succesfully', 'language' => $language], 200);
    }

    public function show(int $id) {
        $language = Language::findOrFail($id);
        return response(['language' => $language], 200);
    }

    public function destroy(int $id, Request $request) {
        $language = Language::findOrFail($id);
        $language->update(['user_id' => $request->user()->id]);
        $language->delete();
        return response(['language' => $language], 200);
    }


}
