<?php

namespace App\Http\Controllers;

use Schema;
use File;
use Excel;
use Validator;
use Carbon\Carbon;
use App\User;
use App\Teams;
use App\Clusters;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Model
        $users = new User();

        // Sorting
        // params: sort_in & sort_by
        if (!empty($request->get('sort_in') && !empty($request->get('sort_by')))) $users = $users->sort($request);

        // Search
        if (!empty($request->get('search_string'))) $users = $users->search($request->get('search_string'));

        // Count all before paginate
        $total = $users->count();

        // Count all users
        $total_users = User::count();

        // Insert pagination
        $users = $users->paginate((!empty($request->show) ? $request->show : 10));
        return view('app.users.index', [
            'users' => $users,
            'users_total' => $total_users,
            'total' => $total,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('app.users.create');
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
            'fname' => 'required|string',
            'lname' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'role' => 'required|string',
            'isActive' => 'required|integer',
            'password' => 'required|string|min:6',
        ]);

        if ($v->fails()) return back()->withErrors($v->errors())->withInput();

        $request['role'] = base64_encode($request['role']);
        $request['password'] = bcrypt($request['password']);
        if (User::insert($request->except('_token'))) {
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
        $user = User::findOrFail($id);

        $teams_model = new Teams();
        $clusters_model = new Clusters();

        return view('app.users.show', [
            'user' => User::findOrFail($id),
            'data' => getMyClusterAndTeam($user), // get cl, tl, agent if any
		]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('app.users.edit', ['user' => User::findOrFail($id)]);
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
        //return $request->all();
        $v = Validator::make($request->all(), [
            'fname' => 'required|string',
            'lname' => 'required|string',
            'email' => 'required|string|email|unique:users,email,'.$id,
            'role' => 'required|string',
            'isActive' => 'required|boolean',
        ]);

        if ($v->fails()) return back()->withErrors($v->errors());

        $request['role'] = base64_encode($request['role']);
        if (!empty($request['password'])) {
            $request['password'] = bcrypt($request['password']);
        }
        else {
            unset($request['password']);
        }

        if (User::where('id', $id)->update($request->except(['_token', '_method']))) {
            return back()->with([
                'notif.style' => 'success',
                'notif.icon' => 'plus-circle',
                'notif.message' => 'Update successful!',
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
        $user = User::findOrFail($id);
        $teams = new Teams();
        $clusters = new Clusters();


        // ** CHECK FOR CLUSTERS TABLE
        // if user is part of the cluster, login user will be prompt to remove the selected user in the cluster to be able to delete the selected user
        $clusters_search = $clusters->where('cluster_id', $id)->first();
        if (!empty($clusters_search)) {
            return redirect()->back()->with([
                'notif.style' => 'warning',
                'notif.icon' => 'warning',
                'notif.message' => 'This user is currently assigned to a cluster, you must remove this user to that cluster in order to delete',
            ]);
        }
        // ** CHECK FOR TEAMS TABLE
        // Search for CL, TL and Agent Code
        // $teams_search = $teams->orWhere('team_id', $id)->orWhere('agent_ids', $id)->count();

        // check if user is part of the team
        return $teams = $teams->get()->map(function ($response) use ($id){
            $response['agent_ids'] = (in_array($id, $response['agent_ids']) ? $id : 'false');
            return $response;
        });

        if ($teams_search == 0) {
            // Clean in CL, TL and Agent
            // Now, lets check the Encoders IDs
            // $teams_search = $teams->get(['encoder_ids'])->map(function ($r) use ($id) {
            //
            //     // json decode to make it array again
            //     $r['encoder_ids'] = json_decode($r['encoder_ids']);
            //
            //     // Check if id is ENCODER ids
            //     if (in_array($id, $r['encoder_ids'])) {
            //         return ['m' => $r, 'v' => false ];
            //     }
            //     else {
            //         return ['m' => $r, 'v' => true ];
            //     }
            // });

            // if theres valid false in collection
            // means it is in a team
            // Encoder specifically
            // if (in_array(false, $teams_search[0])) {
            //     return redirect()->back()->with([
            //         'notif.style' => 'warning',
            //         'notif.icon' => 'warning',
            //         'notif.message' => 'This user is currently assigned to a team, you must remove this user to that team in order to delete',
            //     ]);
            // }

            // Everything is good to go, you may delete this user
            if ($user->delete()) {
                return back()->with([
                    'notif.style' => 'success',
                    'notif.icon' => 'plus-circle',
                    'notif.message' => 'Delete successful!',
                ]);
            }
            else {
                return back()->with([
                    'notif.style' => 'danger',
                    'notif.icon' => 'times-circle',
                    'notif.message' => 'Failed to delete',
                ]);
            }
        }
        else {

            // This user exist in either CL, TL or Agent code
            return back()->with([
                'notif.style' => 'warning',
                'notif.icon' => 'warning',
                'notif.message' => 'This user is currently assigned to a team, you must remove this user to that team in order to delete',
            ]);
        }

    }

    public function importUsers(Request $request){
        // return $request->all();
        // dd($request->hasFile('file'));
        if($request->hasFile('file')) {
            $extension = File::extension($request->file->getClientOriginalName()); // GET FILE EXTENSION
            if($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
                $path = $request->file->getRealPath(); // GET THE REAL PATH OF THE FILE
                $data = Excel::load($path, function($reader){})->get(); // GET THE CONTENT INSIDE THE FILE (THIS IS WHERE THE MAGIC HAPPENS)
                $get_users = [];
                $get_teams_clusters = [];
                foreach($data as $index => $user){

                    // get firtname acronym
                    $tl_name = explode(',', trim($user->name));
                    $words = explode(" ", trim($tl_name[1]));
                    $acronym = "";
                    foreach ($words as $w) {
                        $acronym .= $w[0];
                    }

                    // INSERT USERS ON USERS TABLE
                    $get_users['fname'] = trim($tl_name[1]);
                    $get_users['lname'] = trim($tl_name[0]);
                    $get_users['email'] = strtolower($acronym . $tl_name[0]) . '@bizherd.com';
                    $get_users['password'] = bcrypt('Password123');
                    $get_users['role'] = base64_encode('user');
                    $get_users['created_at'] = Carbon::now();
                    $get_users['updated_at'] = Carbon::now();
                    // check if existing
                    if(empty(user::where('email', $get_users['email'])->first())){
                        $user_id = User::insertGetId($get_users);
                    }

                    // CREATE CLUSTERS IF USER IS CL
                    if($user->position == 'cl'){
                        // CHECK CLUSTER IF EXISTING
                        $check_cluster = Clusters::where('cluster_name', $user->title)->first();
                        if(empty($check_cluster)){
                            Clusters::create([
                                'cluster_name' => trim($user->title),
                                'cl_ids' => [(string)$user_id],
                            ]);
                        }
                    }

                    // // CREATE TEAMS IF USER IS TL
                    if($user->position == 'tl'){
                        Teams::create([
                            'team_name' => trim($user->name),
                            'tl_ids' => [(string)$user_id],
                        ]);

                        // CHECK TLS CLUSTER
                        // return $user->title;
                        $check_position = Clusters::where('cluster_name', 'like', '%' . $user->title . '%')->first();
                        if(!empty($check_position)){
                            if(empty($check_position->team_ids)){
                                $get_teams_clusters[] = (string)$user_id;
                            } else {
                                $get_teams_clusters = $check_position->team_ids;
                                $get_teams_clusters[] = (string)$user_id;
                            }
                            Clusters::where('cluster_name', 'like', '%' . $user->title . '%')->update(['team_ids' => json_encode($get_teams_clusters)]);
                        } else {
                        }
                    }

                }
                    return $get_teams_clusters;
                // return $get_users;
            }
        }
    }
}
