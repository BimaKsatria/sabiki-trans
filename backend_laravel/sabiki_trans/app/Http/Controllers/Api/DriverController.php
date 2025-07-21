<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        return driver::with('rental')->get();
    }

    public function show($id)
    {
        return driver::with('rental')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service' => 'required|string|max:255',
            'driver_fee' => 'required|numeric',
        ]);

        return driver::create($validated);
    }

    public function update(Request $request, $id)
    {
        $driver = driver::findOrFail($id);
        $driver->update($request->all());

        return $driver;
    }

    public function destroy($id)
    {
        $driver = driver::findOrFail($id);
        $driver->delete();

        return response()->json(['message' => 'Driver deleted successfully']);
    }
}
