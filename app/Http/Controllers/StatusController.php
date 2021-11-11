<?php

namespace App\Http\Controllers;

use App\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return response()->json(Status::all());
    }

    public function detail($id)
    {
        return response()->json(Status::find($id));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:status'
        ]);
        $status = Status::create($request->all());
        return response()->json($status, 201);
    }

    public function update($id, Request $request)
    {
        $status = Status::findOrFail($id);
        $status->update($request->all());

        return response()->json($status, 200);
    }

    public function delete($id)
    {
        Status::findOrFail($id)->delete();
        return response()->json(['message'=>'ok'], 200);
    }
}