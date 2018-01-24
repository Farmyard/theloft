@extends('layouts.app') 

@section('style')
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap-select/css/bootstrap-select.min.css') }}">
@endsection

@section('content')
<div class="container">
	<div class="content-area col-md-12">
        <div id="create">
            <form class="form-horizontal" id="formPosts">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $id }}">
                <div class="form-group">
                    <div class="col-md-12">
                    <input type="text" name="title" class="form-control" placeholder="文章标题">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="editor"><textarea name="content" id='myEditor'></textarea></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10">
                        <select id="selPosts" name="category_id" class="selectpicker show-tick form-control" data-live-search="false">
                            <option value="0">选择分类</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <a type="submit" class="btn btn-default btn-block" data-toggle="modal" data-target="#myModal">管理分类</a>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-block" id="btnPosts">保存文章</button>
                    </div>
                </div>
            </form>
            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">管理分类</h4>
                        </div>
                        <div class="modal-body">
                            <form id="formCategory" class="bs-example bs-example-form" role="form">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="col-sm-4">
                                                <input type="text" name="name" class="form-control" placeholder="分类名称">
                                            </div>
                                            <div class="col-sm-4">
                                                <select id="selCategory" name="pid" class="selectpicker show-tick form-control" data-live-search="false">
                                                    <option value="0">选择分类</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <button id="btnEditCategory" type="button" class="btn btn-primary btn-block">修改</button>
                                            </div>
                                            <div class="col-sm-2">
                                                <button id="btnAddCategory" type="button" class="btn btn-primary btn-block">新增</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <hr class="featurette-divider">
                            
                            <ul class="list-group" id="ulCategory" style="display:block;"></ul>
                        </div>
                        <div class="modal-footer"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    @include('editor::head')
    <script src="{{ asset('bootstrap/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('js/save.js')}}"></script>
@endsection
