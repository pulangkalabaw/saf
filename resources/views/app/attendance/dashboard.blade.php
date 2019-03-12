@extends ('layouts.app')

@section ('styles')

@endsection

<styles>
    <link href="{{ asset('assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}" rel="stylesheet">
</styles>

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
        @if( count(checkPosition(auth()->user(), ['tl','agent'], true)) )
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">List of My Attendance</h3>
                </div>
                <div class="panel-body">
                    @if(isset($myattendance))
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                <!-- <div class="col-md-4 col-xs-3"> -->
                                    <a href="{{ route('app.attendanceDashboard', ['date' => $myattendance['prev']]) }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> {{ Carbon\Carbon::parse($myattendance['prev'])->format('M Y') }}</a>
                                </div>
                                <!-- <div class="col-md-4 col-xs-6 text-center">

                                    <form action="{{ route('app.attendanceDashboard') }}" method="get">
                                        <div class="input-group">
                                            <input type="date" class="form-control input-sm" name="exactdate" placeholder="Date">
                                            <span class="input-group-btn">
                                                <button class="btn btn-info btn-sm" type="submit">
                                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                </button>
                                            </span>
                                        </div>
                                    </form>

                                </div> -->
                                <!-- <div class="col-md-4 col-xs-3 text-right"> -->
                                <div class="col-md-6 col-xs-6 text-right">
                                    @if(  today() >= Carbon\Carbon::parse($myattendance['next']) )
                                    <a href="{{ route('app.attendanceDashboard', ['date' => $myattendance['next']]) }}" class="btn btn-default btn-sm">{{ Carbon\Carbon::parse($myattendance['next'])->format('M Y') }} <i class="fa fa-arrow-right"></i></a>
                                    @endif
                                </div>
                            </div>

                            <div class="row text-center">
                                <div class="col-md-4 col-xs-1"></div>
                                <div class="col-md-4 col-xs-10 text-center">
<br>
                                    <form action="{{ route('app.attendanceDashboard') }}" method="get">
                                        <div class="input-group">
                                            <!-- <input type="date" class="form-control input-sm" name="exactdate" placeholder="Date"> -->
                                            <!-- <span class="input-group-btn">
                                                <button class="btn btn-info" type="submit">
                                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                </button>
                                            </span> -->
                                            <div class="input-group date">
                                                <input class="form-control input-sm" type="text" name="exactdate" value="{{ $myattendance['curr']->format('m/d/Y') }}">
                                                <div class="input-group-addon">
                                                  <div class="fa fa-calendar"></div>
                                                </div>
                                            </div>

                                        </div>
                                    </form>

                                </div>
                                <div class="col-md-4 col-xs-1"></div>

                            </div>


                            <h5 class="text-center"><b>{{ $myattendance['currmsg'] }}</b></h5>
                            <br>
                        </div>
                        @if(!$myattendance['attendance']->isEmpty())
                            @foreach($myattendance['attendance'] as $myatt)
                                <div class="col-md-3">
                                    <div class="breadcrumb">
                                        <p><strong>{{ Carbon\Carbon::parse($myatt->created_at)->format('M d Y') }}</strong></p>
                                        <p class="{{ ($myatt->status == 1) ? 'text-success' : ( ($myatt->status == 0) ? 'text-danger' : 'text-warning' ) }}"><b>{{ ($myatt->status == 1) ? 'Present' : ( ($myatt->status == 0) ? 'Absent' : 'Unkown' ) }}</b></p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-md-12">
                                <div class="text-center">No data found</div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @endif
        <!-- END FOR AGENT ONLY LIST OF ATTENDANCE PER MONTH/CUTOFF  -->


        <!-- ATTENDANCE PART  -->
        <div class="{{ (  count(checkPosition(auth()->user(), ['tl','agent'], true))  ) ? 'col-md-4' : 'col-md-12'  }}">
          <div class="panel panel-info">
              <div class="panel-heading">
                  <!-- if the logged in user is an agent only  -->
                  @if( count(checkPosition(auth()->user(), ['agent'], true)) )
                      <h3 class="panel-title">My Today's Attendance</h3>
                  @else
                  <!-- if the logged in user is cl, admin, encoder -->
                      <h3 class="panel-title">Today's Team Attendance</h3>
                  @endif
              </div>
              <div class="panel-body">
                <!-- CLUSTER -->
                <div class="row">
                  @if(!empty($heirarchy) && $heirarchy['clusters'])
                  <div class="{{ (  count(checkPosition(auth()->user(), ['tl','agent'], true))  ) ? 'col-md-12' : 'col-md-3'  }}">
                    <h5>As of {{  now()->format('F d Y') }}</h5>
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
                                      <div class="{{ (  count(checkPosition(auth()->user(), ['tl','agent'], true))  ) ? 'col-md-12' : 'col-md-4'  }}">
                                        <div class="breadcrumb">
                                          <h5>{{ $team->team_name }} <small>Total Agents: {{ $team->total_agents }}</small></h5>
                                          @if(isset($team->totaltl))
                                                  <p>
                                                      TL Attendance: <b class="{{ ($team->totaltl == $team->tlattendance) ? 'text-success' : 'text-danger' }}">{{ $team->tlattendance }}</b>
                                                      <small class="text-muted">(Total TL: {{ $team->totaltl }})</small>
                                                  </p>
                                          @endif
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
<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>

<script src="{{ asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-tabdrop/bootstrap-tabdrop.min.js') }}"></script>
<script>
$(document).ready(function(){
    $('.date').datepicker({
        endDate: '+0d',
    }).on('changeDate', function(){
        window.location = '{{ url('app/attendancedashboard') . '?exactdate=' }}' + $("input[name='exactdate']").val();
    });
	$('.input-daterange').datepicker();
	$('.datepicker-embed').datepicker();
	$('.timepicker input').timepicker({showMeridian: false, showSeconds: true});
});

</script>
