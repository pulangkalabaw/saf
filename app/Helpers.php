<?php

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
        if ($auth->role == base64_encode("agent")) {
            $get_teams = $teams_model->where('agent_code', $auth->agent_code)->get();
        }
        else {
            $get_teams = [];
        }

        // Then check your agent code to them
        // $get_teams = $get_teams->get();

        if (count($get_teams) == 0) {

            // If your ID not in Agent Code
            // Check for Encoders IDs
            if (count($get_teams) == 0) {
                $get_teams = $teams_model->get()->map(function ($r) use ($auth, $teams_model) {
                    // search your id in encoders ids (array)
                    if (in_array($auth->id, json_decode($r['encoder_ids']))) return $r;

                });

                // Filter all null if it has
                $get_teams  = array_filter($get_teams->toArray());

            } // Encoder Search

        } // Agent Code Search

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