<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index()
    {
        return Status::all(); // Laravel automatically converts to JSON
    }

    public function store(Request $request)
    {
        return Status::create(
            $request->validate([
                'name' => 'required|string|max:255|unique:statuses'
            ])
        );
    }

    public function show(Status $status)
    {
        return $status;
    }

    public function update(Request $request, Status $status)
    {
        $status->update(
            $request->validate([
                'name' => 'required|string|max:255|unique:statuses,name,'.$status->id
            ])
        );
        
        return $status;
    }

    public function destroy(Status $status)
    {
        $status->delete();
        return response()->noContent();
    }
}