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
	<link href="{{ asset('assets/libs/summernote/summernote.css') }}" rel="stylesheet">
	<link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

	<style>
	input[type="search"]::-webkit-search-cancel-button {
		-webkit-appearance: searchfield-cancel-button;
	}
	.required {
		color: red
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
				<div class="header-navbar-mobile__title"><span>Blank</span></div>
				<div class="header-navbar-mobile__settings dropdown"><a class="btn dropdown-toggle" href="" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-power-off"></i></a>
					<ul class="dropdown-menu dropdown-menu-right">
						<li><a href="#">Log Out</a></li>
					</ul>
				</div>
			</div>
			<div class="navbar-header"><a class="navbar-brand" href="">
				<div class="logo text-nowrap">
					<div class="logo__img"><i class="fa fa-chevron-right"></i></div><span class="logo__text">Right</span>
				</div></a>
			</div>
			<div class="topnavbar">
				<ul class="userbar nav navbar-nav">
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
							<div class="quickmenu__item {{ str_contains(url()->current(), ['dashboard', 'applications']) ? 'active' : '' }}">
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
							@if (Auth::user()->role == base64_encode("administrator") || Auth::user()->role == base64_encode("cl") || "tl")
								<div class="quickmenu__item {{ str_contains(url()->current(), ['attendance']) ? 'active' : '' }}">
									<div class="fa fa-clock-o"></div>
								</div>
							@endif

							<div class="quickmenu__item {{ str_contains(url()->current(), ['messages']) ? 'active' : '' }}">
								<div class="fa fa-fw fa-bullhorn"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="scrollable scrollbar-macosx">
					<div class="sidebar__cont">
						<div class="sidebar__menu">
							<div class="sidebar__title">Home</div>
							<ul class="nav nav-menu">
								<li>
									<a href="{{ route('app.dashboard') }}">
										<div class="nav-menu__ico"><i class="fa fa-fw fa-dashboard"></i></div>
										<div class="nav-menu__text"><span>Dashboard</span></div>
									</a>
								</li>
								<li>
									<a href="{{ route('app.applications.index') }}">
										<div class="nav-menu__ico"><i class="fa fa-fw fa-folder-o"></i></div>
										<div class="nav-menu__text"><span>Applications</span></div>
									</a>
								</li>


								{{-- YOUR NON ADMIN --}}
								@if (session()->get('_c'))
									<li>
										<a href="{{ route('app.your.clusters') }}">
											<div class="nav-menu__ico"><i class="fa fa-fw fa-building"></i></div>
											<div class="nav-menu__text"><span>Your Clusters</span></div>
										</a>
									</li>
								@endif
								{{--  --}}
							</ul>
						</div>

						@if (Auth::user()->role == base64_encode("administrator"))
							<div class="sidebar__menu">
								<div class="sidebar__title">Users</div>
								<ul class="nav nav-menu">
									<li>
										<a href="{{ route('app.users.index') }}">
											<div class="nav-menu__ico"><i class="fa fa-fw fa-user"></i></div>
											<div class="nav-menu__text"><span>User Accounts</span></div>
										</a>
									</li>
									<li>
										<a href="{{ route('app.teams.index') }}">
											<div class="nav-menu__ico"><i class="fa fa-fw fa-users"></i></div>
											<div class="nav-menu__text"><span>Teams</span></div>
										</a>
									</li>
									<li>
										<a href="{{ route('app.clusters.index') }}">
											<div class="nav-menu__ico"><i class="fa fa-fw fa-building"></i></div>
											<div class="nav-menu__text"><span>Clusters</span></div>
										</a>
									</li>
								</ul>
							</div>
							<div class="sidebar__menu">
								<div class="sidebar__title">Settings</div>
								<ul class="nav nav-menu">
									{{-- <li>
										<a href="{{ route('app.statuses.index') }}">
											<div class="nav-menu__ico"><i class="fa fa-fw fa-quote-right"></i></div>
											<div class="nav-menu__text"><span>Status Management</span></div>
										</a>
									</li>
									<li>
										<a href="{{ route('app.product.index') }}">
											<div class="nav-menu__ico"><i class="fa fa-fw fa-bullseye"></i></div>
											<div class="nav-menu__text"><span>Product Management</span></div>
										</a>
									</li> --}}
									<li>
										<a href="{{ route('app.devices.index') }}">
											<div class="nav-menu__ico"><i class="fa fa-fw fa-mobile"></i></div>
											<div class="nav-menu__text"><span>Devices Management</span></div>
										</a>
									</li>
									<li>
										<a href="{{ route('app.plans.index') }}">
											<div class="nav-menu__ico"><i class="fa fa-fw fa-newspaper-o"></i></div>
											<div class="nav-menu__text"><span>Plans Management</span></div>
										</a>
									</li>
								</ul>
							</div>
						@endif
                        {{-- USER ATTENDANCE --}}
                        <div class="sidebar__menu">
                            <div class="sidebar__title">Attendance</div>
                            <ul class="nav nav-menu">
                                <li>
                                    <a href="{{ route('attendance.index') }}">
                                        <div class="nav-menu__ico"><i class="fa fa-fw fa-user-o"></i></div>
                                        <div class="nav-menu__text"><span>Users Attendance</span></div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        {{-- Message Board --}}
						<div class="sidebar__menu">
							<div class="sidebar__title">Message Board</div>
							<ul class="nav nav-menu">
								<li>
									<a href="{{ route('app.messages.index') }}">
										<div class="nav-menu__ico"><i class="fa fa-fw fa-bullhorn"></i></div>
										<div class="nav-menu__text"><span>Announcements</span></div>
									</a>
								</li>
							</ul>
						</div>
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
