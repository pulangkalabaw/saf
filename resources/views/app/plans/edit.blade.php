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
                        <form action="{{ route('app.plans.update', $plan->id) }}" method="POST">
                            @include('includes.notif')

                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="row">
                                <div class="col-md-7">

                                    <div>
                                        <div class="col-md-3">Product</div>
                                        <div class="col-md-7">
                                            <select class="form-control" name="product">
                                                <option {{ ($plan->product == 'smart') ? 'selected' : '' }} value="smart">Smart</option>
                                                <option {{ ($plan->product == 'smart_bro') ? 'selected' : '' }} value="smart_bro">Smart Bro</option>
                                                <option {{ ($plan->product == 'sun') ? 'selected' : '' }} value="sun">Sun</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Plans name</div>
                                        <div class="col-md-7">
                                            <input type="text" name="plan_name" id="" class="form-control" value="{{ $plan->plan_name }}" required>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">With Device</div>
                                        <div class="col-md-7">
                                            <input {{ ($plan->with_device == 1) ? 'checked' : ''}} type="checkbox" name="with_device" value="1">
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
                                        <div class="col-md-3">Description</div>
                                        <div class="col-md-7">
                                            <textarea name="description" rows="8" cols="80" class="form-control">{{ $plan->description }}</textarea>
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
