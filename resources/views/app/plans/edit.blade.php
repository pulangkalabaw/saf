@extends ('layouts.app')

@section('content')

<div class="main-heading">
    <ol class="breadcrumb">
        <li class="">{{ strtoupper(env('APP_NAME')) }}</li>
        <li class="">Settings</li>
        <li class=""><a href="{{ route('app.plans.index') }}">Plans Management</a></li>
        <li class="">
            {{ $plan->plan_name }}
        </li>
        <li class="active">Edit</li>
    </ol>
</div>
<div class="container-fluid half-padding">
    <div class="template template__blank">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-8">
                                <h3 class="panel-title">
                                    Plans MANAGEMENT
                                </h3>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="{{ route('app.plans.index') }}" class="btn btn-xs btn-default">
                                    <span class='fa fa-th-list'></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('app.plans.update', $plan->plan_id) }}" method="POST">
                            @include('includes.notif')

                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="row">
                                <div class="col-md-7">

                                    <div>
                                        <div class="col-md-3">Plans name</div>
                                        <div class="col-md-7">
                                            <input type="text" name="plan_name" id="" class="form-control" value="{{ $plan->plan_name }}" required>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">With Sim</div>
                                        <div class="col-md-7">
                                            <select class="form-control" name="with_sim" required>
                                                <option {{ $plan->with_sim == '1' ? 'selected':'' }} value="1">YES</option>
                                                <option {{ $plan->with_sim == '0' ? 'selected':'' }} value="0">NO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">With Device</div>
                                        <div class="col-md-7">
                                            <select class="form-control" name="with_device" required>
                                                <option {{ $plan->with_device == '1' ? 'selected':'' }} value="1">YES</option>
                                                <option {{ $plan->with_device == '0' ? 'selected':'' }} value="0">NO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">MSF</div>
                                        <div class="col-md-7">
                                            <input type="text" name="msf" id="" class="form-control" value="{{ $plan->msf }}" required>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3"></div>
                                        <div class="col-md-7 text-right">
                                            <button class="btn btn-xs btn-success">Update changes <span class='fa fa-edit'></span> </button>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
