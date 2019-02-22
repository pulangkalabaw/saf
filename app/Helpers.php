<?php
use App\Teams;
use App\User;
use App\Clusters;

/**
 * GET CLUSTER AND TEAM (_C, _T)
 *
 * The idea of this is to get your
 * Cluster id(s) and Team id(s)
 * whether you are a cluster leader,
 * a team leader or either you are agent.
 *
 * Requirements: the get your cluster and team IDs
 * this method needed your User model or Auth Model
 * but first you must be authenticated to be in this
 * method.
 *
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
// specific div
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
        //         });/
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
?>
