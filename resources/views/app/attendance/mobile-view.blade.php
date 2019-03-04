<div class="panel-body">
    {{-- {{ dd(count($attendance['unpresent'])) }} --}}
    @foreach($attendance['unpresent'] as $index => $value)
        {{-- {{ dd($value) }} --}}
    <div class="form-group">
        <div class="panel">
            <div class="panel-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-10">
                            <h5 class="text-light">{{ $value['fname'] . ' ' . $value['lname'] }}</h5>
                            <label class="text-light">Team: {{ $value['team_name']}}</label>
                        </div>
                        <div class="col-sm-2">
                            <input type="hidden" name="mobile_version" value="1">
                            <input type="hidden" name="users[{{ $index }}][status]" class="setStatus" id="user_mobile_status_{{ $index }}"
                            @if(!empty($value['value_btn']))
                                @if($value['value_btn']['class'] == 'btn-info')
                                    value="1"
                                @elseif($value['value_btn']['class'] == 'btn-danger')
                                    value="0"
                                @endif
                            @endif
                            >
                            <button type="button" name="changeStatus" id="changeStatus_{{ $index }}" onclick="changeButtonStatus('desktop', 'changeStatus_{{ $index }}', 'status_{{ $index }}' , 'user[{{ $index }}][activities]', 'user[{{ $index }}][location]', 'user[{{ $index }}][remarks]')"
                            class="btn pull-right
                            @if(!empty($value['value_btn']))
                                {{ $value['value_btn']['class'] }}
                            @else
                                btn-default
                            @endif attendance
                            ">@php
                            if(!empty($value['value_btn'])){
                                echo $value['value_btn']['label'];
                            }else{
                                echo 'Undecided';
                            }
                            @endphp</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer" id="panel-footer-{{ $index }}"
                @if(empty($value['value_btn']))
                    hidden
                @endif
            >
                <div class="form-group form-horizontal">
                    <div class="row">
                        <div class="col-4">
                            <label class="text-light">Activity</label>
                            <input name="users[{{ $index }}][user_id]" id="user_mobile_userid_{{ $index }}" class="form-control" type="hidden" value={{ $value['id'] }}>
                            <select class="form-control text-light" name="users[{{ $index }}][activities]" id="user_mobile_activity_{{ $index }}"
                                @if(empty($value['value_activity']))
                                    {{-- disabled --}}
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
                        </div>
                        <div class="col-4">
                            <label class="text-light">Location</label>
                            <input name="user[{{ $index }}][location]" id="user[{{ $index }}][location]" class="form-control text-light" required type="text"
                                @if(!empty($value['value_location']))
                                    value="{{ $value['value_location'] }}"
                                @else
                                    disabled
                                @endif
                                >
                        </div>
                        <div class="col-4">
                            <label class="text-light">Remarks</label>
                            <input name="user[{{ $index }}][remarks]" id="user[{{ $index }}][remarks]" class="form-control text-light" required type="text"
                                @if(!empty($value['value_remarks']))
                                    value="{{ $value['value_remarks'] }}"
                                @else
                                    disabled
                                @endif
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
