<?php
use App\Teams;
use App\User;
use App\Clusters;
use App\Attendance;
use Carbon\Carbon;

/**
* Check Position
*
*
* This function will identify your Position
* whether user is TL, CL or agent
*
* PS: User can have a multiple position
*
* Requirements: User Model and a role with 'user'
*
* @return *tml, crl, agt or undefined
*
*/

function checkPosition ($user) {

	// check first if this $user
	// is has a role of 'user'
	if (base64_decode($user->role) != 'user') return 'undefined';

	// get the cluster and team (if any)
	$r = getMyClusterAndTeam($user);

	// hold the multiple position var
	$pos = [];

	// just count them
	// and return the appropriate response
	if (count($r['_a'])) array_push($pos, 'agnt'); // Agent
	if (count($r['_t'])) array_push($pos, 'tl'); // Team leader
	if (count($r['_c'])) array_push($pos, 'cl'); // Cluster leader

	return $pos;
}


/*
* GET CLUSTER AND TEAM (_C, _T)
*
* The idea of this is to get your
* Cluster id(s) and Team id(s)
* whether you are a cluster leader,
* a team leader or either you are an agent.
*
* Requirements: to get your cluster and team IDs
* this method needed your User model or Auth Model
* but first you must be authenticated to be in this
* method.
*
*/

function getMyClusterAndTeam ($auth)
{
	/**
	* Search IF IT HAS Teams.tl_ids
	*/
	$r['_t'] = fetchTeams($auth);

	/**
	* Search IF IT HAS Cluster.cl_ids
	*
	*/
	$r['_c'] = fetchCluster($auth);

	/**
	* Search IF IT HAS Teams.agent_ids
	*
	*/

	$r['_a'] = fetchAgent($auth);

	return $r;
}


/**
* Search your User
* to Cluster Table
*
* Requirements: User or Auth Model
*/
function fetchCluster ($auth)
{
	// init
	$cluster_ids = [];
	$cluster_model = new Clusters();

	// select tl_ids and cluster_id
	$clusters = $cluster_model->get(['cl_ids', 'id'])->toArray();

	// Loop thru clusters
	foreach ($clusters as $cluster) {
		// check first if agent_ids
		// not null
		if (!empty($cluster['cl_ids'])) {
			// check if you are one of
			// the cluster leader of this tems
			if (in_array($auth->id, $cluster['cl_ids'])) {

				// save the cluster id to this variable
				$cluster_ids[] = $cluster['id'];
			}
		}
	}

	return $cluster_model->whereIn('id', $cluster_ids)->get()->toArray();
}


/**
* Search your User
* to Teams Table
*
* Requirements: User or Auth Model
*/
function fetchTeams ($auth)
{
	// init
	$team_ids = [];
	$team_model = new Teams();

	// select tl_ids and team_id
	$teams = $team_model->get(['tl_ids', 'id'])->toArray();

	// Loop thru teams
	foreach ($teams as $team) {
		// check first if agent_ids
		// not null
		if (!empty($team['tl_ids'])) {
			// check if you are one of
			// the team leader of this tems
			if (in_array($auth->id, $team['tl_ids'])) {

				// save the team id to this variable
				$team_ids[] = $team['id'];
			}
		}
	}

	return $team_model->whereIn('id', $team_ids)->get()->toArray();
}

/**
* Search your User
* to Teams Table
*
* This method is only for checking
* if you are agent
*
* Requirements: User or Auth Model
*/
function fetchAgent ($auth)
{
	// init
	$team_ids = [];
	$team_model = new Teams();

	// select tl_ids and team_id
	$teams = $team_model->get(['agent_ids', 'id'])->toArray();

	// Loop thru teams
	foreach ($teams as $team) {
		// check first if agent_ids
		// not null
		if (!empty($team['agent_ids'])) {
			// check if you are one of
			// the team leader of this tems
			if (in_array($auth->id, $team['agent_ids'])) {

				// save the team id to this variable
				$team_ids[] = $team['id'];
			}
		}
	}

	return $team_model->whereIn('id', $team_ids)->get()->toArray();
}




/**
 * Check Position
 *
 *
 * This function will identify your Position
 * whether user is TL, CL or agent
 *
 * PS: User can have a multiple position
 *
 * Requirements: User Model and a role with 'user'
 *
 * @return *tml, crl, agt or undefined
 *
 */

 function checkPosition ($user, $can_access = [], $diff = false) {

 	// check first if this $user
 	// is has a role of 'user'
 	if (base64_decode($user->role) != 'user') return 'undefined';

 	// get the cluster and team (if any)
 	$r = getMyClusterAndTeam($user);

 	// hold the multiple position var
 	$pos = [];

 	// just count them
 	// and return the appropriate response
 	if (count($r['_a'])) array_push($pos, 'agent'); // Agent
 	if (count($r['_t'])) array_push($pos, 'tl'); // Team leader
 	if (count($r['_c'])) array_push($pos, 'cl'); // Cluster leader

 	if ($diff) {

 		// use array diff
 		$arr_diff = array_intersect($pos, $can_access);
 		return $arr_diff;
 	}

 	else return $pos;

 }



function c_array_flatten($array) {
	if (!is_array($array)) {
		return FALSE;
	}
	$result = array();
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			$result = array_merge($result, array_flatten($value));
		}
		else {
			$result[$key] = $value;
		}
	}
	return $result;
}


function comma_separated_to_array($string, $separator = ',')
{
	//Explode on comma
	$vals = explode($separator, $string);

	//Trim whitespace
	foreach($vals as $key => $val) {
		$vals[$key] = trim($val);
	}
	//Return empty array if no items found
	return array_diff($vals, array(""));
}

// Access Control: were you can give an array of roles that can access
function accesesControlMiddleware (...$can_access) {

	// $can_access is a array of roles
	if (!in_array(base64_decode(Auth::user()->role), $can_access)) {
		return back();
	}
}

// Access Control: were you can give an array of roles that can access specific div
function accesesControl ($can_access) {

	// $can_access is a array of roles
	return in_array(base64_decode(Auth::user()->role), $can_access);
}

// Filtered By Helper
function filteredBy($request) {
	$string = "";

	// Sorting
	if (!empty($request->get('sort_in')) && !empty($request->get('sort_by'))) {
		$string = "Sort in: <b>". ucwords(str_replace('-', ' ', $request->get('sort_in')))
		. "</b>, Sort by: <b>" . ucwords($request->get('sort_by')) . "</b> <br>";
	}

	// Show
	if (!empty($request->get('show'))) {
		$string .= "Show: <b>". $request->get('show') . "</b> rows <br>";
	}

	// Search
	if (!empty($request->get('search_string'))) {
		$string .= "Search string: <b>". $request->get('search_string') . "</b> <br>";
	}

	return $string;
}

function array_random_assoc ($arr, $num = 1) {
	$keys = array_keys($arr);
	shuffle($keys);

	$r = array();
	for ($i = 0; $i < $num; $i++) {
		$r[$keys[$i]] = $arr[$keys[$i]];
	}
	return $r;
}

function array_remove_null ($item) {
	if (!is_array($item)) {
		return $item;
	}

	return collect($item)
	->reject(function ($item) {
		return is_null($item);
	})
	->flatMap(function ($item, $key) {

		return is_numeric($key)
		? [array_remove_null($item)]
		: [$key => array_remove_null($item)];
	})
	->toArray();
}


/*
* [ Search ID to TEAMS Table ]
* [ tl, agent_code, encoder_ids ]
*
*/

function searchTeamAndCluster ($auth) {
	$teams_model = new \App\Teams();
	$clusters_model = new \App\Clusters();
	$_t_data = [];
	$_c_data = [];
	$_t_data2 = [];
	$_c_data2 = [];

	// **************************
	// TEAMS TABLE SEARCH
	// **************************

	// Search your ID to TL
	$get_teams = $teams_model->where('tl_id', $auth->id)->get();

	if (count($get_teams) == 0) {

		// If your not TL
		// Search your Agent to Agent Code

		// Filter all non null agent code
		if ($auth->role == base64_encode("agent") || $auth->role == base64_encode("agent_referral")) {
			$get_teams = $teams_model->where('agent_code', $auth->id)->get();
		}
		else {
			$get_teams = [];
		} // Agent Code Search

		// ***** Since encoder can access all application instead of their "teams"
		// 		 	We will remove this code (under)

		// // Then check your agent code to them
		// // $get_teams = $get_teams->get();
		//
		// if (count($get_teams) == 0) {
		//
		//     // If your ID not in Agent Code
		//     // Check for Encoders IDs
		//     if (count($get_teams) == 0) {
		//         $get_teams = $teams_model->get()->map(function ($r) use ($auth, $teams_model) {
		//             // search your id in encoders ids (array)
		//             if (in_array($auth->id, json_decode($r['encoder_ids']))) return $r;
		//
		//         });
		//
		//         // Filter all null if it has
		//         $get_teams  = array_filter($get_teams->toArray());
		//
		//     } // Encoder Search
		//
		// }

	} // TL search



	// This user EXIST in Teams Table
	if (count($get_teams) != 0) {

		// GET CLUSTER OF THIS TEAMS
		$cluster = collect($get_teams)->map(function ($r) use ($auth, $teams_model) {
			$r['cluster'] = $teams_model->clusters($r['team_id']);
			return $r['cluster'];
		});

		// Save to Session
		$_t_data = collect($get_teams)->map(function ($r) {return $r['team_id']; })->toArray();
		$_c_data = collect(array_values($cluster->toArray())[0])->map(function ($r) {return $r['cluster_id']; })->toArray();
		return [
			'_c' => array_values($_c_data),
			'_t' => array_values($_t_data)
		];
	}

	// NOT EXIST! in Teams Table
	// Lastly, check the Clusters Table
	else {


		// **************************
		// CLUSTERS TABLE SEARCH
		// **************************


		$cluster = $clusters_model->where('cl_id', $auth->id)->get();

		if (count($cluster) != 0) {

			// Save to Session
			$_t_data2 = $cluster->map(function ($r) use ($auth, $clusters_model) {$r['team_ids'] = json_decode($r['team_ids']); return $clusters_model->teams($r['team_ids']); })->toArray();
			$_c_data2 = collect($cluster)->map(function ($r) {return $r['cluster_id']; })->toArray();

			return [
				'_t' => array_merge(...$_t_data2),
				'_c' => array_values($_c_data2)
			];

		}
		else {
			return [
				'_c' => [],
				'_t' => []
			];
		}
	}

}

// FOR DASHBOARD GET CLSUTER WITH TEAMS AND AGENTS
function getHeirarchy(){

	$teams_model = new Teams();
	$clusters_model = new Clusters();
	$user_model = new User();
	$attendance_model = new Attendance();

	// ROLES WHICH WILL BE CHANGE IF THERE IS CHANGE ON ROLE
	$roles = [
		'administrator' => 'administrator',
		'user' => 'user',
		'encoder' => 'encoder',
	];

	// FOR ADMIN
	if(base64_decode(Auth()->user()->role) == $roles['administrator']){
		if( empty((Session::get('_c'))) && empty((Session::get('_t'))) && empty((Session::get('_a'))) ){
			$cluster_query = $clusters_model->get();
			if(!empty($cluster_query->toArray())){
				$clusters = $cluster_query->pluck('cluster_name');
				$count = [
					'present' => 0,
					'absent' => 0,
					'unkown' => 0,
				];
				$teams = $teams_model->whereIn('id',$cluster_query[0]['team_ids'])->get()->map(function($res) use ($user_model, $attendance_model,&$count){
					$res['total_agents'] = count($res['agent_ids']);
					// $res['agents'] = $user_model->whereIn('id',collect(Session::get('_t'))->pluck('id'))->get();
					// calculate present, absent, unkown
					$agents = $user_model->whereIn('id',$res['agent_ids'])->get();
					$res['attendance'] = $agents->map(function($res) use ($attendance_model,&$count){
						if( count($attendance_model->where(['user_id' => $res['id'], 'status' => 1])->where('created_at', '>=', Carbon::today())->get()) > 0){
							++$count['present'];
						}else if( count($attendance_model->where(['user_id' => $res['id'], 'status' => 0])->where('created_at', '>=', Carbon::today())->get()) > 1){
							++$count['absent'];
						}else{
							++$count['unkown'];
						}
						return [
							'present' => $count['present'],
							'absent' => $count['absent'],
							'unkown' => $count['unkown'],
						];
					});
					$res['attendance'] = $res['attendance']->values()->last();
					// end of calculate present, absent, unkown
					return $res;
				});
				// dd($teams);
			}
		}

		// FOR USER ROLE
	}else if(base64_decode(Auth()->user()->role) == $roles['user']){

		// FOR CLUSTER HEAD
		if( !empty((Session::get('_c'))) ){
			$clusters = collect(Session::get('_c'))->pluck('cluster_name');
			$count = [
				'present' => 0,
				'absent' => 0,
				'unkown' => 0,
			];
			$teams = $teams_model->whereIn('id',Session::get('_c')[0]['team_ids'])->get()->map(function($res) use ($user_model,$attendance_model,&$count){
				$res['total_agents'] = count($res['agent_ids']);
				// $res['agents'] = $user_model->whereIn('id',collect(Session::get('_c'))->pluck('id'))->get();
				// calculate present, absent, unkown
				$agents = $user_model->whereIn('id',collect(Session::get('_t'))->pluck('agent_ids')[0])->get();
				$res['attendance'] = $agents->map(function($res) use ($attendance_model,&$count){
					if( count($attendance_model->where(['user_id' => $res['id'], 'status' => 1])->where('created_at', '>=', Carbon::today())->get()) > 0){
						++$count['present'];
					}else if( count($attendance_model->where(['user_id' => $res['id'], 'status' => 0])->where('created_at', '>=', Carbon::today())->get()) > 1){
						++$count['absent'];
					}else{
						++$count['unkown'];
					}
					return [
						'present' => $count['present'],
						'absent' => $count['absent'],
						'unkown' => $count['unkown'],
					];
				});
				$res['attendance'] = $res['attendance']->values()->last();
				// end of calculate present, absent, unkown
				return $res;
			});
		}
		// FOR TEAM LEAD
		else if( !empty((Session::get('_t'))) ){
			$clusters = [null];
			$count = [
				'present' => 0,
				'absent' => 0,
				'unkown' => 0,
			];
			// $count = 0;
			$teams = $teams_model->whereIn('id',collect(Session::get('_t'))->pluck('id'))->get()->map(function($res) use ($user_model, $attendance_model,&$count){
				$res['total_agents'] = count($res['agent_ids']);
				// calculate present, absent, unkown
				$agents = $user_model->whereIn('id',collect(Session::get('_t'))->pluck('agent_ids')[0])->get();
				$res['attendance'] = $agents->map(function($res) use ($attendance_model,&$count){
					if( count($attendance_model->where(['user_id' => $res['id'], 'status' => 1])->where('created_at', '>=', Carbon::today())->get()) > 0){
						++$count['present'];
					}else if( count($attendance_model->where(['user_id' => $res['id'], 'status' => 0])->where('created_at', '>=', Carbon::today())->get()) > 1){
						++$count['absent'];
					}else{
						++$count['unkown'];
					}
					return [
						'present' => $count['present'],
						'absent' => $count['absent'],
						'unkown' => $count['unkown'],
					];
				});
				$res['attendance'] = $res['attendance']->values()->last();
				// end of calculate present, absent, unkown
				return $res;
			});
		}
		else if( !empty((Session::get('_a'))) ){
			$clusters = [null];
			$count = [
				'present' => 0,
				'absent' => 0,
				'unkown' => 0,
			];
			$teams = $teams_model->whereIn('id',collect(Session::get('_a'))->pluck('id'))->get()->map(function($res) use ($user_model,$attendance_model,&$count){
				$res['total_agents'] = count($res['agent_ids']);
				// $res['agents'] = $user_model->whereIn('id',collect(Session::get('_a'))->pluck('id'))->get();
				// calculate present, absent, unkown
				$agents = $user_model->whereIn('id',collect(Session::get('_t'))->pluck('agent_ids')[0])->get();
				$res['attendance'] = $agents->map(function($res) use ($attendance_model,&$count){
					if( count($attendance_model->where(['user_id' => $res['id'], 'status' => 1])->where('created_at', '>=', Carbon::today())->get()) > 0){
						++$count['present'];
					}else if( count($attendance_model->where(['user_id' => $res['id'], 'status' => 0])->where('created_at', '>=', Carbon::today())->get()) > 1){
						++$count['absent'];
					}else{
						++$count['unkown'];
					}
					return [
						'present' => $count['present'],
						'absent' => $count['absent'],
						'unkown' => $count['unkown'],
					];
				});
				$res['attendance'] = $res['attendance']->values()->last();
				// end of calculate present, absent, unkown
				return $res;
			});
		}

		// FOR ENCODER ROLE
	}else if(base64_decode(Auth()->user()->role) == $roles['encoder']){

		if( empty((Session::get('_c'))) && empty((Session::get('_t'))) && empty((Session::get('_a'))) ){
			$cluster_query = $clusters_model->get();
			if(!empty($cluster_query->toArray())){
				$clusters = $cluster_query->pluck('cluster_name');
				$teams = $teams_model->whereIn('id',$cluster_query[0]['team_ids'])->get()->map(function($res) use ($user_model){
					$res['total_agents'] = count($res['agent_ids']);
					$res['agents'] = $user_model->whereIn('id',collect(Session::get('_t'))->pluck('id'))->get();
					return $res;
				});
			}
		}

	}

	// RETURN DATA BACK TO LARAVEL VIEW
	return [
		'clusters' => (!empty($clusters)) ? $clusters : [],
		'teams' => (!empty($teams)) ? $teams : [],
	];


}

function getHeirarchy2(){

	$teams_model = new Teams();
	$clusters_model = new Clusters();
	$user_model = new User();
	$attendance_model = new Attendance();

	// ROLES WHICH WILL BE CHANGE IF THERE IS CHANGE ON ROLE
	$roles = [
		'administrator' => 'administrator',
		'user' => 'user',
		'encoder' => 'encoder',
	];

	// FOR ADMIN
	if(base64_decode(Auth()->user()->role) == $roles['administrator']){
		if( empty((Session::get('_c'))) && empty((Session::get('_t'))) && empty((Session::get('_a'))) ){
			$clusters = $clusters_model->get()->map(function($res) use ($teams_model, $user_model,$attendance_model){
				$res['teams'] = $teams_model->whereIn('id', $res['team_ids'])->get()->map(function($res) use ($teams_model, $user_model,$attendance_model){
					$agents = $user_model->whereIn('id', $res['agent_ids'])->get();
					$res['total_agents'] = count($agents);
					$res['agents'] = $agents;
					// calculate present, absent, unkown
					$count = [
						'present' => 0,
						'absent' => 0,
						'unkown' => 0,
					];
					$agents = $user_model->whereIn('id',$res['agent_ids'])->get();
					$res['attendance'] = $agents->map(function($res) use ($attendance_model,&$count){
						if( count($attendance_model->where(['user_id' => $res['id'], 'status' => 1])->where('created_at', '>=', Carbon::today())->get()) > 0){
							++$count['present'];
						}else if( count($attendance_model->where(['user_id' => $res['id'], 'status' => 0])->where('created_at', '>=', Carbon::today())->get()) > 1){
							++$count['absent'];
						}else{
							++$count['unkown'];
						}
						return [
							'present' => $count['present'],
							'absent' => $count['absent'],
							'unkown' => $count['unkown'],
						];
					});
					$res['attendance'] = $res['attendance']->values()->last();
					// end of calculate present, absent, unkown
					return $res;
				});
				return $res;
			});
			// dd($clusters[0]->teams[0]->total_agents);
		}

		// FOR USER ROLE
	}else if(base64_decode(Auth()->user()->role) == $roles['user']){

		// FOR CLUSTER HEAD
		if( !empty((Session::get('_c'))) ){
			$clusters = $clusters_model->whereIn('id',collect(Session::get('_c'))->pluck('id'))->get()->map(function($res) use ($teams_model,$user_model,$attendance_model){
				$res['teams'] = $teams_model->whereIn('id',Session::get('_c')[0]['team_ids'])->get()->map(function($res) use ($teams_model,$user_model,$attendance_model){
					$agents = $user_model->whereIn('id',$res['agent_ids'])->get();
					$res['total_agents'] = count($agents);
					$res['agents'] = $agents;
					// calculate present, absent, unkown
					$count = [
						'present' => 0,
						'absent' => 0,
						'unkown' => 0,
					];
					$agents = $user_model->whereIn('id',$res['agent_ids'])->get();
					$res['attendance'] = $agents->map(function($res) use ($attendance_model,&$count){
						if( count($attendance_model->where(['user_id' => $res['id'], 'status' => 1])->where('created_at', '>=', Carbon::today())->get()) > 0){
							++$count['present'];
						}else if( count($attendance_model->where(['user_id' => $res['id'], 'status' => 0])->where('created_at', '>=', Carbon::today())->get()) > 1){
							++$count['absent'];
						}else{
							++$count['unkown'];
						}
						return [
							'present' => $count['present'],
							'absent' => $count['absent'],
							'unkown' => $count['unkown'],
						];
					});
					$res['attendance'] = $res['attendance']->values()->last();
					// end of calculate present, absent, unkown
					return $res;
				});
				return $res;
			});
			// dd($clusters);
		}
		// FOR TEAM LEAD
		else if( !empty((Session::get('_t'))) ){
			// dd(collect(Session::get('_t'))->pluck('id'));
			$clusters = $clusters_model->get()->map(function($res) use ($teams_model,$user_model,$attendance_model){
				if(array_intersect(collect(Session::get('_t'))->pluck('id')->toArray(),$res['team_ids'])){
					// dd(Session::get('_t'));
					$team_ids = $res['team_ids'];
					$res['teams'] = $teams_model->whereIn('id', $res['team_ids'])->get()->map(function($res) use ($teams_model,$user_model,$attendance_model,$team_ids){
						if( in_array($res['id'],collect(Session::get('_t'))->pluck('id')->toArray()) ){
							$agents = $user_model->whereIn('id',$res['agent_ids'])->get();
							$res['total_agents'] = count($agents);
							$res['agents'] = $agents;
							// calculate present, absent, unkown
							$count = [
								'present' => 0,
								'absent' => 0,
								'unkown' => 0,
							];
							$agents = $user_model->whereIn('id',$res['agent_ids'])->get();
							$res['attendance'] = $agents->map(function($res) use ($attendance_model,&$count){
								if( count($attendance_model->where(['user_id' => $res['id'], 'status' => 1])->where('created_at', '>=', Carbon::today())->get()) > 0){
									++$count['present'];
								}else if( count($attendance_model->where(['user_id' => $res['id'], 'status' => 0])->where('created_at', '>=', Carbon::today())->get()) > 1){
									++$count['absent'];
								}else{
									++$count['unkown'];
								}
								return [
									'present' => $count['present'],
									'absent' => $count['absent'],
									'unkown' => $count['unkown'],
								];
							});
							$res['attendance'] = $res['attendance']->values()->last();
							// end of calculate present, absent, unkown
							return $res;
						}
					});
					return $res;
				}
			});
			// dd($clusters);
		}
		else if( !empty((Session::get('_a'))) ){

			$myattendance = $teams_model->whereIn('id',collect(Session::get('_a'))->pluck('id'))->get()->map(function($res) use ($user_model,$attendance_model){
				$agents = $user_model->where('id',Auth()->user()->id)->first();
				$res['agents'] = $agents;
				$res['total_agents'] = count($agents);				
				// dd($res['id']);
				$count = [
					'present' => 0,
					'absent' => 0,
					'unkown' => 0,
				];
				// please add now date
				$res['attendance'] = $attendance_model->where([ 'user_id' => Auth()->user()->id, 'team_id' => $res['id']])->where('created_at', '>=', Carbon::today())->get()->map(function($res){
					return $res;
				});

				return $res;
			});

		}

		// FOR ENCODER ROLE
	}else if(base64_decode(Auth()->user()->role) == $roles['encoder']){

	}

	// RETURN DATA BACK TO LARAVEL VIEW
	return [
		'clusters' => (!empty($clusters)) ? $clusters : [],
		'teams' => (!empty($teams)) ? $teams : [],
		'myattendance' => (!empty($myattendance)) ? $myattendance : [],
	];


}

?>
