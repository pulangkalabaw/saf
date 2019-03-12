<div class="panel-body padding-left-0 padding-right-0">
    {{-- {{ dd(count($attendance['unpresent'])) }} --}}
    <input name="selected_date" type="hidden" value="{{ $date['selected'] }}">
    @foreach($attendance['unpresent'] as $index => $value)
        {{-- {{ dd($value) }} --}}
    <div class="form-group">
        <div class="panel bg-default">
            <div class="panel-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-10">
                            <h5 class="text-light"><span class="margin-vertical {{ !empty($value['tl']) ? 'text-info' : '' }}">{{ $value['fname'] . ' ' . $value['lname']}}</span></h5>
                            <label class="text-light">Team: {{ $value['team_name']}}</label>
                        </div>
                        <div class="col-sm-2">
                            <input type="hidden" name="mobile_version" value="1">
                            <input type="hidden" name="users[{{ $index }}][status]" class="setStatus" id="user_mobile_status_{{ $value['id'] }}"
                            @if(!empty($value['value_btn']))
                                @if($value['value_btn']['class'] == 'btn-info')
                                    value="1"
                                @elseif($value['value_btn']['class'] == 'btn-danger')
                                    value="0"
                                @endif
                            @endif
                            >
                            <button type="button" name="changeStatus" id="changeMobileStatus_{{ $value['id'] }}" onclick="changeButtonStatus('mobile', 'changeMobileStatus_{{ $value['id'] }}', 'status_{{ $index }}' , 'user[{{ $index }}][activities]', 'user[{{ $index }}][location]', 'user[{{ $index }}][remarks]', '{{ $value['id'] }}');
                            @if(!empty($value['value_btn']))
                                showMobileClRemark('{{ $value['id'] }}', '{{ $value['value_location'] }}', '{{ $value['value_remarks'] }}', '{{ $value['value_activity'] }}', '{{ $value['value_btn']['label'] }}');
                            @endif
                            @if(!empty(request()->date))
                                @if(request()->date != Carbon\Carbon::now()->toDateString())
                                    showRemark('{{ $value['id'] }}', 'mobile');
                                @endif
                            @endif
                            "
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
            <div class="panel-footer bg-default" id="panel-footer-{{ $value['id'] }}"
                @if(empty($value['value_btn']))
                    hidden
                @endif
            >
                <div class="form-group form-horizontal">
                    <div class="row">
                        <div class="col-4 margin-bottom-20">
                            <label class="text-light">Activity</label>
                            <input name="users[{{ $index }}][user_id]" id="user_mobile_userid_{{ $index }}" class="form-control" type="hidden" value={{ $value['id'] }}>
                            <select class="form-control text-light input-gray" name="users[{{ $index }}][activities]" id="user_mobile_activity_{{ $value['id'] }}"
                                @if(empty($value['value_activity']))
                                    disabled
                                @else
                                    onchange="showMobileClRemark('{{ $value['id'] }}', '{{ $value['value_location'] }}', '{{ $value['value_remarks'] }}', '{{ $value['value_activity'] }}', '{{ $value['value_btn']['label'] }}')"
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
                        <div class="col-4 margin-bottom-20">
                            <label class="text-light">Location</label>
                            <input name="users[{{ $index }}][location]" id="user_mobile_location_{{ $value['id'] }}" class="form-control text-light input-gray" required type="text"
                                @if(!empty($value['value_location']))
                                    value="{{ $value['value_location'] }}"
                                    oninput="showMobileClRemark('{{ $value['id'] }}', '{{ $value['value_location'] }}', '{{ $value['value_remarks'] }}', '{{ $value['value_activity'] }}', '{{ $value['value_btn']['label'] }}')"
                                @else
                                    disabled
                                @endif
                                >
                        </div>
                        <div class="col-4 margin-bottom-20">
                            <label class="text-light">Remarks</label>
                            <input name="users[{{ $index }}][remarks]" id="user_mobile_remarks_{{ $value['id'] }}" class="form-control text-light input-gray" required type="text"
                                @if(!empty($value['value_remarks']))
                                    value="{{ $value['value_remarks'] }}"
                                    oninput="showMobileClRemark('{{ $value['id'] }}', '{{ $value['value_location'] }}', '{{ $value['value_remarks'] }}', '{{ $value['value_activity'] }}', '{{ $value['value_btn']['label'] }}')"
                                @else
                                    disabled
                                @endif
                            >
                        </div>
                    </div>
                    <div class="row" id="row-accordion-container-{{ $value['id'] }}">
                        <div class="collapse" id="accordion-mobile-container-{{ $value['id'] }}">
                            <hr>
                            <div class="col-md-12">
                                <label class="text-light">Edit Remark</label>
                                @if(!empty($value['value_location']) || request()->date != Carbon\Carbon::now()->toDateString())
                                    <input type="hidden" name="users[{{ $index }}][modified_status]" value="1">
                                @endif
                                <input name="users[{{ $index }}][modified_remarks]" id="user_mobile_modified_remarks_{{ $value['id'] }}" class="form-control text-light input-gray" required disabled type="text"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
