<div class="panel-body">
    @foreach($attendance['unpresent'] as $index => $value)
    <div class="panel">
        <div class="panel-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <h5 class="text-light">{{ $value->fname . ' ' . $value->lname }}</h5>
                    </div>
                    <div class="col-xs-6">
                        <button type="button" name="changeStatus" id="changeStatus_mobile_{{ $index }}" onclick="changeButtonStatus('changeStatus_mobile_{{ $index }}', 'status_{{ $index }}' , 'user[{{ $index }}][activities]', 'user[{{ $index }}][location]', 'user[{{ $index }}][remarks" class="btn btn-default pull-right">Undecided</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="form-group form-horizontal">
                <div class="row">
                    <div class="col-xs-4">
                        <div class="row">
                            <div class="col-xs-4">
                                <labe class="pull-right">Activity</labe>
                            </div>
                            <div class="col-xs-8">
                            <select class="form-control" name="user[{{ $index }}][activities]"
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
