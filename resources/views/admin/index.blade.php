<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.head')
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
                    <li class="dropdown">
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')
    <div class="modal fade" id="Loading" style="height:100vh">
        <div style="position: relative;top:calc(50% - 35px);text-align:center;">
          <i class="fa fa-refresh fa-spin fa-5x fa-fw"></i>
        </div>
    </div>
</body>

</html>
