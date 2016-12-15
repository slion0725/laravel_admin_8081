@extends('admin.features.home')

@section('head')
<script src="/js/datatable.js"></script>
<script src="/js/menugroups.js"></script>
@endsection

@section('content')
<div class="btn-group">
    <button type="button" class="btn btn-default" title="Chaek" id="Chaek"><i class="glyphicon glyphicon-check"></i></button>
    <button type="button" class="btn btn-default" title="Uncheck" id="Uncheck"><i class="glyphicon glyphicon-unchecked"></i></button>
    <button type="button" class="btn btn-default" title="Search" id="Search"><i class="glyphicon glyphicon-search"></i></button>
    <button type="button" class="btn btn-default" title="View" id="View"><i class="glyphicon glyphicon-list-alt"></i></button>
    <button type="button" class="btn btn-default" title="Add" id="Add"><i class="glyphicon glyphicon-plus"></i></button>
    <button type="button" class="btn btn-default" title="Edit" id="Edit"><i class="glyphicon glyphicon-pencil"></i></button>
    <button type="button" class="btn btn-default" title="Remove" id="Remove"><i class="glyphicon glyphicon-trash"></i></button>
</div>

<p></p>

<table class="table table-striped table-bordered table-condensed table-hover" id="datatable">
    <thead>
        <tr>
            @foreach ($table as $d)
            <th nowrap class="{{ $d['class'] }}">{{ $d['text'] }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<div class="modal fade" id="Search-Modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Search</h4>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th nowrap>Fields</th>
                            <th nowrap>Search Text</th>
                            <th nowrap>Regex</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td nowrap>All</td>
                            <td><input type="text" class="form-control input-sm" id="global_text"></td>
                            <td></td>
                        </tr>
                        @foreach ($search as $d)
                        <tr>
                            <td nowrap>{{ $d['text'] }}</td>
                            <td><input type="text" class="form-control input-sm colfilter" name="{{ $d['db'] }}"></td>
                            <td><input type="checkbox" id="regex-{{ $d['db'] }}"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-default" id="Search-Clear">Clear</button>
                <button type="button" class="btn btn-primary" id="Search-Submit">Search</button>
            </div>
        </div>
    </div>
</div>

<!-- modal-dialog size = null ,modal-lg ,modal-sm -->
<div class="modal fade" id="View-Modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">View</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <colgroup>
                            <col class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <col class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                        </colgroup>
                        <tbody>
                            @foreach ($view as $d)
                            <tr>
                                <td nowrap>{{ $d['text'] }}</td>
                                <td class="view-{{ $d['class'] }}"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- modal-dialog size = null ,modal-lg ,modal-sm -->
<div class="modal fade" id="Add-Modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add</h4>
            </div>
            <form enctype="multipart/form-data" autocomplete="off" id="Add-Form">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Name" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" class="form-control" name="description" placeholder="Description" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="accounts[]">Accounts</label>
                                <select class="form-control" name="accounts[]" multiple="multiple" required>
                                    @foreach ($accounts as $d)
                                        <option value="{{$d['id']}}">{{$d['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="menuitems[]">Menuitems</label>
                                <select class="form-control" name="menuitems[]" multiple="multiple" required>
                                    @foreach ($menuitems as $d)
                                        <option value="{{$d['id']}}">{{$d['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- modal-dialog size = null ,modal-lg ,modal-sm -->
<div class="modal fade" id="Edit-Modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit</h4>
            </div>
            <form enctype="multipart/form-data" autocomplete="off" id="Edit-Form">
                {{ method_field('PUT') }}
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Name" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" class="form-control" name="description" placeholder="Description" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="accounts[]">Accounts</label>
                                <select class="form-control" name="accounts[]" multiple="multiple" required>
                                    @foreach ($accounts as $d)
                                        <option value="{{$d['id']}}">{{$d['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="menuitems[]">Menuitems</label>
                                <select class="form-control" name="menuitems[]" multiple="multiple" required>
                                    @foreach ($menuitems as $d)
                                        <option value="{{$d['id']}}">{{$d['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
