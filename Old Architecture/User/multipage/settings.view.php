<!DOCTYPE html>
<html>
<head>
<META charset="Content-Type" content="text/html; charset=UTF-8" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width = device-width">
<meta name="viewport" content="initial-scale = 1.0, user-scalable = no">
<link href="http://territorialcup.azurewebsites.net/rsc/lobbystyles.css" rel="stylesheet" type="text/css" />
<title>Account Settings</title>

</head>

<body>

<div id="main">
	<div id="text" style="padding-top: 10%;padding-left:5%">Account Settings:
		<div id="text" style="padding-top: 5%; padding-left:5%">Change Email Address:
		<form action="/user/settings" method="post" id="mailForm">
			<input type="email" class="rounded" name="email" placeholder="E-mail Address">
			<input type="email" class="rounded" name="emailconfirm" placeholder="Confirm E-mail Address">
		</form>
		<button type="submit" class="rounded" form="mailForm" name="submit"> Change E-mail </button>
		</div>

		<div id="text" style="padding-top: 5%; padding-left:5%">Change Password:
		<form action="/user/settings" method="post" id="pwForm">
			<input type="password" class="rounded" name="currentpw" placeholder="Current Password">
			<input type="password" class="rounded" name="newpw" placeholder="New Password">
			<input type="password" class="rounded" name="confirmpw" placeholder="Confirm New Password">
		</form>
		<button type="submit" class="rounded" form="pwForm" name="submit"> Change Password </button>
		
		</div>
	</div>
	
	<div id="buttonField">
		<a href="/" class="button buttonright">
			<span class="button-text">- Back</span>
		</a>
	</div>
</div>
</body>

</html> 
