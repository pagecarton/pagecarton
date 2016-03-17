<?php 
/* 	Turn a list into array.
	The list is of the form "a, b, c, d=3, etc"
	@return array

  */
function toArray( $value )
{
	if( is_array( $value ) )
	return $value;
	$value = (string) $value;
	$arrayValue = array_map( 'trim', explode( ',', $value ) );
	$arrayValue = array_flip( $arrayValue );
	foreach( $arrayValue as $parameter => $value )
	{
		$arrayValue[$parameter] = NULL;
		if( strpos( $parameter, '=' ) )
		{	
			list( $a, $b ) = array_map( 'trim', explode( '=', $parameter ) );
			$arrayValue[$a] = $b;
			unset( $arrayValue[$parameter] );
		}

	}
	return $arrayValue;
}

function _Array( $value )
{
	if( is_array( $value ) )
	{
		return $value;
	}
	$value = (string) $value;
	$arrayValue = array_map( 'trim', explode( '::', $value ) );
	$arrayValue = array_flip( $arrayValue );
	foreach( $arrayValue as $parameter => $value )
	{
		$arrayValue[$parameter] = NULL;
		if( strpos( $parameter, '=>' ) )
		{	
			list( $a, $b ) = array_map( 'trim', explode( '=>', $parameter ) );
			$arrayValue[$a] = $b;
			unset( $arrayValue[$parameter] );
		}

	}
	return $arrayValue;
}

?>