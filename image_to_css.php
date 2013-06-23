<?php

/**
 * Convert an image to absolutely positioned colored SPANs in a relatively positioned DIV wrap
 * 
 * @author sprky0
 */
class ImageToCSS {

	protected $buffer = "";
	protected $html = true;

	protected $pixelElement = "b";

	protected $width = 640;
	protected $height = 480;

	protected $colors = array();
	protected $positions = array();

	public function setWrap($wrap = false) {
		$this->html = $wrap;
	}

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

		$this->width = imagesx($i);
		$this->height = imagesy($i);

		// first loop populates the pallete
		// the reason for this is that we may try to apply compression
		// can replace the image operation on the second loop with direct access to an array with the meta from this loop later
		/*
		for($x = 0; $x < $this->width; $x++) {
			for($y = 0; $y < $this->height; $y++) {
				$colors = imagecolorsforindex($i,$rgb);
				$rgb = imagecolorat($i,$x,$y);
				$colors = imagecolorsforindex($i,$rgb);
				$this->color($colors['red'],$colors['green'],$colors['blue']);
			}
		}
		*/

		for($x = 0; $x < $this->width; $x++) {
			for($y = 0; $y < $this->height; $y++) {
				$rgb = imagecolorat($i,$x,$y);
				$colors = imagecolorsforindex($i,$rgb);
				$this->pixel($x,$y,$colors['red'],$colors['green'],$colors['blue'],1 - ($colors['alpha'] / 127));
			}
		}

		$instance = "r";

		$CSS = ".$instance{width:{$this->width}px;height:{$this->height}px;position:relative;}";
		$CSS .= ".a{position:absolute}";
		$CSS .= ".p{width:1px;height:1px;display:block;}";
		$CSS .= implode($this->colors,"");
		$CSS .= implode($this->positions,"");

		if ($this->html === true)
			$result = "<html><body><style>{$CSS}</style><div class=\"$instance\">{$this->buffer}</div></body></html>";
		else
			$result = "<style>{$CSS}</style><div class=\"$instance\">{$this->buffer}</div></div>";

		if (!is_null($output_file))
			return file_put_contents($output_file,$result);
		else
			return $result;

	}

	private function pixel($x,$y,$r,$g,$b,$a=1) {

		// skip transparency for now
		if ($a < 1)
			return;

		// $r$g$b$a classname is long, could shorten it

		if (!isset($this->colors["c$r$g$b$a"])) {
			// $this->colors["c$r$g$b$a"] = ".cc$r$g$b$a{background-color:rgba($r,$g,$b,$a)}";
			$hex = $this->rgbToHex($r,$g,$b);
			$this->colors["c$r$g$b$a"] = ".c$r$g$b$a{background-color:#{$hex}}";
		}

		if (!isset($this->positions["x$x"]))
			$this->positions["x$x"] = ".x$x{left:{$x}px}";

		if (!isset($this->positions["y$y"]))
				$this->positions["y$y"] = ".y$y{top:{$y}px}";

		$classes = "a x$x y$y c$r$g$b$a p";

		$this->buffer .= "<{$this->pixelElement} class=\"{$classes}\"></{$this->pixelElement}>";

	}

	/*
	private function color($r,$g,$b) {
		$rgb = imagecolorat($i,$x,$y);
		$colors = imagecolorsforindex($i,$rgb);
		$this->pixel($x,$y,$colors['red'],$colors['green'],$colors['blue'],1 - ($colors['alpha'] / 127));	
	}
	*/

	private function rgbToHex($r,$g,$b) {
		return str_pad(dechex($r), 2, "0", STR_PAD_LEFT) . str_pad(dechex($g), 2, "0", STR_PAD_LEFT) . str_pad(dechex($b), 2, "0", STR_PAD_LEFT);
	}

}
