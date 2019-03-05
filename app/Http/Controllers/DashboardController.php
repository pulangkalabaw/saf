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
        ]);
    }

    // Method for attendance Dashboard
    public function attendanceDashboard(){
        $attendance_model = new Attendance();
        // dd(Carbon::now()->daysInMonth);
        $myattendance = $attendance_model->where('user_id', auth()->user()->id)->whereMonth('created_at', '=', Carbon::now()->month)->get();
        return view('app.attendance.dashboard', [
            'heirarchy' => getHeirarchy2(),
            'myattendance' => $myattendance,
        ]);
    }
}
