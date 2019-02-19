@extends ('layouts.app')

@section('content')

<div class="main-heading">
    <ol class="breadcrumb">
        <li class="">{{ strtoupper(env('APP_NAME')) }}</li>
        <li class="">Settings</li>
        <li class=""><a href="{{ route('app.statuses.index') }}">Status Management</a></li>
        <li class="">
            {{ $status->status }}
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
                        <form action="{{ route('app.statuses.update', $status->id) }}" method="POST">
                            @include('includes.notif')

                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="row">
                                <div class="col-md-7">

                                    <div>
                                        <div class="col-md-3">Status</div>
                                        <div class="col-md-7">
                                            <input type="text" name="status" id="" class="form-control" value="{{ $status->status }}" required>
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
