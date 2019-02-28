@extends ('layouts.app')
@section('content')

    <div class="main-heading">
        <ol class="breadcrumb">
            <li class="">{{ strtoupper(env('APP_NAME')) }}</li>
            <li class="">Attendance</li>
            <li class="active">User Attendance</li>
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
                                        User Attendance
                                    </h3>
                                </div>
                                <div class="col-md-4 text-right">
                                    <a href="" class="btn btn-xs btn-default">
                                        <span class='fa fa-plus-circle'></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{-- {{dd(empty($attendance['unpresent']))}} --}}
                        @if(empty($attendance['unpresent']))
                        <div class="panel-body">
                            <div class="form-group text-center">
                                <label><h2><i class="fa fa-close text-danger"></i> Sorry you don't have any teams or clusters!</h2></label>
                            </div>
                            <div class="form-group text-center">
                                <label>Please make sure that you are a team leader or a cluster</label>
                            </div>
                        </div>
                        @else
                        <div class="panel-body">
                            <div class="form-group">
                                @if(!empty($clusters))
                                    @foreach(array_reverse($clusters) as $cluster)
                                        {{-- {{ dd(url('attendance') . '?team_id=' . $cluster['id']) }} --}}
                                            <button type="button" onclick="window.location='{{ url('attendance') . '?cl_id=' . $cluster['id'] }}'" class="btn btn-primary" id="mutliplebtn" {{ $selected_cluster == $cluster['id'] ? 'disabled' : '' }}>{{ $cluster['name'] }}</button>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="panel-body" style="display:none;" id="showpanel">
                            <div class="form-group text-center">
                                <label><h2><i class="fa fa-close text-danger"></i> Sorry you cannot access user attendance</h2></label>
                            </div>
                            <div class="form-group text-center">
                                <label>You can only use it around 5:00 AM to 10:30 AM</label>
                            </div>
                        </div>
                        <div class="panel-body" id="hidepanel">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="active" role="presentation"><a href="#unpresent" aria-controls="unpresent" role="tab" data-toggle="tab">Roll call <span class="label">12</span> </a></li>
                                <li role="presentation"><a href="#present" aria-controls="present" role="tab" data-toggle="tab">Present</a></li>
                                <li role="presentation"><a href="#absent" aria-controls="absent" role="tab" data-toggle="tab">Absents</a></li>
                            </ul>
                            <div class="tab-content" >
                                <div class="tab-pane active" id="unpresent" role="tabpanel">
    								@include('includes.notif')
                                    <form enctype="multipart/form-data" action="{{route('attendance.store')}}" method="POST">
                                        {{ csrf_field() }}
                                        {{-- <input type="hidden" name="cl_id" value="{{  }}"> --}}
                                        <div class="panel-body">
                                            <button class="btn btn-primary pull-right">Submit</button>
                                        </div>
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="table table-hovered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Status</th>
                                                            <th>Name</th>
                                                            <th>Activities</th>
                                                            <th>Location</th>
                                                            <th>Remarks</th>
                                                            <th class="text-center">Team</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($attendance['unpresent'] as $index => $value)
                                                            <tr>
                                                                <td class="text-center">
                                                                    <input type="hidden" hidden name="user[{{ $index }}][status]" class="setStatus" id="status_{{ $index }}"
                                                                    @if(!empty($value['value_btn']))
                                                                        @if($value['value_btn']['class'] == 'btn-info')
                                                                            value="1"
                                                                        @elseif($value['value_btn']['class'] == 'btn-danger')
                                                                            value="0"
                                                                        @endif
                                                                    @endif
                                                                    >
                                                                    <button type="button" name="changeStatus" id="changeStatus_{{ $index }}" onclick="changeButtonStatus('changeStatus_{{ $index }}', 'status_{{ $index }}' , 'user[{{ $index }}][activities]', 'user[{{ $index }}][location]', 'user[{{ $index }}][remarks]')"
                                                                    class="btn
                                                                    @if(!empty($value['value_btn']))
                                                                        {{ $value['value_btn']['class'] }}
                                                                    @else
                                                                        btn-default
                                                                    @endif
                                                                    ">@php
                                                                    if(!empty($value['value_btn'])){
                                                                        echo $value['value_btn']['label'];
                                                                    }else{
                                                                        echo 'Undecided';
                                                                    }
                                                                    @endphp</button>
                                                                </td>
                                                                <td class="text-bg-light"><span class="margin-vertical {{ !empty($value['tl']) ? 'text-info' : '' }}">{{ $value['fname'] . ' ' . $value['lname']}}</span></td>
                                                                {{-- <td>{{  }}</td> --}}
                                                                <td>
                                                                    <input name="user[{{ $index }}][user_id]" class="form-control" type="hidden" value={{ $value['id'] }}>
                                                                    <select class="form-control input-gray" name="user[{{ $index }}][activities]"
                                                                        @if(empty($value['value_activity']))
                                                                            disabled
                                                                        @endif
                                                                    >
                                                                        <option
                                                                        @if(!empty($value['value_activity']))
                                                                            @if($value['value_activity'] == 'Blitz')
                                                                                selected
                                                                            @endif
                                                                        @endif
                                                                        >Blitz</option>
                                                                        <option
                                                                        @if(!empty($value['value_activity']))
                                                                            @if($value['value_activity'] == 'Saturation')
                                                                                selected
                                                                            @endif
                                                                        @endif
                                                                        >Saturation</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <input name="user[{{ $index }}][location]" id="user[{{ $index }}][location]" class="form-control input-gray" required type="text"
                                                                        @if(!empty($value['value_location']))
                                                                            value="{{ $value['value_location'] }}"
                                                                        @else
                                                                            disabled
                                                                        @endif
                                                                        >
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <input name="user[{{ $index }}][remarks]" id="user[{{ $index }}][remarks]" class="form-control input-gray" required type="text"
                                                                    @if(!empty($value['value_remarks']))
                                                                        value="{{ $value['value_remarks'] }}"
                                                                    @else
                                                                        disabled
                                                                    @endif
                                                                    >
                                                                </td>

                                                                <td class="text-bg-light text-center">{{ $value['team_name'] }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <button class="btn btn-primary pull-right">Submit</button>
                                            </div>
                                            <div class="form-group">
                                                <h4>Attach Image: </h4>
                                                <input type="file" name="empImg">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="present" role="tabpanel">
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-hovered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Activities</th>
                                                        <th>Location</th>
                                                        <th>Remarks</th>
                                                        <th class="text-center">Team</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($attendance['present'] as $present)
                                                    <tr>
                                                        <td class="text-bg-light">{{ $present['users']['fname'] . ' ' . $present['users']['lname'] }}</td>
                                                        <td class="text-bg-light">{{ $present['activities'] }}</td>
                                                        <td class="text-bg-light">{{ $present['location'] }}</td>
                                                        <td class="text-bg-light">{{ $present['remarks'] }}</td>
                                                        <td class="text-bg-light text-center">{{ $present['team_name'] }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="absent" role="tabpanel">
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-hovered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Activities</th>
                                                        <th>Location</th>
                                                        <th>Remarks</th>
                                                        <th class="text-center">Team</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($attendance['absent'] as $absent)
                                                        <tr>
                                                            <td class="text-bg-light">{{ $absent['users']['fname'] . ' ' . $absent['users']['lname'] }}</td>
                                                            <td class="text-bg-light">{{ $absent['activities'] }}</td>
                                                            <td class="text-bg-light">{{ $absent['location'] }}</td>
                                                            <td class="text-bg-light">{{ $absent['remarks'] }}</td>
                                                            <td class="text-bg-light text-center">{{ $absent['team_name'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
$toggleButton = 0
function changeButtonStatus(buttonName = "", status, activities, location, remarks){
    // alert(buttonName);
    $buttonText = $('#' + buttonName).text();
    if($buttonText == 'Undecided'){
        $('#' + buttonName).removeClass('btn-default').addClass('btn-info');
        $('#' + buttonName).text('Present');
        $('#' + status).val(1);
        $('select[name="' + activities + '"]').prop("disabled", false);
        $('input[name="' + location + '"]').prop("disabled", false);
        $('input[name="' + remarks + '"]').prop("disabled", false);
    } else if($buttonText == 'Present'){
        $('#' + buttonName).removeClass('btn-info').addClass('btn-danger');
        $('#' + buttonName).text('Absent');
        $('#' + status).val(0);
        $('select[name="' + activities + '"]').prop("disabled", false);
        $('input[name="' + location + '"]').prop("disabled", false);
        $('input[name="' + remarks + '"]').prop("disabled", false);
    } else if($buttonText == 'Absent'){
        $('#' + buttonName).removeClass('btn-danger').addClass('btn-default');
        $('#' + buttonName).text('Undecided');
        $('#' + status).val(null);
        $('select[name="' + activities + '"]').prop("disabled", true);
        $('input[name="' + location + '"]').prop("disabled", true);
        $('input[name="' + location + '"]').val(null);
        $('input[name="' + remarks + '"]').prop("disabled", true);
        $('input[name="' + remarks + '"]').val(null);
    }
}

function toggle(source) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source) checkboxes[i].checked = source.checked;
    }
}

function agentAttendance(){
    console.log($('#chb1').val() == "" && $('#ch6').val() == "");
    if($('#chb1').val() != "" && $('#ch6').val() != ""){
        $('#present').removeAttr('disabled');
        $('#absent').removeAttr('disabled');
    }else{
        $('#present').attr('disabled');
        $('#absent').attr('disabled');
    }
}
var today = new Date();
var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
var timein = 10 + ":" + 30 + ":" + 00;

// alert(time + ">=" +  timein + " = " + time >= timein);
// alert(time);
if(time >= timein){
    $('#hidepanel').hide();
    $('#showpanel').show();
    $('#mutliplebtn').attr('disabled', true);
}
</script>
@endsection
