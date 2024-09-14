<header class="main-header">
	<a href="../../index2.html" class="logo">
	</a>
	<nav class="navbar navbar-static-top">
		<!-- Sidebar toggle button-->
		<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</a>

		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<span class="hidden-xs">{{ $session_user->name }}</span>
					</a>
					<ul class="dropdown-menu">
						<li>
                            <a role="button" onclick="$('#form-logout').submit()">Logout</a>
                            <form id="form-logout" action="{{ route('logout') }}" method="POST">
                                @csrf
                            </form>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
</header>
