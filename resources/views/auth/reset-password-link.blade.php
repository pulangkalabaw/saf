<!DOCTYPE html>
<html>
<head>
	<title></title>

	<style type="text/css">
	body{
		background-color: #ffffff;
	}
		.container{
			width: 50%;
			height: 300px;
			background: #f7f7fa;
			padding-top: 10px;
			text-align: left;
		}

		.btn{
			background-color:#080b11;
			color: white;
			font-family: sans-serif;
			margin-left: 35%;
		}

		h2{
			text-align: center;
			font-family: sans-serif;
			background-color: black;
			color:white;
		}
		.messhead{
			margin-left: 20px;
		}
		.footer{
			background-color: black;
			color:white;
			width: 50%;
		}
		.header{
			background-color: black;
			color: white;
			width: 50%;
		}
		.messBody{
			font-family: sans-serif;
			font-size-adjust: 20px;
			margin-left: 20px;
		}

	</style>

</head>
<body>
	<center>
		<div class="header">
			<h3>RESET PASSWORD (SAF)</h3>
		</div>
		<div class="container">
			<h1 class="messhead">Hi User,</h1>
			<p class="messBody">We recieve a request to reset your password click button below to set up a new password for your account.</p>
			<p class="messBody">
			If you  did not request to reset your password ignore this email and the
			link will expire on its own.</p><br><br><br>
			<form method="POST" action="{{ url('set-new-password/'. $token) }}">
				{{ csrf_field() }}
				<button class="btn"><h4>SET NEW PASSWORD</h4></button>
			</form>
		</div>
		<div class="footer">
			<center>
				<p>Copy right &copy; SAF</p>
			</center>
		</div>
	</center>
</body>
</html>
