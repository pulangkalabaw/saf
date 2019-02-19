@extends ('layouts.app')

@section('content')

<div class="main-heading">
    <ol class="breadcrumb">
        <li class="">{{ strtoupper(env('APP_NAME')) }}</li>
        <li class="">Settings</li>
        <li class="">
            <a href="{{ route('app.statuses.index') }}">Status Management</a>
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
                                    STATUS MANAGEMENT
                                </h3>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="{{ route('app.statuses.index') }}" class="btn btn-xs btn-default">
                                    <span class='fa fa-th-list'></span> 
                                </a>
                            </div>
                        </div>
                    </div>


                    <div class="panel-body">
                        <form action="{{ route('app.statuses.store') }}" method="POST">
                            @include('includes.notif')

                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-7">

                                    <div>
                                        <div class="col-md-3">Status</div>
                                        <div class="col-md-7">
                                            <input type="text" name="status" id="" class="form-control" required>
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
