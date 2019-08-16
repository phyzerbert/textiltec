<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Bootstrap 4 Admin Template">
	<meta name="author" content="Bootlab">

	<title>{{config('app.name')}}</title>

    <link href="{{asset('master/css/app.css')}}" rel="stylesheet">
    
</head>

<body>
    <main class="main h-100 w-100" style="background-image: url('{{asset('images/bg.jpg')}}')">
		<div class="container h-100">
            @yield('content')			
		</div>
	</main>

	<script src="{{asset('master/js/app.js')}}"></script>
</body>

</html>