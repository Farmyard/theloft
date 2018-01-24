@extends('layouts.app') 
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">注册</div>

				<div class="panel-body">
					<form class="form-horizontal" method="POST" action="{{ route('register') }}">
						{{ csrf_field() }}

						<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">

							<div class="col-md-6 col-md-offset-3">
								<div class="input-icon-group">
									<div class="input-group">
										<span class="input-group-addon">
											<i class="glyphicon glyphicon-user"> </i>
										</span>
										<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus placeholder="用户名">
									</div>
								</div>


								@if ($errors->has('name'))
								<span class="help-block">
									<strong>{{ $errors->first('name') }}</strong>
								</span>
								@endif
							</div>
						</div>

						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

							<div class="col-md-6 col-md-offset-3">
								<div class="input-icon-group">
									<div class="input-group">
										<span class="input-group-addon">
											<i class="glyphicon glyphicon-envelope"> </i>
										</span>
										<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="邮箱">
									</div>
								</div>

								@if ($errors->has('email'))
								<span class="help-block">
									<strong>{{ $errors->first('email') }}</strong>
								</span>
								@endif
							</div>
						</div>

						<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

							<div class="col-md-6  col-md-offset-3">
								<div class="input-icon-group">
									<div class="input-group">
										<span class="input-group-addon">
											<i class="glyphicon glyphicon-lock"> </i>
										</span>
										<input id="password" type="password" class="form-control" name="password" required placeholder="密码">
									</div>
								</div>


								@if ($errors->has('password'))
								<span class="help-block">
									<strong>{{ $errors->first('password') }}</strong>
								</span>
								@endif
							</div>
						</div>

						<div class="form-group">

							<div class="col-md-6 col-md-offset-3">
								<div class="input-icon-group">
									<div class="input-group">
										<span class="input-group-addon">
											<i class="glyphicon glyphicon-check"> </i>
										</span>
										<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="确认密码">
									</div>
								</div>

							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-3">
								<button type="submit" class="btn btn-primary btn-block">
									注册
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection