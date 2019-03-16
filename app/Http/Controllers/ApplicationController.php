<?php

namespace App\Http\Controllers;

use Auth;
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
use App\ApplicationStatus;
use Illuminate\Http\Request;

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


		// Get the current login users cluster Id
		// this will show all the applications base on which cl or tl is login
		$applications = $applications->applicationSubmitted(Auth::user());
		$applications_total = $applications->count();


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

		// variable by default
		// if sim / device field is empty default given value will be null
		$sim = null;
		$device = null;

		// Checks if the given number is sim number or device number gives null on device if sim else gives null on sim if device is selected
		if(substr($request['sim'],0,2) === '09' || substr($request['sim'],0,3) === '+63'){
			$sim = $request['sim'];
			$device = null;
		} else {
			$sim = null;
			$device = $request['sim'];
		}

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
			'sim' => $sim,
			'device_id' => $device,
			'agent_id' => (int) $request['user_id'],
			'status' => 'new',
			'insert_by' => (int) Auth::user()->id,
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

		if (Application::insert($application_data)) {

			// Insert Application Status
			ApplicationStatus::insert($application_status_data);

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
		//
		$application_model = new Application();
		$application_status = new ApplicationStatus();
		$application = $application_model
		->with([
			'getDevice',
			'getPlan',
			'getProduct',
			'getInsertBy',
		])
		->where('application_id', $id)
		->firstOrFail();

		return view('app.applications.show', [
			'application' => $application,
			'application_status' => $application_status->appStatus($id),
			'application_model' => $application_model
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
		//
		$users = new User();

		$application_model = new Application();
		$application_status = new ApplicationStatus();
		$plans = Plans::get();
		$devices = Devices::get();
		$products = Product::get();
		$application = $application_model->where('application_id', $id)->firstOrFail();

		return view('app.applications.edit', [
			'application' => $application,
			'application_model' => $application_model,
			'application_status' => $application_status->appStatus($id),
			'users' => $users->get(), // !!! FIX THIS: this code will show all users, but we need is the users or agents that is in this team. !!!
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
		$application_model = new Application();
		$application = $application_model->where('application_id', $id)->firstOrFail();
		$msf = Plans::where('id',$request['plan_id'])->value('msf');
		$product = Plans::where('id',$request['plan_id'])->value('product');

		// Place to $data variable
		$data['customer_name'] = $request->post('customer_name');
		$data['contact'] = $request->post('contact');
		$data['address'] = $request->post('address');
		$data['product'] = $product;
		$data['plan_id'] = (int) $request->post('plan_id');
		$data['sim'] = $request->post('sim');
		$data['device_id'] = empty($request->post('device_id')) ? '-' : $request->post('device_id');
		$data['agent_id'] = (int) $request->post('agent_id');
		$data['msf'] = (float) $msf;
		$data['sr_no'] = $request->post('sr_no');
		$data['so_no'] = $request->post('so_no');
		$data['status'] = $request->post('status');
		$data['encoder_id'] = Auth::user()->id;
		$data['encoded_at'] = now();
		$data['updated_at'] = now();

		if ($application->update($data)) {

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
}
