<?php

namespace App\Http\Controllers;
// use Form;
use Auth;
use Storage;
use Session;
use Validator;
use App\User;
use App\Plans;
use App\Teams;
use App\Product;
use App\Devices;
use App\Clusters;
use App\Statuses;
use App\Application;
use App\Application_Files;
use App\ApplicationStatus;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Input;

class ApplicationController extends Controller
{
	// limits the access control of the user
	public function __construct()
	{
		$this->middleware('access_control:encoder,administrator,user', ['only' => 'index', 'show']);
		$this->middleware('access_control:administrator,user', ['only' => 'create']);
		$this->middleware('access_control:encoder', ['only' => 'edit']);
	}

	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index(Request $request)
	{
		$applications = new Application();

		$applications_total = $applications->count();

		// Get the current login users cluster Id
		// this will show all the applications base on which cl or tl is login
		$applications = $applications->applicationSubmitted(Auth::user());

		// Sorting
        // params: sort_in & sort_by
        if (!empty($request->get('sort_in') && !empty($request->get('sort_by')))) $applications = $applications->sort($request);

		// Searching
		if (!empty($request->get('search_string'))) {
			// With search string parameter
			$applications = $applications->search($request->get('search_string'));
		}
		// dd($applications->get());
		$total = $applications->count();

		// Insert pagination
		$applications = $applications
		->with(['getClusterName', 'getTeam', 'getAgentName', 'getPlan'])
		->paginate((!empty($request->show) ? $request->show : 10));

		return view('app.applications.index', [
			'applications' => $applications,
			'applications_total' => $applications_total,
			'total' => $total
		]);
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function create()
	{
		$data = getUserDetailClusterAndTeam(Auth::user());
		$plans = Plans::get();
		$clusters = Clusters::get();
		$teams = Teams::get();
		$agents = User::get();

		if(base64_decode(Auth::user()->role) != 'administrator'){

			if(!empty(checkUserAgents(Auth::user())))
			{
				$clusters = $data['_c'];
				$teams = $data['_t'];
				$agents = $data['_a'];
			}
			else
			{
				return back()->with([
                    'notif.style' => 'danger',
                    'notif.icon' => 'times-circle',
                    'notif.message' => 'Failed to access module!',
                ]);
			}
		}

		return view('app.applications.create', [
			'status' => 'success',
			'plans' => $plans,
			'clusters' => $clusters,
			'teams' => $teams,
			'agents' => $agents,
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
		$input_file = $request->all();
		$validator = Validator::make(
		$input_file, [
		'image_file.*' => 'required|file|mimes:xlsx,xls,csv,jpg,jpeg,png,bmp,doc,docx,pdf,tif,tiff'
		],[
		    'image_file.*.required' => 'Please upload an image',
		    'image_file.*.mimes' => 'Only xlsx,xls,csv,jpg,jpeg,png,bmp,doc,docx,pdf,tif,tiff images are allowed',
		]);

		if ($validator->fails()) {
		    $messages = $validator->messages();
		    return Redirect::to('/')->with('message', 'Your erorr message');
		}else{
			if (($request->has('attached_files'))) {
			$files = $request->file('attached_files');

				$destinationPath = storage_path() . '/app/public/';
				foreach ($files as $file) {
					$fileName  =  $file->getClientOriginalName(); //date('Y-m-d') . '-' .
					$extension = $file->getClientOriginalExtension();
			 		$storeName = $fileName;
					// Store the file in the disk
					$file->move($destinationPath, $storeName);
					$data[] = $storeName;
					// $storeName [] = $fileName;
					// $files_Details = implode(',', (array)$attach_files);
				}
			}

			// $form = new Form();
		 	$attached_files = json_encode($data);
		}
		// return $request->all();
		$validate = Validator::make($request->all(),[
			'customer_name' => 'required',
			'contact' => 'required',
			'address' => 'required',
			'plan_id' => 'required',
			'user_id' => 'required',
			'team_id' => 'required'
		],[
			'plan_id.required' => 'Please select plan',
			'user_id.required' => 'Please select user',
			'team_id.required' => 'Please select team'
		]);

		if($validate->fails()) return back()->withErrors($validate->errors())->withInput();

		// Get plan data
		$plan = Plans::findOrFail($request['plan_id']);

		// Generate application_id
		$application_id = rand(1111,9999);

		// Get cluster
		$team_model = new Teams();
		$team_model = $team_model->getCluster($request['team_id']);
		// Modified: get the cluster id
		foreach($team_model as $cluster){
			$cluster_id = $cluster['id'];
		}

		if (empty($cluster_id)) {
			return back()->with([
				'notif.style' => 'danger',
				'notif.icon' => 'times-circle',
				'notif.message' => 'This team has no cluster!',
			]);
		}
		// Data to be inserted to Application table
		$application_data = [
			'application_id' => $application_id,
			'cluster_id' => $cluster_id,
			'team_id' => $request['team_id'],
			'product' => $plan->product,
			'customer_name' => $request['customer_name'],
			'contact' => $request['contact'],
			'address' => $request['address'],
			'plan_id' => (int) $request['plan_id'],
			'msf' => (float) $plan->msf,
			'device' => $request['device'],
			'agent_id' => (int) $request['user_id'],
			'status' => 'new',
			'insert_by' => (int) Auth::user()->id,
			'expires_at' => now()->addDays(30)->toDateString(),
			'created_at' => now(),
		];

		// Data to be inserted to Application Status table
		$application_status_data = [
			'application_id' => $application_id,
			'team_id' => $request['team_id'],
			'status_id' => 'new',
			'active' => 1,
			'added_by' => Auth::user()->id,
			'created_at' => now(),
		];

		//Data to be inserted to Application File
		$attach_files = [
			'application_id' => $application_id,
			'attached_files' => $attached_files,
		];


		Application_Files::insert($attach_files);
		ApplicationStatus::insert($application_status_data);
		if (Application::insert($application_data)) {

			// Insert Application Status

			// Maybe we just pretend everything is fine :(
			// so, return it hehe
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
	* @param  \App\Application  $application
	* @return \Illuminate\Http\Response
	*/
	public function show($id)
	{

		$application_model = new Application();
		$application_status = new ApplicationStatus();
		$application_files = new Application_Files();
		$application = $application_model
		->with([
			'getDevice',
			'getPlan',
			'getProduct',
			'getInsertBy',
			'getTeam',
			'getCluster'
		])

		->where('application_id', $id)
		->firstOrFail();

		// return $application_files->where('application_id', $id)->with(['Application'])->value('attached_files');
		return view('app.applications.show', [
			'application' => $application,
			'application_model' => $application_model,
			'application_files' => $application_files->where('application_id', $id)->with(['Application'])->value('attached_files'),
			'application_status' => $application_status->appStatus($id)
		]);
	}
	/**
	* Show the form for editing the specified resource.
	*
	* @param  \App\Application  $application
	* @return \Illuminate\Http\Response
	*/
	public function edit($id)
	{
		$users = new User();
		$teams = new Teams();

		// filters the agent and tl_ids
		$tl_ids = [];
		$teams_tl = $teams->get()->pluck("tl_ids")->toArray();

		foreach($teams_tl as $tl){
			$tl_ids = array_unique(array_merge($tl_ids, $tl));
		}

		$agent_ids = [];
		$teams_agent = $teams->get()->pluck("agent_ids")->toArray();

		foreach($teams_agent as $agent){
			$agent_ids = array_unique(array_merge($agent_ids, $agent));
		}

		$user_ids = array_unique(array_merge($agent_ids, $tl_ids));
		$available_users = $users
		->whereIn('id',$user_ids)
		->where('isActive', '1')
		->get();
		// filter end

		$application_model = new Application();
		$application_status = new ApplicationStatus();
		$plans = Plans::get();
		$products = Product::get();
		$application = $application_model->where('application_id', $id)->with(['getEncoderData'])->firstOrFail();
		$agent = $users->where('id',$application['agent_id'])->first();

		return view('app.applications.edit', [
			'application' => $application,
			'application_model' => $application_model,
			'application_status' => $application_status->appStatus($id),
			'users' => $available_users,
			'agent' => $agent,
			'encoders' => $users->where('role', base64_encode('encoder'))->get(),
			'plans' => $plans,
		]);
	}

	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \App\Application  $application
	* @return \Illuminate\Http\Response
	*/
	public function update(Request $request, $id)
	{
		$validate = Validator::make($request->all(),[
			'encoder_id' => 'required'
		],[
			'encoder.required' => 'Please select encoder!'
		]);

		if($validate->fails()) return back()->withErrors($validate->errors())->withInput();

		$application_model = new Application();
		$application = $application_model->where('application_id', $id);
		$msf = Plans::where('id',$request['plan_id'])->value('msf');
		$product = Plans::where('id',$request['plan_id'])->value('product');

		//Check if the status does not change
		if($request->post('status') != 'new'){
			$expires_at = null;
		} else {
			$expires_at = $application->value('expires_at');
		}

		// Place to $data variable
		$data['status'] = $request->post('status');
		$data['customer_name'] = $request->post('customer_name');
		$data['plan_id'] = (int) $request->post('plan_id');
		$data['contact'] = $request->post('contact');
		$data['address'] = $request->post('address');
		$data['product'] = $product;
		$data['sim'] = $request->post('sim');
		$data['sim_id'] = $request->post('sim_id');
		$data['device'] = $request->post('device');
		$data['imei'] = $request->post('imei');
		$data['agent_id'] = (int) $request->post('agent_id');
		$data['team_id'] = (int) $request->post('team_id');
		$data['msf'] = (float) $msf;
		$data['sr_no'] = $request->post('sr_no');
		$data['so_no'] = $request->post('so_no');
		$data['encoder_id'] = $request->post('encoder_id');
		$data['remarks'] = $request->post('remarks');
		$data['min_no'] = $request->post('min_no');
		$data['awaiting_device'] = $request->post('awaiting_device');
		$data['expires_at'] = $expires_at;
		$data['encoded_at'] = now();
		$data['updated_at'] = now();

		if ($application->firstOrFail()->update($data)) {

			ApplicationStatus::where('id', $id)->update(['active' => 0]);

			ApplicationStatus::insert([
				'application_id' => (string) $id,
				'status_id' => $data['status'], // change this to status
				'added_by' => Auth::user()->id,
				'active' => 1,
				'team_id' => (int) $request->post('team_id'),
				'created_at' => now(),
			]);

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
	* @param  \App\Application  $application
	* @return \Illuminate\Http\Response
	*/
	public function destroy(Application $application)
	{
		//
	}

	public function replaceDashIfNull($arr)
	{
		return !empty($arr) ? $arr : "-";
	}


	public function ImageView(Request $request){
		return 123;
	}

	public function showAvailableApi(Request $request, $id)
	{
		$team_model = new Teams();
		$user = new User();
		$user_data = $user->where('id', $id)->first();
		// check first if the given user is tl
		if(checkPosition($user_data)[0] == 'tl'){
			$team_ids = [];

			$teams = $team_model->get(['tl_ids', 'id'])->toArray();

			foreach ($teams as $team) {
				if(!empty('tl_ids')) {
					if(in_array($id, $team['tl_ids'])){
						$team_ids[] = $team['id'];
					}
				}
			}

			$team = $team_model->whereIn('id', $team_ids)->first()->toArray();
		}
		else if(checkPosition($user_data)[0] == 'agent'){
			$team_ids = [];

			$teams = $team_model->get(['agent_ids', 'id'])->toArray();

			foreach($teams as $team) {
				if(!empty('agent_ids')){
					if(in_array($id, $team['agent_ids'])){
						$team_ids[] = $team['id'];
					}
				}
			}

			$team = $team_model->whereIn('id',$team_ids)->first()->toArray();
		}

		return response()->json([
			'team' => $team,
			'user' => $user_data
		]);
	}
}
