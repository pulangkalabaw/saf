@extends ('layouts.app')

@section('content')

<div class="main-heading">
    <ol class="breadcrumb">
        <li class="">{{ strtoupper(env('APP_NAME')) }}</li>
        <li class="">Home</li>
        <li class="">
            <a href="{{ route('app.applications.index') }}">Applications</a>
        </li>
        <li class="">
            {{ $application->application_id }}
        </li>
        <li class="active">Edit</li>
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
                                    TEAM
                                </h3>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="{{ route('app.applications.show', $application->application_id) }}" class="btn btn-xs btn-default">
                                    <span class='fa fa-eye'></span> 
                                </a>
                                <a href="{{ route('app.applications.index') }}" class="btn btn-xs btn-default">
                                    <span class='fa fa-th-list'></span> 
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">

                        <form action="{{ route('app.applications.update', $application->application_id) }}" method="POST">
                            @include('includes.notif')
                            
                            <div class="row">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                                <div class="col-md-6">
                                    <div>
                                        <div class="col-md-3">Application #:</div>
                                        <div class="col-md-7">
                                            {{ $application->application_id }}
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Status:</div>
                                        <div class="col-md-7">
                                            <select name="status" id="" class="form-control input-sm" required="">
                                                @foreach ($statuses as $status)
                                                <option {{ $application_model->recentStatusShort($application->application_id, 'id') == $status->id ? 'selected' : ''}} value="{{ $status->id }}">{{ $status->status }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Encoder:</div>
                                        <div class="col-md-7">
                                            {{ $application->getEncoder->fname . ' ' . $application->getEncoder->lname }}
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Team:</div>
                                        <div class="col-md-7">
                                            <select name="team_id" id="" class="form-control input-sm" required="">
                                                @foreach ($teams as $team)
                                                <option {{ $application->getTeam->team_id == $team['team_id'] ? 'selected' : ''}} value="{{ $team['team_id'] }}">{{ $team['team_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Received date:</div>
                                        <div class="col-md-7">
                                            <input type="date" name="received_date" id="" class="form-control input-sm" value="{{ $application->received_date }}" required="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Encoded date:</div>
                                        <div class="col-md-7">
                                            {{ $application->encoded_date }}
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Customer name:</div>
                                        <div class="col-md-7">
                                            <input type="text" name="customer_name" id="" class="form-control input-sm" value="{{ $application->customer_name }}" required="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Device:</div>
                                        <div class="col-md-7">
                                            <select name="device_name" id="" class="form-control input-sm" required="">
                                                @foreach ($devices as $device)
                                                <option {{ $application->device_name == $device->device_id ? 'selected' : ''}} value="{{ $device->device_id }}">{{ $device->device_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Plan applied:</div>
                                        <div class="col-md-7">
                                            <select name="plan_applied" id="" class="form-control input-sm" required="">
                                                @foreach ($plans as $plan)
                                                <option {{ $application->plan_applied == $plan->plan_id ? 'selected' : ''}} value="{{ $plan->plan_id }}">{{ $plan->plan_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Product type:</div>
                                        <div class="col-md-7">
                                            <select name="product_type" id="" class="form-control input-sm" required="">
                                                @foreach ($products as $product)
                                                <option {{ $application->product_type == $product->product_id ? 'selected' : ''}} value="{{ $product->product_id }}">{{ $product->product_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Volume:</div>
                                        <div class="col-md-7">
                                            <input type="text" name="volume" id="" class="form-control input-sm" value="{{ $application->volume }}" required="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">MSF:</div>
                                        <div class="col-md-7">
                                            <input type="text" name="msf" id="" class="form-control input-sm" value="{{ $application->msf }}" required="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">SAF #:</div>
                                        <div class="col-md-7">
                                            <input type="text" name="saf_no" id="" class="form-control input-sm" value="{{ $application->saf_no }}" required="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                </div>


                                <div class="col-md-6">
                                    <div>
                                        <div class="col-md-3">CODIS #:</div>
                                        <div class="col-md-7">
                                            <input type="text" name="codis_no" id="" class="form-control input-sm" value="{{ $application->codis_no }}" required="">

                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">SR #:</div>
                                        <div class="col-md-7">
                                            <input type="text" name="sr_no" id="" class="form-control input-sm" value="{{ $application->sr_no }}" required="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>    

                                    <div>
                                        <div class="col-md-3">SO #:</div>
                                        <div class="col-md-7">
                                            <input type="text" name="so_no" id="" class="form-control input-sm" value="{{ $application->so_no }}" required="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>    

                                    <div>
                                        <div class="col-md-3">Account #:</div>
                                        <div class="col-md-7">
                                            <input type="text" name="account_no" id="" class="form-control input-sm" value="{{ $application->account_no }}" required="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>    

                                    <div>
                                        <div class="col-md-3">Mobile #:</div>
                                        <div class="col-md-7">
                                            <input type="text" name="mobile_no" id="" class="form-control input-sm" value="{{ $application->mobile_no }}" required="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>    

                                    <div>
                                        <div class="col-md-3">ICCID:</div>
                                        <div class="col-md-7">
                                            <input type="text" name="iccid" id="" class="form-control input-sm" value="{{ $application->iccid }}" required="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>    

                                    <div>
                                        <div class="col-md-3">IMEI:</div>
                                        <div class="col-md-7">
                                            <input type="text" name="imei" id="" class="form-control input-sm" value="{{ $application->imei }}" required="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>    

                                    <div>
                                        <div class="col-md-3">Sales Source:</div>
                                        <div class="col-md-7">
                                            <input type="text" name="sales_source" id="" class="form-control input-sm" value="{{ $application->sales_source }}" required="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>    

                                    <div>
                                        <div class="col-md-3">Agent:</div>
                                        <div class="col-md-7">
                                            <select name="agent_code" id="" class="form-control">
                                                @foreach ($users->getAvailableAgent() as $agent)
                                                <option {{  $application->getAgentCode->agent_code == $agent->agent_code ? 'selected' : ''}} value="{{ $agent->agent_code }}">{{ $agent->fname . ' ' . $agent->lname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>    

                                    <div>
                                        <div class="col-md-3">Status remarks:</div>
                                        <div class="col-md-7">
                                            <input type="text" name="status_remarks" id="" class="form-control input-sm" value="{{ $application->status_remarks }}" required="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>    

                                    <div>
                                        <div class="col-md-3">Document remarks:</div>
                                        <div class="col-md-7">
                                            <input type="text" name="document_remarks" id="" class="form-control input-sm" value="{{ $application->document_remarks }}" required="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>    
                                </div>
                            </div>
                            <hr>
                            <button class="btn btn-success btn-xs">Update changes <span class='fa fa-edit'></span> </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
