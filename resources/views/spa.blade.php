<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
	<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<title>PSTIC</title>
			
			<!-- CSRF Token -->
			<meta name="csrf-token" content="{{ csrf_token() }}">
			
			<link rel="stylesheet" href="{{ asset('css/app.css') }}">
	</head>
	<body class="hold-transition sidebar-mini">

	<div class="wrapper" id="app">
		<app-container></app-container>
	</div>
	<!-- ./wrapper -->

	{{-- <script>
		@auth
			window.Permissions = {!! json_encode(Auth::user()->allPermissions, true) !!};
		@else
			window.Permissions = [];
		@endauth
	</script> --}}

	<script src="{{ asset('js/app.js') }}" defer></script>







	</body>
</html>
