@extends ('layouts.app')

@section('content')

<div class="main-heading">
    <ol class="breadcrumb">
        <li class="">{{ strtoupper(env('APP_NAME')) }}</li>
        <li class="">Settings</li>
        <li class="">
            <a href="{{ route('app.plans.index') }}">Plans Management</a>
        </li>
        <li class="active">Create</li>
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
                        <form action="{{ route('app.plans.store') }}" method="POST">
                            @include('includes.notif')

                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-7">
                                    <div>
                                        <div class="col-md-3">Product</div>
                                        <div class="col-md-7">
                                            <select class="form-control" name="product">
                                                <option value="" disable="" selected="">Select Product (required)</option>
                                                <option {{ old('product') == 'smart' ? 'selected': '' }} value="smart">SMART</option>
                                                <option {{ old('product') == 'smart_bro' ? 'selected': '' }} value="smart_bro">SMART BRO</option>
                                                <option {{ old('product') == 'sun' ? 'selected': '' }} value="sun">SUN</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Plan name</div>
                                        <div class="col-md-7">
                                            <input type="text" name="plan_name" id="" class="form-control" required value="{{ old('plan_name') }}">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">With Device</div>
                                        <div class="col-md-7">
                                            <input type="checkbox" name="with_device" value="1">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">MSF</div>
                                        <div class="col-md-7">
                                            <input type="text" name="msf" id="" class="form-control" required value="{{ old('plan_name') }}">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3">Description</div>
                                        <div class="col-md-7">
                                            <textarea name="description" rows="8" cols="80" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br>

                                    <div>
                                        <div class="col-md-3"></div>
                                        <div class="col-md-7 text-right">
                                            <button class="btn btn-xs btn-primary">Submit <span class='fa fa-plus-circle'></span> </button>
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
