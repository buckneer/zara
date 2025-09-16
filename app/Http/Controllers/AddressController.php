<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::where('user_id', Auth::id())->paginate(25);
        return request()->wantsJson() ? response()->json($addresses) : view('account.addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('account.addresses.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:191',
            'company' => 'nullable|string|max:191',
            'line1' => 'required|string|max:255',
            'line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:191',
            'state' => 'nullable|string|max:191',
            'postal_code' => 'nullable|string|max:50',
            'country' => 'required|string|max:100',
            'phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();

        $address = Address::create($data);

        return $request->wantsJson() ? response()->json($address, 201) : redirect()->route('addresses.index')->with('success','Address saved.');
    }

    public function edit(Address $address)
    {
        
        return view('account.addresses.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        
        $data = $request->validate([
            'name' => 'nullable|string|max:191',
            'company' => 'nullable|string|max:191',
            'line1' => 'required|string|max:255',
            'line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:191',
            'state' => 'nullable|string|max:191',
            'postal_code' => 'nullable|string|max:50',
            'country' => 'required|string|max:100',
            'phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $address->update($data);

        return $request->wantsJson() ? response()->json($address) : redirect()->route('addresses.index')->with('success','Address updated.');
    }

    public function destroy(Address $address)
    {
        
        $address->delete();
        return request()->wantsJson() ? response()->json(['message'=>'deleted']) : redirect()->route('addresses.index')->with('success','Address removed.');
    }
}
