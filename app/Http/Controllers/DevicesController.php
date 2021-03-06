<?php

namespace App\Http\Controllers;

use Validator;
use App\Devices;
use App\Application;
use Illuminate\Http\Request;

class DevicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Model
        $devices = new Devices();

        // Sorting
        // params: sort_in & sort_by
        if (!empty($request->get('sort_in') && !empty($request->get('sort_by')))) $devices = $devices->sort($request);

        // Search
        if (!empty($request->get('search_string'))) $devices = $devices->search($request->get('search_string'));

        // Count all before paginate
        $total = $devices->count();

        // Insert pagination
        $devices = $devices->paginate((!empty($request->show) ? $request->show : 10));
        return view('app.devices.index', ['devices' => $devices, 'devices_total' => $total]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('app.devices.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'device_name' => 'required|string|max:255',
        ]);

        // Once validated
        $request['device_id'] = rand(111, 99999);
        if (Devices::create($request->except('_token'))) {
            return back()->with([
                'notif.style' => 'success',
                'notif.icon' => 'plus-circle',
                'notif.message' => 'Added successful!',
            ]); 
        }

        else { 
            return back()->with([
                'notif.style' => 'danger',
                'notif.icon' => 'times-circle',
                'notif.message' => 'Failed to add',
            ]); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $device = Devices::where('device_id', $id)->firstOrFail();
        return view('app.devices.edit', ['device' => $device]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $device = Devices::where('device_id', $id)->firstOrFail();
        
        $request->validate([
            'device_name' => 'required|string|max:255',
        ]);

        // Once validated
        if ($device->update($request->only('device_name'))) {
            return back()->with([
                'notif.style' => 'success',
                'notif.icon' => 'plus-circle',
                'notif.message' => 'Update successful!',
            ]); 
        }

        else { 
            return back()->with([
                'notif.style' => 'danger',
                'notif.icon' => 'times-circle',
                'notif.message' => 'Failed to update',
            ]); 
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $device = Devices::where('device_id', $id)->firstOrFail();

        $application = Application::where('device_name', $id);
        if ($application->first()) {
            return back()->with([
                'notif.style' => 'danger',
                'notif.icon' => 'times-circle',
                'notif.message' => "Total of ". $application->count(). " application(s) that uses this device, you must update those application to another device before you remove",
            ]); 
        }
        else {
            $device->delete();
            return back()->with([
                'notif.style' => 'success',
                'notif.icon' => 'plus-circle',
                'notif.message' => 'Delete successful!',
            ]); 
        }
    }
}
