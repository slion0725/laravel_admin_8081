<div class="panel-group" id="menu">
		@if (count(array_intersect($availablemenu,['item1'])) > 0)
		<div class="panel panel-default">
				<div class="panel-heading" data-toggle="collapse" data-parent="#menu" href="#collapseProduct">Product</div>
				<div id="collapseProduct" class="list-group panel-collapse collapse">
						@if(in_array('profile',$availablemenu))
			      <a href="/item1" class="list-group-item{{ Request::is('item1') ? ' active' : '' }}">Item1</a>
						@endif
				</div>
		</div>
		@endif
		@if (count(array_intersect(['profile','accounts','menuitems','menugroups','settings','weblogs'],array_keys($availablemenu))) > 0)
	  <div class="panel panel-default">
			<div class="panel-heading" data-toggle="collapse" data-parent="#menu" href="#collapseSettings">Management</div>
					<div id="collapseSettings" class="list-group panel-collapse collapse">
							@if(array_key_exists('profile',$availablemenu))
							<a href="/profile" class="list-group-item{{ Request::is('profile') ? ' active' : '' }}">{{$availablemenu['profile']}}</a>
							@endif
							@if(array_key_exists('accounts',$availablemenu))
							<a href="/accounts" class="list-group-item{{ Request::is('accounts') ? ' active' : '' }}">{{$availablemenu['accounts']}}</a>
							@endif
							@if(array_key_exists('menuitems',$availablemenu))
							<a href="/menuitems" class="list-group-item{{ Request::is('menuitems') ? ' active' : '' }}">{{$availablemenu['menuitems']}}</a>
							@endif
							@if(array_key_exists('menugroups',$availablemenu))
							<a href="/menugroups" class="list-group-item{{ Request::is('menugroups') ? ' active' : '' }}">{{$availablemenu['menugroups']}}</a>
							@endif
							@if(array_key_exists('settings',$availablemenu))
							<a href="/settings" class="list-group-item{{ Request::is('settings') ? ' active' : '' }}">{{$availablemenu['settings']}}</a>
							@endif
							@if(array_key_exists('weblogs',$availablemenu))
							<a href="/weblogs" class="list-group-item{{ Request::is('weblogs') ? ' active' : '' }}">{{$availablemenu['weblogs']}}</a>
							@endif
					</div>
		</div>
		@endif
</div>
