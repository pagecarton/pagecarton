<?php

class Ayoola_Page_Display
{
	protected $_page = array();
    protected $_layout;
	protected static $_instance = null;

    public function __construct()
    {
        $this->_page = $this->getPage();
    }
	
    public static function getInstance()
    {
        // action body
		return is_null(self::$_instance) ? new self() : self::$_instance; 
		
    }
    
    public function getPage()
    {
        /* Get new page info here */
		
		// Try from the XML file
		require_once 'Ayoola/Xml.php';
		$xml = new Ayoola_Xml;
		
		if( $filePath = Ayoola_Loader::checkFile( PAGE_DATA_FILE ) )
		{
			$xml->load( $filePath );
			$page = $xml->getTextNodesData();
			return $page;			
		}
	}    

    public function __get( $property )
    {
        // Process returned properties, set to default if page settings is missing
		if ( isset($this->_page[$property]) )
		{
			return $this->_page[$property];
		}
    }    
    public function __set($property, $value)
    {
        // Set the object property
        return $this->_page[$property]  = $value;
    }
    public function __call($method, $args)
    {
        // Calling the object method.
    }
    public function __toString()
    {
        return $this->_page['displayname'];
    }  
    
}
