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
      @if(!empty($heirarchy))
      <div class="row">

        <!-- FOR AGENT ONLY LIST OF ATTENDANCE PER MONTH/CUTOFF  -->
        @if( in_array('agent',checkPosition(auth()->user(), ['agent'], true)) && (count(checkPosition(auth()->user(), ['agent'], true)) == 1) )
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">List of My Attendance</h3>
                </div>
                <div class="panel-body">
                    @if(isset($myattendance))
                        <h5><b>For this month of {{ now()->format('F') }}</b></h5>
                        @foreach($myattendance as $myatt)
                            <div class="col-md-3">
                                <div class="breadcrumb">
                                    <p><strong>{{ Carbon\Carbon::parse($myatt->created_at)->format('M d Y') }}</strong></p>
                                    <p class="{{ ($myatt->status == 1) ? 'text-success' : ( ($myatt->status == 0) ? 'text-danger' : 'text-warning' ) }}"><b>{{ ($myatt->status == 1) ? 'Present' : ( ($myatt->status == 0) ? 'Absent' : 'Unkown' ) }}</b></p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        @endif
        <!-- END FOR AGENT ONLY LIST OF ATTENDANCE PER MONTH/CUTOFF  -->


        <!-- ATTENDANCE PART  -->
        <div class="{{ (  in_array('agent',checkPosition(auth()->user(), ['agent'], true)) && (count(checkPosition(auth()->user(), ['agent'], true)) == 1)  ) ? 'col-md-4' : 'col-md-12'  }}">
          <div class="panel panel-info">
              <div class="panel-heading">
                  <h3 class="panel-title">Today's Attendance</h3>
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
                        <div class="col-md-12 mt-4 mb-4">
                          <div class="breadcrumb">
                            <h5>{{ $team->team_name }}</h5>
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
        <!-- END OF ATTENDANCE PART  -->

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
      @endif
      <!-- ATTENDANCE WIDGET -->
    </div>
</div>

@endsection

@section ('scripts')
{{-- @include('partials.scripts._datatables') --}}

@endsection
