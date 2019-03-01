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

		$clusters = new Clusters();

		// Count all before paginate
		$clusters_total = $clusters->count();

		// Sorting
		// params: sort_in & sort_by
		if (!empty($request->get('sort_in') && !empty($request->get('sort_by')))) $clusters = $clusters->sort($request);

		// Search
        if (!empty($request->get('search_string'))) $clusters = $clusters->search($request->get('search_string'));

        // Count all before paginate
        $total = $clusters->count();

		// Pagination
		$clusters = $clusters->paginate((!empty($request->show) ? $request->show : 10));

		return view('app.clusters.index', ['clusters' => $clusters, 'clusters_total' => $clusters_total, 'total' => $total]);

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
            'cluster_id' => 'required|string',
            'cluster_name' => 'required|string',
            'cl_ids' => 'nullable',
            'team_ids' => 'nullable',

        ]);

        if ($v->fails()) return back()->withErrors($v->errors());

        if (Clusters::create($request->except('_token'))) {
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
		$cluster = Clusters::where('id', $id)->firstOrFail();

		return view('app.clusters.show', ['cluster' => $cluster]);


        $cluster = Clusters::where('id', $id)->with([
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
		$cluster = $clusters->findOrFail($id);

		$cluster_leaders = $users->getAvailableClusterLeader();
		$teams = $teams->get();

		return view('app.clusters.edit', ['cluster' => $cluster, 'teams' => $teams, 'cluster_leaders' => $cluster_leaders]);


        $cluster = Clusters::where('id', $id)->with([
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
        $clusters = Clusters::where('id', $id)->firstOrFail();
        $v = Validator::make($request->all(), [
			'cluster_id' => 'required|string',
            'cluster_name' => 'required|string',
            'cl_ids' => 'nullable',
            'team_ids' => 'nullable',

        ]);

        if ($v->fails()) return back()->withErrors($v->errors());

		$request['cl_ids'] = empty($request['cl_ids']) ? [] : $request['cl_ids'];
		$request['team_ids'] = empty($request['team_ids']) ? [] : $request['team_ids'];

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
