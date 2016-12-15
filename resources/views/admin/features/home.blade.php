<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.head')
    <script src="/js/home.js"></script>
    <link href="/css/home.css" rel="stylesheet">
    @yield('head')
</head>

<body>
    <nav class="navbar navbar-default navbar-static-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-login">
                  <span class="sr-only">Toggle Navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ url('/') }}">Laravel</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-login">
                <ul class="nav navbar-nav navbar-right">
                    @include('admin.features.submenu')
                    <li class="dropdown">
                        <li>
                            <a href="/profile" title="Profile">
                                <i class="fa fa-user"></i>
                                <span class="visible-xs-inline">Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="logout" title="Logout">
                                <i class="fa fa-sign-out"></i>
                                <span class="visible-xs-inline">Logout</span>
                            </a>
                        </li>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div calss="row">
        <div class="col-md-2 col-sm-4 hidden-xs">
            @include('admin.features.menu')
        </div>
        <div class="col-md-10 col-sm-8 col-xs-12">
            @yield('content')
        </div>
    </div>

    <div class="modal fade" id="Loading" style="height:100vh">
        <div style="position: relative;top:calc(50% - 35px);text-align:center;">
          <i class="fa fa-refresh fa-spin fa-5x fa-fw"></i>
        </div>
    </div>

</body>

</html>
