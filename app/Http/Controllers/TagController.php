<?php

namespace App\Http\Controllers;

use App\Rol;
use App\Tag;
use App\TagContent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
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
            'name' => 'required|unique:tags_content',
            'user_id' => 'required',
        ];

        // Validating object
        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        if ($request->user()->rol->name == Rol::ADMIN) {
            $tag = new Tag($request->all());
            $tag->save();
            $tag_content = new TagContent();
            $tag_content->name = $request->name;
            $tag_content->tag_id = $tag->id;
            $tag_content->language_id = $request->languaje_id;
            $tag_content->save();

            return response()->json(['tag' => $tag], 200);
        }
        return response()->json(['error' => 'Unhautorized'], 401);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        if ($request->user()->rol->name == Rol::ADMIN) {
            $tag = Tag::query()->find($id);
            return response()->json(['tag' => $tag->toArray()], 200);
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
        $tag = Tag::query()->findOrFail($id);

        $rules = [
          'name' => 'unique:tags_content',
        ];


        $data = $request->all();

        $validator = Validator::make($data,$rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $content = $data['name'];


        if ($request->has('name')) {
            $content->name = $content['name'];
        }

        if (!$tag->isDirty()) {
            return response()->json(['error' => 'You must specify at least one different value to update', 'code' => 422], 422);
        }

        $tag->save();

        return response()->json(['success' => 'country updated succesfully', 'country' => $tag], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        //
    }
}
