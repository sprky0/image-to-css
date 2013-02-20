<?php

include('image_to_css.php');

if (!empty($_FILES)) {

	$converter = new ImageToCSS();
	$converter->setWrap(false);

	$result = array();

	foreach($_FILES as $file) {

		$result[] = $converter->convert($file['tmp_name']);

	}

	var_dump($result);

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

<?php if(isset($result)) { ?>

<?php echo implode("\n\n", $result); ?>

<?php } ?>

</body>	
</html>