@extends('layouts.app') 

@section('content')
<div class="container">
	<div class="content-area col-md-12">
		<div id="lists" topic="{{ $topic }}"></div>
		<!-- #post-## -->
		<div class="clearfix"></div>

		<nav id="listsPager" style="display:none">
			<ul class="pager">
			  <li class="previous"><a style="color:#5e5d5d;" href="javascript:void(0);" class="page" current="1" action="-">上一页</a></li>
			  <li class="next"><a style="color:#5e5d5d;" href="javascript:void(0);" class="page" current="1" action="+">下一页</a></li>
			</ul>
		</nav>
	</div>
</div>
@endsection
@section('script')
<script src="{{ asset('js/lists.js')}}"></script>
@endsection