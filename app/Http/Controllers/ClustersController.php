<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use App\Teams;
use App\Clusters;
use Illuminate\Http\Request;

class ClustersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

		$cluters = new Clusters();

		// Count all before paginate
		$total = $cluters->count();

		// Pagination
		$cluters = $cluters->paginate((!empty($request->show) ? $request->show : 10));

		return view('app.clusters.index', ['clusters' => $cluters, 'clusters_total' => $total]);



         // Model
        $clusters = new Clusters();

        // Query
        $clusters = $clusters
        ->join('users as cl', 'clusters.cl_id', '=', 'cl.id')
        ->select(
            'cl.fname as cl_fname', 'cl.lname as cl_lname',
            'clusters.*'
        );

        // Sorting
        if (!empty($request->get('sort_in') && !empty($request->get('sort_by')))) {

            if (!in_array($request->get('sort_in'), ['cluster-name', 'cluster-leader'])) return back();
            else $request['sort_in'] = $request->get('sort_in') == "cluster-name" ? 'cluster_name' : 'cl_fname';

            $clusters = $clusters->orderBy($request->get('sort_in'), $request->get('sort_by'));
        }

        // Search
        if (!empty($request->get('search_string'))) $clusters = $clusters->search($request->get('search_string'));

        // Count all before paginate
        $total = $clusters->count();

        // Pagination
        $clusters = $clusters->paginate((!empty($request->show) ? $request->show : 10));

        return view('app.clusters.index', ['clusters' => $clusters, 'clusters_total' => $total]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = new User();
        $teams = new Teams();
        return view('app.clusters.create', [
            'users' => $users->get(), // clusters
            'teams' => $teams->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'cluster_name' => 'required|string',
            'cl_id' => 'required',
            'team_ids' => 'required',

        ]);

        if ($v->fails()) return back()->withErrors($v->errors());

        $request['cluster_id'] = rand(111,99999);
        $request['team_ids'] = json_encode($request['team_ids']);
        if (Clusters::insert($request->except('_token'))) {
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
        $cluster = Clusters::where('cluster_id', $id)->with([
            'getClusterLeader',
        ])->firstOrFail();

        return view('app.clusters.show', ['cluster' => $cluster]);
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
        $users = new User();
        $teams = new Teams();
        $clusters = new Clusters();
        $cluster = Clusters::where('cluster_id', $id)->with([
            'getClusterLeader',
        ])->firstOrFail();

        return view('app.clusters.edit', [
            'users' => $users,
            'teams' => $teams->get(),
            'cluster' => $cluster,
            'clusters' => $clusters
        ]);

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
        $clusters = Clusters::where('cluster_id', $id)->firstOrFail();
        $v = Validator::make($request->all(), [
            'cluster_name' => 'required|string',
            'cl_id' => 'required',
            'team_ids' => 'required',

        ]);

        if ($v->fails()) return back()->withErrors($v->errors());

        $request['team_ids'] = json_encode($request['team_ids']);
        if ($clusters->update($request->except(['_token', '_method']))) {
            return back()->with([
                'notif.style' => 'success',
                'notif.icon' => 'plus-circle',
                'notif.message' => 'Update complete',
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
    }
}
