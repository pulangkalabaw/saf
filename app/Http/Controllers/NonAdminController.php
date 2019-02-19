<?php

namespace App\Http\Controllers;

use Session;
use Validator;
use App\User;
use App\Teams;
use App\Clusters;
use Illuminate\Http\Request;

class NonAdminController extends Controller
{

    /**
    * Display your clusters using the session that you get
    * from the login process
    *
    * @param  [type] $id [description]
    * @return [type]     [description]
    */
    public function yourClusters(Request $request)
    {
        $clusters = new Clusters();
        $clusters = $clusters->whereIn('cluster_id', Session::get('_c'));

        // Sort
        if (!empty($request->get('search_string'))) {

            // If everything is good
            $clusters = $clusters->orderBy('cluster_name', $request->get('sort_by'));
        }


        // Search
        if (!empty($request->get('search_string'))) $clusters = $clusters->where('cluster_name', 'LIKE', '%'. $request->get('search_string') .'%');

        // Count all before paginate
        $total = $clusters->count();

        // Pagination
        $clusters = $clusters->paginate((!empty($request->show) ? $request->show : 10));

        return view('app.your_clusters.index', [
            'clusters' => $clusters,
            'clusters_total' => $total
        ]);
    }

    public function clusterShow($id)
    {
        // Check the given id is in your session Cluster (_c)
        if (!in_array($id, Session::get('_c'))) {
            return response()->json(["message" => "Invalid link!"]);
        }

        $cluster = Clusters::where('cluster_id', $id)->with([
            'getClusterLeader',
        ])->firstOrFail();

        return view('app.your_clusters.show', ['cluster' => $cluster]);
    }

    public function teamShow($id)
    {
        // Check the given id is in your session Teams (_t)
        if (!in_array($id, Session::get('_t'))) {
            return response()->json(["message" => "Invalid link!"]);
        }

        $teams = Teams::where('team_id', $id)->with([ 'getTeamLeader', 'getAgentCode'])->firstOrFail();

        return view('app.your_teams.show', ['team' => $teams]);

    }
}
