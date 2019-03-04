@extends ('layouts.app')

@section ('styles')

@endsection

@section('content')

<div class="main-heading">
    <ol class="breadcrumb">
        <li class="">{{ strtoupper(env('APP_NAME')) }}</li>
        <li class="">Users</li>
        <li class="active">Dashboard</li>
    </ol>
</div>
<!-- {{ base64_encode('administrator') }} -->
<div class="container-fluid half-padding">
    <div class="template template__blank">

        <!-- PAT WIDGET  -->
        @if( (!empty(checkPosition(auth()->user(), ['tl','cl'])) || accessControl(['administrator','user'])) && isset($heirarchy) )
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Applications</h3>
                    </div>
                    <div class="panel-body">
                        <h5>As of {{ now()->format('M d y g:i a') }} - {{ now()->format('M d y g:i a') }}</h5>

                        <!-- PUT FOREACH TEAM  -->
                        @foreach($heirarchy['clusters'] as $clus)
                            @if($clus) <!-- FOR CATCHING NULL ERRORS -->
                            <div class="container">
                                <div class="col-md-12">
                                    <h4>{{ $clus->cluster_name }}</h4>
                                </div>
                                <!-- PUT FOREACH TEAM  -->
                                @foreach($clus->teams as $team)
                                    @if($team)
                                    <div class="col-md-2">
                                        <div class="breadcrumb">
                                            <h5><b>{{ $team->team_name }}</b></h5>
                                            <p>New: <b>{{ (float)$team->getallsafthiscutoff['new'] }}</b></p>
                                            <p>Activated: <b class="text-info">{{ (float)$team->getallsafthiscutoff['activated'] }}</b></p>
                                            <p>Paid: <b class="text-primary">{{ (float)$team->getallsafthiscutoff['paid'] }}</b></p>
                                            <p>Target %: <b class="text-success">{{ (float)$team->pat }}%</b></p>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                                <!-- PUT ENDFOREACH TEAM -->
                            </div>
                            @endif
                        @endforeach
                        <!-- PUT ENDFOREACH CLUSTER  -->
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- PAT WIDGET -->

        <!-- START OF GRAPHS  -->
        <!-- <div class="row">
            <div class="col-md-6">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">Application counter (Cluster)</h3>
                    </div>
                    <div class="panel-body">
                        <canvas id="application_counter_by_cluster"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Application counter (Team)</h3>
                    </div>
                    <div class="panel-body">
                        <canvas id="application_counter_by_teams"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">Status application</h3>
                    </div>
                    <div class="panel-body">
                        <canvas id="no_of_status_that_used"></canvas>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- END OF GRAPHS  -->
    </div>
</div>

@endsection

@section ('scripts')
{{-- @include('partials.scripts._datatables') --}}

<script src="{{ asset('assets/js/Chart.bundle.js') }}"></script>
<script src="{{ asset('assets/js/Chart.js') }}"></script>

<script>

// Dashboard Widgets (Cluster)
var acbc_ctx = document.getElementById("application_counter_by_cluster").getContext('2d');
var acbc_labels = {!! $application_counter_by_cluster->map(function ($r) {return " (".$r['total_count']. ") " .$r['getClusterName']['cluster_name']; }) !!};
var acbc_data = {!! $application_counter_by_cluster->map(function ($r) {return $r['total_count']; }) !!};
var acbc_myChart = new Chart(acbc_ctx, {
    type: 'horizontalBar',
    data: {
        labels: acbc_labels,
        datasets: [
            {label: 'Application counter', data: acbc_data, backgroundColor:'rgba(255, 99, 132, 0.2)', borderColor: '#ed4949', borderWidth: 3 }
        ]
    },
    options: {
        scales: {
            xAxes: [{
                ticks: {
                    beginAtZero:true,
                }
            }],
            yAxes: [{
                ticks: {
                    maxRotation: 30,
                    minRotation: 30
                }
            }] },
            responsive: true,
        },
    });

    // Dashboard Widgets (Team)
    var acbt_ctx = document.getElementById("application_counter_by_teams").getContext('2d');
    var acbt_labels = {!! $application_counter_by_teams->map(function ($r) {return "(".$r['total_count']. ") " .$r['getTeam']['team_name']; }) !!};
    var acbt_data = {!! $application_counter_by_teams->map(function ($r) {return $r['total_count']; }) !!};
    console.log(acbt_data)
    var acbt_myChart = new Chart(acbt_ctx, {
        type: 'horizontalBar',
        data: {
            labels: acbt_labels,
            datasets: [
                {label: 'Application counter', data: acbt_data, backgroundColor:'rgba(54, 162, 235, 0.2)', borderColor: 'rgba(54, 162, 235, 1)', borderWidth: 3 }
            ]
        },
        options: {
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero:true,
                    }
                }],
                yAxes: [{
                    ticks: {
                        maxRotation: 30,
                        minRotation: 30
                    }
                }]
            },
            responsive: true,
        },
    });

    {{-- Status Application --}}
    var nostu_ctx = document.getElementById("no_of_status_that_used").getContext('2d');
    var nostu_labels = {!! $no_of_status_that_used->map(function ($r) {return "(".$r['total_count']. ") " .$r['status']; }) !!};
    var nostu_data = {!! $no_of_status_that_used->map(function ($r) {return $r['total_count']; }) !!};
    var nostu_myChart = new Chart(nostu_ctx, {
        type: 'bar',
        data: {
            labels: nostu_labels,
            datasets: [
                {label: 'Status used', data: nostu_data, backgroundColor:'rgba(255, 206, 86, 0.2)', borderColor: 'rgba(255, 206, 86, 1)', borderWidth: 3 }
            ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true,
                    }
                }],
                xAxes: [{
                    ticks: {
                        maxRotation: 30,
                        minRotation: 30
                    }
                }]
            },
            responsive: true,
        }
    });



</script>

@endsection
