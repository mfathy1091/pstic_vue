<!DOCTYPE html>
<html lang="en">
	<head>
		
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>PSTIC</title>
		
		<!-- (1) CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		
		<link rel="stylesheet" href="{{ asset('css/app.css') }}">

		<!-- (2) Redirect if there is no auth token in local storage -->
		<script>
			if(!localStorage.hasOwnProperty("blog_token")){
				window.location.replace("/login");
			}
		</script>

	</head>
	
	<body class="hold-transition sidebar-mini">
		
		<!-- (3) VUE ENTRY POINT-->
		<div class="wrapper" id="app">
			<app-container></app-container>
		</div>

		<!-- (4) app.js-->
		<script src="{{ asset('js/app.js') }}" defer></script>

	</body>
</html>
