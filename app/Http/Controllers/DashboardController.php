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

        // FOR ADMIN
        if( empty((Session::get('_c'))) && empty((Session::get('_t'))) && empty((Session::get('_a'))) ){
          $cluster_query = $clusters_model->get();
          $clusters = $cluster_query->pluck('cluster_name');
          $teams = $teams_model->whereIn('id',$cluster_query[0]['team_ids'])->get()->map(function($res) use ($user_model){
              $res['total_agents'] = count($res['agent_ids']);
              // $res['agents'] = $user_model->whereIn('id',collect(Session::get('_t'))->pluck('id'))->get();
              return $res;
          });
        }
        // FOR CLUSTER HEAD
        else if( !empty((Session::get('_c'))) ){
          $clusters = collect(Session::get('_c'))->pluck('cluster_name');
          $teams = $teams_model->whereIn('id',Session::get('_c')[0]['team_ids'])->get()->map(function($res) use ($user_model){
              $res['total_agents'] = count($res['agent_ids']);
              $res['agents'] = $user_model->whereIn('id',collect(Session::get('_c'))->pluck('id'))->get();
              return $res;
          });

        }
        // FOR TEAM LEAD
        else if( !empty((Session::get('_t'))) ){
          $clusters = [null];
          // $clusters = collect(Session::get('_t'))->pluck('team_name');
          // $teams = collect(Session::get('_t'))->pluck('id');
          $teams = $teams_model->whereIn('id',collect(Session::get('_t'))->pluck('id'))->get()->map(function($res) use ($user_model){
              $res['total_agents'] = count($res['agent_ids']);
              $res['agents'] = $user_model->whereIn('id',collect(Session::get('_t'))->pluck('id'))->get();
              return $res;
          });
          // dd($teams);

        }
        else if( !empty((Session::get('_a'))) ){
          $clusters = [null];
          $teams = $teams_model->whereIn('id',collect(Session::get('_a'))->pluck('id'))->get()->map(function($res){
              $res['total_agents'] = count($res['agent_ids']);
              $res['agents'] = $user_model->whereIn('id',collect(Session::get('_a'))->pluck('id'))->get();
              return $res;
          });
        }


        // dd(getHeirarchy());

        return view('app.dashboard', [
            'no_of_status_that_used' => $no_of_status_that_used,
            'application_counter_by_cluster' => $application_counter_by_cluster,
            'application_counter_by_teams' => $application_counter_by_teams,
            'clusters' => (!empty($clusters)) ? $clusters : null,
            'teams' => (!empty($teams)) ? $teams : null,
            'org' => getHeirarchy(),
        ]);
    }
}
