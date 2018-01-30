<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--可以在地址栏中显示出图标-->
	<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>
	<link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'TheLoft') }}</title>

	<!-- Styles -->
	@yield('style')
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/public.css')}}" rel="stylesheet">
</head>

<body>
	<div id="app">
		<nav class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/">{{ config('app.name', 'TheLoft') }}</a>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav" id="topic">
						<li class="dropdown" v-for="nav in navbar">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">@{{nav.name}}
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu" v-if="nav.children.length>0">
								<li v-for="v in nav.children"><a v-bind:href="'/topic/id/'+v.id">@{{v.name}}</a></li>
							</ul>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						@if(Auth::check())
						<li>
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">管理
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li>
									<a href="{{ route('create') }}">发布文章</a>
								</li>
								<li>
									<a href="{{ route('register') }}">注册账号</a>
								</li>
								<li>
									<a href="javascript:void(0);" id="logout">退出系统</a>
									<form id="logoutForm" method="POST" action="{{ route('logout') }}">{{ csrf_field() }}</form>
								</li>
							</ul>
						</li>
						@else
						<li>
							<a href="{{ route('login') }}">登录</a>
						</li>
						@endif
					</ul>
				</div>
				<!--/.nav-collapse -->
			</div>
		</nav>

		@yield('content')
		<div class="modal fade bd-example-modal-sm" id="dialog" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-body" id="dialog-msg"></div>
				</div>
			</div>
		</div>
	</div>

	<!-- Scripts -->
	<script src="{{ asset('js/jquery-3.2.1.min.js')}}"></script>
	<script src="{{ asset('js/bootstrap.js') }}"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue"></script>
	<script src="{{ asset('js/public.js')}}"></script>
	@yield('script')
</body>

</html>