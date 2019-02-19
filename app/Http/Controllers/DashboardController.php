<?php

namespace App\Http\Controllers;

use App\Teams;
use App\Clusters;
use App\Statuses;
use App\Application;
use App\ApplicationStatus;
use Illuminate\Http\Request;

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


        return view('app.dashboard', [
            'no_of_status_that_used' => $no_of_status_that_used,
            'application_counter_by_cluster' => $application_counter_by_cluster,
            'application_counter_by_teams' => $application_counter_by_teams,
        ]);
    }
}
