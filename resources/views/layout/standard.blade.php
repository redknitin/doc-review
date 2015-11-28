<!doctype html><html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title')</title>
	{!! Html::style('css/bootstrap.min.css') !!}
	{!! Html::style('css/bootstrap-theme.min.css') !!}
	{!! Html::style('css/style.css') !!}
	{!! Html::script('js/jquery-2.1.1.min.js') !!}
	{!! Html::script('js/bootstrap.min.js') !!}
</head>
<body>

	<header>
		<nav class="navbar navbar-inverse navbar-fixed-top"><div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="{!! url('/') !!}">CheckPoint Reviews</a>
		</div>
		<div id="navbar" class="collapse navbar-collapse">
		<ul class="nav navbar-nav">
			<li>
				{!! Html::link('/auth/logout', 'Logout') !!}
			</li>
		</ul>
		</div>
		</div></nav>
	</header>

	<div style="margin-bottom: 70px;"></div>

	<div class="container">
		@yield('content')
	</div>
	
</body>
</html>