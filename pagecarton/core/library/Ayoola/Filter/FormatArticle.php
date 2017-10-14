<?php

class Ayoola_Filter_FormatArticle implements Ayoola_Filter_Interface 
{

	public $replace = '';
	
    public function filter( $value )
	{
	//	$search = array( '...', '-', '\'', '"', '"', '\'', '\'', '&', '\'', '...', '"', '"', '1/2',  ); 
		$search = array( '&hellip;', '&ndash;', '&quot;', '&rdquo;', '&ldquo;', '&rsquo;', '&lsquo;', '&amp;', '‘', '’', '…', '“', '”', '½',  );
		if( ! $this->replace )
		{
			$this->replace = array( '...', '-', '\'', '"', '"', '\'', '\'', '&', '\'', '\'', '...', '"', '"', '1/2',  );
		//	$this->replace = array( '&hellip;', '&ndash;', '&quot;', '&rdquo;', '&ldquo;', '&rsquo;', '&lsquo;', '&amp;', '’', '…', '“', '”', '½',  );
		}
		$value = str_replace( $search, $this->replace, $value );
	//	var_export( $value );
		return $value;
	}
 
}
