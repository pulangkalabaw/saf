<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Statuses;
use App\Application;
use Illuminate\Http\Request;

class StatusesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Model
        $statuses = new Statuses();
        $application_model = new Application();

        // Sorting
        // params: sort_in & sort_by
        if (!empty($request->get('sort_in') && !empty($request->get('sort_by')))) $statuses = $statuses->sort($request);

        // Search
        if (!empty($request->get('search_string'))) $statuses = $statuses->search($request->get('search_string'));

        // Count all before paginate
        $total = $statuses->count();

        // Insert pagination
        $statuses = $statuses->paginate((!empty($request->show) ? $request->show : 10));
        return view('app.statuses.index', ['statuses' => $statuses, 'statuses_total' => $total]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('app.statuses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|string|max:255',
        ]);

        // Once validated
        if (Statuses::create($request->except('_token'))) {
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $status = Statuses::findOrFail($id);
        return view('app.statuses.edit', ['status' => $status]);
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
        $status = Statuses::findOrFail($id);
        
        $request->validate([
            'status' => 'required|string|max:255',
        ]);

        // Once validated
        if ($status->update($request->only('status'))) {
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
        $status = Statuses::findOrFail($id);
        $application = Application::where('status', $id);
        if ($application->first()) {
            return back()->with([
                'notif.style' => 'danger',
                'notif.icon' => 'times-circle',
                'notif.message' => "Total of ". $application->count(). " application(s) that uses this status, you must update those application to another status before you remove",
            ]); 
        }
        else {
            $status->delete();
            return back()->with([
                'notif.style' => 'success',
                'notif.icon' => 'plus-circle',
                'notif.message' => 'Delete successful!',
            ]); 
        }
    }
}
