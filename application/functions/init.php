<?php

// Open a known directory, and proceed to read its contents
if (is_dir(__DIR__)) {
    if ($dh = opendir(__DIR__)) {
        while (($file = readdir($dh)) !== false) {
        	if (is_file(__DIR__ . DS . $file))
			include_once(__DIR__ . DS . $file);
        }
        closedir($dh);
    }
}