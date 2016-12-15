<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" id="submenu" title="Menu">
				<i class="glyphicon glyphicon-menu-hamburger"></i>
				<span class="visible-xs-inline">Menu</span>
		</a>
		<ul class="dropdown-menu">
				@if (count(array_intersect(['item1'],array_keys($availablemenu))) > 0)
				<li class="dropdown-submenu">
						<a href="#">Product</a>
						<ul class="dropdown-menu">
								@if(array_key_exists('item1',$availablemenu))
								<li class="{{ Request::is('item1') ? 'active' : '' }}"><a href="/item1">{{$availablemenu['profile']}}</a></li>
								@endif
						</ul>
				</li>
				@endif
				@if (count(array_intersect(['profile','accounts','menuitems','menugroups','settings','weblogs'],array_keys($availablemenu))) > 0)
				<li class="dropdown-submenu">
						<a href="#">Management</a>
						<ul class="dropdown-menu">
								@if(array_key_exists('profile',$availablemenu))
								<li class="{{ Request::is('profile') ? 'active' : '' }}"><a href="/profile">{{$availablemenu['profile']}}</a></li>
								@endif
								@if(array_key_exists('accounts',$availablemenu))
								<li class="{{ Request::is('accounts') ? 'active' : '' }}"><a href="/accounts">{{$availablemenu['accounts']}}</a></li>
								@endif
								@if(array_key_exists('menuitems',$availablemenu))
								<li class="{{ Request::is('menuitems') ? 'active' : '' }}"><a href="/menuitems">{{$availablemenu['menuitems']}}</a></li>
								@endif
								@if(array_key_exists('menugroups',$availablemenu))
								<li class="{{ Request::is('menugroups') ? 'active' : '' }}"><a href="/menugroups">{{$availablemenu['menugroups']}}</a></li>
								@endif
								@if(array_key_exists('settings',$availablemenu))
								<li class="{{ Request::is('settings') ? 'active' : '' }}"><a href="/weblogs">{{$availablemenu['settings']}}</a></li>
								@endif
								@if(array_key_exists('weblogs',$availablemenu))
								<li class="{{ Request::is('weblogs') ? 'active' : '' }}"><a href="/weblogs">{{$availablemenu['weblogs']}}</a></li>
								@endif
						</ul>
				</li>
				@endif
		</ul>
</li>
