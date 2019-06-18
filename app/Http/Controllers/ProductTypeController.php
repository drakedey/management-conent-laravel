<?php

namespace App\Http\Controllers;

use App\Language;
use App\ProductType;
use App\ProducTypeLanguages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class ProductTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('client.admincredentials');
    }

    public function index() {
        $productTypes = ProductType::all();
        return response(['productTypes' => $productTypes], 200);
    }

    public function store(Request $request) {
        $rules = [
            'content' => 'required',
            'user_id' => 'required'
        ];

        // Validating object
        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        }

        //Validating that at content there's the spanish body (the minimun required)
        $content = $data['content'];
        $contentRules = [Language::SPANISH => 'required'];
        $contentValidator = Validator::make($content, $contentRules);
        if($contentValidator->fails()) {
            return response(['error' => $validator->errors()], 400);
        }

        //Validating that the language exists on DB and the content is not empty and accomplish with the minimun data
        foreach ($content as $language => $language_content) {
            //Validating thar exists in other case returns 404
            Language::where('name', '=', $language)->firstOrFail();
            $rules = [
                'name' => 'required',
            ];
            //Spanish is mandatory, if language content is not empty the validate
            if($language == Language::SPANISH || $language_content ) {
                $innerContentValidator = Validator::make($language_content, $rules);
                if($innerContentValidator->fails()) {
                    return response(['error' => $innerContentValidator->errors()], 400);
                }
            }
        }

        //Creating
        $productType = ProductType::create(['user_id' => $data['user_id']]);
        foreach ($content as $language => $language_content) {
            $languageRegister = Language::where('name', '=', $language)->firstOrFail();
            $productTypeLanguage = [
                'name' => $language_content['name'],
                'type_id' => $productType->id,
                'language_id' => $languageRegister->id
            ];
            ProducTypeLanguages::create($productTypeLanguage);
        }

        return response(['success' => 'product type created succesfully', 'productType' => $productType], 200);
    }

    public function update(int $id, Request $request)
    {

        $productType = ProductType::findOrFail($id);

        $rules = [
            'content' => 'required'
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        }

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
            $contentTarget = ProducTypeLanguages::where('type_id', '=', $productType->id)->where('language_id','=', $languageTarget->id)->first();
            $productTypeLanguage = [
                'name' => $language_content['name'],
            ];
            if($contentTarget) {
                $contentTarget->update($productTypeLanguage);
            } else {
                $productTypeLanguage['type_id'] = $productType->id;
                $productTypeLanguage['language_id'] = $languageTarget->id;
                ProducTypeLanguages::create($productTypeLanguage);
            }
        }

        return response(['success' => 'language updated succesfully', 'productType' => $productType], 200);
    }

    public function show(int $id) {
        $productType = ProductType::findOrFail($id);
        return response(["productType" => $productType], 200);
    }

    public function destroy(int $id, Request $request) {
        $productType = ProductType::findOrFail($id);
        $data = $request->all();
        $rules = [
            'all' => 'required',
        ];

        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return response(['errors' => $validator->errors()], 400);
        }

        $data['all'] = $data['all'] == 'true' ? true : false;

        if($data['all'] == true) {
            $productType->delete();
            return response(['success' => 'product type deleted', 'productType' => $productType], 200);
        } else {
            //Validating
            $rules = [
                'languageTargets' => 'required'
            ];
            $validator = Validator::make($data, $rules);
            if($validator->fails()) {
                return response(['errors' => $validator->errors()], 400);
            }
            foreach ($data['languageTargets'] as $language) {
                $languageTarget = Language::where('name', '=', $language)->firstOrFail();
                ProducTypeLanguages::where('type_id', '=', $productType->id)->where('language_id','=', $languageTarget->id)->firstOrFail();
            }
            //Deleting
            foreach ($data['languageTargets'] as $language) {
                $languageTarget = Language::where('name', '=', $language)->firstOrFail();
                $productContent = ProducTypeLanguages::where('type_id', '=', $productType->id)->where('language_id','=', $languageTarget->id)->firstOrFail();
                $productContent->delete();
            }
            return response(['success' => 'product type languages deleted', 'productType' => $productType], 200);
        }
    }
}
