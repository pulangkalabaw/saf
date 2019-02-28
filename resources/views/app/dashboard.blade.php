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

      <!-- ATTENDANCE WIDGET  -->
      <div class="row">
        <div class="{{ !empty(Session::get('_a') || !empty(Session::get('_t'))) ? 'col-md-12' : 'col-md-12'  }}">
          <div class="panel panel-info">
              <div class="panel-heading">
                  <h3 class="panel-title">Attendance</h3>
              </div>
              <div class="panel-body">
                <!-- CLUSTER -->
                <div class="row">
                  @if(!empty($heirarchy) && $heirarchy['clusters'])
                  <div class="col-md-3">
                    <h5>As of <small>{{  now()->format('M d y g:i a') }}</small></h5>
                    <!-- <input type="date" class="form-control"> -->
                  </div>
                  @endif
                  @if(!empty($heirarchy))
                    @foreach($heirarchy['clusters'] as $clus)
                        @if(!empty($clus))
                            <div class="col-md-12">
                              <h4>{{ $clus->cluster_name }} <!--<small>Total Agents: 100</small>--></h4>
                            </div>
                            <!-- TEAMS  -->
                            @if(!empty($clus->teams))
                              @foreach($clus->teams as $team)
                                  @if(!empty($team))
                                      <div class="col-md-4">
                                        <div class="breadcrumb">
                                          <h5>{{ $team->team_name }} <small>Total Agents: {{ $team->total_agents }}</small></h5>
                                          <p>Present: <b class="text-success">{{ $team->attendance['present'] }}</b></p>
                                          <p>Absent: <b class="text-danger">{{ $team->attendance['absent'] }}</b></p>
                                          <p>Unkown: <b class="text-warning">{{ $team->attendance['unkown'] }}</b></p>
                                        </div>
                                      </div>
                                  @endif
                              @endforeach
                            @endif
                            <!-- TEAMS -->
                        @endif
                    @endforeach

                    @if(!empty($heirarchy['myattendance']))
                    <div class="col-md-12">
                        <br>
                        @foreach($heirarchy['myattendance'] as $team)
                        <div class="col-md-4 mt-4 mb-4">
                          <div class="breadcrumb">
                            <h5>{{ $team->team_name }}</h5>
                            <!-- <p>Present: <b class="text-success">30</b></p> -->
                            <!-- <p>Absent: <b class="text-danger">2</b></p> -->
                            <!-- <p>Unkown: <b class="text-warning">1</b></p> -->
                            <p>Your attendance is <span class="{{ ($team->attendance == 'Present') ? 'text-success' : (($team->attendance == 'Absent') ? 'text-danger' : 'text-warning') }}">{{ $team->attendance }}</span> today.</p>
                          </div>
                        </div>
                        @endforeach
                    </div>

                    @endif
                  @endif
                </div>
                <!-- CLUSTER -->
              </div>
          </div>

        </div>
        @if( (empty(Session::get('_t')) && empty(Session::get('_a'))) || !empty(Session::get('_c')) )
        <div class="col-md-3">
          <!-- OVERVIEW -->
          @if(0)
          <div class="panel panel-success">
              <div class="panel-heading">
                <h3 class="panel-title">Overview</h3>
              </div>
              <div class="panel-body">
                <div class="ov-widget">
                  <div class="ov-widget__list">
                    <div class="ov-widget__item ov-widget__item_inc">
                      <div class="ov-widget__value">253</div>
                      <div class="ov-widget__info">
                        <div class="ov-widget__title">Total users</div>
                        <div class="ov-widget__change"><span>15</span><span class="fa fa-level-up"></span><span class="fa fa-level-down"></span><span class="fa fa-bolt"></span><span class="fa fa-thumb-tack"></span></div>
                      </div>
                    </div>
                    <div class="ov-widget__item ov-widget__item_dec">
                      <div class="ov-widget__value">₱ 309,092</div>
                      <div class="ov-widget__info">
                        <div class="ov-widget__title">Earnings</div>
                        <div class="ov-widget__change"><span>₱ 3614</span><span class="fa fa-level-up"></span><span class="fa fa-level-down"></span><span class="fa fa-bolt"></span><span class="fa fa-thumb-tack"></span></div>
                      </div>
                    </div>
                    <div class="ov-widget__item ov-widget__item_warn">
                      <div class="ov-widget__value">945</div>
                      <div class="ov-widget__info">
                        <div class="ov-widget__title">New Applications</div>
                        <div class="ov-widget__change"><span>12</span><span class="fa fa-level-up"></span><span class="fa fa-level-down"></span><span class="fa fa-bolt"></span><span class="fa fa-thumb-tack"></span></div>
                      </div>
                    </div>
                    <div class="ov-widget__item ov-widget__item_tack">
                      <div class="ov-widget__value">320</div>
                      <div class="ov-widget__info">
                        <div class="ov-widget__title">Activated</div>
                        <div class="ov-widget__change"><span>21</span><span class="fa fa-level-up"></span><span class="fa fa-level-down"></span><span class="fa fa-bolt"></span><span class="fa fa-thumb-tack"></span></div>
                      </div>
                    </div>
                    <!-- <div class="ov-widget__bar"><span>Cluster Target %</span>
                      <div class="progress">
                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 85%;"></div>
                      </div>
                    </div> -->
                  </div>
                </div>
              </div>
            </div>
            @endif
            <!-- OVERVIEW -->
        </div>
        @endif
      </div>
      <!-- ATTENDANCE WIDGET -->

        <div class="row">
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
        </div>
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
