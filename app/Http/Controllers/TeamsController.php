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
        // Model
        $teams = new Teams();

        // Query
        $teams = $teams
        ->join('users as tl', 'teams.tl_id', '=', 'tl.id')
        ->leftJoin('users as ac', function($q){
            $q->on('ac.id', '=', 'teams.agent_code')
            ->where('ac.role', '=', base64_encode('agent'));
        })
        ->select(
            'tl.fname as tl_fname', 'tl.lname as tl_lname',
            'ac.fname as ac_fname', 'ac.lname as ac_lname', 'ac.id as ac_agent_code',
            'teams.*'
        );

        // Sorting
        if (!empty($request->get('sort_in') && !empty($request->get('sort_by')))) {

            if (!in_array($request->get('sort_in'), ['team-name', 'team-leader'])) return back();
            else $request['sort_in'] = $request->get('sort_in') == "team-name" ? 'team_name' : 'tl_fname';

            $teams = $teams->orderBy($request->get('sort_in'), $request->get('sort_by'));
        }

        // Search
        if (!empty($request->get('search_string'))) $teams = $teams->search($request->get('search_string'));

        // Count all before paginate
        $total = $teams->count();

        // Pagination
        $teams = $teams->paginate((!empty($request->show) ? $request->show : 10));

        return view('app.teams.index', ['teams' => $teams, 'teams_total' => $total]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $users = new User();
        return view('app.teams.create', ['users' => $users]);
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
            'team_name' => 'required|string',
            'tl_id' => 'required',
            'agent_code' => 'required', // agent id
        ]);

        if ($v->fails()) return back()->withErrors($v->errors());

        $request['team_id'] = rand(111,99999);
		$request['agent_code'] = json_encode($request['agent_code']);

        if (Teams::insert($request->except('_token'))) {
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
        $teams = Teams::where('team_id', $id)->with([
            'getTeamLeader',
            'getAgentCode',
        ])->firstOrFail();

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
        $teams = Teams::where('team_id', $id)->with([
            'getTeamLeader',
            'getAgentCode',
        ])->firstOrFail();

        return view('app.teams.edit', ['team' => $teams, 'users' => $users]);
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
        $teams = Teams::where('team_id', $id)->firstOrFail();
        $v = Validator::make($request->all(), [
            'team_name' => 'required|string',
            'tl_id' => 'required',
            'agent_code' => 'required',
        ]);

        if ($v->fails()) return back()->withErrors($v->errors());

		$request['agent_code'] = json_encode($request['agent_code']);
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
