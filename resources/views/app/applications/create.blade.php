@extends ('layouts.app')


@section('content')

<div class="main-heading">
    <ol class="breadcrumb">
        <li class="">{{ strtoupper(env('APP_NAME')) }}</li>
        <li class="">Home</li>
        <li class="">
            <a href="{{ route('app.applications.index') }}">Applications</a>
        </li>
        <li class="active">Create</li>
    </ol>
</div>
<div class="container-fluid half-padding">
    <div class="template template__blank">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-8">
                                <h3 class="panel-title">
                                    Application Form
                                </h3>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="{{ route('app.applications.index') }}" class="btn btn-xs btn-default">
                                    <span class='fa fa-th-list'></span> 
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('app.applications.store') }}" method="POST">
                            @include('includes.notif')
                            {{ csrf_field() }}
                            
                            <div style="overflow-x: auto;">
                                <table class="table" style="width: 2000px;">
                                    <thead>
                                        <tr>
                                            <th width="5px"></th>
                                            <th width="120px">Team</th>
                                            <th width="120px">Recieved</th>
                                            <th width="150px">Customer</th>
                                            <th width="120px">Plan</th>
                                            <th width="120px">Device</th>
                                            <th width="120px">Product</th>
                                            <th width="120px">MSF</th>
                                            <th width="120px">SAF #</th>
                                            <th width="120px">Codis #</th>
                                            <th width="120px">SR #</th>
                                            <th width="120px">SO #</th>
                                            <th width="120px">Account</th>
                                            <th width="120px">Agent</th>
                                            <th width="120px">Status</th>
                                            <th width="150px">Document Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($i = 0; $i <= 9; $i++) 
                                        <tr>
                                            <td>{{ ($i < 10) ? ($i + 1) : $i }}.</td>
                                            <td>
                                                <select name="team_id[]" id="" class="form-control">
                                                    <option value="" disabled="" selected="">Please select Team name</option>
                                                    @foreach ($teams as $team)
                                                    <option {{ old('team_id.'.$i) == $team['team_id'] ? 'selected' : ''}} value="{{ $team['team_id'] }}">{{ $team['team_name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="date" name="received_date[]" id="" class="form-control" value="{{ old('received_date.'.$i) }}">
                                            </td>
                                            <td>
                                                <input type="text" name="customer_name[]" id="" class="form-control" value="{{ old('customer_name.'.$i) }}">
                                            </td>
                                            <td>
                                                <select name="plan_applied[]" id="" class="form-control">
                                                    @foreach ($plans as $plan)
                                                    <option {{ old('plan_applied.'.$i) == $plan->plan_id ? 'selected' : ''}} value="{{ $plan->plan_id }}">{{ $plan->plan_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="device_name[]" id="" class="form-control">
                                                    @foreach ($devices as $device)
                                                    <option {{ old('device_name.'.$i) == $device->device_id ? 'selected' : ''}} value="{{ $device->device_id }}">{{ $device->device_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="product_type[]" id="" class="form-control">
                                                    @foreach ($products as $product)
                                                    <option {{ old('product_type.'.$i) == $product->product_id ? 'selected' : ''}} value="{{ $product->product_id }}">{{ $product->product_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" name="msf[]" id="" class="form-control" value="{{ old('msf.'.$i) }}"></td>
                                            <td><input type="text" name="saf_no[]" id="" class="form-control" value="{{ old('saf_no.'.$i) }}"></td>
                                            <td><input type="text" name="codis_no[]" id="" class="form-control" value="{{ old('codis_no.'.$i) }}"></td>
                                            <td><input type="text" name="sr_no[]" id="" class="form-control" value="{{ old('sr_no.'.$i) }}"></td>
                                            <td><input type="text" name="so_no[]" id="" class="form-control" value="{{ old('so_no.'.$i) }}"></td>
                                            <td><input type="text" name="account_no[]" id="" class="form-control" value="{{ old('account_no.'.$i) }}"></td>
                                            <td>
                                                <select name="agent_code[]" id="" class="form-control">
                                                    @foreach ($users->getAvailableAgent() as $agent)
                                                    <option {{ old('agent_code.'.$i) == $agent->agent_code ? 'selected' : ''}} value="{{ $agent->agent_code }}">{{ $agent->fname . ' ' . $agent->lname }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="status[]" id="" class="form-control">
                                                    @foreach ($statuses as $status)
                                                    <option {{ old('status.'.$i) == $status->id ? 'selected' : ''}} value="{{ $status->id }}">{{ $status->status }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" name="document_remarks[]" id="" class="form-control" value="{{ old('document_remarks.'.$i) }}"></td>
                                        </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <button class="btn btn-primary">Submit <span class='fa fa-plus-circle'></span> </button>
                            <br>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection