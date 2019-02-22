<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Attendance;
use App\User;
use App\Teams;
use App\Clusters;
use Faker\Factory as Faker;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function sample(){
        $faker=faker::create();

        // $user_id = Auth::user()->id;
        return $user_id = User::where('id', 77)->first();
        // if(Auth::user()->role == base64_encode("Team Leader")){
        if($user_id->role == base64_encode("Team Leader")){
            // return Teams::
        }
     }
    public function index()
    {
        if(Auth::user()->role == base64_encode('tl')){
            $get_agents = Teams::where('tl_id', Auth::user()->id)->value('agent_code'); // GET TL/CL's AGENT/S
        }
        else if(Auth::user()->role == base64_encode('cl')){
            $get_agents = Clusters::where('cl_id', Auth::user()->id)->value('tl_ids'); // GET TL/CL's AGENT/S
        }

        // GET UNPRESENT AGENTS
        $selected_unpresent_agents = [];
        foreach($get_agents as $agent){
            // CHECK IF AGENT IS ALREADY EXISTING ON ATTENDANCE TABLE ON THIS DAY
            // return $agent;
            $check_agent = Attendance::where('user_id', $agent)->whereDate('created_at', Carbon::today())->first();
            if(empty($check_agent) || $check_agent == null){
                array_push($selected_unpresent_agents, $agent);
            }
        }
        // return $selected_unpresent_agents;
        $attendance['unpresent'] = User::whereIn('id', $selected_unpresent_agents)->get();

        // GET PRESENT AGENTS
        $attendance['present'] = Attendance::whereIn('user_id', $get_agents)->whereDate('created_at', Carbon::today())->where('status', 1)->with(['Users'])->get();

        // GET ABSENT AGENTS
        $attendance['absent'] = Attendance::whereIn('user_id', $get_agents)->whereDate('created_at', Carbon::today())->where('status', 0)->with(['Users'])->get();

        return view('app.attendance.index', compact('attendance'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $data = [];
        foreach($request->only('user')['user'] as $user){
            if(!empty($user['selected_row'])){
                $set_data = [
                    "user_id" => $user['user_id'],
                    "activities" => $user['activities'],
                    "location" => $user['location'],
                    "remarks" => $user['remarks'],
                    "status" => $user['status'],
                    'created_at' => Carbon::today(),
                    'updated_at' => Carbon::today(),
                ];
                array_push($data, $set_data);
            }
        }
        // return $data;
        Attendance::insert($data);
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
