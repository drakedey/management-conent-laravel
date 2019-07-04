<?php

namespace App\Http\Controllers;

use App\Category;
use App\CategoryLanguage;
use App\Country;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
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
        //
        $categories = Category::all();
        return response(['categories' => $categories], 200);
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
            'division_id' => 'required|exists:divisions,id',
            'parent_category'=>'exists:categories,id',
            'content' => 'required',
            'countries' => 'required'
        ];

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
            if($language == Language::SPANISH || !empty($language_content) ) {
                $innerContentValidator = Validator::make($language_content, $rules);
                if($innerContentValidator->fails()) {
                    return response(['error' => $innerContentValidator->errors()], 400);
                }
            }
        }

        //Validating countries, that exists
        $countries = array_unique($data['countries']);
        foreach ($countries as $country) {
            Country::where('id', '=', $country)->firstOrFail();
        }

        $createData = [
            'user_id' => $request->user()->id,
            'division_id' => $data['division_id'],
        ];

        if(!empty($data['parent_category'])) {
            $createData['parent_category'] = $data['parent_category'];
        }

        //Creating
        $category = Category::create($createData);
        $category->countries()->sync($data['countries']);
        foreach ($content as $language => $language_content) {
            $languageRegister = Language::where('name', '=', $language)->firstOrFail();
            $categoryContentData = [
                'name' => $language_content['name'],
                'category_id' => $category->id,
                'language_id' => $languageRegister->id
            ];
            CategoryLanguage::create($categoryContentData);
        }

        return response(['success' => 'Category created succesfully', 'category' => $category], 200);


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
            $category = Category::query()->findOrFail($id);
            return response(["category" => $category], 200);
        } catch (\Exception $exception) {
            return response(["error" => "category with id ". $id ." not found"], 404);
        }
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
        $rules = [
            'division_id' => 'exists:divisions,id',
        ];

        if($request->has("parent_category")) {
            if($request->get("parent_category") != null)
                $rules['parent_category'] = 'exists:categories,id';
        }

        $category = Category::findOrFail($id);

        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        }

        //Validating that at content there's the spanish body (the minimun required)
        if($request->has("content")){
            $content = $data['content'];

            //Validating that the language exists on DB and the content is not empty and accomplish with the minimun data
            foreach ($content as $language => $language_content) {
                //Validating thar exists in other case returns 404
                Language::where('name', '=', $language)->firstOrFail();
                $rules = [
                    'name' => 'required',
                ];
                //Spanish is mandatory, if language content is not empty the validate
                if($language == Language::SPANISH || !empty($language_content) ) {
                    $innerContentValidator = Validator::make($language_content, $rules);
                    if($innerContentValidator->fails()) {
                        return response(['error' => $innerContentValidator->errors()], 400);
                    }
                }
            }

            foreach ($content as $language => $language_content) {
                $languageTarget = Language::where('name', '=', $language)->first();
                $contentTarget = CategoryLanguage::where('category_id', '=', $category->id)->where('language_id','=', $languageTarget->id)->first();
                $contentLanguage = [
                    'name' => $language_content['name'],
                ];
                if($contentTarget) {
                    $contentTarget->update($contentLanguage);
                } else {
                    $contentLanguage['category_id'] = $category->id;
                    $contentLanguage['language_id'] = $languageTarget->id;
                    CategoryLanguage::create($contentLanguage);
                }
            }
        }

        //Validating countries, that exists

        //Adding country
        if($request->has("addCountries")) {
        $countries = array_unique($data['addCountries']);
            foreach ($countries as $country) {
                Country::where('id', '=', $country)->firstOrFail();
            }
                try{
                    $category->countries()->sync($countries, false);
                } catch (\Exception $e) {
                    return response(['error' => 'error at adding new country to the relation']);
                }
        }

        //Removing country
        if($request->has('removeCountries')) {
            try{
                $countries = array_unique($data['removeCountries']);
                foreach ($countries as $country) {
                    Country::where('id', '=', $country)->firstOrFail();
                }
                $category->countries()->detach($countries);
            } catch (\Exception $e) {
                return response(['error' => 'error at removing existing country to the relation']);
            }
        }

        //Removing child categories
        if($request->has("removeChildren")) {
            $children = $data["removeChildren"];
            foreach ($children as $child){
                try {
                    $childCategory = Category::findOrFail($child);
                    $childCategory->update([
                        'parent_category' => null
                    ]);
                } catch (\Exception $e) {
                    return response(['error' => "Error deleting relation"], 400);
                }
            }
        }

        //Adding child categories
        if($request->has("addChildren")) {
            $children = $data["addChildren"];
            foreach ($children as $child){
                try {
                    $childCategory = Category::findOrFail($child);
                    $childCategory->update([
                        'parent_category' => $category->id
                    ]);
                } catch (\Exception $e) {
                    return response(['error' => "Error creating relation"], 400);
                }
            }
        }

        //Updating related columns table
        $arrayUpdate = [];
        if($request->has("division_id"))
            $arrayUpdate["division_id"] = $data["division_id"];
        if($request->has("parent_category"))
            $arrayUpdate["parent_category"] = $data["parent_category"];

        if(!empty($arrayUpdate))
            $category->update($arrayUpdate);

        return response(['success' => 'category updated succesfully', 'division' => $category], 200);
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
            $category = Category::query()->findOrFail($id);
            $category->delete();
            return response(['success' => 'category deleted succesfully', 'category' => $category], 200);
        } catch (\Exception $e) {
            return response(['error' => "category with id: $id not found"], 404);
        }
    }
}
