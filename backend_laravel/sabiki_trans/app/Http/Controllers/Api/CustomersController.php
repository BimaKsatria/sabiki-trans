<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\customers;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function index()
    {
        return customers::with('user')->get();
    }

    public function show($id)
    {
        return customers::with('user')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

        return customers::create($validated);
    }

    public function update(Request $request, $id)
    {
        $customer = customers::findOrFail($id);
        $customer->update($request->all());

        return $customer;
    }

    public function destroy($id)
    {
        $customer = customers::findOrFail($id);
        $customer->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    public function getByUserId($user_id)
    {
        $customer = customers::where('user_id', $user_id)->first();

        if ($customer) {
            return response()->json($customer, 200);
        }

        return response()->json([], 200); // Kosong jika tidak ada, bukan 404 supaya di-handle dari Flutter
    }
}