@extends ('layouts.app')

@section('content')

	<div class="main-heading">
		<ol class="breadcrumb">
			<li class="">{{ strtoupper(env('APP_NAME')) }}</li>
			<li class="">Users</li>
			<li class="active">User Accounts</li>
		</ol>
	</div>
	<div class="container-fluid half-padding">
		<div class="template template__blank">
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-danger">
						<div class="panel-heading">
							<div class="row">
								<div class="col-md-8 col-xs-8">
									<h3 class="panel-title">
										User Accounts
									</h3>
								</div>
								<div class="col-md-4 col-xs-4 text-right">
									<a href="{{ route('app.users.create') }}" class="btn btn-sm btn-primary">
										<span class='fa fa-plus-circle'></span>
									</a>
								</div>
							</div>
						</div>
						<div class="panel-body">
							<div class="row">

								@include('includes.filter')

								<div class="col-md-4 col-xs-4">
									<div class="form-inline">
										<div class="form-group">
											<label>Number of rows: </label>
											<select name="" id="" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value)" class="form-control">
												<option {{ !empty(request()->get('show') && request()->get('show') == 10) ? 'selected' : ''  }}
													value="{{ request()->fullUrlWithQuery(['show' => '10']) }}">10
												</option>
												<option {{ !empty(request()->get('show') && request()->get('show') == 25) ? 'selected' : ''  }}
													value="{{ request()->fullUrlWithQuery(['show' => '25']) }}">25
												</option>
												<option {{ !empty(request()->get('show') && request()->get('show') == 50) ? 'selected' : ''  }}
													value="{{ request()->fullUrlWithQuery(['show' => '50']) }}">50
												</option>
												<option {{ !empty(request()->get('show') && request()->get('show') == 100) ? 'selected' : ''  }}
													value="{{ request()->fullUrlWithQuery(['show' => '100']) }}">100
												</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-3"></div>
								<div class="col-md-5 col-xs-5">
									<form action="{{ request()->fullUrl() }}" method="GET">
										<div class="input-group">
											<input autofocus type="search" name="search_string" id="" value="{{ !empty(request()->get('search_string')) ? request()->get('search_string') : '' }}" class="form-control"
											placeholder="Search for first name, last name, email and role">
											<span class="input-group-btn">
												<button class="btn btn-primary"><span class='fa fa-search'></span> </button>
											</span>
										</div>
									</form>
								</div>
							</div>
							<div class="clearfix"></div><br>
							<div class="table-responsive">
								<table class="table table-hovered table-striped">
									<thead>
										<tr>
											<th>
												First name
												<a data-toggle="tooltip" title="Sort" href="{{ request()->fullUrlWithQuery(['sort_in' => 'fname', 'sort_by' => (Request::get('sort_by') == "asc") ? 'desc' : 'asc']) }}">
													<span class='fa fa-sort'></span>
												</a>
											</th>
											<th>
												Last name
												<a data-toggle="tooltip" title="Sort" href="{{ request()->fullUrlWithQuery(['sort_in' => 'lname', 'sort_by' => (Request::get('sort_by') == "asc") ? 'desc' : 'asc']) }}">
													<span class='fa fa-sort'></span>
												</a>
											</th>
											<th>
												Email
												<a data-toggle="tooltip" title="Sort" href="{{ request()->fullUrlWithQuery(['sort_in' => 'email', 'sort_by' => (Request::get('sort_by') == "asc") ? 'desc' : 'asc']) }}">
													<span class='fa fa-sort'></span>
												</a>
											</th>
											<th>Role</th>
											<th>
												Status
												<a data-toggle="tooltip" title="Sort" href="{{ request()->fullUrlWithQuery(['sort_in' => 'isActive', 'sort_by' => (Request::get('sort_by') == "asc") ? 'desc' : 'asc']) }}">
													<span class='fa fa-sort'></span>
												</a>
											</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($users as $user)
											<tr>
												<td>{{ $user->fname }}</td>
												<td>{{ $user->lname }}</td>
												<td>{{ $user->email }}</td>
												<td>{{ strtoupper(base64_decode($user->role)) }}</td>
												<td>{{ ($user->isActive == 1) ? 'Actived' : 'Deactived' }}</td>
												<td>
													<a data-toggle="tooltip" title="View User" href="{{ route('app.users.show', $user->id) }}" class="btn btn-warning btn-xs"><span class='fa fa-eye'></span></a>
													<a data-toggle="tooltip" title="Edit User" href="{{ route('app.users.edit', $user->id) }}" class="btn btn-success btn-xs"><span class='fa fa-edit'></span></a>
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							<br>
							<div class="row">
								<div class="col-md-8 col-xs-8">
									{{ $users->appends(request()->input())->links() }}
								</div>
								<div class="col-md-4 col-xs-4 text-right">
									Total <b>{{ $users_total }}</b> result(s)
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section ('scripts')
	<script>
	$(document).ready(function() {
		$(".delete").on("submit", function(){
			return confirm("Are you sure?");
		});
	});

	function submitSort() {
		document.sorting.submit();
	}
</script>
@endsection
