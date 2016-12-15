@extends('admin.features.home')

@section('head')
<script src="/js/datatable.js"></script>
<script src="/js/weblogs.js"></script>
@endsection

@section('content')
<div class="btn-group">
    <button type="button" class="btn btn-default" title="Chaek" id="Chaek"><i class="glyphicon glyphicon-check"></i></button>
    <button type="button" class="btn btn-default" title="Uncheck" id="Uncheck"><i class="glyphicon glyphicon-unchecked"></i></button>
    <button type="button" class="btn btn-default" title="Search" id="Search"><i class="glyphicon glyphicon-search"></i></button>
    <button type="button" class="btn btn-default" title="View" id="View"><i class="glyphicon glyphicon-list-alt"></i></button>
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
                            <tr>
                                <td>ID</td>
                                <td class="view-id"></td>
                            </tr>
                            <tr>
                                <td>User ID</td>
                                <td class="view-user_id"></td>
                            </tr>
                            <tr>
                                <td nowrap>Name</td>
                                <td class="view-name"></td>
                            </tr>
                            <tr>
                                <td nowrap>Level</td>
                                <td class="view-level"></td>
                            </tr>
                            <tr>
                                <td nowrap>Method</td>
                                <td class="view-method"></td>
                            </tr>
                            <tr>
                                <td nowrap>Require Data</td>
                                <td class="view-require_data"></td>
                            </tr>
                            <tr>
                                <td nowrap>Created At</td>
                                <td class="view-created_at"></td>
                            </tr>
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

@endsection
