<?php

class Ayoola_Loader_Autoloader
{
	protected static $_instance;
	protected $autoloaders;
	protected $defaultAutoloader;
	
    public function __construct()
    {
        /* Initialize action controller here */
        spl_autoload_register( array( $this, 'autoload' ) );
    }

    public static function getInstance()
    {
		self::$_instance = is_null( self::$_instance ) ? new self() : self::$_instance; 
		return self::$_instance;
    }
	
    public function autoload( $file )
    {
		//var_export( $file );
		$file = $file . EXT;
		$file = str_replace( '_', '/', $file );
		$library = strstr( $file, '/', true );
		require_once 'Ayoola/Loader.php';
		if ( Ayoola_Loader::loadFile( $file, true ) )
		{
			//var_export( $file );
			return true;
		}
		return false;
    }

	
}
