<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\User;
use App\Clusters;
use App\Teams;
use App\Oic;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = getUserDetailClusterAndTeam(Auth::user());
        $oic = new Oic();
        $cluster = new Clusters();

        $agent_ids = [];

        if(base64_decode(Auth::user()->role) == 'user')
        {
            // get first all the agents available to the user
            // if login user is cl, all avaliable agents on its team will be shown
            // if login user is tl, all available agents in the team will be shown
            if(!empty($data["_a"])){
                foreach($data["_a"] as $agent){
                    $agent_ids = array_unique(array_merge($agent_ids,(array) $agent['id']));
                }
                $oic = $oic->whereIn('user_id', $agent_ids);
            } else {
                return back()->with([
                    'notif.style' => 'danger',
                    'notif.icon' => 'times-circle',
                    'notif.message' => 'Failed to access module!',
                ]);
            }
        }

        // Sorting
        // params: sort_in & sort_by
        if (!empty($request->get('sort_in') && !empty($request->get('sort_by')))) $oic = $oic->sort($request);

		// Searching
		if (!empty($request->get('search_string'))) {
			// With search string parameter
			$oic = $oic->search($request->get('search_string'));
		}

        $total = $oic->count();

        $oic = $oic
        ->with(['getTeam','getAgent'])
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
        $data = getUserDetailClusterAndTeam(Auth::user());
        $user = Auth::user();

        if(base64_decode($user->role) == 'administrator')
        {
            $users = User::get();
            $user_teams = Teams::get();
        }
        else if(base64_decode($user->role) == 'user')
        {
            if(empty($data['_a']))
            {
                return back()->with([
                    'notif.style' => 'danger',
    				'notif.icon' => 'times-circle',
    				'notif.message' => 'Failed to access module!',
                ]);
            }
            // checkPosition(Auth::user())
            $user_teams = $data['_t'];
            $users = $data['_a'];
        }


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
        $oic = new Oic();
        $user = new User();

        //Get the value of cluster id within the team
        $teams = new Teams();
        $clusters = $teams->clusters($request['team_id']);
        // Get cluster id
        // this foreach will get the cluster id of the login user
        foreach($clusters as $cluster){
            $cluster_id = $cluster['id'];
        }
        $validate = Validator::make($request->all(),[
            'team_id' => 'required',
            'user_id' => 'required',
        ]);

        $oic_data = [
            'cluster_id' => (int) $cluster_id,
            'team_id' => (int) $request['team_id'],
            'user_id' => $request['user_id'],
            'assign_date' => Carbon::parse($request->assign_date)->toDateString(),
            'insert_by' => Auth::user()->id,
            'created_at' => now(),
            'expired_at' => Carbon::parse($request->assign_date)->addHours('24')->toDateString()
        ];

        if ($validate->fails()) return back()->withErrors($validate->errors());

        // This will check if the user assigns an agent on the same date
        if($oic->where('assign_date', $oic_data['assign_date'])->first()){
            if($oic->where('team_id',$oic_data['team_id'])->where('assign_date', $oic_data['assign_date'])->first()){
                if($oic->where('user_id',$oic_data['user_id'])->where('assign_date', $oic_data['assign_date'])->first()){
                    return back()->with([
                        'notif.style' => 'danger',
                        'notif.icon' => 'times-circle',
                        'notif.message' => 'Agent is already assign!',
                    ]);
                } else {
                    return back()->with([
                        'notif.style' => 'danger',
                        'notif.icon' => 'times-circle',
                        'notif.message' => 'You already assign an agent on this date ' .$oic_data['assign_date'].'!',
                    ]);
                }
            }
        }

        if($oic->insert($oic_data)) {
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
        $oic = new Oic();

        $oic = $oic->where('id', $id)
        ->with(['getCluster','getTeam','getAgent'])
        ->firstOrFail();

        return view('app.oic.show',['oic' => $oic]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = getUserDetailClusterAndTeam(Auth::user());
        $user = Auth::user();

        if(base64_decode($user->role) == 'administrator')
        {
            $users = User::get();
            $user_teams = Teams::get();
        }
        else if(base64_decode($user->role) == 'user')
        {
            if(empty($data['_a']))
            {
                return back()->with([
                    'notif.style' => 'danger',
    				'notif.icon' => 'times-circle',
    				'notif.message' => 'Failed to access module!',
                ]);
            }
            // checkPosition(Auth::user())
            $user_teams = $data['_t'];
            $users = $data['_a'];
        }

        $oic = new Oic();

        $oic = $oic->where('id', $id)
        ->with(['getCluster','getTeam','getAgent'])
        ->firstOrFail();

        return view('app.oic.edit',[
            'oic' => $oic,
            'users' => $users,
            'teams' => $user_teams
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
        $oic = new Oic();
        $oic = $oic->where('id',$id)->first();
        $teams = new Teams();
        $clusters = $teams->clusters($request->team_id);

        foreach($clusters as $cluster){
            $cluster_id = $cluster['id'];
        }
        $validate = Validator::make($request->all(),[
            'team_id' => 'required',
            'user_id' => 'required',
            'assign_date' => 'required'
        ]);

        $oic_data = [
            'cluster_id' => (int) $cluster_id,
            'team_id' => (int) $request['team_id'],
            'user_id' => $request['user_id'],
            'assign_date' => Carbon::parse($request->assign_date)->toDateString(),
            'insert_by' => Auth::user()->id,
            'created_at' => now(),
            'expired_at' => Carbon::parse($request->assign_date)->addHours('24')->toDateString()
        ];

        if ($validate->fails()) return back()->withErrors($validate->errors());

        if($oic->update($oic_data)) {
            return back()->with([
                'notif.style' => 'success',
    			'notif.icon' => 'plus-circle',
    			'notif.message' => 'Updated successfully!',
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

    }
}
