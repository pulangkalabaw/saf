<?php

namespace App\Http\Controllers;

use Validator;
use App\Plans;
use App\Application;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Model
        $plans = new Plans();

        // Sorting
        // params: sort_in & sort_by
        if (!empty($request->get('sort_in') && !empty($request->get('sort_by')))) $plans = $plans->sort($request);

        // Search
        if (!empty($request->get('search_string'))) $plans = $plans->search($request->get('search_string'));

        // Count all before paginate
        $total = $plans->count();

        // Insert pagination
        $plans = $plans->paginate((!empty($request->show) ? $request->show : 10));
        return view('app.plans.index', ['plans' => $plans, 'plans_total' => $total]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('app.plans.create');

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
            'plan_name' => 'required|string|max:255',
        ]);

        // Once validated
        $request['plan_id'] = rand(111, 99999);
        if (Plans::create($request->except('_token'))) {
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
        $plan = Plans::where('plan_id', $id)->firstOrFail();
        return view('app.plans.edit', ['plan' => $plan]);
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
        $plan = Plans::where('plan_id', $id)->firstOrFail();
        
        $request->validate([
            'plan_name' => 'required|string|max:255',
        ]);

        // Once validated
        if ($plan->update($request->except(['_token','_method']))) {
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
        $plan = Plans::where('plan_id', $id)->firstOrFail();

        $application = Application::where('plan_applied', $id);
        if ($application->first()) {
            return back()->with([
                'notif.style' => 'danger',
                'notif.icon' => 'times-circle',
                'notif.message' => "Total of ". $application->count(). " application(s) that uses this plan, you must update those application to another devicplan before you remove",
            ]); 
        }
        else {
            $plan->delete();
            return back()->with([
                'notif.style' => 'success',
                'notif.icon' => 'plus-circle',
                'notif.message' => 'Delete successful!',
            ]); 
        }
    }
}
