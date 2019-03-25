<?php
namespace App\Http\Controllers;
use Auth;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;
use App\Attendance;
use App\Attendance_image;
use App\User;
use App\Teams;
use App\Clusters;
class AttendanceController extends Controller
{
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function __construct () {
		$this->middleware('access_control:administrator,user', ['only' => [
			'index',
		]]);
	}
	public function sample(){
		$faker = faker::create();
		$cluster_id = $faker->randomElement(Clusters::get());
		// return $cluster_id['team_ids'];
		return $team_id  = Teams::where('team_id', $faker->randomElement($cluster_id['team_ids']))->first();
		$user_id = User::where('id', $faker->randomElement($team_id['agent_ids']))->value('id');
	}
	public function index(Request $request)
	{
		// return count(checkPosition(Auth::user(), ['cl', 'tl'], true));
		$get_session;
        // return count(session()->get('_t'));
        if(count(session()->get('_c')) == 0 && count(session()->get('_t')) == 0){
    		return view('app.attendance.index');
        }
		// dd()
		$date_original = $request->date == null ? Carbon::parse(date('Y-m-d')) : Carbon::parse($request->date);
		$date_select = $date_original;
		// return $date_select->subDays(1);
		$date['selected'] = $date_select->toDateString();
		$date['previous'] = $request->date == null ? Carbon::parse(date('Y-m-d'))->subDays(1)->toDateString() : Carbon::parse($request->date)->subDays(1)->toDateString();
		if($date_select != Carbon::parse(date('Y-m-d'))){
			$date['next'] = $request->date == null ? Carbon::parse(date('Y-m-d'))->addDays(1)->toDateString() : Carbon::parse($request->date)->addDays(1)->toDateString();
		} else {
			$date['next'] = null;
		}
		// return $date;
		$date_select = $request->date != null ? $request->date : date('Y-m-d H:i:s');
		$get_user_attendance = [];
		if(count(session()->get('_c')) >= 1){
			$get_session = session()->get('_c');
			$user_ids = session()->get('_c')[0];
			$get_user_ids = [];
			$get_user_attendance = [];
			// return $user_ids['team_ids'];
			foreach($user_ids['team_ids'] as $team_id){
				$get_teams = Teams::where('id', $team_id)->orderBy('id', 'desc')->first();
				// GET ALL AGENT ID
				foreach($get_teams['agent_ids'] as $user){
					if(!empty($get_user_ids['user_ids'])){
						if(!in_array($user, $get_user_ids['user_ids'])){
							$get_user_ids['user_ids'][] = $user;
							$get_user_ids['user_info'][] = ['user_id' => $user, 'team_name' => $get_teams['team_name']];
						}
					}
					else {
						$get_user_ids['user_ids'][] = $user;
						$get_user_ids['user_info'][] = ['user_id' => $user, 'team_name' => $get_teams['team_name']];
					}
				}
				// GET ALL TEAM LEADERs ID
				foreach($get_teams['tl_ids'] as $tl_id){
					if(!in_array($tl_id, $get_user_ids['user_ids'])){
						$get_user_ids['user_ids'][] = $tl_id;
						$get_user_ids['user_info'][] = ['user_id' => $tl_id, 'team_name' => $get_teams['team_name']];
					}
				}
			}
			// return $get_user_ids;
			if(count(session()->get('_c')) == 1){
				$teams = Teams::whereIn('id', $user_ids['team_ids'])->get();
				$selected_unpresent_users = [];
				foreach($user_ids['team_ids'] as $agent){
					foreach(Teams::where('id', $agent)->pluck('agent_ids') as $agents){
						foreach($agents as $agnt){
							$check_agent = Attendance::where('user_id', $agnt)->whereDate('created_at', $date_original)->first();
							if(!empty($check_agent) || $check_agent != null){
								$get_user_attendance[] = $check_agent;
							}
							array_push($selected_unpresent_users, $agnt);
						}
					}
					foreach(Teams::where('id', $agent)->pluck('tl_ids') as $tl_ids){
						foreach($tl_ids as $tl){
							$check_tl = Attendance::where('user_id', $tl)->whereDate('created_at', $date_original)->first();
							if(!empty($check_tl) || $check_tl != null){
								$get_user_attendance[] = $check_tl;
							}
							array_push($selected_unpresent_users, $tl);
						}
					}
				}
				// return $get_user_attendance;
			}
            else if(count(session()->get('_c')) > 1){
                // return session()->get('_c');
                if($request->cl_id != null){
                    foreach(session()->get('_c') as $cluster){
						// return $request->cl_id;
                        if($request->cl_id == $cluster['id']){
                            $user_ids = $cluster;
                            break;
                        }
                    }
					$get_user_ids = [];
					$get_user_attendance = [];
					// return $user_ids['team_ids'];
					foreach($user_ids['team_ids'] as $team_id){
						// return $team_id;
						$get_teams = Teams::where('id', $team_id)->orderBy('id', 'desc')->first();
						// GET ALL AGENT ID
						foreach($get_teams['agent_ids'] as $user){
							if(!empty($get_user_ids['user_ids'])){
								if(!in_array($user, $get_user_ids['user_ids'])){
									$get_user_ids['user_ids'][] = $user;
									$get_user_ids['user_info'][] = ['user_id' => $user, 'team_name' => $get_teams['team_name']];
								}
							}
							else {
								$get_user_ids['user_ids'][] = $user;
								$get_user_ids['user_info'][] = ['user_id' => $user, 'team_name' => $get_teams['team_name']];
							}
						}
						// GET ALL TEAM LEADERs ID
						foreach($get_teams['tl_ids'] as $tl_id){
							if(!in_array($tl_id, $get_user_ids['user_ids'])){
								$get_user_ids['user_ids'][] = $tl_id;
								$get_user_ids['user_info'][] = ['user_id' => $tl_id, 'team_name' => $get_teams['team_name']];
							}
						}
					}
                    // return $user_ids['team_ids'];
    				$teams = Teams::whereIn('id', $user_ids['team_ids'])->get();
    				$selected_unpresent_users = [];
    				foreach($user_ids['team_ids'] as $agent){
                        // return $agent;
    					foreach(Teams::where('id', $agent)->pluck('agent_ids') as $agents){
                            // return $agents;
    						foreach($agents as $agnt){
                                // return $agnt;
    							$check_agent = Attendance::where('user_id', $agnt)->whereDate('created_at', $date_original)->first();
    							// if(empty($check_agent) || $check_agent == null){
								if(!empty($check_agent) || $check_agent != null){
									$get_user_attendance[] = $check_agent;
								}
								array_push($selected_unpresent_users, $agnt);
    						}
    					}
                        // return $get_user_attendance;
                        // return $selected_unpresent_users;
    					foreach(Teams::where('id', $agent)->pluck('tl_ids') as $tl_ids){
                            // return $tl_ids;
    						foreach($tl_ids as $tl){
								$check_tl = Attendance::where('user_id', $tl)->whereDate('created_at', $date_original)->first();
								if(!empty($check_tl) || $check_tl != null){
									$get_user_attendance[] = $check_tl;
								}
								array_push($selected_unpresent_users, $tl);
    						}
    					}
    				}
                    $selected_cluster = $user_ids['id'];
                    // return $selected_unpresent_users;
                } else {
        			$user_ids = session()->get('_c')[count(session()->get('_c')) - 1];
					// return $user_ids['team_ids'];
                    $teams = Teams::whereIn('id', $user_ids['team_ids'])->get();
    				$selected_unpresent_users = [];
    				foreach($user_ids['team_ids'] as $agent){
						// return $agent;
    					foreach(Teams::where('id', $agent)->pluck('agent_ids') as $agents){
    						foreach($agents as $agnt){
								$check_tl = Attendance::where('user_id', $agnt)->whereDate('created_at', $date_original)->first();
								if(!empty($check_tl) || $check_tl != null){
									$get_user_attendance[] = $check_tl;
								}
								array_push($selected_unpresent_users, $agnt);
    							// }
    						}
    					}
						// return $get_user_attendance;
    					foreach(Teams::where('id', $agent)->pluck('tl_ids') as $tl_ids){
    						foreach($tl_ids as $tl){
								$check_tl = Attendance::where('user_id', $tl)->whereDate('created_at', $date_original)->first();
								if(!empty($check_tl) || $check_tl != null){
									$get_user_attendance[] = $check_tl;
								}
								array_push($selected_unpresent_users, $tl);
    						}
    					}
    				}
                    // return $user_ids['id'];
                    $selected_cluster = $user_ids['id'];
                }
                // return session()->get('_c');
                foreach(session()->get('_c') as $cluster){
                    $clusters[] = ['id' => $cluster['id'], 'name' => $cluster['cluster_name']];
                    // $clusters[] = $cluster['cluster_name'];
                }
                // return $selected_cluster;
                // return $clusters;
                // return $selected_unpresent_users;
            }
		}
		else if(count(session()->get('_t')) >= 1){
			$check_if_has_cluster = null;
			foreach(session()->get('_t') as $teams){
				// return $teams['tl_ids'];
				foreach($teams['tl_ids'] as $tl_id){
					if(Auth::user()->id == $tl_id){
						// return $teams['id'];
						$find_clusters = Clusters::get();
						foreach($find_clusters as $get_clusters){
							// return $get_clusters;
							foreach($get_clusters['team_ids'] as $get_team_id){
								// return $get_team_id;
								// return $teams['id'];
								// dd($teams['id'] == $get_team_id);
								if($teams['id'] == $get_team_id){
									$check_if_has_cluster = 'meron nga';
								}
							}
						}
					}
				}
			}
			// return $check_if_has_cluster;
			if($check_if_has_cluster == null){
				$dont_have_cl = 1;
	    		return view('app.attendance.index', compact('dont_have_cl'));
			}
			$get_session = session()->get('_t');
			$user_ids = session()->get('_t')[0];
			if(count(session()->get('_t')) == 1){
				$selected_unpresent_users = [];
				$get_user_ids = [];
				// $get_user_ids['user_ids'] = $user_ids['agent_ids'];
				// return $user_ids['agent_ids'];
				// return $get_user_ids;
				foreach($user_ids['agent_ids'] as $user){
					// return $get_user_ids;
					// return $user;
					// dd(!empty($get_user_ids));
					if(!empty($get_user_ids)){
						// return $user;
						// return $get_user_ids['user_ids'];
						// return $get_user_ids['user_info'];
						// dd(!in_array($user, $get_user_ids['user_ids']));
						if(!in_array($user, $get_user_ids['user_ids'])){
							// return $user;
							$get_user_ids['user_ids'][] = $user;
							$get_user_ids['user_info'][] = ['user_id' => $user, 'team_name' => $user_ids['team_name']];
						}
					}else {
							$get_user_ids['user_ids'][] = $user;
							$get_user_ids['user_info'][] = ['user_id' => $user, 'team_name' => $user_ids['team_name']];
					}
				}
				// return $get_user_ids;
				foreach($user_ids['agent_ids'] as $agent){
					$check_agent = Attendance::where('user_id', $agent)->whereDate('created_at', $date_original)->first();
					if(empty($check_agent) || $check_agent == null){
						array_push($selected_unpresent_users, $agent);
					}
				}
			}
			else if(count(session()->get('_t')) > 1){
				$combine =[];
				$get_user_ids = [];
				$user_ids = session()->get('_t');
				foreach(session()->get('_t') as $agents){
					foreach($agents['agent_ids'] as $agent){
						array_push($combine, $agent);
					}
				}
				foreach($user_ids as $user_id){
					foreach($user_id['agent_ids'] as $user){
						if(!empty($get_user_ids)){
							if(!in_array($user, $get_user_ids['user_ids'])){
								$get_user_ids['user_ids'][] = $user;
								$get_user_ids['user_info'][] = ['user_id' => $user, 'team_name' => $user_id['team_name']];
							}
						} else {
							$get_user_ids['user_ids'][] = $user;
							$get_user_ids['user_info'][] = ['user_id' => $user, 'team_name' => $user_id['team_name']];
						}
					}
					foreach($user_id['tl_ids'] as $tl_id){
						if(!empty($get_user_ids)){
							if(!in_array($tl_id, $get_user_ids['user_ids'])){
								$get_user_ids['user_ids'][] = $tl_id;
								$get_user_ids['user_info'][] = ['user_id' => $tl_id, 'team_name' => $user_id['team_name']];
							}
						} else {
							$get_user_ids['user_ids'][] = $user;
							$get_user_ids['user_info'][] = ['user_id' => $user, 'team_name' => $user_id['team_name']];
						}
					}
				}
				$selected_unpresent_users = $combine;
			}
			$teams = $get_session != null ? $get_session : null ;
		}
		// return $get_user_ids;
		// GET ROLL CALL
		// return $selected_unpresent_users;
		$unpresent = User::whereIn('id', $selected_unpresent_users)->get();
		collect($unpresent)->map(function($r) use ($teams, $get_user_attendance){
			if(!empty($get_user_attendance)){
				foreach($get_user_attendance as $get_attendance){
					if($r->id == $get_attendance['user_id']){
						$r['value_activity'] = $get_attendance['activities'];
						$r['value_location'] = $get_attendance['location'];
						$r['value_remarks'] = $get_attendance['remarks'];
						$r['value_status'] = $get_attendance['status'];
						if($get_attendance['status'] == 1){
							$r['value_btn'] = ['class' => 'btn-info', 'label' => 'Present'];
						}
						else if($get_attendance['status'] == 0){
							$r['value_btn'] = ['class' => 'btn-danger', 'label' => 'Absent'];
						}
					}
				}
			}
			foreach($teams as $team){
				foreach($team['agent_ids'] as $agent){
					if($r->id == $agent){
						$r['team_name'] = $team['team_name'];
					}
				}
				foreach($team['tl_ids'] as $agent){
					if($r->id == $agent){
						$r['team_name'] = $team['team_name'];
					}
				}
				// return $team['tl_ids'];
				if(in_array( $r->id, $team['tl_ids'])){
					// return 'sad';
					$r['tl'] = 1;
				}
				else {
					// $r['tl'] = 0;
				}
			}
			return $r;
		});
		if(count(Session::get('_c')) != 0){
			// return $unpresent;
			$sortArray = array();
			foreach((array)$unpresent as $get_unpresent){
				// return $get_unpresent;
				foreach((array)$get_unpresent as $un_present){
					// return $un_present;
					$x = 0;
					foreach(collect($un_present) as $key=>$value){
						// if($x == 3){
						// 	return $key;
						// }
						// return $value;
						// return $x;
						// return $value;
						if(!isset($sortArray[$key])){
							$sortArray[$key] = array();
						}
						$sortArray[$key][] = $value;
						// $x++;
					}
					// return $sortArray;
				}
			}
			// return $sortArray;
			$orderby = "team_name"; //change this to whatever key you want from the array\
			$unpresent = $unpresent->toArray();
			// array_multisort($sortArray['id'],SORT_ASC, $unpresent);
			array_multisort($sortArray[$orderby],SORT_ASC, $unpresent);
		}
		// array_multisort($sortArray['tl'],SORT_ASC, $unpresent);
		$attendance['unpresent'] = $unpresent;
		// GET PRESENT AGENTS
		// return $get_user_ids;
		// return $get_user_ids['user_ids'];
		$attendance['present'] = Attendance::whereIn('user_id', $get_user_ids['user_ids'])->whereDate('created_at', $date_original)->where('status', 1)->with(['Users'])->orderBy('id', 'desc')->get();
        collect($attendance['present'])->map(function($r) use ($get_user_ids){
			foreach($get_user_ids['user_info'] as $user){
				// return $r['users']->id;
				// return $user;
				if($r['users']->id == $user['user_id']){
					$r['team_name'] = $user['team_name'];  // INSERT TEAM NAME ON PRESENT USERS
				}
			}
            return $r;
        });
		// GET ABSENT AGENTS
		$attendance['absent'] = Attendance::whereIn('user_id', $get_user_ids['user_ids'])->whereDate('created_at', $date_original)->where('status', 0)->with(['Users'])->orderBy('id', 'desc')->get();
        collect($attendance['absent'])->map(function($r) use ($get_user_ids){
			// return $get_user_ids;
			foreach($get_user_ids['user_info'] as $user){
				// return $user;
				if($r['users']->id == $user['user_id']){
					$r['team_name'] = $user['team_name'];  // INSERT TEAM NAME ON PRESENT USERS
				}
			}
            return $r;
        });
		// return $attendance['unpresent'];
		// return $attendance['present'];
		// return $attendance['absent'];
		// return $get_user_attendance;
		return view('app.attendance.index', compact('attendance', 'teams', 'clusters', 'selected_cluster', 'get_user_attendance', 'date'));
	}

	public function list(Request $request){
		// for jump to date, previous, and next
		$date_original = $request->date == null ? Carbon::parse(date('Y-m-d')) : Carbon::parse($request->date);
		$date_select = $date_original;

		$date['selected'] = $date_select->toDateString(); // this will be the value of jump to date

		$date['previous'] = $request->date == null ? Carbon::parse(date('Y-m-d'))->subDays(1)->toDateString() : Carbon::parse($request->date)->subDays(1)->toDateString();

		if($date_select != Carbon::parse(date('Y-m-d'))){
			$date['next'] = $request->date == null ? Carbon::parse(date('Y-m-d'))->addDays(1)->toDateString() : Carbon::parse($request->date)->addDays(1)->toDateString();
		} else {
			$date['next'] = null;
		}

		$data = getIds('all');
        $attendance = Attendance::whereIn('user_id', $data['ids']['all'])->where('user_id', '!=', Auth::user()->id)->whereDate('created_at', $date_select)->orderBy('status', 'desc');

        if (!empty($request->get('search_string'))) $attendance = $attendance->search($request->get('search_string'));

        // Count all before paginate
        $total = $attendance->count();

        // Count all attendance
        $total_attendance = User::count();

		$date_select = $request->date != null ? $request->date : date('Y-m-d H:i:s');

        // Insert pagination
        $attendance = $attendance->paginate((!empty($request->show) ? $request->show : 10));
		return view('app.attendance.list', [
			'date' => $date,
			'attendance' => $attendance,
            'attendance_total' => $total_attendance,
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
		//
	}
	/**
	* Store a newly created resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @return \Illuminate\Http\Response
	*/
	public function store(Request $request)
	{
		// $faker = faker::create();
		// $cluster_id = $faker->randomElement(Clusters::get());
		// // return $cluster_id['team_ids'];
		// $team_id  = Teams::where('team_id', $faker->randomElement($cluster_id['team_ids']))->first();
		// $user_id = User::where('id', $faker->randomElement($team_id['agent_ids']))->value('id');
		// $cluster_id = Clusters::where('team_ids', 'like', '%' . $team_id['team_id'] . '%')->value('id');
		// return $request->all();
		// return $team_id = Teams::where('tl_id', Auth::user()->id)->get(['team_id']);
		// return Clusters::where('team_ids', 'like', '%' . $team_id . '%')->get(['cluster_id']);
		// return checkPosition(Auth::user(), ['tl'], true);

		// return $request->date;
		$selected_date = !empty($request->selected_date) ? $request->selected_date : Carbon::now()->toDateString();

		if(count(Session::get('_c')) == 0){
			$get_team = Session::get('_t')[0];
			$get_cluster = Clusters::get();
						// return Auth::user()->id;
			foreach($get_cluster as $cluster){
				$cluster['team_ids'];
				foreach($cluster['team_ids'] as $tl){
					// return $tl;
					if($get_team['id'] == $tl){
						$user_cluster_id = $cluster['id'];
						// break;
					}
				}
			}
			// return $user_cluster_id;
			// return ;
			$check_user_attendance = Attendance::where('user_id', Auth::user()->id)->whereDate('created_at', Carbon::now()->toDateString())->first();
			if(empty($check_user_attendance)){
				Attendance::create([
					"cluster_id" => $user_cluster_id,
					"team_id" => $get_team['id'],
					"user_id" => Auth::user()->id,
					"activities" => 'Team Leader',
					"location" => 'Team Leader',
					"remarks" => 'Team Leader',
					"status" => 1,
					'created_by' => Auth::user()->id,
				]);
			}
		}
		// $this->validate($request, [
		// 	'empImg' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
		// ]);
		//
		// if($request->hasFile('empImg')) {
		// 	$image = $request->file('empImg');
		// 	$name = time().'.'.$image->getClientOriginalExtension();
		// 	$destinationPath = public_path('/images/attendance');
		// 	$image->move($destinationPath, $name);
		// 	$data_image = [
		// 		'user_id' => Auth::user()->id,
		// 		'image' => $name,
		// 		'alt' => $name,
		// 	];
		// }

		if(!empty($request->empImg)){
			if($image = $request->input('empImg')){
				// $this->validate($request, [
				// 	'empImg' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
				// ]);
				$image = $request->input('empImg'); // image base64 encoded
				preg_match("/data:image\/(.*?);/",$image,$image_extension); // extract the image extension
				$image = preg_replace('/data:image\/(.*?);base64,/','',$image); // remove the type part
				$image = str_replace(' ', '+', $image);
				$imageName = 'image_' . time() . '.' . $image_extension[1]; //generating unique file name;
				Storage::disk('public')->put($imageName,base64_decode($image));
				Session::flash('success', "Your photo has been uploaded successfully");
				$status	= $request->status = 0;
				$has_date =	$request->has_date = 0;
			}else{
				// $this->validate($request, [
				// 	'empImg' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
				// ]);
				if(\Carbon\Carbon::now()->format('H:i:s') >= \Carbon\Carbon::parse('10:30:00')->format('H:i:s')){
					Session::flash('message', "Sorry you cannot upload this photo at this time");
					return back();
				}else{
					// $this->validate($request, [
					// 	'selected_user[]' => 'in:0,1',
					// 	'empImg' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
					// ]);
					if(empty(exif_read_data($request->empImg)['DateTimeOriginal'])){
						$file = $request->file('empImg');
						$name = 'image_' . time().'.'.$file->getClientOriginalExtension();
						Storage::disk('public')->put($name,file_get_contents($file));
						Session::flash('success', "Your photo has been uploaded successfully");
						$status	= $request->status = 1;
						$has_date =	$request->has_date = 1;
					}elseif(date("m/d/Y", strtotime(exif_read_data($request->empImg)['DateTimeOriginal'])) != Carbon::today()->format('m/d/Y')){
						Session::flash('message', "Sorry this photo is not taken today");
						return back();
					} else{
						if($request->hasFile('empImg')) {
							// return $request->empImg;
							// $image = $request->file('empImg');
							// $name = time().'.'.$image->getClientOriginalExtension();
							// $destinationPath = public_path('/images');
							// $image->move($destinationPath, $name);
							// return $name;
							$file = $request->file('empImg');
							$name = 'image_' . time().'.'.$file->getClientOriginalExtension();
							Storage::disk('public')->put($name,file_get_contents($file));
							Session::flash('success', "Your photo has been uploaded successfully");
							$status	= $request->status = 1;
							$has_date =	$request->has_date = 0;
						}
					}
					$data_image = [
					'user_id' => Auth::user()->id,
					'image' => $name,
					'alt' => $name,
					'status' => $status,
					'has_date' => $has_date,
					];
				}
			}
		}


		if($request->mobile_version == null){
			$get_user = $request->only('user')['user'];
		} else {
			$get_user = $request->only('users')['users'];
		}
		// return $get_user;
		$data = [];
		foreach($get_user as $user){
			// return $request->all();

			$team_id;
			if(count(Session::get('_c')) != 0){
				if(count(Session::get('_c')) == 1){
					$_c = Session::get('_c')[0];
					$cluster_id = $_c['id'];
					foreach($_c['team_ids'] as $teams){
						$get_team = Teams::where('id', $teams)->first();
						if(in_array($user['user_id'], $get_team['agent_ids'])){
								$team_id = $get_team['id'];
						}
						if(in_array($user['user_id'], $get_team['tl_ids'])){
								$team_id = $get_team['id'];
						}
					}
				}
				else if(count(Session::get('_c')) > 1){
					foreach(Session::get('_c') as $_c){
						$cluster_id = $_c['id'];
						foreach($_c['team_ids'] as $teams){
							$get_team = Teams::where('id', $teams)->first();
							if(in_array($user['user_id'], $get_team['agent_ids'])){
									$team_id = $get_team['id'];
							}
							if(in_array($user['user_id'], $get_team['tl_ids'])){
									$team_id = $get_team['id'];
							}
						}
					}
				}
			}
			else if(count(Session::get('_t')) != 0){
				foreach(session()->get('_t') as $teams){
					// return $teams;
					if(in_array($user['user_id'], $teams['agent_ids'])){
						$team_id = $teams['id'];
					}
					if(in_array($user['user_id'], $teams['tl_ids'])){
						$team_id = $teams['id'];
					}
				}
			}
			// return $user['activities'];
			// $request->all();
			// return $team_id;
			$clusters = Clusters::get();
			foreach($clusters as $cluster){
				foreach($cluster['team_ids'] as $tl){
					if($team_id == $tl){
						$cluster_id = $cluster['id'];
					}
				}
			}
			if(empty($user['modified_status']) && $user['status'] != null){
				$check_attendance = Attendance::where('user_id', $user['user_id'])->whereDate('created_at', Carbon::now()->toDateString())->first();
				if(empty($check_attendance)){
					$set_data = [
						"cluster_id" => $cluster_id,
						"team_id" => $team_id,
						"user_id" => $user['user_id'],
						"activities" => $user['activities'],
						"location" => $user['location'],
						"remarks" => $user['remarks'],
						"status" => $user['status'],
						'created_by' => Auth::user()->id,
						'created_at' => date('Y-m-d H:i:s'),
						'updated_at' => date('Y-m-d H:i:s'),
					];
					array_push($data, $set_data);
				}
			}
			if(!empty($user['modified_status'])){
				// return $user['modified_status'];
				// return $user['user_id'];
				$check_the_fucking_attendance = Attendance::where('user_id', $user['user_id'])->whereDate('created_at', $selected_date)->first();
				if(!empty($check_the_fucking_attendance)){
					if($user['status'] == null){
						Attendance::where('user_id', $user['user_id'])->delete();
					}
					else {
						if(!empty($user['modified_remarks'])){
							$set_data = [
								"activities" => $user['activities'],
								"location" => $user['location'],
								"remarks" => $user['remarks'],
								"status" => $user['status'],
								'modified_by' => Auth::user()->id,
								'modified_remarks' => $user['modified_remarks'],
							];
							Attendance::where('user_id', $user['user_id'])->update($set_data);
						}
					}
				} else {
					if($user['status'] != null){
						// return $team_id;
						// return $cluster_id;
						// return $user['user_id'];
						// return [
						// 	"cluster_id" => $cluster_id,
						// 	"team_id" => $team_id,
						// 	"user_id" => $user['user_id'],
						// 	"activities" => $user['activities'],
						// 	"location" => $user['location'],
						// 	"remarks" => $user['remarks'],
						// 	"status" => $user['status'],
						// 	'created_by' => Auth::user()->id,
						// 	'modified_by' => Auth::user()->id,
						// 	'modified_remarks' => $user['modified_remarks'],
						// 	'created_at' => $selected_date,
						// 	'updated_at' => date('Y-m-d H:i:s'),
						// ];\
						// return $request->all();
						// return $selected_date;
						// return Attendance::where('user_id', 49)->whereDate('created_at', $selected_date)->first();
						// return $user;
						// return $user['modified_remarks'];
						Attendance::insert([
							"cluster_id" => $cluster_id,
							"team_id" => $team_id,
							"user_id" => $user['user_id'],
							"activities" => $user['activities'],
							"location" => $user['location'],
							"remarks" => $user['remarks'],
							"status" => $user['status'],
							'created_by' => Auth::user()->id,
							'modified_by' => Auth::user()->id,
							'modified_remarks' => $user['modified_remarks'],
							'created_at' => $selected_date,
							'updated_at' => date('Y-m-d H:i:s'),
						]);
					}
				}
			}
		}
		// return $data;
		// $get_user_team = Session::get('_t');
		// Attendance::create([
		// 	"cluster_id" => $cluster_id,
		// 	"team_id" => $team_id,
		// 	"user_id" => Auth::user()->id,
		// 	'created_at' => date('Y-m-d H:i:s'),
		// 	'updated_at' => date('Y-m-d H:i:s'),
		// ]);
		// if(){}
		// return $data;
		Attendance::insert($data);
		if(!empty($data_image)){
			// return $data_image;
			Attendance_image::create($data_image);
		}
		return back();
	}

	public function gallery(Request $request){
	 	$date = $request->date != null ? Carbon::parse($request->date)->toDateString() : date('Y-m-d');
	 	$date_select = $date;
		$image = Attendance_image::orderBy('image', 'decs')->whereDate('created_at', $date)->paginate(12);

	 	$date_selected = $date_select;
	 	$previous = $request->date == null ? Carbon::parse(date('Y-m-d'))->subDays(1)->toDateString() : Carbon::parse($request->date)->subDays(1)->toDateString();
		if($date != Carbon::parse(date('Y-m-d'))->toDateString()){
			$next = $request->date == null ? Carbon::parse(date('Y-m-d'))->addDays(1)->toDateString() : Carbon::parse($request->date)->addDays(1)->toDateString();
		} else {
			$next = null;
		}
		return view('app.attendance.gallery', compact('image', 'date', 'previous', 'next'));
	}

	public function destroy_image($id){
	    $image = Attendance_image::findOrFail($id);
		$image->delete();
		Session::flash('message', "Your photo has been deleted successfully");
		return back();
	}
	/**
	* Display the specified resource.
	*
	* @param  \App\Attendance  $attendance
	* @return \Illuminate\Http\Response
	*/
	public function show(Attendance $attendance)
	{
		//
	}
	/**
	* Show the form for editing the specified resource.
	*
	* @param  \App\Attendance  $attendance
	* @return \Illuminate\Http\Response
	*/
	public function edit(Attendance $attendance)
	{
		//
	}
	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \App\Attendance  $attendance
	* @return \Illuminate\Http\Response
	*/
	public function update(Request $request, Attendance $attendance)
	{
		//
	}
	/**
	* Remove the specified resource from storage.
	*
	* @param  \App\Attendance  $attendance
	* @return \Illuminate\Http\Response
	*/
	public function destroy(Attendance $attendance)
	{
		//
	}
}
