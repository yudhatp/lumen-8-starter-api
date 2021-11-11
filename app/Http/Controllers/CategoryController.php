<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return response()->json(Category::all());
    }

    public function detail($id)
    {
        return response()->json(Category::find($id));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'category_name' => 'required|unique:categories',
            'price' => 'required|integer',
            'description' => 'required|string'
        ]);
        $Category = Category::create($request->all());
        return response()->json(['message'=>'ok'], 200);
    }

    public function update($id, Request $request)
    {
        $Category = Category::findOrFail($id);
        $Category->update($request->all());

        return response()->json(['message'=>'ok'], 200);
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        return response()->json(['message'=>'ok'], 200);
    }
}