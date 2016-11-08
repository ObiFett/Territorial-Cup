<!DOCTYPE html>
<html>
<head>
<META charset="Content-Type" content="text/html; charset=UTF-8" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width = device-width">
<meta name="viewport" content="initial-scale = 1.0, user-scalable = no">
<title>Login</title>
<link href="http://territorialcup.azurewebsites.net/rsc/lobbystyles.css" rel="stylesheet" type="text/css" />
<title>Territorial Cup: Duel in the Desert - Login</title>


</head>

<body>


<div id="main">
	<div id="text" style="padding-top: 10%; padding-left:5%">Log in:
	<div id=formData >
        <form action="/user/login" method="post" id="loginForm">
            <div id="errorText"><?php echo $login_error; ?></div>
            <input type="text" class="rounded" name="user" placeholder="Username">
            <input type="password" class="rounded" name="pword" placeholder="Password">
        </form>
		<button type="submit" class="rounded" form="loginForm" name="login"> Log in </button>
	</div>
	</div>
	<div id="text" style="padding-top: 5%; padding-left:5%">
		<a href="/user/recovery">Forgot Username/Password?</a>
	</div>
	<div id="text" style="padding-top: 5%; padding-left:5%">
		Don't have an account? <a href="/user/create">Sign up here.</a>
	</div>
</div>

</body>

</html>
