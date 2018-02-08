<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Xml
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Xml.php 10.23.2011 8.13PM ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Xml
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Xml extends DOMDocument
{
    /**
     * Array of TagNames and the number of times in which they occur as values
     *
     * @var array
     */
	protected $_tags = null;
	
    /**
     * The current filename
     *
     * This is a convenience property to access the resent filename of the XML Document
     * @var string
     */
	protected $_filename = null;
	
    /**
     * Array of Text Node Data in this Document
     *
     * @var array
     */
	protected $_textNodesData = array();
	
    /**
     * Array of DOMCharacterData values in this Document
     *
     * @var array
     */
	protected $_cDataValues = array();
	
    /**
     * Array of tag counters. Used to address issue of where we have multiple tags with same name
     *
     * @var array
     */
	protected $_tagCounter = array();
	
    /**
     * Whether to have CDataValues as a multidimensional array. This is useful if some of the tag appears multiple times in the document.
     *
     * @var boolean
     */
	public $multiDimensionalCDataValues = false;

    /**
     * Constructor - Initialize the class making sure default settings 
     *
     * @param string
     * @param string
     * 
     */
    public function __construct( $xml = null, $version = '1.0', $encoding = 'utf-8' )
    {
		parent::__construct( $version, $encoding );
		$this->formatOutput = true;
		$this->preserveWhiteSpace = false;
		if( is_file( $xml ) ){ $this->load( $xml ); }
	}

    /**
     * Calculates the node from the given parameter. 
     *
     * This helper method will return a node matching the parameter given
     *
     * @param mixed The parameter to use to search for the node
     * @return DOMNode
     */
    public function getNode( $node )
    {
		if( is_string( $node ) )
		{
			$node = $this->getElementsByTagName( $node )->item( 0 );
		}
		elseif( is_int( $node ) )
		{
			$this->setId();
			$node = $this->getElementById( $node );
		}
		if( ! $node instanceof DOMNode )
		{
			$node = $this->documentElement ? : $this;
		}
		return $node;
    } 

    /**
     * This method sets the filename property to a value
     *
     * @param string The XML document filename
     * @return null
     */
    public function setFilename( $filename )
    {
        $this->_filename = realpath( $filename );
    } 
	
    /**
     * This method returns the filename property
     *
     * @param void
     * @return string The current XML Document filename
     */
    public function getFilename()
    {
        return $this->_filename;
    } 
	
    /**
     * This method creates a new html tag that insert html 
     *
     * @param string tagName
     * @param string innerHTML
     * @return DOMNode
     */
    public function createHTMLElement( $tag, $innerHTML = null )
    {
		$node = $this->createElement( $tag );
		$html = new Ayoola_Xml();
	//	var_export( htmlspecialchars_decode( $innerHTML ) );
		$html->loadHTML( htmlspecialchars_decode( $innerHTML ) );
		if( $html->documentElement )
		{
			$html = $this->importNode( $html->documentElement->firstChild->firstChild, true );
			$node->appendChild( $html );
		}
		return $node;
	}
	
    /**
     * This method creates a new element and then append it to a parent
     *
     * @return string tagName for child
     * @param DOMNode
     * @param string
     * @param array
     */
    public function createChild( $child, DOMNode $parent, $content = null, array $attributes = null )
    {
		$child = @$this->createHTMLElement( $child, $content );
		if( $attributes )
		foreach( $attributes as $key => $value )
		{
			$child->setAttribute( $key, $value );
		}
		$parent->appendChild( $child );
		return $child;
    } 
	
    /**
     * This method complements the parent 'load' method
     *
     * @param string Filename
     * @param int Options
     * @return boolean
     */
    public function load( $filename, $options = null )
    {
		if( ! $path = Ayoola_Loader::checkFile( $filename ) )
		{
			require_once 'Ayoola/Xml/Exception.php';
			throw new Ayoola_Xml_Exception( "Invalid Filename {$filename}");     
		}
	//	var_export( $path );
        @$result = parent::load( $path, $options );
		$this->setFilename( $path );
		return $result;
    } 
	
    /**
     * This method conplements the parent 'save' method
     *
     * @param string Filename
     * @param int Options
     * @return int No of Bytes written
     */
    public function save( $filename = null, $options = null )
    {
		//throw new Exception;
		$filename = $filename ? : $this->getFilename();
		
		//	Make sure the file is saved before you give up
		$giveUpAfter = 5; //sec
		$time = time();
		while( ! @$result = parent::save( $filename, $options ) )
		{ 
			if( time() > $time + $giveUpAfter )
			{ 
				throw new Ayoola_Xml_Exception( 'Error while saving ' . basename( $filename ) ); 
			}
			continue; 
		}
		$this->setFilename( $filename );
		return $result;
    } 
	
    /**
     * This method auto-increment the ID attribute with int values
     *
     * It makes sure there is only one of such value in a document. To preserve document integrity
     *
     * @param string idKey
     * @param DOMNode
     * @return int The auto increment value
     */
    public function autoId( $idKey = null, $node = null )
    {
		$node = $this->getNode( $node );
		//if( $node->parentNode ){ $node = $node->parentNode; }
		$id = (int) $node->getAttribute( __FUNCTION__ );
		$id = $id ? : 1; //	One is default ID
		$this->setId( $idKey, $node ); 
		//	Check if Id already exist
		while( $element = $this->getElementById( $id ) )
		{
			$id++;
			$node->setAttribute( __FUNCTION__, $id );
		}
		$node->setAttribute( __FUNCTION__, $id + 1 );
		return $id;	
    } 
	
    /**
     * This method attempts to set the id attribute of the document
     *
     * @param string ID attribute
     * @param string Tag to search from
     * @return null
     */
    public function setId( $id = 'id', $tag = null )
    {
		//	Calculate the tag name
		$tag = $this->getNode( $tag );
		foreach( $tag->childNodes as $node )
		{
			if( $node instanceof DOMText ){ continue; }
			if( method_exists( $node, 'hasAttribute' ) && $node->hasAttribute( $id ) ){ $node->setIdAttribute( $id, true ); }
		}
    } 

    /**
     * Insert the tag in current document if it does not exist
     *
     * @param 
     * @return 
     */
    public function addTag( $tag, $value, Array $attributes )
    {
        if( $tags = $this->getElementsByTagName( $tag )->item( 0 ) )
		{
			foreach( $attributes as $key => $value )
			{
				if( ! $a = $tags->getAttribute( $key ) )
				{
					$tags->setAttribute( $key, $value );
				}
			}
			return;
		}
		$value = $this->createCDATASection( $value );
		$tags = $this->createElement( $tag );
		foreach( $attributes as $key => $value )
		{
			$tags->setAttribute( $key, $value );
		}
		
		$tags->appendChild( $value );
		$this->documentElement->appendChild( $tags );
    } 
	
    /**
     * This method is born from the need to maintain integrity of generated xml documents. 
     * So many atimes, I want to update the template xml files and that would require me to update all inheriting files as well
     *
     * @param string 
     * @return 
     */
    public function sanitize( $default = null )
    {
		if( null === $default )
		{	$default = 'default';
			if( ! $default = $this->getElementsByTagName( $default ) ){	return false; }
			// retrieve the path
			//var_export( $default->item( 0 ) );
			$default = $default->item( 0 );
			if( ! $default ){ return false; }
			$default = $default->getAttribute( 'path' );
			
		}
		$class = __CLASS__;
		$xml = new $class;
		$xml->load( $default );
		
		//	First compare the the default with the implementor and update the integrity of the implementor
		foreach( $xml->documentElement->childNodes as $node )
		{
			if( ! $firstLevel = $this->getElementsByTagName( $node->tagName )->item( 0 ) )
			{
				$new = $this->importNode( $node, true );
				$this->documentElement->appendChild( $new );
			}
			else
			{
				foreach( $node->childNodes as $each )
				{
					if( ! $secondLevel = $this->getElementsByTagName( $each->tagName )->item( 0 ) )
					{
						$new = $this->importNode( $each, true );
						$firstLevel->appendChild( $new );
					}
				}
			}
			
		}
		
		//	Do just the reverse, Attempt to delete nodes that are in the implementor but not in the default
		foreach( $this->documentElement->childNodes as $node )
		{
			if( ! $firstLevel = $xml->getElementsByTagName( $node->tagName )->item( 0 ) )
			{
				$this->documentElement->removeChild( $node );
			}
			else
			{
				foreach( $node->childNodes as $each )
				{
					if( ! $secondLevel = $xml->getElementsByTagName( $each->tagName )->item( 0 ) )
					{
					//	var_export( $secondLevel );
						$node->removeChild( $each );
					}
				}
			}
			
		}
		
    } 
	
    /**
     * This method retrieves the text node data
     *
     * @param DOMNode
     * @return array
     */
    public function getTextNodesData( $node = null )
    {
		if( null === $node )
		{
			$node = $this;
		}
		
		if( $node instanceof DOMText )
		{
			$parent = $node->parentNode;
			
			$this->_textNodesData[$parent->tagName] = $this->getTextNodeData( $parent->tagName );
		}
		
		if( $node->hasChildNodes() )
		{
			foreach( $node->childNodes as $child )
			{
				$this->getTextNodesData( $child );
			}
		}
		return $this->_textNodesData;
		
    } 
	
    /**
     * This method retrieves the text node data
     *
     * @param DOMNode
     * @return array
     */
    public function getTextNodeData( $element )
    {
        $element = (string) $element;
		$tags = $this->getElementsByTagName( $element );
		
		if( $tag = $tags->item( 0 ) )
		{			
			// 	Check if we have values that match as text
			if( $tag->hasChildNodes() && $tag->firstChild instanceof DOMText )
			{
				return $tag->firstChild->wholeText;
			}
			
			// 	Else just return null
			return null;
			
		}
    } 
	
    /**
     * This method retrieves the DOMCharacterData node data
     *
     * @param DOMNode
     * @return array
     */
    public function getCDataValues( $node = null )
    {
		if( null === $node )
		{
			$node = $this;
		}
		
		if( $node instanceof DOMCharacterData )
		{
			$parent = $node->parentNode;
			if( $this->multiDimensionalCDataValues )
			{
				$this->_cDataValues[$parent->tagName][] = $this->getCDataValue( $parent->tagName );
			}
			else
			{
				$this->_cDataValues[$parent->tagName] = $this->getCDataValue( $parent->tagName );
			}
		}
		
		if( $node->hasChildNodes() )
		{
			foreach( $node->childNodes as $child )
			{
				$this->getCDataValues( $child );
			}
		}
		return $this->_cDataValues;
		
    } 
	
    /**
     * This method retrieves the DOMCharacterData node data
     *
     * @param string
     * @return string
     */
    public function getCDataValue( $element )
    {
        $element = (string) $element;
		$tags = $this->getElementsByTagName( $element );
		$this->_tagCounter[$element] = isset( $this->_tagCounter[$element] ) ? $this->_tagCounter[$element] : 0;
		if( $tag = $tags->item( $this->_tagCounter[$element] ) )
		{			
			$this->_tagCounter[$element]++;
			// 	Check if we have values that match as text
			if( $tag->hasChildNodes() && $tag->firstChild instanceof DOMCharacterData )
			{
				return $tag->firstChild->data;
			}
			
			// 	Else just return null
			return null;
			
		}
    } 
	
    /**
     * This method method attempts to load xml tags with equivalent values from a web form
     *
     * @param array
     * @return int
     */
    public function arrayAsTextNode(Array $values )
    {
		$counter = 0;
        foreach( $values as $key => $value )
		{
			//	Calculate the tag name
			$tags = $this->getElementsByTagName( $key );
			
			//	Makes sure the tag is valid
			if( $tag = $tags->item( 0 ) )
			{
				//	I can create a text node containing the value
				$text = $this->createTextNode( htmlspecialchars( $value ) );
				
				// 	If there is a text there already, just replace
				if( $tag->hasChildNodes() )
				{
					$tag->replaceChild( $text, $tag->firstChild );
				}
				
				// 	Else just append it
				else
				{
					$tag->appendChild( $text );
				}
				
				// 	Records the progress
				$counter++;
			}
		}
		return $counter;
    } 
		
    /**
     * This method method attempts to load xml tags with equivalent values from a web form
     *
     * @param array
     * @return int
     */
    public function arrayAsCData(Array $values )
    {
		$counter = 0;
        foreach( $values as $key => $value )
		{
			//	Calculate the tag name
			$tags = $this->getElementsByTagName( $key );
			
			//	Makes sure the tag is valid
			if( $tag = $tags->item( 0 ) )
			{
				//	I can create a text node containing the value
				$text = $this->createCDATASection( htmlspecialchars_decode( $value ) );
				
				// 	If there is a text there already, just replace
				if( $tag->hasChildNodes() )
				{
					$tag->replaceChild( $text, $tag->firstChild );
				}
				
				// 	Else just append it
				else
				{
					$tag->appendChild( $text );
				}
				
				// 	Records the progress
				$counter++;
			}
		}
		return $counter;
    } 
		
    /**
     * Converts an array to attribute pairs
     *
     * @param array Values
     * @param string TagName
     * @return int Number of attributes created
     */
    public function arrayAsAttributes(Array $values, $tag )
    {
		//	Calculate the tag name
		if( is_string( $tag ) )
		{
			$tag = $this->getElementsByTagName( $tag );
			
			//	Makes sure the tag is valid
			if( ! $tag = $tag->item( 0 ) ){ return 0; }
		}
		elseif( $tag instanceof DOMElement ){ $tag = $tag; }
		else{ return 0; }
		$counter = 0;
        foreach( $values as $key => $value )
		{
			$key = htmlspecialchars( htmlspecialchars_decode( $key ) );
			$value = htmlspecialchars( htmlspecialchars_decode( $value ) );
			
			//	Because of the preset ID attribute in some fields I need to make sure there are no replacement inserts
			if( $tag->getAttribute( $key ) ){ continue; }
			if( $tag->setAttribute( $key, $value ) ){ $counter++; }
		}
		return $counter;
    } 
		
    /**
     * Converts an update attributes pairs with array
     *
     * @param array Values
     * @param mixed TagName or DOMElement
     * @return int Number of attributes updated
     */
    public function updateAttributes(Array $values, $tag = null )
    {
		//	We need id as a primary key
		if( empty( $values['id'] ) )
		{
			return 0;
		}

		$this->setId( 'id', $tag );
		
		if( ! $tag = $this->getElementById( $values['id'] ) )
		{
			return 0;
		}
		$counter = 0;
        foreach( $values as $key => $value )
		{
			$key = htmlspecialchars( htmlspecialchars_decode( $key ) );
			$value = htmlspecialchars( htmlspecialchars_decode( $value ) );
			if( $tag->setAttribute( $key, $value ) )
			{
				$counter++;
			}
		}
		return $counter;
    } 
	
    /**
     * Fetches the attributes  of one record in an xml doc simulating a dbase table
     *
     * @param array Attributes to fetch
     * @param int The ID of the attribute record
     * @return array A multidimentional array of Attributes
     */
    public function fetchOneAttributes( Array $attributes, $id )
    {	
		$result = array();
		$this->setId( 'id', $id );
		
		if( ! $tag = $this->getElementById( $id ) )
		{
			//	return early
			return $result;
		}
		
		foreach( $attributes as $attribute )
		{
			$result[$attribute] = $each->getAttribute( $attribute );
		}
		return (array) $result;
    } 
	
    /**
     * Fetches the attributes in an xml doc simulating a dbase table
     *
     * @param array Attributes to fetch
     * @param mixed The Domnode to select from
     * @return array A multidimentional array of Attributes
     */
    public function fetchAttributes( Array $attributes, $node = null )
    {
		$node = $this->getNode( $node );
		$result = array();
		foreach( $node->childNodes as $each )
		{
			$present = array();
			foreach( $attributes as $attribute )
			{
				$present[$attribute] = $each->getAttribute( $attribute );
			}
			$result[] = $present;
		}
		return $result;
    } 
	
    /**
     * Fetchs an array of Attribute from a particular tag
     *
     * @param mixed Tag
     * @return array
     */
    public function getTagAttributes( $tag )
    {
        $tag = $this->getNode( $tag );
        $result = array();
		if( ! $tag->attributes ){ return false; }
        foreach( $tag->attributes as $attribute )
        {
            if( ! $attribute instanceof DOMAttr ){ continue; }
            $result[$attribute->name] = $attribute->value;
        }
        return $result;
    } 

	public function exportHTML( $node )
	{
			$voids = array( 'area',
					'base',
					'br',
					'col',
					'colgroup',
					'command',
					'embed',
					'hr',
					'img',
					'input',
					'keygen',
					'link',
					'meta',
					'param',
					'source',
					'track',
					'wbr' );

			// Every empty node. There is no reason to match nodes with content inside.
			$query = '//*[not(node())]';
			$nodes = new DOMXPath($this);
			$nodes = $nodes->query($query);

			foreach ($nodes as $n) {
					if (! in_array($n->nodeName, $voids)) {
							// If it is not a void/empty tag,
							// we need to leave the tag open.
							$n->appendChild(new DOMComment('NOT_VOID'));
					}
			}

			// Let's remove the placeholder.
			return str_replace('<!--NOT_VOID-->', '', $this->saveXML( $node ));
	}	

    /**
     * Returns the xml as a well formatted text
     * and that can be displayed in the browser
     * @param void
     * @return string
     */
    public function view( DOMNode $node = null)
    {
		header('Content-type: text/xml');
        return $this->saveXML( $node );
    } 
	// END OF CLASS
}
