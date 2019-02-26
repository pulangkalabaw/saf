<?php
namespace App\Http\Controllers;

use Auth;
use Session;
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
        $faker = faker::create();
        $cluster_id = $faker->randomElement(Clusters::get());
        // return $cluster_id['team_ids'];
        return $team_id  = Teams::where('team_id', $faker->randomElement($cluster_id['team_ids']))->first();
        $user_id = User::where('id', $faker->randomElement($team_id['agent_ids']))->value('id');
     }
    public function index()
    {
        $get_session;

        if(count(session()->get('_c')) >= 1){
            $get_session = session()->get('_c');
            $user_ids = session()->get('_c')[0];
            if(count(session()->get('_c')) == 1){
                $teams = Teams::whereIn('team_id', $user_ids['team_ids'])->get();
                $selected_unpresent_users = [];
                foreach($user_ids['team_ids'] as $agent){
                    foreach(Teams::where('team_id', $agent)->pluck('agent_ids') as $agents){
                        foreach($agents as $agnt){
                            $check_agent = Attendance::where('user_id', $agnt)->whereDate('created_at', Carbon::today())->first();
                            if(empty($check_agent) || $check_agent == null){
                                array_push($selected_unpresent_users, $agnt);
                            }
                        }
                    }
                    foreach(Teams::where('team_id', $agent)->pluck('tl_ids') as $tl_ids){
                        foreach($tl_ids as $tl){
                            $check_tl = Attendance::where('user_id', $tl)->whereDate('created_at', Carbon::today())->first();
                            if(empty($check_tl) || $check_tl == null){
                                array_push($selected_unpresent_users, $tl);
                            }
                        }
                    }
                }
            }
        }
        else if(count(session()->get('_t')) > 1){
            $get_session = session()->get('_t');
            $user_ids = session()->get('_t')[0];
            if(count(session()->get('_t')) == 1){
                $selected_unpresent_users = [];
                foreach($user_ids['agent_ids'] as $agent){
                    $check_agent = Attendance::where('user_id', $agent)->whereDate('created_at', Carbon::today())->first();
                    if(empty($check_agent) || $check_agent == null){
                        array_push($selected_unpresent_users, $agent);
                    }
                }
            }
            else if(count(session()->get('_t')) > 1){
                $combine =[];
                foreach(session()->get('_t') as $agents){
                    foreach($agents['agent_ids'] as $agent){
                        array_push($combine, $agent);
                    }
                }
                $selected_unpresent_users = $combine;
            }
            $teams = $get_session != null ? $get_session : null ;
        }
        // GET ROLL CALL
        $unpresent = User::whereIn('id', $selected_unpresent_users)->get();
        collect($unpresent)->map(function($r) use ($teams){
            foreach($teams as $team){
                foreach($team['agent_ids'] as $agent){
                    if($r->id == $agent){
                        $r['team_name'] = $team['team_name'];
                    }
                }
                foreach($team['tl_ids'] as $agent){
                    if($r->id == $agent){
                        $r['team_name'] = $team['team_name'];
                    }
                }
                // return $team['tl_ids'];
                if(in_array( $r->id, $team['tl_ids'])){
                    // return 'sad';
                    $r['tl'] = 1;
                }
                // else {
                //     $r['tl'] = 0;
                // }
            }
            return $r;
        });

        $sortArray = array();

        foreach((array)$unpresent as $get_unpresent){
            foreach((array)$get_unpresent as $un_present){
                foreach(collect($un_present) as $key=>$value){
                    if(!isset($sortArray[$key])){
                        $sortArray[$key] = array();
                    }
                    $sortArray[$key][] = $value;
                }
            }
        }
        // return $sortArray;
        $orderby = "team_name"; //change this to whatever key you want from the array\
        $unpresent = $unpresent->toArray();
        array_multisort($sortArray[$orderby],SORT_ASC, $unpresent);
        // array_multisort($sortArray['tl'],SORT_ASC, $unpresent);
        $attendance['unpresent'] = $unpresent;

        // GET PRESENT AGENTS
        $attendance['present'] = Attendance::whereIn('user_id', $user_ids)->whereDate('created_at', Carbon::today())->where('status', 1)->with(['Users'])->get();

        // GET ABSENT AGENTS
        $attendance['absent'] = Attendance::whereIn('user_id', $user_ids)->whereDate('created_at', Carbon::today())->where('status', 0)->with(['Users'])->get();
        // return $attendance['unpresent'];
        return view('app.attendance.index', compact('attendance', 'teams'));
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
        // $faker = faker::create();
        // $cluster_id = $faker->randomElement(Clusters::get());
        // // return $cluster_id['team_ids'];
        // $team_id  = Teams::where('team_id', $faker->randomElement($cluster_id['team_ids']))->first();
        // $user_id = User::where('id', $faker->randomElement($team_id['agent_ids']))->value('id');
        // $cluster_id = Clusters::where('team_ids', 'like', '%' . $team_id['team_id'] . '%')->value('id');
        // return $request->all();
        // return $team_id = Teams::where('tl_id', Auth::user()->id)->get(['team_id']);
        // return Clusters::where('team_ids', 'like', '%' . $team_id . '%')->get(['cluster_id']);

        $this->validate($request, [
            'empImg' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($request->hasFile('empImg')) {
            $image = $request->file('empImg');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            // $this->save();
            // return $name;
        }
        $data = [];
        foreach($request->only('user')['user'] as $user){
            if($user['status'] != null){
                $team_id;
                foreach(session()->get('_t') as $teams){
                    // return $agent;
                    if(in_array( $user['user_id'], $teams['agent_ids'])){
                        $team_id = $teams['team_id'];
                    }
                }
                // return $team_id;
                $cluster_id = Clusters::where('team_ids', 'like', '%' . $team_id . '%')->value('cluster_id');
                $set_data = [
                    "cluster_id" => $cluster_id,
                    "team_id" => $team_id,
                    "user_id" => $user['user_id'],
                    "activities" => $user['activities'],
                    "location" => $user['location'],
                    "remarks" => $user['remarks'],
                    "status" => $user['status'],
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
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
