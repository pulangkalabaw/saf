<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use App\Teams;
use Illuminate\Http\Request;

class TeamsController extends Controller
{
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index(Request $request)
	{

		$teams = new Teams();

		// Count all before paginate
		$teams_total = $teams->count();

		// Sorting
        // params: sort_in & sort_by
        if (!empty($request->get('sort_in') && !empty($request->get('sort_by')))) {
			$teams = $teams->sort($request);
		}

		// Search
        if (!empty($request->get('search_string'))) $teams = $teams->search($request->get('search_string'));

        // Count all before paginate
        $total = $teams->count();

		// Pagination
		$teams = $teams->paginate((!empty($request->show) ? $request->show : 10));

		return view('app.teams.index', ['teams' => $teams, 'teams_total' => $teams_total, 'total' => $total]);

	}
	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function create()
	{
		//
		$users = User::get();
		return view('app.teams.create', [
			'users' => $users
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
			'team_id' => 'required|string|max:4',
			'team_name' => 'required|string',
			'tl_ids' => 'nullable',
			'agent_ids' => 'nullable',
		]);

		if ($v->fails()) return back()->withErrors($v->errors());

		if (Teams::create($request->except('_token'))) {
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
		$teams = Teams::where('id', $id)->firstOrFail();

		return view('app.teams.show', ['team' => $teams]);
	}

	/**
	* Show the form for editing the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function edit($id)
	{
		$users = new User();
		$teams = Teams::where('id', $id)->firstOrFail();

		$agents = $users->getAvailableAgent();
		$team_leaders = $users->getAvailableTeamLeader();

		return view('app.teams.edit', ['team' => $teams, 'team_leaders' => $team_leaders, 'agents' => $agents]);
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
		$teams = Teams::where('id', $id)->firstOrFail();

		$v = Validator::make($request->all(), [
			'team_id' => 'required|string|max:4',
			'team_name' => 'required|string',
			'tl_ids' => 'nullable',
			'agent_ids' => 'nullable',
		]);

		if ($v->fails()) return back()->withErrors($v->errors());

		$request['tl_ids'] = empty($request['tl_ids']) ? [] : $request['tl_ids'];
		$request['agent_ids'] = empty($request['agent_ids']) ? [] : $request['agent_ids'];

		if ($teams->update($request->except(['_token', '_method']))) {
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
