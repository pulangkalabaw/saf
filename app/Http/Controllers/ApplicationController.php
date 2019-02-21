<?php

namespace App\Http\Controllers;

use Auth;
use Session;
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
	public function __construct()
	{
		// $this->middleware('admin_only', ['only' => 'create']);
	}

	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index(Request $request)
	{
		//
		$applications = new Application();

		if (Auth::user()->role != base64_encode("administrator")) $applications = $applications->where('insert_by', Auth::user()->id);
		if (!empty($request->get('search_string'))) {
			// With search string parameter
			$applications = $applications->search($request->get('search_string'));
		}

		// Count all before paginate
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
		$users = new User();
		$teams = Teams::whereIn('team_id', Session::get('_t'))->get();
		$statuses = Statuses::get();
		$plans = Plans::get();
		$devices = Devices::get();
		$products = Product::get();

		return view('app.applications.create', [
			'agents' => $users->whereIn('role', [base64_encode('agent'), base64_encode('agent_referral')])->get(), // Get agent only!
			'plans' => $plans,
			'devices' => $devices,
			'products' => $products,
			'statuses' => $statuses,
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

		// Count row for looping (for now, we will reference customer_name)
		// how many row did he/she filled
		$row_count = count(array_filter($request['customer_name']));

		// if the row has a length of 0
		if ($row_count == 0) {
			return back()->with([
				'notif.style' => 'danger',
				'notif.icon' => 'times-circle',
				'notif.message' =>
				'All fields are required'
			])
			->withInput();
		}

		// Loop through all rows
		for ($i = 0; $i <= $row_count - 1; $i++) {

			if ($request['agent_id'][$i] == 0 || $request['plan_id'][$i] == 0) {
				return back()->with([
					'notif.style' => 'danger',
					'notif.icon' => 'times-circle',
					'notif.message' => 'Agent and Plan are also requried!',
				])
				->withInput();
			}

			// Get the amount of that plan
			$plan = Plans::findOrFail($request['plan_id'][$i]);

			// Generate application_id
			$application_id = rand(1111, 99999);

			// Data to insert in Application table
			$application_data[$i] = [

				'application_id' => $application_id,
				'customer_name' => $this->replaceDashIfNull($request['customer_name'][$i]),
				'insert_by' => Auth::user()->id,
				'team_id' => Session::get('_t')[0],
				'cluster_id' => Session::get('_c')[0],
				'contact' => $this->replaceDashIfNull($request['contact'][$i]),
				'address' => $this->replaceDashIfNull($request['address'][$i]),
				'plan_id' => (int) $request['plan_id'][$i],
				'msf' => (double) $plan->msf,
				'sim' => $this->replaceDashIfNull($request['sim'][$i]),
				'device_id' => $this->replaceDashIfNull($request['device_id'][$i]), // string
				'agent_id' => (int) $request['agent_id'][$i],
				'status' => 'new',
				'created_at' => now(),

			];

			// Data to insert in Application Status table
			$application_status_data[$i] = [
				'application_id' => (string) $application_id,
				'team_id' => Session::get('_t')[0],
				'status_id' => 'new', // change this to status
				'active' => 1, // Active means it is the current status ;)
				'added_by' => Auth::user()->id,
				'created_at' =>now(),
			];
		}

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
				'agents' => $users->whereIn('role', [base64_encode('agent_referral'), base64_encode('agent')])->get(),
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

			// Place to $data variable
			$data['customer_name'] = $request->post('customer_name');
			$data['contact'] = $request->post('contact');
			$data['address'] = $request->post('address');
			$data['plan_id'] = (int) $request->post('plan_id');
			$data['sim'] = $request->post('sim');
			$data['device_id'] = empty($request->post('device_id')) ? '-' : $request->post('device_id');
			$data['agent_id'] = (int) $request->post('agent_id');
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
