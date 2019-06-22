<?php

namespace App\Http\Controllers;

use App\Language;
use App\Rol;
use App\Tag;
use App\TagContent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('client.admincredentials');
    }


    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(Request $request)
    {
        if ($request->user()->rol->name == Rol::ADMIN) {
            $tag = Tag::all();
            return response()->json(['tag' => $tag->toArray()], 200);
        }
        return response()->json(['error' => 'Unhautorized'], 401);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $rules = [
            'content' => 'required'
        ];

        // Validating object
        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
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
            //Validating thar exists in other case returns 400
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
        }

        //Creating
        $tag = Tag::create(['user_id' => $request->user()->id]);
        foreach ($content as $language => $language_content) {
            $languageRegister = Language::where('name', '=', $language)->firstOrFail();
            $tagContent = [
                'name' => $language_content['name'],
                'tag_id' => $tag->id,
                'language_id' => $languageRegister->id
            ];
            TagContent::create($tagContent);
        }

        return response()->json(['tag' => $tag], 200);


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show(int $id)
    {
        $tag = Tag::findOrFail($id);
        return \response(['tag' => $tag], 200);
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
        $tag = Tag::query()->findOrFail($id);

        $rules = [
          'content' => 'required',
        ];


        $data = $request->all();

        $validator = Validator::make($data,$rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
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
            $contentTarget = TagContent::where('tag_id', '=', $tag->id)->where('language_id','=', $languageTarget->id)->first();
            $tagContentLanguage = [
                'name' => $language_content['name'],
            ];
            if($contentTarget) {
                $contentTarget->update($tagContentLanguage);
            } else {
                $tagContentLanguage['tag_id'] = $tag->id;
                $tagContentLanguage['language_id'] = $languageTarget->id;
                TagContent::create($tagContentLanguage);
            }
        }

        return response()->json(['success' => 'tag updated succesfully', 'tag' => $tag], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \http\Env\Response response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tag = Tag::query()->findOrFail($id);
        $tag->delete();
        return \response(['success' => 'tag deleted', 'tag' => $tag], 200);
    }
}
