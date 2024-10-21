<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->can('Manage Location')) {
            $locations = Location::where('created_by', \Auth::user()->creatorId())->get();

            return view('location.index', compact('locations'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->can('Create Location')) {
            return view('location.create');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Location')) {
            $this->validate(
                $request,
                [
                    'name' => 'required|max:40',
                ]
            );

            $location             = new Location();
            $location->name       = $request->name;
            $location->store_id   = \Auth::user()->current_store;
            $location->created_by = \Auth::user()->creatorId();
            $location->save();

            return redirect()->back()->with('success', __('Location added!'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Location $location
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Location $location
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $location)
    {
        if (\Auth::user()->can('Edit Location')) {
            return view('location.edit', compact('location'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Location $location
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        if (\Auth::user()->can('Edit Location')) {
            $this->validate(
                $request,
                [
                    'name' => 'required|max:40',
                ]
            );

            $location->name       = $request->name;
            $location->created_by = \Auth::user()->creatorId();
            $location->save();

            return redirect()->back()->with('success', __('Location Updated!'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Location $location
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        if (\Auth::user()->can('Delete Location')) {
            $location->delete();

            return redirect()->back()->with('success', __('Location Deleted!'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
