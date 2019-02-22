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
                        <div class="panel-body">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="active" role="presentation"><a href="#unpresent" aria-controls="unpresent" role="tab" data-toggle="tab">All</a></li>
                                <li role="presentation"><a href="#present" aria-controls="present" role="tab" data-toggle="tab">Present</a></li>
                                <li role="presentation"><a href="#absent" aria-controls="absent" role="tab" data-toggle="tab">Absents</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="unpresent" role="tabpanel">
                                    <div class="panel-body">
                                        <form action="{{route('attendance.store')}}" method="POST">
                                            {{ csrf_field() }}
                                            <div class="table-responsive">
                                                <table class="table table-hovered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th><input id="ch6" type="checkbox" onclick="toggle(this);"> Select all</th>
                                                            <th>{{ Auth::user()->role == base64_encode('tl') ? 'Agents Name': (Auth::user()->role == base64_encode('cl') ? 'Teams Name' : '') }}</th>
                                                            <th>Activities</th>
                                                            <th>Location</th>
                                                            <th>Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($attendance['unpresent'] as $index => $value)
                                                        <tr>
                                                            <td class="text-center">
                                                                <div class="form-group">
                                                                    <div class="col-sm-10">
                                                                        <div class="checkbox">
                                                                            <input id="chb1" type="checkbox" name="user[{{ $index }}][selected_row]">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="text-bg-light"><span class="margin-vertical">{{ $value['fname'] . ' ' . $value['lname'] }}</span></td>
                                                            <td>
                                                                <input name="user[{{ $index }}][user_id]" class="form-control" type="hidden" value={{ $value['id'] }}>
                                                                <select class="form-control input-gray" name="user[{{ $index }}][activities]">
                                                                    <option>Blitz</option>
                                                                    <option>Saturation</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <input name="user[{{ $index }}][location]" class="form-control input-gray" type="text">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input name="user[{{ $index }}][remarks]" class="form-control input-gray" type="text">
                                                                <input type="hidden" name="user[{{ $index }}][status]" class="setStatus" id="status">
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        <br>
                                            <div class="panel-body">
                                                {{-- <input type="hidden" id="status"> --}}
                                                <button class="btn btn-primary" onclick="$('.setStatus').val('1')">Present</button>
                                                <button class="btn btn-danger" onclick="$('.setStatus').val('0') ">Absent</button>
                                            </div>
                                        </form>
                                        <div class="row">
                                            <div class="col-md-10">
                                            </div>
                                            <div class="col-md-2 text-right">
                                            </div>
                                        </div>
                                    </div>
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
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($attendance['present'] as $present)
                                                    <tr>
                                                        <td>{{ $present['users']['fname'] . ' ' . $present['users']['lname'] }}</td>
                                                        <td>{{ $present['activities'] }}</td>
                                                        <td>{{ $present['location'] }}</td>
                                                        <td>{{ $present['remarks'] }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-10">
                                            </div>
                                            <div class="col-md-2 text-right">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="absent" role="tabpanel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-5 col-xs-5">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div><br>
                                        <table class="table table-hovered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Activities</th>
                                                    <th>Location</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($attendance['absent'] as $absent)
                                                <tr>
                                                    <td>{{ $absent['users']['fname'] . ' ' . $absent['users']['lname'] }}</td>
                                                    <td>{{ $absent['activities'] }}</td>
                                                    <td>{{ $absent['location'] }}</td>
                                                    <td>{{ $absent['remarks'] }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-10">
                                            </div>
                                            <div class="col-md-2 text-right">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
@endsection

<script>
function toggle(source) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
        checkboxes[i].checked = source.checked;
    }
}
</script>
                                                                                                                                                               
