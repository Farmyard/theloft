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
                        <input type="text" name="url" class="form-control" placeholder="文章链接">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="editor"><textarea name="content" id='myEditor'></textarea></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10">
                        <select name="category_id" class="selectpicker show-tick form-control" data-live-search="false">
                            <option value="0">选择分类</option>
                            <option v-for="opt in options" v-bind:value="opt.id">@{{opt.symbol}}@{{opt.name}}</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <a type="submit" class="btn btn-default btn-block" data-toggle="modal" data-target="#myModal">管理分类</a>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <button type="submit" v-on:click="save" class="btn btn-primary btn-block" id="btnPosts">保存文章</button>
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
                                                <select name="pid" class="selectpicker show-tick form-control" data-live-search="false">
                                                    <option value="0">选择分类</option>
                                                    <option v-for="opt in options" v-bind:value="opt.id">@{{opt.symbol}}@{{opt.name}}</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <button v-on:click="edit" type="button" class="btn btn-primary btn-block">修改</button>
                                            </div>
                                            <div class="col-sm-2">
                                                <button v-on:click="add" type="button" class="btn btn-primary btn-block">新增</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <hr class="featurette-divider">
                            
                            <!-- 子级组件 -->
                            <template id="lists-template">
                                <li class="list-group-item" style="padding: 10px 0;margin-top">
                                    <a href="javascript:void(0);" class="badge" style="margin-right:15px;"><span v-bind:data-id="model.id" v-on:click="destroy($event)" class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                    <a href="javascript:void(0);" class="drop-down" v-bind:data-id="model.id" style="margin-left:15px;">@{{model.symbol}}@{{model.name}}</a>  
                                    <!-- 组件中引用当前组件，传入下级数据 -->
                                    <ul class="list-group" v-if="model.children.length>0" v-bind:id="'drop-down-menu-'+model.id" style="margin-top: 10px;display:none">
                                        <lists v-for="model in model.children" :model="model"></lists>
                                    </ul>
                                </li>
                            </template>
                            <!-- 父级 -->
                            <ul class="list-group" id="categoryLists">
                                <lists v-for="model in lists" :model="model"></lists>
                            </ul>
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
