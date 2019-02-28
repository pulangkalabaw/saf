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
		if (base64_decode(Auth::user()->role) == 'user'){
			$applications = $applications->where('insert_by', Auth::user()->id)
			->orWhere('agent_id', Auth::user()->id);
		}

		// Check if the login user is a cluster leader
		// Outputs the application data of all the teams under the cluster
		$cluster_data = getMyClusterAndTeam(Auth::user());
		if(!empty($cluster_data['_c'][0])){
			$cluster_id = $cluster_data['_c'][0]['cluster_id'];
			$applications = $applications->where('cluster_id', $cluster_id);
		}

		if (!empty($request->get('search_string'))) {
			// With search string parameter
			$applications = $applications->search($request->get('search_string'));
		}

		$total = $applications->count();

		// Insert pagination
		$applications = $applications
		->with(['getClusterName', 'getTeam', 'getAgentName'])
		->paginate((!empty($request->show) ? $request->show : 10));

		return view('app.applications.index', ['applications' => $applications, 'applications_total' => $total]);
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function create()
	{
		$users = User::get();
		$teams = Teams::get();
		$statuses = Statuses::get();
		$plans = Plans::get();
		$devices = Devices::get();
		$products = Product::get();

		return view('app.applications.create', [
			'users' => $users,
			'plans' => $plans,
			'devices' => $devices,
			'products' => $products,
			'statuses' => $statuses,
			'teams' => $teams,
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

		// Data to be inserted to Application table
		$application_data = [
			'application_id' => $application_id,
			'cluster_id' => $team_model[0]['cluster_id'],
			'team_id' => $request['team_id'],
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
		])
		->where('application_id', $id)
		->firstOrFail();

		return view('app.applications.show', [
			'application' => $application,
			'application_model' => $application_model, 'application_status' => $application_status]);
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
			$teams = Teams::whereIn('team_id', Session::get('_t'))->get();
			$application_model = new Application();
			$application_status = new ApplicationStatus();
			$plans = Plans::get();
			$devices = Devices::get();
			$products = Product::get();

			$application = $application_model->where('application_id', $id)->firstOrFail();

			return view('app.applications.edit', [
				'application' => $application,
				'application_model' => $application_model,
				'application_status' => $application_status,
				'agents' => $users->get(),
				'teams' => $teams,
				'plans' => $plans,
				'devices' => $devices,
				'products' => $products,
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

			// Place to $data variable
			$data['customer_name'] = $request->post('customer_name');
			$data['contact'] = $request->post('contact');
			$data['address'] = $request->post('address');
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
