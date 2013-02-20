#!/usr/bin/php
<?php

/**
 * CLI utility to convert images to HTML/CSS
 * 
 * @author sprky0
 */
include("image_to_css.php");

if (!isset($argv[1]))
	throw new Exception("Fatal: Missing input file.");

if (!is_file($argv[1]))
	throw new Exception("Fatal: Input file not found.");

if (isset($argv[2]))
	$output_file = $argv[2];
else
	$output_file = "{$argv[1]}.html";
	
$converter = new ImageToCSS();
$converter->convert($argv[1], $output_file);

echo "Converted image {$argv[1]} to {$output_file}\n";
