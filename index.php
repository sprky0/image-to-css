<?php

if (!empty($_FILES)) {
	var_dump($_FILES);
	exit();
}

?><!DOCTYPE html>
<html>
<body>

	<h1>Convert your image to a very large and cumbersome HTML file with inline CSS.</h1>

	<form method="post" enctype="multipart/form-data">
		<label for="file">Select a file:</label>
		<input type="file" name="file" id="file"><br />
		<input type="submit" name="submit" value="Submit" />
	</form>

</body>	
</html>