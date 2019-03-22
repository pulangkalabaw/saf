@extends ('layouts.app')
@section('content')
	<div class="main-heading">
		<ol class="breadcrumb">
			<li class="">{{ strtoupper(env('APP_NAME')) }}</li>
			<li class="">Home</li>
			<li class="active">Gallery</li>
		</ol>
	</div>
    @if (Session::has('message'))
    <div class="alert alert-dismissable alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
            {{ Session::get('message') }}
    </div>
    @endif
	<div class="container-fluid half-padding">
		<div class="template template__blank">
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<div class="row">
								<div class="col-md-8 col-xs-8">
									<h3 class="panel-title">
										Gallery
									</h3>
								</div>
								<div class="col-md-4 col-xs-4 text-right">
									<a href="{{ route('app.applications.index') }}" class="btn btn-sm btn-default">
										<span class='fa fa-th-list'></span>
									</a>
								</div>
							</div>
						</div>
						<div class="form-group" style="padding-top:10px;">
							<form method="GET"  action="{{ route('app.gallery') }}">
								<div class="row" style="margin-left:8px; margin-right:8px;">
									<div class="col-lg-3 col-md-4 col-sm-4">
										<div class="input-group date">
											<input class="form-control text-light" name="gal_date" type="text" id="jump_to_date" value="{{ $date }}"
											max="{{ \Carbon\Carbon::today()->toDateString() }}" readonly>
											<div class="input-group-addon" title="Jump to certain date" id="icon-container">
												<div class="fa fa-calendar" title="Jump to certain date"></div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
						{{-- Body start --}}
						<div class="panel-body" style="margin-left:1%;">
                            <div class="row">
                                <div class="col-md-12">
                                    @foreach($image as $images)
                                        <div class="col-md-3">
                                            <div class="row">
                                                <form method="POST" action=" {{ route('app.image.destroy', $images->id) }}">
                                                    <div class="container-fluid half-padding ">
                                                		<div class="template template__blank">
                                                			<div class="row">
                                                				<div class="col-md-11">
                                                					<div class="panel panel-default">
                                                						<div class="panel-heading">
                                                							<div class="row">
                                                								<div class="col-md-8 col-xs-8">
                                                									<h3 class="panel-title">
                                                										{{$images->image}}
                                                									</h3>
                                                								</div>
																				@if(count(checkPosition(auth()->user(), ['cl'], true)) != 0 || Auth::user()->role == base64_encode("administrator"))
                                                								<div class="col-md-4 col-xs-4" style="padding-left:50px;">
                                                                                    {{ csrf_field() }}
                                                                                    {{ method_field('DELETE') }}
                                                                                    <button class="btn btn-danger btn-xs" style="margin-right:30px;`margin-left:30px;" onclick="confirmDelete()"><span class="fa fa-trash-o"></span></button>
                                                								</div>
																				@endif
                                                							</div>
                                                						</div>
                                                						<div class="panel-body">
                                                                            <div class="row">
                                                                                <div class="col-md-12 text-center">
                                                                                    <img src="{{ asset('public/storage/' .$images['image']) }}" alt="" width="220" height="220"
                                                                                     style="object-fit:cover;"  id="image"/>
                                                                                </div>
                                                                            </div>
                                                	                    </div>
                                                					</div>
                                                                </div>
                                                			</div>
                                                		</div>
                                                	</div>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            {{ $image->links() }}
	                    </div>
						{{-- Body end --}}
					</div>
				</div>
			</div>
		</div>
	</div>
@stop
@section ('scripts')

<script>

jQuery(document).ready(function () {
    $('.fa-calendar').click(function() {
        $("#jump_to_date").focus();
    });
    $('#icon-container').click(function() {
        $("#jump_to_date").focus();
    });

    jQuery('#jump_to_date').datepicker ({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        endDate: '+0d',
    }).on('changeDate', function(){
        window.location = '{{ url('app/gallery') . '?date=' }}' + this.value;
    });
});

</script>
@stop
