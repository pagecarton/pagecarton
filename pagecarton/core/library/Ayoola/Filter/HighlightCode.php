<?php

class Ayoola_Filter_HighlightCode implements Ayoola_Filter_Interface     
{

	public $replace = '';
	
    public function filter( $value )
	{  
		$string = $value;
		$decode = true;
//	function highlight_html($string, $decode = TRUE){
		$tag = '#0000ff';
		$att = '#ff0000';
		$val = '#8000ff';
		$com = '#34803a';
		$php = '#DD0000';
		$func = '#DD0000';
		$find = array(
			'~(\s[a-z].*?=)~',                    // Highlight the attributes
			'~(&lt;\!--.*?--&gt;)~s',            // Hightlight comments
			'~(&quot;[a-zA-Z0-9\/&;\?\s].*?&quot;)~',    // Highlight the values
			'~(&lt;[a-z].*?&gt;)~',                // Highlight the beginning of the opening tag
			'~(&lt;/[a-z].*?&gt;)~',            // Highlight the closing tag
			'~(&amp;.*?;)~',                    // Stylize HTML entities
			'~(&lt;\?php)~',                    // Stylize HTML entities
			'~(\?&gt;)~',                    // Stylize HTML entities
		//	'~([a-z]*?\(.*\))~',                    // Stylize HTML entities
		);
		$replace = array(
			'<span style="color:'.$att.';">$1</span>',
			'<span style="color:'.$com.';">$1</span>',
			'<span style="color:'.$val.';">$1</span>',
			'<span style="color:'.$tag.';">$1</span>',
			'<span style="color:'.$tag.';">$1</span>',
			'<span style="font-style:italic;">$1</span>',
			'<span style="color:'.$php.';">$1</span>',
			'<span style="color:'.$php.';">$1</span>',
		//	'<span style="color:'.$php.';">$1</span>',
		);
	//	if($decode)
			$string = htmlentities($string);
		return ''. nl2br( preg_replace($find, $replace, $string) ) .'';
//	}
	}
 
}
