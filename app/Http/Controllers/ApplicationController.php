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

        if (Auth::user()->role != base64_encode("administrator")) $applications = $applications->where('user_id', Auth::user()->id);
        if (!empty($request->get('search_string'))) {
            // With search string parameter
            $applications = $applications->search($request->get('search_string'));
        }

        // Count all before paginate
        $total = $applications->count();

        // Insert pagination
        $applications = $applications
        ->with(['getEncoder', 'getTeam'])
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
            'users' => $users, 
            'teams' => $teams,
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
        // Team id must be prioritized to be required
        if (empty($request->team_id)) {
            return back()->with([
                'notif.style' => 'danger',
                'notif.icon' => 'times-circle',
                'notif.message' => 'All fields are required',
            ])->withInput();
        }

        // Local var
        $application_data = [];
        $application_status_data = [];

        // Init setup
        $data = [];
        $data['team_id'] = str_replace("", "-", $request->team_id);
        $data['received_date'] = str_replace("", "-", $request->received_date);
        $data['customer_name'] = str_replace("", "-", $request->customer_name);
        $data['plan_applied'] = str_replace("", "-", $request->plan_applied);
        $data['device_name'] = str_replace("", "-", $request->device_name);
        $data['product_type'] = str_replace("", "-", $request->product_type);
        $data['msf'] = str_replace("", "-", $request->msf);
        $data['saf_no'] = str_replace("", "-", $request->saf_no);
        $data['codis_no'] = str_replace("", "-", $request->codis_no);
        $data['sr_no'] = str_replace("", "-", $request->sr_no);
        $data['so_no'] = str_replace("", "-", $request->so_no);
        $data['saf_no'] = str_replace("", "-", $request->saf_no);
        $data['account_no'] = str_replace("", "-", $request->account_no);
        $data['agent_code'] = str_replace("", "-", $request->agent_code);
        $data['status'] = str_replace("", "-", $request->status);
        $data['document_remarks'] = str_replace("", "-", $request->document_remarks);

        if (
            empty($data['team_id']) || empty($data['received_date']) || empty($data['customer_name']) ||
            empty($data['plan_applied']) || empty($data['device_name']) || empty($data['product_type']) ||
            empty($data['msf']) || empty($data['saf_no']) || empty($data['codis_no']) ||
            empty($data['sr_no']) || empty($data['so_no']) || empty($data['account_no']) || empty($data['saf_no']) ||
            empty($data['agent_code']) || empty($data['status']) || empty($data['document_remarks'])
        ) {
            return back()->with([
                'notif.style' => 'danger',
                'notif.icon' => 'times-circle',
                'notif.message' => 'All fields are required',
            ])->withInput();
        }
        // Count Team id count
        $counted_array = count($request->team_id) - 1;
        $teams = new Teams();

        for ($i = 0; $i <= $counted_array; $i++) {

            $team_id = $this->replaceDashIfNull($data['team_id'][$i]);
            $cluster_id = $teams->getCluster($team_id)[0]['cluster_id'];

            $received_date = $this->replaceDashIfNull($data['received_date'][$i]);
            $customer_name = $this->replaceDashIfNull($data['customer_name'][$i]);
            $plan_applied = $this->replaceDashIfNull($data['plan_applied'][$i]);
            $device_name = $this->replaceDashIfNull($data['device_name'][$i]);
            $product_type = $this->replaceDashIfNull($data['product_type'][$i]);
            $msf = $this->replaceDashIfNull($data['msf'][$i]);
            $codis_no = $this->replaceDashIfNull($data['codis_no'][$i]);
            $sr_no = $this->replaceDashIfNull($data['sr_no'][$i]);
            $saf_no = $this->replaceDashIfNull($data['saf_no'][$i]);
            $so_no = $this->replaceDashIfNull($data['so_no'][$i]);
            $account_no = $this->replaceDashIfNull($data['account_no'][$i]);
            $agent_code = $this->replaceDashIfNull($data['agent_code'][$i]);
            $status = $this->replaceDashIfNull($data['status'][$i]);
            $document_remarks = $this->replaceDashIfNull($data['document_remarks'][$i]);

            if ($sr_no) {
                if (!Application::where(['sr_no' => $sr_no])->first()) {

                    // Application ID
                    $application_id = rand(11111, 99999);

                    // Data to insert in Application table
                    $application_data[$i] = [
                        'application_id' => $application_id,
                        'user_id' => Auth::user()->id,
                        'team_id' => $team_id,
                        'cluster_id' => $cluster_id,
                        'received_date' => $received_date,
                        'customer_name' => $customer_name,
                        'plan_applied' => $plan_applied,
                        'device_name' => $device_name,
                        'product_type' => $product_type,
                        'msf' => $msf,
                        'codis_no' => $codis_no,
                        'sr_no' => $sr_no,
                        'saf_no' => $saf_no,
                        'so_no' => $so_no,
                        'account_no' => $account_no,
                        'agent_code' => $agent_code,
                        'status' => $status,  // Leave it like that, official status will not be here
                        'document_remarks' => $document_remarks,
                        'encoded_date' => now(),
                    ];

                    // Data to insert in Application Status table
                    $application_status_data[$i] = [
                        'application_id' => (string) $application_id,
                        'team_id' => $team_id,
                        'status_id' => (int) $status,
                        'active' => 1,
                        'added_by' => Auth::user()->id,
                        'created_at' =>now(),
                    ];

                }
            }
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
        ->where('application_id', $id)->firstOrFail();

        return view('app.applications.show', ['application' => $application, 'application_model' => $application_model, 'application_status' => $application_status]);
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
        $statuses = Statuses::get();
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
            'users' => $users, 
            'teams' => $teams,
            'plans' => $plans,
            'devices' => $devices,
            'products' => $products,
            'statuses' => $statuses,
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
        //
        $application_model = new Application();
        $application = $application_model->where('application_id', $id)->firstOrFail();

        if ($application->update($request->only([
            'team_id', 'received_date', 'customer_name',
            'device_name', 'plan_applied', 'product_type',
            'volume', 'msf', 'saf_no',
            'codis_no', 'sr_no', 'so_no',
            'account_no', 'mobile_no', 'iccid',
            'imei', 'sales_source', 'agent_code',
            'status_remarks', 'document_remarks',
            'status',
        ]))) {

            ApplicationStatus::where('application_id', $id)->update(['active' => 0]);

            ApplicationStatus::insert([
                'application_id' => (string) $id,
                'status_id' => (int) $request->post('status'),
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

