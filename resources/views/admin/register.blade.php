@extends('admin.index')

@section('head')
<script src="/js/register.js"></script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <form autocomplete="off" class="form-horizontal" role="form" id="register-form">
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="text" name="name" placeholder="name" maxlength="255" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">Email</label>
                            <div class="col-md-6">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" maxlength="255" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label">Password</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" minlength="6" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation" class="col-md-4 control-label">Confirm Password</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required data-fv-identical="true" data-fv-identical-field="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-4 col-md-6">
                                <button type="submit" class="btn btn-primary">
                                    Register
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
