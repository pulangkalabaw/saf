<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ env('APP_NAME') }}</title>
	<link rel="icon" type="image/png" href="img/favicon.png">
	<link rel="apple-touch-icon-precomposed" href="img/apple-touch-favicon.png">
	<link href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/libs/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/libs/jquery.scrollbar/jquery.scrollbar.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/libs/ionrangeslider/css/ion.rangeSlider.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/libs/ionrangeslider/css/ion.rangeSlider.skinFlat.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/libs/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/libs/summernote/summernote.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<meta content="stuff, to, help, search, engines, not" name="keywords">
	<meta content="What this page is about." name="description">
	<meta content="Display Webcam Stream" name="title">

	<style>
	input[type="search"]::-webkit-search-cancel-button {
		-webkit-appearance: searchfield-cancel-button;
	}
	.required {
		color: red
	}

	.select2-selection {
	  background-color: rgba(36, 41, 44, 0.5) !important;
	  border-color: #343033 !important;
	}
	.select2-selection .select2-selection__rendered {
	  color: grey !important;
	}

	.select2-search {
	  border: 0 !important;
	}
	.select2-search input {
	  background: #1F1F1F !important;
	  color: white;
	  border-radius: 3px;
	  border: 1px !important;
	  outline-width: 0 !important;
	  outline: none !important;
	  -webkit-box-shadow: none !important;
	  -webkit-appearance: none;
	}
	.select2-search input :focus, .select2-search input :active {
	  outline-width: 0 !important;
	  outline: none !important;
	  -webkit-box-shadow: none !important;
	  -webkit-appearance: none;
	}

	.select2-dropdown {
	  background-color: #382835;
	  border: 0 !important;
	}
	.select2-dropdown option {
	  color: red !important;
	}

	.select2-results {
	  color: grey !important;
	}
	.select2-results .select2-results__option:hover, .select2-results .select2-results__option--highlighted {
	  background: #2E2930 !important;
	}
	.select2-results [aria-selected=true] {
	  background: #2E2930 !important;
	}

	.select2-container {
	  border: 1 !important;
	}

	</style>
@yield('styles')


<link class="demo__css" href="{{ asset('assets/css/right.dark.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/demo.css') }}" rel="stylesheet">

<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->

</head>
<body class="framed main-scrollable">
	<div class="wrapper">
		<nav class="navbar navbar-static-top header-navbar">
			<div class="header-navbar-mobile">
				<div class="header-navbar-mobile__menu">
					<button class="btn" type="button"><i class="fa fa-bars"></i></button>
				</div>
				<div class="header-navbar-mobile__title"><span>{{ env('APP_NAME') }}</span></div>
				<div class="header-navbar-mobile__settings dropdown"><a class="btn dropdown-toggle" href="" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-power-off"></i></a>
					<ul class="dropdown-menu dropdown-menu-right">
						<li><a href="{{ route('logout') }}">Log Out</a></li>
					</ul>
				</div>
			</div>
			<div class="navbar-header"><a class="navbar-brand" href="">
				<div class="logo text-nowrap">
					<div class="logo__img"><i class="fa fa-chevron-right"></i></div><span class="logo__text">{{ env('APP_NAME') }}</span>
				</div></a>
			</div>
			<div class="topnavbar">
				<ul class="userbar nav navbar-nav">
					<li class="dropdown">
						<a href="#">
							{{ auth()->user()->fname }}
							{{ auth()->user()->lname }}

							({{ ucfirst(base64_decode(auth()->user()->role)) }})
						</a>
					</li>
					<li class="dropdown"><a class="userbar__settings dropdown-toggle" href="" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-power-off"></i></a>
						<ul class="dropdown-menu dropdown-menu-right">
							<li><a href="{{ route('logout') }}">Log Out</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</nav>

		<div class="dashboard">
			<div class="sidebar">
				<div class="quickmenu">
					<div class="quickmenu__cont">
						<div class="quickmenu__list">
							<div class="quickmenu__item {{ str_contains(url()->current(), ['home', 'applications', '']) ? 'active' : '' }}">
								<div class="fa fa-fw fa-home"></div>
							</div>
							@if (Auth::user()->role == base64_encode("administrator"))
								<div class="quickmenu__item {{ str_contains(url()->current(), ['users', 'teams', 'clusters']) ? 'active' : '' }}">
									<div class="fa fa-fw fa-users"></div>
								</div>
								<div class="quickmenu__item {{ str_contains(url()->current(), ['statuses', 'plans', 'product', 'devices']) ? 'active' : '' }}">
									<div class="fa fa-fw fa-cog"></div>
								</div>
							@endif

							@if(accessControl(['administrator', 'user']))
								{{-- @if (Auth::user()->role == base64_encode("administrator") || Auth::user()->role == base64_encode("cl") || "tl") --}}
								<div class="quickmenu__item {{ str_contains(url()->current(), ['attendance','attendancedashboard', 'gallery']) ? 'active' : '' }}">
									<div class="fa fa-clock-o"></div>
								</div>
							@endif
							{{-- dissable for presentation --}}
							{{-- <div class="quickmenu__item {{ str_contains(url()->current(), ['messages']) ? 'active' : '' }}">
								<div class="fa fa-fw fa-bullhorn"></div>
							</div> --}}

						</div>
					</div>
				</div>
				<div class="scrollable scrollbar-macosx">
					<div class="sidebar__cont">
						<div class="sidebar__menu">
							<div class="sidebar__title">Home</div>
							<ul class="nav nav-menu">
								@if(auth()->user()->role != base64_encode('encoder'))
								<li class="{{ sidebarActive(['homedashboard']) }}">
									<a href="{{ route('app.dashboard') }}">
										<div class="nav-menu__ico"><i class="fa fa-fw fa-dashboard"></i></div>
										<div class="nav-menu__text"><span>Dashboard</span></div>
									</a>
								</li>
								@else
								<li class="{{ sidebarActive(['encoderdashboard']) }}">
									<a href="{{ route('app.encoder-dashboard') }}">
										<div class="nav-menu__ico"><i class="fa fa-fw fa-dashboard"></i></div>
										<div class="nav-menu__text"><span>Dashboard</span></div>
									</a>
								</li>
								@endif
							</ul>

							@if(!empty(checkUserAgents(auth()->user())) || accessControl(['administrator', 'encoder']))
							<div class="sidebar__title">Applications</div>
							<ul class="nav nav-menu">
								<li class="{{ sidebarActive(['applications', 'edit', 'show']) }}">
									<a href="{{ route('app.applications.index') }}">
										<div class="nav-menu__ico"><i class="fa fa-fw fa-folder-o"></i></div>
										<div class="nav-menu__text"><span>List applications</span></div>
									</a>
								</li>
								@if (count(checkPosition(auth()->user(), ['tl', 'cl'], true)) != 0 || accessControl(['administrator']))
									<li class="{{ sidebarActive(['applications', 'create'], false) }}">
										<a href="{{ route('app.applications.create') }}">
											<div class="nav-menu__ico"><i class="fa fa-fw fa-plus-circle"></i></div>
											<div class="nav-menu__text"><span>Add applications</span></div>
										</a>
									</li>
								@endif
							</ul>
							@endif

							{{-- @if(!empty(checkUserAgents(auth()->user())) || accessControl(['administrator']))
							<div class="sidebar__title">OIC</div>
							<ul class="nav nav-menu">
								<li>
									<a href="{{ route('app.oic.index') }}">
										<div class="nav-menu__ico"><i class="fa fa-fw fa-folder-o"></i></div>
										<div class="nav-menu__text"><span>List OIC</span></div>
									</a>
								</li>

								<li>
									<a href="{{ route('app.oic.create') }}">
										<div class="nav-menu__ico"><i class="fa fa-fw fa-plus-circle"></i></div>
										<div class="nav-menu__text"><span>Assign OIC</span></div>
									</a>
								</li>
							</ul>
							@endif --}}

						</div>

						@if (Auth::user()->role == base64_encode("administrator"))
							<div class="sidebar__menu">
								<div class="sidebar__title">Users</div>
								<ul class="nav nav-menu">
									<li class="{{ sidebarActive(['users', 'edit', 'show']) }}">
										<a href="{{ route('app.users.index') }}">
											<div class="nav-menu__ico"><i class="fa fa-fw fa-user"></i></div>
											<div class="nav-menu__text"><span>User accounts</span></div>
										</a>
									</li>
									<li class="{{ sidebarActive(['users', 'create'], false) }}">
										<a href="{{ route('app.users.create') }}">
											<div class="nav-menu__ico"><i class="fa fa-fw fa-plus-circle"></i></div>
											<div class="nav-menu__text"><span>Add user</span></div>
										</a>
									</li>
								</ul>

								<div class="sidebar__title">Teams</div>
								<ul class="nav nav-menu">
									<li class="{{ sidebarActive(['teams', 'edit', 'show']) }}">
										<a href="{{ route('app.teams.index') }}">
											<div class="nav-menu__ico"><i class="fa fa-fw fa-users"></i></div>
											<div class="nav-menu__text"><span>Teams</span></div>
										</a>
									</li>
									<li class="{{ sidebarActive(['teams', 'create'], false) }}">
										<a href="{{ route('app.teams.create') }}">
											<div class="nav-menu__ico"><i class="fa fa-fw fa-plus-circle"></i></div>
											<div class="nav-menu__text"><span>Add team</span></div>
										</a>
									</li>
								</ul>

								<div class="sidebar__title">Clusters</div>
								<ul class="nav nav-menu">
									<li class="{{ sidebarActive(['clusters', 'edit', 'show']) }}">
										<a href="{{ route('app.clusters.index') }}">
											<div class="nav-menu__ico"><i class="fa fa-fw fa-building"></i></div>
											<div class="nav-menu__text"><span>Clusters</span></div>
										</a>
									</li>
									<li class="{{ sidebarActive(['clusters', 'create'], false) }}">
										<a href="{{ route('app.clusters.create') }}">
											<div class="nav-menu__ico"><i class="fa fa-fw fa-plus-circle"></i></div>
											<div class="nav-menu__text"><span>Add cluster</span></div>
										</a>
									</li>
								</ul>
							</div>
							<div class="sidebar__menu">
								<div class="sidebar__title">Plans Management</div>
								<ul class="nav nav-menu">
									<li class="{{ sidebarActive(['plans', 'edit']) }}">
										<a href="{{ route('app.plans.index') }}">
											<div class="nav-menu__ico"><i class="fa fa-fw fa-newspaper-o"></i></div>
											<div class="nav-menu__text"><span>Plans</span></div>
										</a>
									</li>
									<li class="{{ sidebarActive(['plans', 'create'], false) }}">
										<a href="{{ route('app.plans.create') }}">
											<div class="nav-menu__ico"><i class="fa fa-fw fa-plus-circle"></i></div>
											<div class="nav-menu__text"><span>Add plan</span></div>
										</a>
									</li>
								</ul>
							</div>
						@endif

                        {{-- USER ATTENDANCE --}}
						@if(accessControl(['administrator', 'user']) )
	                        <div class="sidebar__menu">
	                            <div class="sidebar__title">Attendance</div>
	                            <ul class="nav nav-menu">
									<li class="{{ sidebarActive(['attendancedashboard']) }}">
	                                    <a href="{{ route('app.attendanceDashboard') }}">
	                                        <div class="nav-menu__ico"><i class="fa fa-fw fa-dashboard"></i></div>
	                                        <div class="nav-menu__text"><span>Summary</span></div>
	                                    </a>
	                                </li>
									{{-- @if(count(checkPosition(auth()->user(), ['cl', 'tl'], true)) != 0) --}}
									{{-- {{ sidebarActive(['attendance']) }} --}}
	                                <li class="{{ sidebarActive(['attendance']) }}">
	                                    <a href="{{ route('app.attendance.index') }}">
	                                        <div class="nav-menu__ico"><i class="fa fa-fw fa-clock-o"></i></div>
	                                        <div class="nav-menu__text"><span>Attendance</span></div>
	                                    </a>
	                                </li>
	                                <li class="{{ sidebarActive(['attendance', 'list'], false) }}">
	                                    <a href="{{ route('app.attendance.list') }}">
	                                        <div class="nav-menu__ico"><i class="fa fa-fw fa-file"></i></div>
	                                        <div class="nav-menu__text"><span>List of Attendance</span></div>
	                                    </a>
	                                </li>
	                                <li class="{{ sidebarActive(['gallery']) }}">
										<a href="{{ route('app.gallery') }}">
											<div class="nav-menu__ico"><i class="fa fa-file-image-o fa-fw"></i></div>
											<div class="nav-menu__text"><span>Gallery</span></div>
										</a>
									</li>
									{{-- @endif --}}
	                            </ul>
	                        </div>

						@endif

                        {{-- Message Board --}}
						{{-- <div class="sidebar__menu">
							<div class="sidebar__title">Message Board</div>
							<ul class="nav nav-menu">
								<li>
									<a href="{{ route('app.messages.index') }}">
										<div class="nav-menu__ico"><i class="fa fa-fw fa-bullhorn"></i></div>
										<div class="nav-menu__text"><span>Announcements</span></div>
									</a>
								</li>
							</ul>
						</div> --}}

					</div>
				</div>
			</div>
			<div class="main">
				<div class="main__scroll scrollbar-macosx">
					<div class="main__cont">
						@yield ('content')
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
	<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('assets/libs/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
	<script src="{{ asset('assets/libs/bootstrap-tabdrop/bootstrap-tabdrop.min.js') }}"></script>
	<script src="{{ asset('assets/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
	<script src="{{ asset('assets/libs/ionrangeslider/js/ion.rangeSlider.min.js') }}"></script>
	<script src="{{ asset('assets/libs/inputNumber/js/inputNumber.js') }}"></script>
	<script src="{{ asset('assets/libs/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/moment.js') }}"></script> --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
	<script src="http://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/a549aa8780dbda16f6cff545aeabc3d71073911e/src/js/bootstrap-datetimepicker.js"></script>

	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/js/bootstrap-datetimepicker.min.js"></script> --}}
    <script src="{{ asset('assets/libs/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
	<script src="{{ asset('assets/js/main.js') }}"></script>
	<script src="{{ asset('assets/js/demo.js') }}"></script>

	@yield ('scripts')

	<script>
	console.log("APP VERSION {{ env('APP_VERSION') }} | Khurt Russel")

	$("form").on("submit", function(){
		$("form button").attr("disabled", "disabled")
	})
</script>
</body>
</html>
