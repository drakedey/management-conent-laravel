<?php

namespace App\Http\Controllers;

use App\BranchType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('client.admincredentials')->only('store', 'update', 'destroy');
    }

    public function index() {
        $branchTypes = BranchType::all();
        return response(['branchTypes' => $branchTypes], 200);
    }

    public function store(Request $request) {
        $rules = [
            'name' => 'required|unique:branch_types',
        ];

        // Validating object
        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        }
        $data['user_id'] = $request->user()->id;
        $branchType = BranchType::create($data);
        return response(['success' => 'branch created successfully', 'branchType' => $branchType], 200);
    }

    public function update(int $id, Request $request) {
        $branchType = BranchType::findOrFail($id);
        $rules = [
            'name' => 'required|unique:branch_types',
        ];
        // Validating object
        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        }

        $data['user_id'] = $request->user()->id;
        $branchType->update($data);
        return response(['success' => 'branch updated successfully', 'branchType' => $branchType], 200);
    }

    public function show(int $id) {
        $branchType = BranchType::findOrFail($id);
        return response(["branchType" => $branchType], 200);
    }

    public function destroy(int $id) {
        $branchType = BranchType::findOrFail($id);
        $branchType->delete();
        return response(['success' => 'branch deleted successfully', 'branchType' => $branchType], 200);
    }
}
