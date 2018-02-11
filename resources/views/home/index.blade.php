@extends('layouts.app') 

@section('content')
<div class="container">
	<div class="content-area col-md-12">
		<div id="lists" topic="{{ $topic }}">
			@if($topic>=0)
			<div class="item" v-for="(item, index) in lists"><h5>@{{index}}<span class="badge">@{{item.length}}</span></h5><ul class="list-group">
				<li class="list-group-item" style="margin-bottom: 5px;" v-for="(t,i) in item">
					@if(Auth::check())
					<a v-if="t.restore" href="javascript:void(0);" class="badge"><span v-bind:data-id="t.id" v-on:click="restore($event)" class="glyphicon glyphicon-refresh" aria-hidden="true"></span></a>
					<a v-if="t.destroy" href="javascript:void(0);" class="badge"><span v-bind:data-type="t.restore" v-bind:data-id="t.id" v-on:click="destroy($event)" class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
					<a v-if="t.edit" v-bind:href="'/admin/posts/edit/id/'+t.id" class="badge"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
					@endif
					<a v-if="t.restore" v-bind:href="'/posts/id/' + t.id" style="color:#5e5d5d;"><s>@{{t.title}}</s></a>
					<a v-else="t.restore" v-bind:href="'/posts/id/' + t.id" style="color:#5e5d5d;">@{{t.title}}</a>
				</li>
			</div>
			<!-- #post-## -->
			<div class="clearfix"></div>

			<nav v-if="total>0">
				<ul class="pager">
					<li class="previous"><a v-on:click="pager($event)" href="javascript:void(0);" style="color:#5e5d5d;" action="-">上一页</a></li>
					<li class="next"><a v-on:click="pager($event)" href="javascript:void(0);" style="color:#5e5d5d;" action="+">下一页</a></li>
				</ul>
			</nav>
			@endif
		</div>
		
	</div>
</div>
@endsection
@section('script')
<script src="{{ asset('js/lists.js')}}"></script>
@endsection