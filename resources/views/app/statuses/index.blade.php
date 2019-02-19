@extends ('layouts.app')

@section('content')

<div class="main-heading">
    <ol class="breadcrumb">
        <li class="">{{ strtoupper(env('APP_NAME')) }}</li>
        <li class="">Settings</li>
        <li class="active">Status Management</li>
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
                                <a href="{{ route('app.statuses.create') }}" class="btn btn-xs btn-default">
                                    <span class='fa fa-plus-circle'></span> 
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            @include('includes.notif')
                            
                            <div class="col-md-4 col-xs-4">
                                <div class="form-inline">
                                    <div class="form-group">
                                        <label>Number of rows: </label>
                                        <select name="" id="" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value)" class="form-control">
                                            <option {{ !empty(request()->get('show') && request()->get('show') == 10) ? 'selected' : ''  }} 
                                                value="{{ request()->fullUrlWithQuery(['show' => '10']) }}">10</option>
                                            <option {{ !empty(request()->get('show') && request()->get('show') == 25) ? 'selected' : ''  }} 
                                                value="{{ request()->fullUrlWithQuery(['show' => '25']) }}">25</option>
                                            <option {{ !empty(request()->get('show') && request()->get('show') == 50) ? 'selected' : ''  }} 
                                                value="{{ request()->fullUrlWithQuery(['show' => '50']) }}">50</option>
                                            <option {{ !empty(request()->get('show') && request()->get('show') == 100) ? 'selected' : ''  }} 
                                                value="{{ request()->fullUrlWithQuery(['show' => '100']) }}">100</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                            <div class="col-md-5 col-xs-5">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group">
                                        <input type="search" name="search_string" id="" value="{{ !empty(request()->get('search_string')) ? request()->get('search_string') : '' }}" class="form-control" placeholder="Search for Status">
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary"><span class='fa fa-search'></span> </button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="clearfix"></div><br>

                        <table class="table table-hovered table-striped">
                            <thead>
                                <tr>
                                    <th width="80%">
                                        Status
                                        <a data-toggle="tooltip" title="Sort" href="{{ request()->fullUrlWithQuery(['sort_in' => 'status', 'sort_by' => (Request::get('sort_by') == "asc") ? 'desc' : 'asc']) }}">
                                            <span class='fa fa-sort'></span> 
                                        </a>
                                    </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($statuses as $status)
                                <tr>
                                    <td>{{ $status->status }}</td>
                                    <td>
                                        <a data-toggle="tooltip" title="Edit Status" href="{{ route('app.statuses.edit', $status->id) }}" class="btn btn-success btn-xs"><span class='fa fa-edit'></span></a>
                                        @if (base64_decode($status->role) != "administrator" || Auth::status()->id != $status->id)
                                        <button data-toggle="tooltip" title="Delete Status" class="btn btn-xs btn-danger" for="submit-form" tabindex="0" form="{{ $status->id }}myform"><span class='fa fa-trash'></span>
                                            <form class="delete" method="POST" action="{{ route('app.statuses.destroy', $status->id) }}" id="{{ $status->id }}myform">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                            </form>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <div class="row">
                            <div class="col-md-10">
                                {{ $statuses->appends(request()->input())->links() }}
                            </div>
                            <div class="col-md-2 text-right">
                                Total <b>{{ $statuses_total }}</b> result(s)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section ('scripts')
<script>
    $(document).ready(function() {
        $(".delete").on("submit", function(){
            return confirm("Are you sure?");
        });
    });

    function submitSort() {   
        document.sorting.submit(); 
    } 
</script>
@endsection