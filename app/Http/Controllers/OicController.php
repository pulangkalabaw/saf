<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\User;
use App\Clusters;
use App\Teams;
use App\Oic;
use Illuminate\Http\Request;

class OicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $oic = new Oic();

        // Sorting
        // params: sort_in & sort_by
        if (!empty($request->get('sort_in') && !empty($request->get('sort_by')))) $oic = $oic->sort($request);

		// Searching
		if (!empty($request->get('search_string'))) {
			// With search string parameter
			$oic = $oic->search($request->get('search_string'));
		}

        $total = $oic->count();

        $oic = $oic->with(['getCluster','getTeam','getAgent'])
        ->paginate((!empty($request->show) ? $request->show : 10));

        return view('app.oic.index',[
            'oics' => $oic,
            'total' => $total,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $users = Auth::user();
        $getClusterAndTeam = getMyClusterAndTeam(Auth::user());
        // (!empty($getClusterAndTeam['_c'])) ? $getClusterOrTeam = $getClusterAndTeam['_c'] : $getClusterOrTeam = $getClusterAndTeam['_t'];

        // if the login user is cl
        // then show all available tl and agents for that cl leader
        if(!empty($getClusterAndTeam["_c"]))
        {
            $getClusterOrTeam = $getClusterAndTeam['_c'];
            $team_ids = collect($getClusterOrTeam)->pluck('team_ids');
            $agent = Teams::whereIn('id', $team_ids[0])->get()->pluck('agent_ids');
            $user_teams = Teams::whereIn('id', $team_ids[0])->get();
        }
        // else if the login user is tl
        // then show all available teams
        else if (!empty($getClusterAndTeam["_t"]))
        {
            $getClusterOrTeam = $getClusterAndTeam['_t'];
            $team_ids = collect($getClusterOrTeam)->pluck('team_id');
            $agent = Teams::whereIn('team_id', $team_ids)->get()->pluck('agent_ids');
            $user_teams = fetchTeams(Auth::user());
        }

        // $getClusterOrTeam = $getClusterAndTeam['_t'];
        // $team_ids = collect($getClusterOrTeam)->pluck('team_id');

        $agent_decoded = json_decode($agent);
        $users = $users->whereIn('id', $agent_decoded)->get();

        return view('app.oic.create', [
            'users' => $users,
            'teams' => $user_teams
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
        //Get the value of cluster id within the team
        // return $request->all();
        $teams = new Teams();
        $teams = $teams->clusters($request->team_id);
        $cluster_id = $teams[0]['cluster_id'];

        $validate = Validator::make($request->all(),[
            'team_id' => 'required',
            'user_id' => 'required',
        ]);

        $oic_data = [
            'cluster_id' => $cluster_id,
            'team_id' => $request['team_id'],
            'user_id' => $request['user_id'],
            'assign_date' => $request['assign_date'],
            'insert_by' => Auth::user()->id,
            'created_at' => now()
        ];

        if ($validate->fails()) return back()->withErrors($validate->errors());

        if(Oic::insert($oic_data)) {
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
