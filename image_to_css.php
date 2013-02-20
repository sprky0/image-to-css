<?php

/**
 * Convert an image to absolutely positioned colored SPANs in a relatively positioned DIV wrap
 * 
 * @author sprky0
 */
class ImageToCSS {

	protected $buffer = "";
	protected $html = true;

	public function convert($image, $output_file = null) {

		$this->buffer = "";

		$mime = mime_content_type($image);

		switch($mime) {
			default: throw new Exception("Don't know how to do this!"); break;
			case "image/jpeg": $i = imagecreatefromjpeg($image); break;
			case "image/gif": $i = imagecreatefromgif($image); break;
			case "image/png": $i = imagecreatefrompng($image); break;
			case "string": $i = imagecreatefromstring($image); break;
		}

		$width = imagesx($i);
		$height = imagesy($i);
	
		for($x = 0; $x < $width; $x++) {
			for($y = 0; $y < $height; $y++) {
				$rgb = imagecolorat($i,$x,$y);
				$colors = imagecolorsforindex($i,$rgb);
				$this->pixel($x,$y,$colors['red'],$colors['green'],$colors['blue'],1 - ($colors['alpha'] / 127));
			}
		}

		if ($this->html === true)
			$result = "<html><body><div style=\"width:{$width}px;height:{$height}px;position:relative;\">{$this->buffer}</div></body></html>";
		else
			$result = "<div style=\"width:{$width}px;height:{$height}px;position:relative;\">{$this->buffer}</div>";
			
		if (!is_null($output_file))
			return file_put_contents($output_file,$result);
		else
			return $result;

	}

	private function block($x,$y,$w,$h,$r,$g,$b,$a=1) {

		$this->buffer .= "<span style=\"position:absolute;background-color:rgba($r,$g,$b,$a);top:{$y}px;left:{$x}px;\"></span>";

	}
			
	private function pixel($x,$y,$r,$g,$b,$a=1) {

		$this->buffer .= "<span style=\"position:absolute;background-color:rgba($r,$g,$b,$a);top:{$y}px;left:{$x}px;width:1px;height:1px;\"></span>";

	}
	
}
