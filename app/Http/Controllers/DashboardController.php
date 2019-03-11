<?php

namespace App\Http\Controllers;

use App\Teams;
use App\Clusters;
use App\Statuses;
use App\Application;
use App\ApplicationStatus;
use App\Attendance;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;

class DashboardController extends Controller
{
    //

    public function home () {
        return view ('welcome');
    }

    public function dashboard (Request $request) {

        $agents_percentage = $this->percentageDashboard();
        $application_status_model = new ApplicationStatus();
        $application_model = new Application();
        $statuses_model = new Statuses();
        $teams_model = new Teams();
        $clusters_model = new Clusters();
        $attendance_model = new Attendance();
        $user_model = new User();

        //
        $no_of_status_that_used = $statuses_model->get(['id', 'status'])->map(function ($r) use ($application_model){
            $r['total_count'] = $application_model->where('status', $r['id'])->count();
            return $r;
        });

        // Application counter by cluster
        $application_counter_by_cluster = $application_model->with('getClusterName')->groupBy('cluster_id')
        ->get(['cluster_id'])->map(function ($r) use ($application_model){
            $r['total_count'] = $application_model->where('cluster_id', $r['cluster_id'])->count();
            return $r;
        })->sortByDesc('total_count');

        // Application counter by cluster
        $application_counter_by_teams = $application_model->with('getTeam')->groupBy('team_id')
        ->get(['team_id'])->map(function ($r) use ($application_model){
            $r['total_count'] = $application_model->where('team_id', $r['team_id'])->count();
            return $r;
        })->sortByDesc('total_count');

        // dd($application_counter_by_teams);

        // dd(getHeirarchy2());

        return view('app.dashboard', [
            'no_of_status_that_used' => $no_of_status_that_used,
            'application_counter_by_cluster' => $application_counter_by_cluster,
            'application_counter_by_teams' => $application_counter_by_teams,
            // 'clusters' => (!empty($clusters)) ? $clusters : null,
            // 'teams' => (!empty($teams)) ? $teams : null,
            'heirarchy' => getHeirarchy2(),
            'percentage' => $agents_percentage,
        ]);
    }

    // Method for attendance Dashboard
    public function attendanceDashboard(Request $request){
        // date format should be year-month-day e.g. 2019-03-01
        try{

            $datenow = ($request->date !== null) ? Carbon::parse($request->date) : ((!empty($request->exactdate)) ? Carbon::parse($request->exactdate) : now());
            $tempdate = [
                'prev' => ($request->date !== null) ? Carbon::parse($request->date) : ((!empty($request->exactdate)) ? Carbon::parse($request->exactdate) : now()),
                'next' => ($request->date !== null) ? Carbon::parse($request->date) : ((!empty($request->exactdate)) ? Carbon::parse($request->exactdate) : now()),
            ];

        }catch (\Exception $e){
            $datenow = now();
            $tempdate = [
                'prev' =>  now(),
                'next' =>  now(),
            ];
        }

        $attendance_model = new Attendance();
        if(!empty($request->exactdate)){
            $myattendance['attendance'] = $attendance_model->where('user_id', auth()->user()->id)->whereDate('created_at', '=', $datenow->format('Y-m-d'))->get();
            $myattendance['currmsg'] =  'Data On '.$datenow->format("F d Y");

        }else{
            $myattendance['attendance'] = $attendance_model->where('user_id', auth()->user()->id)->whereMonth('created_at', '=', $datenow->month)->get();
            $myattendance['currmsg'] =  'For this month of '.$datenow->format("F");

        }
        $myattendance['prev'] =  $tempdate['prev']->subMonths(1)->format('Y-m-d');
        $myattendance['curr'] =  $datenow;
        $myattendance['next'] =  $tempdate['next']->addMonths(1)->format('Y-m-d');


        // RETURN VIEW
        return view('app.attendance.dashboard', [
            'heirarchy' => getHeirarchy2(),
            'myattendance' => (!empty($myattendance)) ? $myattendance : null,
        ]);
    }

    public function percentageDashboard(){


        $agent_ids = [];
        $team_name = '';
        // For cl agents
        // if(!empty(Session::get('_c'))){
        //     if(!empty(Session::get('_c')[0]['team_ids'])){
        //
        //         $team_ids = Teams::whereIn('id',Session::get('_c')[0]['team_ids'])->get(['agent_ids']);
        //         $agent_id = $team_ids->pluck('agent_ids')->toArray();
        //
        //         $agent_ids = [];
        //
        //         foreach($agent_id as $ids){
        //             foreach($ids as $id){
        //                 $agent_ids[] = $id;
        //             }
        //         }
        //     }
        // }else
        // if tl get agents id
        if(!empty(Session::get('_t'))) {

            if(!empty(Session::get('_t')[0]['agent_ids'])){

                $agent_ids = Session::get('_t')[0]['agent_ids'];
                $team_name = Session::get('_t')[0]['team_name'];
            }
        }elseif(!empty(Session::get('_a'))){
            // if agent get id
            if(!empty(Session::get('_a')[0]['agent_ids'])){
                $agent_ids = [Auth()->user()->id];
                $team_name = Session::get('_a')[0]['team_name'];

            }
        }
        // get date today
        $date = Carbon::now();
        // agent target, new, activated and percentage to target information
        $agents_percentage = ['data_percentage' => computation($agent_ids,$date),
        'team_name' => $team_name
    ];

    return $agents_percentage;

    }
}
