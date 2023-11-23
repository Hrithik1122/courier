<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Receiver;

class LocationController extends Controller
{
    //
    public function showForm()
    {
        return view('index');
    }

    public function submitForm(Request $request)
    {
        $location = Location::create($request->except('receiver'));

        // Insert receiver locations
        $receiverData = $request->input('receiver');
        foreach ($receiverData as $receiver) {
            $location->receivers()->create($receiver);
        }

        // You can add a success message or redirect to a success page
        return redirect()->route('location.form')->with('success', 'Locations inserted successfully');
    }
}
