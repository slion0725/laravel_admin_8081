@extends('admin.index')

@section('head')
<script src="/js/resetpasswordbymailtolink.js"></script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>
                <div class="panel-body">
                    <form autocomplete="off" class="form-horizontal" role="form" id="resetpasswordbymailtolink-form">
                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">Email</label>
                            <div class="col-md-6">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" maxlength="255" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-4 col-md-6">
                                <button type="submit" class="btn btn-primary">
                                    Send Link
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
