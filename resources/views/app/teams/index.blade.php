@extends ('layouts.app')

@section('content')

<div class="main-heading">
    <ol class="breadcrumb">
        <li class="">{{ strtoupper(env('APP_NAME')) }}</li>
        <li class="">Users</li>
        <li class="active">Teams</li>
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
                                    Teams
                                </h3>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="{{ route('app.teams.create') }}" class="btn btn-xs btn-default">
                                    <span class='fa fa-plus-circle'></span> 
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
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
                                        <input type="search" name="search_string" id="" value="{{ !empty(request()->get('search_string')) ? request()->get('search_string') : '' }}" class="form-control" placeholder="Search for Team name, TL and Agent">
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
                                    <th>Cluster name</th>
                                    <th>
                                        Team name
                                        <a data-toggle="tooltip" title="Sort" href="{{ request()->fullUrlWithQuery(['sort_in' => 'team-name', 'sort_by' => (Request::get('sort_by') == "asc") ? 'desc' : 'asc']) }}">
                                            <span class='fa fa-sort'></span> 
                                        </a>
                                    </th>
                                    <th>
                                        Team Leader
                                        <a data-toggle="tooltip" title="Sort" href="{{ request()->fullUrlWithQuery(['sort_in' => 'team-leader', 'sort_by' => (Request::get('sort_by') == "asc") ? 'desc' : 'asc']) }}">
                                            <span class='fa fa-sort'></span> 
                                        </a>
                                    </th>
                                    <th>Agent</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($teams as $team)
                                <tr>
                                    <td>
                                        @if (count($team->getCluster($team->team_id)) != 0)
                                        <a href="{{ route('app.clusters.show', $team->getCluster($team->team_id)[0]['cluster_id']) }}">
                                            {{  $team->getCluster($team->team_id)[0]['cluster_name'] }}
                                        </a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>{{ $team->team_name }}</td>
                                    <td>{{ $team->tl_fname . ' ' . $team->tl_lname }}</td>
                                    <td>
                                        {{ $team->getAgentCode->fname . " " . $team->getAgentCode->lname }}
                                        {{ base64_decode($team->getAgentCode->role) == "agent" ? ' (' . $team->getAgentCode->agent_code .')' : '' }}
                                    </td>
                                    <td>
                                        <a data-toggle="tooltip" title="View Team" href="{{ route('app.teams.show', $team->team_id) }}" class="btn btn-warning btn-xs"><span class='fa fa-eye'></span></a>
                                        <a data-toggle="tooltip" title="Edit Team" href="{{ route('app.teams.edit', $team->team_id) }}" class="btn btn-success btn-xs"><span class='fa fa-edit'></span></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <div class="row">
                            <div class="col-md-10">
                                {{ $teams->appends(request()->input())->links() }}
                            </div>
                            <div class="col-md-2 text-right">
                                Total <b>{{ $teams_total }}</b> result(s)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection