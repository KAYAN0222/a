<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $query = Branch::where('is_active', true);

        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->governorate) {
            $query->where('governorate', $request->governorate);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar'     => 'required|string|max:150',
            'name_en'     => 'nullable|string|max:150',
            'type'        => 'in:branch,agent',
            'city'        => 'nullable|string|max:80',
            'governorate' => 'nullable|string|max:80',
            'address'     => 'nullable|string',
            'phone'       => 'nullable|string|max:30',
            'email'       => 'nullable|email',
            'manager'     => 'nullable|string|max:100',
            'is_active'   => 'boolean',
            'lat'         => 'nullable|numeric',
            'lng'         => 'nullable|numeric',
        ]);

        return response()->json(Branch::create($data), 201);
    }

    public function update(Request $request, Branch $branch)
    {
        $branch->update($request->all());
        return response()->json($branch->fresh());
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return response()->json(['message' => 'تم الحذف بنجاح.']);
    }
}
