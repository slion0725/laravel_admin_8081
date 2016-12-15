@extends('admin.features.home')

@section('head')
<script src="/js/profile.js"></script>
@endsection

@section('content')

<div class="row">
    <div class="col-md-4">
        <h4>Profile</h4>
        <form enctype="multipart/form-data" autocomplete="off" id="Profile-Form">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" placeholder="Name" value="{{ $name }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" placeholder="Email" maxlength="255" value="{{ $email }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <div class="col-md-4">
        <h4>Change Password</h4>
        <form enctype="multipart/form-data" autocomplete="off" id="Password-Form">
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Password" minlength="6" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required data-fv-identical="true" data-fv-identical-field="password">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

@endsection
