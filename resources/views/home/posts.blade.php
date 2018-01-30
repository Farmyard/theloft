@extends('layouts.app') 

@section('content')
<div class="container">
	<div class="content-area col-md-12">
		<div id="posts" postsId="{{ $id }}">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title" id="postsTitle">
                        <h3><b>@{{title}}</b></h3>
                        <h6><small><i class="glyphicon glyphicon-folder-open" style="margin-right:5px;"></i>@{{category}}</small></h6>
                    </div>
                </div>
                <div class="panel-body">
                    <div v-html="content"></div>
                </div>
                <div class="panel-footer" id="postsFooter">
                    <small>
                        <span><i class="glyphicon glyphicon-user" style="margin-right:5px;"></i>@{{user}}</span>
                        <span><i class="glyphicon glyphicon-calendar" style="margin:0 5px;"></i>@{{time}}</span>
                        @if(Auth::check())
                        <a class="badge" href="/admin/posts/edit/id/{{ $id }}" style="float:right"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('js/posts.js')}}"></script>
@endsection