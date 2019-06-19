<?php

namespace App\Http\Controllers;

use App\Branch;
use App\BranchType;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('client.admincredentials');
    }

    public function index() {
        $branches = Branch::all();
        return response(['branches' => $branches], 200);
    }

    public function show($id) {
        $branch = Branch::findOrFail($id);
        return response(['branch' => $branch], 200);
    }

    public function store(Request $request) {
        $rules = [
            'name' => 'required|unique:branches',
            //Verify dimensions and file size
            'image' => 'required|image|mimes:png|max:2048',
            'branch_type_id' => 'required|exists:branch_types,id'
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        }

        $data['user_id'] = $request->user()->id;
        //Create branch
        $newBranch = Branch::create($data);

        //Create image
        $imageName = $newBranch->id. '.png';
        $path = $request->file('image')->move(public_path('images/branch/'), $imageName);
        $url = url('/images/branch/'.$imageName);
        $imageData = array(
            'name' => $imageName,
            'type' => 'branch',
            'branch_id' => $newBranch->id,
            'url' => $url,
            'product_id' => null,
            'new_id' => null
        );
        $image = Image::create($imageData);
        return response(['success' => 'branch created succesfully', 'branch' => $newBranch], 200);
    }

    public function update(int $id, Request $request) {
        $branch = Branch::findOrFail($id);
        $rules = [
            'name' => 'unique:branches',
            'image' => 'image|mimes:png|max:2048',
            'branch_type_id' => 'exists:branch_types,id,deleted_at,null'

        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        }

        if($request->filled('name') || $request->filled('branch_type_id')) {
            $branch->update($data);
        }

        if($request->hasFile('image')) {
            $branchImage = Image::where('branch_id', '=', $branch->id)->firstOrFail();
            //Deleting current image
            Storage::disk('public')->delete('images/branch/'.$branchImage->name);
            //Create image
            $imageName = $branch->id . '.png';
            $path = $request->file('image')->move(public_path('images/branch/'), $imageName);
            $url = url('/images/branch/'.$imageName);
            $imageData = array(
                'name' => $imageName,
                'type' => 'branch',
                'url' => $url,
            );
            $branchImage->update($imageData);
        }

        return response(['success' => 'branch updated succesfully', 'branch' => $branch], 200);
    }

    public function destroy(int $id) {
        $branch = Branch::findOrFail($id);
        $branch->delete();
        return response(['success' => 'branch deleted successfully', 'branch' => $branch], 200);
    }

    public function getByTypeId(int $id) {
        $branchtype = BranchType::findOrFail($id);
        $branches = $branchtype->branches;
        return response(['branches' => $branches, 'branchType' => $branchtype], 200);
    }
}
