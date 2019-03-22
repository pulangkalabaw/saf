<?php
use App\Teams;
use App\User;
use App\Clusters;
use App\Attendance;
use App\Application;
use Carbon\Carbon;

// check if login user has agents for cl and tl
function checkUserAgents($auth) {
	if(in_array('cl', checkPosition($auth))){
		$teams = new Teams();
		$agents = new User();

		$team_ids = [];
		$agent_ids = [];

		$clusters_model = fetchCluster($auth);
		// get teams inside the cluster
		foreach($clusters_model as $cluster){
			// array unique removes duplicate values
			if (!empty($cluster['team_ids'])) {
				$team_ids = array_unique(array_merge($team_ids, $cluster['team_ids']));
			}
		}

		$teams = $teams->whereIn('id', $team_ids)->get()->toArray();

		// get agents inside the team
		foreach($teams as $team){
			// array unique removes duplicate values
			if (!empty($team['agent_ids'])) {
				$agent_ids = array_unique(array_merge($agent_ids, $team['agent_ids']));
			}
		}
		$agents = $agents->whereIn('id', $agent_ids)->get()->toArray();

		return $r = $agents;
	}

	// if the login user is tl get all the available agents under that tl
	if(in_array('tl', checkPosition($auth))){
		$cluster_model = new Clusters();
		$agents = new User();

		$agent_ids = [];
		$team_arr = [];
		$cluster_ids = [];

		$teams = fetchTeams($auth);
		// get agents inside the team
		foreach($teams as $team){
			// array unique removes duplicate values
			// $team_ids[] = $team['id'];
			if (!empty($team['agent_ids'])) {
				$agent_ids = array_unique(array_merge($agent_ids, $team['agent_ids']));
			}
		}

		$agents = $agents->whereIn('id', $agent_ids)->get()->toArray();

		return $r = $agents;
	}
}

function productNameConvert ($pname) {
	return ucwords(str_replace('_', ' ', $pname));
}


/*
*
*
*
* Get all the available teams and agents of the login in user
*
*
*/
function getUserDetailClusterAndTeam($auth) {

	if(in_array('cl', checkPosition($auth))){
		$teams = new Teams();
		$agents = new User();

		$team_ids = [];
		$agent_ids = [];

		$clusters_model = fetchCluster($auth);
		// get teams inside the cluster
		foreach($clusters_model as $cluster){
			// array unique removes duplicate values
			if (!empty($cluster['team_ids'])) {
				$team_ids = array_unique(array_merge($team_ids, $cluster['team_ids']));
			}
		}

		$teams = $teams->whereIn('id', $team_ids)->get()->toArray();

		// get agents inside the team
		foreach($teams as $team){
			// array unique removes duplicate values
			if (!empty($team['agent_ids'])) {
				$agent_ids = array_unique(array_merge($agent_ids, $team['agent_ids']));
			}
		}
		$agents = $agents->whereIn('id', $agent_ids)->get()->toArray();

		$r['_c'] = $clusters_model;
		$r['_t'] = $teams;
		$r['_a'] = $agents;
		return $r;
	}

	// if the login user is tl get all the available agents under that tl
	if(in_array('tl', checkPosition($auth))){
		$cluster_model = new Clusters();
		$agents = new User();

		$agent_ids = [];
		$team_arr = [];
		$cluster_ids = [];

		$teams = fetchTeams($auth);
		// get agents inside the team
		foreach($teams as $team){
			// array unique removes duplicate values
			// $team_ids[] = $team['id'];
			if (!empty($team['agent_ids'])) {
				$agent_ids = array_unique(array_merge($agent_ids, $team['agent_ids']));
			}
		}

		$clusters = $cluster_model->get(['team_ids','id'])->toArray();

		// foreach($clusters as $cluster){
		// 	dump(in_array($teams, $cluster['team_ids']));
		// }
		//
		// dd('end');
		$agents = $agents->whereIn('id', $agent_ids)->get()->toArray();

		$r['_c'] = null;
		$r['_t'] = $teams;
		$r['_a'] = $agents;
		return $r;
	}
}

/**
* Update Sessions
*
*/

function getSessions () {
	$auth = Auth::user();

	if (!empty($auth)) {

		$_data = getMyClusterAndTeam($auth);
		Session::put('_t', $_data['_t']);
		Session::put('_c', $_data['_c']);
		Session::put('_a', $_data['_a']);
	}

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
	if (base64_decode($user->role) == 'encoder') return [];


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

		// will return the array1 that has the array2
		//
		$arr_diff = array_intersect($pos, $can_access);
		return $arr_diff;
	}

	// will return the reponse
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
function accessControlMiddleware (...$can_access) {

	// $can_access is a array of roles
	if (!in_array(base64_decode(Auth::user()->role), $can_access)) {
		return back();
	}
}

// Access Control: were you can give an array of roles that can access specific div
function accessControl ($can_access) {

	// $can_access is a array of roles
	return in_array(base64_decode(Auth::user()->role), $can_access);
}

// Filtered By Helper
function filteredBy($request) {
	$string = "";

	// Sorting
	if (!empty($request->get('sort_in')) && !empty($request->get('sort_by'))) {

		// Special cases
		$sort_in = ucwords(str_replace('-', ' ', $request->get('sort_in')));
		$sort_in = ucwords(str_replace('_', ' ', $sort_in));
		$sort_in = ucwords(str_replace('Id', 'code', $sort_in));

		$string = "Sort in: <b>". $sort_in
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

// Function for getting dashbaord reports

function getHeirarchy2($date = null,$dateto = null){
	// $date = '28-03-2018';
	$isSearching = 1;
	if($date != null && $dateto != null){
		$isSearching = 1;
	}
	$date = ($date !== null) ? Carbon::parse($date) : Carbon::now()->startOfMonth();
	$dateto = ($dateto !== null) ? Carbon::parse($dateto)->endOfDay() : Carbon::now()->endOfMonth();
	// dd($dateto);

	$teams_model = new Teams();
	$clusters_model = new Clusters();
	$user_model = new User();
	$attendance_model = new Attendance();
	$application_model = new Application();

	// ROLES WHICH WILL BE CHANGE IF THERE IS CHANGE ON ROLE
	$roles = [
		'administrator' => 'administrator',
		'user' => 'user',
		'encoder' => 'encoder',
	];

	// FOR ADMIN
	if(base64_decode(Auth()->user()->role) == $roles['administrator']){
		$clusters = $clusters_model->get()->map(function($res) use ($teams_model, $user_model,$attendance_model,$application_model,$date,$dateto,$isSearching){
			$count_applications = [
				'new' => 0,
				'activated' => 0,
				'paid' => 0,
				'target' => 0,
			];
			$res['date'] = ($dateto == null) ? ($date->format('F d Y')) : ($date->format('F d Y').' - '.$dateto->format('F d Y'));
			$res['teams'] = $teams_model->whereIn('id', collect($res['team_ids'])->toArray())->get()->map(function($res) use ($teams_model, $user_model,$attendance_model,$application_model,&$count_applications,$date,$dateto,$isSearching){

				// calcualting applications and saf

				if($isSearching == 1){
					$res['getallsafthiscutoff'] = $application_model->where('team_id', $res['id'])->whereBetween('created_at', [$date,$dateto])->get()->map(function($res) use ($teams_model, $user_model,$attendance_model,&$count_applications,$date){
						if($res['status'] == 'new'){
							(float)$count_applications['new'] += (float)$res['msf'];
						}else if($res['status'] == 'activated'){
							$count_applications['activated'] += $res['msf'];
						}else if($res['status'] == 'paid'){
							$count_applications['paid'] += $res['msf'];
						}
						$count_applications['target'] += (float)$res['msf'];
						return [
							'new' => $count_applications['new'],
							'activated' => $count_applications['activated'],
							'paid' => $count_applications['paid'],
							'target' => $count_applications['target'],
						];
					});
				}else{
					$res['getallsafthiscutoff'] = $application_model->where('team_id', $res['id'])->whereDate('created_at', $date)->get()->map(function($res) use ($teams_model, $user_model,$attendance_model,&$count_applications,$date){
						if($res['status'] == 'new'){
							(float)$count_applications['new'] += (float)$res['msf'];
						}else if($res['status'] == 'activated'){
							$count_applications['activated'] += $res['msf'];
						}else if($res['status'] == 'paid'){
							$count_applications['paid'] += $res['msf'];
						}
						$count_applications['target'] += (float)$res['msf'];
						return [
							'new' => $count_applications['new'],
							'activated' => $count_applications['activated'],
							'paid' => $count_applications['paid'],
							'target' => $count_applications['target'],
						];
					});
				}

				$res['getallsafthiscutoff'] = $res['getallsafthiscutoff']->values()->last();
				// end of calculating applications and saf

				$agents = $user_model->whereIn('id', collect($res['agent_ids'])->toArray())->get();
				$res['total_agents'] = count($agents);
				$res['agents'] = $agents;
				// calculate present, absent, unkown
				$count = [
					'present' => 0,
					'absent' => 0,
					'unkown' => 0,
					'totaltarget' => 0, // ADD THIS
				];
				$agents = $user_model->whereIn('id',collect($res['agent_ids'])->toArray())->get();
				$res['attendance'] = $agents->map(function($res) use ($attendance_model,&$count,$date){
					if( count($attendance_model->where(['user_id' => $res['id'], 'status' => 1])->where('created_at', '>=', $date)->get()) > 0){
						++$count['present'];
					}else if( count($attendance_model->where(['user_id' => $res['id'], 'status' => 0])->where('created_at', '>=', $date)->get()) >= 1){
						++$count['absent'];
					}else{
						++$count['unkown'];
					}
					$count['totaltarget'] += (float)$res['target']; // ADD THIS
					return [
						'present' => $count['present'],
						'absent' => $count['absent'],
						'unkown' => $count['unkown'],
						'totaltarget' => $count['totaltarget'], // ADD THIS
					];
				});
				$res['attendance'] = $res['attendance']->values()->last();
				// end of calculate present, absent, unkown

				// calculate/get attendance of tl on this day
				$res['tlattendance'] = count($attendance_model->whereIn('user_id',collect($res['tl_ids'])->toArray())->where('created_at', '>=', $date)->get());
				$res['totaltl'] = count(collect($res['tl_ids'])->toArray());
				// end of calculate/get attendance of tl on this day

				// for percentage of this cutoff
				// $res['pat'] = (int)round(($res['getallsafthiscutoff']['target']/($res['attendance']['totaltarget'] !== 0) ? $res['attendance']['totaltarget'] : 0) * 100); // ADD THIS
				$res['total_target'] = $res['getallsafthiscutoff']['target']; // total current selled
        		$res['total_based_target'] = $res['attendance']['totaltarget'] != 0 ? $res['attendance']['totaltarget'] : 0; // based_target
        		if ($res['total_based_target'] == 0) {
			  		$res['pat'] = 0;
				}
				else {
					$pat = ($res['total_target'] / $res['total_based_target']) * 100;
					$res['pat'] = $pat;
				}

				return $res;

			});
			return $res;
		});
		// dd($clusters[0]->teams[0]->total_agents);

		// FOR USER ROLE
	}else if(base64_decode(Auth()->user()->role) == $roles['user']){

		// FOR CLUSTER HEAD
		if( !empty((Session::get('_c'))) ){
			$clusters = $clusters_model->whereIn('id',collect(Session::get('_c'))->pluck('id'))->get()->map(function($res) use ($teams_model,$user_model,$application_model,$attendance_model,$date,$dateto,$isSearching){
				$count_applications = [
					'new' => 0,
					'activated' => 0,
					'paid' => 0,
					'target' => 0,
				];
				$res['date'] = ($dateto == null) ? ($date->format('F d Y')) : ($date->format('F d Y').' - '.$dateto->format('F d Y'));
				$res['teams'] = $teams_model->whereIn('id',collect(Session::get('_c')[0]['team_ids'])->toArray())->get()->map(function($res) use ($teams_model,$user_model,$application_model,$attendance_model,&$count_applications,$date,$dateto,$isSearching){

					// calcualting applications and saf
					if($isSearching == 1){
						$res['getallsafthiscutoff'] = $application_model->where('team_id', $res['id'])->whereBetween('created_at',[$date,$dateto])->get()->map(function($res) use ($teams_model, $user_model,$attendance_model,&$count_applications,$date){
							if($res['status'] == 'new'){
								(float)$count_applications['new'] += (float)$res['msf'];
							}else if($res['status'] == 'activated'){
								$count_applications['activated'] += $res['msf'];
							}else if($res['status'] == 'paid'){
								$count_applications['paid'] += $res['msf'];
							}
							$count_applications['target'] += (float)$res['msf'];
							return [
								'new' => $count_applications['new'],
								'activated' => $count_applications['activated'],
								'paid' => $count_applications['paid'],
								'target' => $count_applications['target'],
							];
						});
					}else{
						$res['getallsafthiscutoff'] = $application_model->where('team_id', $res['id'])->whereDate('created_at',$date)->get()->map(function($res) use ($teams_model, $user_model,$attendance_model,&$count_applications,$date){
							if($res['status'] == 'new'){
								(float)$count_applications['new'] += (float)$res['msf'];
							}else if($res['status'] == 'activated'){
								$count_applications['activated'] += $res['msf'];
							}else if($res['status'] == 'paid'){
								$count_applications['paid'] += $res['msf'];
							}
							$count_applications['target'] += (float)$res['msf'];
							return [
								'new' => $count_applications['new'],
								'activated' => $count_applications['activated'],
								'paid' => $count_applications['paid'],
								'target' => $count_applications['target'],
							];
						});
					}

					$res['getallsafthiscutoff'] = $res['getallsafthiscutoff']->values()->last();
					// end of calculating applications and saf

					$agents = $user_model->whereIn('id',collect($res['agent_ids'])->toArray())->get();
					$res['total_agents'] = count($agents);
					$res['agents'] = $agents;
					// calculate present, absent, unkown
					$count = [
						'present' => 0,
						'absent' => 0,
						'unkown' => 0,
						'totaltarget' => 0, // ADD THIS
					];
					$agents = $user_model->whereIn('id',collect($res['agent_ids'])->toArray())->get();
					$res['attendance'] = $agents->map(function($res) use ($attendance_model,&$count,$date){
						if( count($attendance_model->where(['user_id' => $res['id'], 'status' => 1])->where('created_at', '>=', $date)->get()) > 0){
							++$count['present'];
						}else if( count($attendance_model->where(['user_id' => $res['id'], 'status' => 0])->where('created_at', '>=', $date)->get()) >= 1){
							++$count['absent'];
						}else{
							++$count['unkown'];
						}
						$count['totaltarget'] += (float)$res['target']; // ADD THIS
						return [
							'present' => $count['present'],
							'absent' => $count['absent'],
							'unkown' => $count['unkown'],
							'totaltarget' => $count['totaltarget'], // ADD THIS
						];
					});
					$res['attendance'] = $res['attendance']->values()->last();
					// end of calculate present, absent, unkown

					// calculate/get attendance of tl on this day
					$res['tlattendance'] = count($attendance_model->whereIn('user_id',collect($res['tl_ids'])->toArray())->where('status',1)->where('created_at', '>=', $date)->get());
					$res['totaltl'] = count(collect($res['tl_ids'])->toArray());
					// end of calculate/get attendance of tl on this day

					// for percentage of this cutoff
					// $res['pat'] = (int)round(($res['getallsafthiscutoff']['target']/($res['attendance']['totaltarget'] !== 0) ? $res['attendance']['totaltarget'] : 0) * 100); // ADD THIS
					$res['total_target'] = $res['getallsafthiscutoff']['target']; // total current selled
	        		$res['total_based_target'] = $res['attendance']['totaltarget'] != 0 ? $res['attendance']['totaltarget'] : 0; // based_target
	        		if ($res['total_based_target'] == 0) {
				  		$res['pat'] = 0;
					}
					else {
						$pat = ($res['total_target'] / $res['total_based_target']) * 100;
						$res['pat'] = $pat;
					}

					return $res;
				});
				return $res;
			});
			// dd($clusters);
		}
		// FOR TEAM LEAD
		else if( !empty((Session::get('_t'))) ){
			// dd(collect(Session::get('_t'))->pluck('id'));
			$clusters = $clusters_model->get()->map(function($res) use ($teams_model,$user_model,$attendance_model,$application_model,$date,$dateto,$isSearching){
				$count_applications = [
					'new' => 0,
					'activated' => 0,
					'paid' => 0,
					'target' => 0,
				];
				if( array_intersect(collect(Session::get('_t'))->pluck('id')->toArray(),collect($res['team_ids'])->toArray()) ){

					// dd(Session::get('_t'));
					$res['date'] = ($dateto == null) ? ($date->format('F d Y')) : ($date->format('F d Y').' - '.$dateto->format('F d Y'));
					$team_ids = $res['team_ids'];
					$res['teams'] = $teams_model->whereIn('id', collect($res['team_ids'])->toArray())->get()->map(function($res) use ($teams_model,$user_model,$application_model,$attendance_model,$team_ids,&$count_applications,$date,$dateto,$isSearching){
						if( in_array($res['id'],collect(Session::get('_t'))->pluck('id')->toArray()) ){

							// calcualting applications and saf
							if($isSearching == 1){
								$res['getallsafthiscutoff'] = $application_model->where('team_id', $res['id'])->whereBetween('created_at', [$date,$dateto])->get()->map(function($res) use ($teams_model, $user_model,$attendance_model,&$count_applications,$date){
									if($res['status'] == 'new'){
										(float)$count_applications['new'] += (float)$res['msf'];
									}else if($res['status'] == 'activated'){
										$count_applications['activated'] += $res['msf'];
									}else if($res['status'] == 'paid'){
										$count_applications['paid'] += $res['msf'];
									}
									$count_applications['target'] += (float)$res['msf'];
									return [
										'new' => $count_applications['new'],
										'activated' => $count_applications['activated'],
										'paid' => $count_applications['paid'],
										'target' => $count_applications['target'],
									];
								});
							}else{
								$res['getallsafthiscutoff'] = $application_model->where('team_id', $res['id'])->whereDate('created_at', $date)->get()->map(function($res) use ($teams_model, $user_model,$attendance_model,&$count_applications,$date){
									if($res['status'] == 'new'){
										(float)$count_applications['new'] += (float)$res['msf'];
									}else if($res['status'] == 'activated'){
										$count_applications['activated'] += $res['msf'];
									}else if($res['status'] == 'paid'){
										$count_applications['paid'] += $res['msf'];
									}
									$count_applications['target'] += (float)$res['msf'];
									return [
										'new' => $count_applications['new'],
										'activated' => $count_applications['activated'],
										'paid' => $count_applications['paid'],
										'target' => $count_applications['target'],
									];
								});
							}

							$res['getallsafthiscutoff'] = $res['getallsafthiscutoff']->values()->last();
							// end of calculating applications and saf

							$agents = $user_model->whereIn('id',collect($res['agent_ids'])->toArray())->get();
							$res['total_agents'] = count($agents);
							$res['agents'] = $agents;
							// calculate present, absent, unkown
							$count = [
								'present' => 0,
								'absent' => 0,
								'unkown' => 0,
								'totaltarget' => 0, // ADD THIS
							];
							$agents = $user_model->whereIn('id',collect($res['agent_ids'])->toArray())->get();
							$res['attendance'] = $agents->map(function($res) use ($attendance_model,&$count,$date){
								if( count($attendance_model->where(['user_id' => $res['id'], 'status' => 1])->where('created_at', '>=', $date)->get()) > 0){
									++$count['present'];
								}else if( count($attendance_model->where(['user_id' => $res['id'], 'status' => 0])->where('created_at', '>=', $date)->get()) >= 1){
									++$count['absent'];
								}else{
									++$count['unkown'];
								}
								$count['totaltarget'] += (float)$res['target']; // ADD THIS
								return [
									'present' => $count['present'],
									'absent' => $count['absent'],
									'unkown' => $count['unkown'],
									'totaltarget' => $count['totaltarget'], // ADD THIS
								];
							});
							$res['attendance'] = $res['attendance']->values()->last();
							// end of calculate present, absent, unkown

							// for percentage of this cutoff
							// $res['pat'] = (int)round(($res['getallsafthiscutoff']['target']/($res['attendance']['totaltarget'] !== 0) ? $res['attendance']['totaltarget'] : 0) * 100); // ADD THIS
							$res['total_target'] = $res['getallsafthiscutoff']['target']; // total current selled
			        		$res['total_based_target'] = $res['attendance']['totaltarget'] != 0 ? $res['attendance']['totaltarget'] : 0; // based_target
			        		if ($res['total_based_target'] == 0) {
						  		$res['pat'] = 0;
							}
							else {
								$pat = ($res['total_target'] / $res['total_based_target']) * 100;
								$res['pat'] = $pat;
							}

							return $res;
						}
					});
					return $res;
				}
			});
			// dd($clusters);
		}
		else if( !empty((Session::get('_a'))) ){

			$myattendance = $teams_model->whereIn('id',collect(Session::get('_a'))->pluck('id'))->get()->map(function($res) use ($user_model,$attendance_model,$date){
				$agents = $user_model->where('id',Auth()->user()->id)->first();
				$res['agents'] = $agents;
				// $res['total_agents'] = count($agents);
				// dd($res['id']);
				$count = [
					'present' => 0,
					'absent' => 0,
					'unkown' => 0,
				];
				// please add now date
				$temp_myatt = $attendance_model->where([ 'user_id' => Auth()->user()->id, 'team_id' => $res['id']])->where('created_at', '>=', $date)->value('status');
				$res['attendance'] = ($temp_myatt === null) ? 'Unkown' : (($temp_myatt == 1) ? 'Present' : 'Absent');
				// dd($res['attendance']);
				return $res;
			});


		}

		// FOR ENCODER ROLE
	}else if(base64_decode(Auth()->user()->role) == $roles['encoder']){

	}

	// RETURN DATA BACK TO LARAVEL VIEW
	// dd($date);
	return [
		'clusters' => (!empty($clusters)) ? $clusters : [],
		// 'teams' => (!empty($teams)) ? $teams : [],
		// 'dates' => [
		// 	'prev' => Carbon::now()->subMonths(1),
		// 	'curr' => Carbon::now(),
		// 	'next' => Carbon::now()->addMonths(1),
		// ],
		'myattendance' => (!empty($myattendance)) ? $myattendance : [],
	];


}

// function to get all the ids of clusters, teams, and agents

function getIds($getWhat = 'all'){
	if($getWhat == 'all'){
		$clusters = new App\Clusters;
		$teams = new App\Teams;
		$get_c = Session::get('_c');
		$get_t = Session::get('_t');
		$data['all'] = [];
		$data['clusters'] = [];
		$data['teams'] = [];
		$data['agents'] = [];
		if(Auth::user()->role != base64_encode('administrator')){
			if(count($get_c) != 0){
				$status = true;
				$message = "Collected all ids";
				foreach($get_c as $cluster){
					foreach($cluster['cl_ids'] as $cl_id){
						$data['all'][] = $cl_id;
						$data['clusters'][] = $cl_id;
					}
					foreach($cluster['team_ids'] as $team_id){
						$check_teams = Teams::where('id', $team_id)->first();
						if(!empty($check_teams)){
							foreach($check_teams['tl_ids'] as $tl_id){
								if(!in_array($tl_id, $data['all'])){
									$data['all'][] = (string)$tl_id;
								}
								if(!in_array($tl_id, $data['teams'])){
									$data['teams'][] = (string)$tl_id;
								}
							}
							foreach($check_teams['agent_ids'] as $agent_id){
								if(!in_array($agent_id, $data['all'])){
									$data['all'][] = (string)$agent_id;
								}
								if(!in_array($agent_id, $data['agents'])){
									$data['agents'][] = (string)$agent_id;
								}
							}
						}
					}
				}
			} else if(count($get_t) != 0) {
				$status = true;
				$message = "Collected all ids";

				foreach($get_t as $teams){
					foreach($teams['tl_ids'] as $tl_ids){
						$data['all'][] = (string)$tl_ids;
						$data['teams'][] = (string)$tl_ids;
					}
					foreach($teams['agent_ids'] as $agent_ids){
						$data['all'][] = (string)$agent_ids;
						$data['agents'][] = (string)$agent_ids;
					}
				}
			} else {
			}
		} else {
			$status = true;
			$message = "Collected all ids";
			foreach($clusters->get() as $cluster){
				foreach($cluster['cl_ids'] as $cl_id){
					$data['all'][] = $cl_id;
					$data['clusters'][] = $cl_id;
				}
				foreach($cluster['team_ids'] as $team_id){
					$check_teams = Teams::where('id', $team_id)->first();
					if(!empty($check_teams)){
						foreach($check_teams['tl_ids'] as $tl_id){
							if(!in_array($tl_id, $data['all'])){
								$data['all'][] = (string)$tl_id;
							}
							if(!in_array($tl_id, $data['teams'])){
								$data['teams'][] = (string)$tl_id;
							}
						}
						foreach($check_teams['agent_ids'] as $agent_id){
							if(!in_array($agent_id, $data['all'])){
								$data['all'][] = (string)$agent_id;
							}
							if(!in_array($agent_id, $data['agents'])){
								$data['agents'][] = (string)$agent_id;
							}
						}
					}
				}
			}
		}

		$return['status'] = $status;
		$return['ids'] = $data;
		$return['message'] = $message;
		return $return;
	}
}

?>
