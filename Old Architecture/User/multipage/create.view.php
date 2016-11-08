<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width = device-width">
<meta name="viewport" content="initial-scale = 1.0, user-scalable = no">
<title>Duel in the Desert</title>
<link rel="stylesheet" type="text/css" href="http://territorialcup.azurewebsites.net/rsc/lobbystyles.css">

<body>

    <div id="main">
		<div id="text" style="padding-top: 10%;padding-left:5%">Register:
		<div id="formData">
			<form action="/user/create" method="post" id="regForm">
				<input type="text" class="rounded" name="username" placeholder="Username">
				<input type="email" class="rounded" name="email" placeholder="E-mail Address">
				<input type="email" class="rounded" name="emailconfirm" placeholder="Confirm E-mail Address">
				<input type="password" class="rounded" name="password" placeholder="Password">
				<input type="password" class="rounded" name="passwordconfirm" placeholder="Confirm Password">
				<br>
				Choose Your Side:
				<select name="university" style="display:block">
				<option value="ArizonaStateUniversity">Arizona State University</option>
				<option value="NorthernArizonaUniversity">Northern Arizona University</option>
				<option value="UniversityOfArizona">University of Arizona</option>
				<option value="GrandCanyonUniversity">Grand Canyon University</option>
				</select>
			</form>
			<button type="submit" class="rounded" form="regForm" name="register"> Register </button>
		</div>
	</div>
</body>
</html>
