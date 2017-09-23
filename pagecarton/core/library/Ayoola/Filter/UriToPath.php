<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Filter_UriToPath
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 10-25-2011 3.25pm ayoola $
 */

/**
 * @see Ayoola_Filter_Interface
 */
 
require_once 'Ayoola/Filter/Interface.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Filter_UriToPath
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
 
class Ayoola_Filter_UriToPath implements Ayoola_Filter_Interface
{
    /**
     * available path types and there Templates
     *
     * @var array
     */
	protected $_pathTemplates; 
	
    /**
     * Available Types of path
     *
     * @var array
     */
	protected $_types = array(	
								'data' => array( 'path' => PAGE_DATA, 'ext' => EXT_DATA  ), //	relegated
								'data_php' => array( 'path' => PAGE_DATA, 'ext' => EXT  ),	//	now saving data as .php extension
								'data_json' => array( 'path' => PAGE_DATA, 'ext' => '.json'  ),	//	now saving data as .json extension
								'template' => array( 'path' => PAGE_TEMPLATE, 'ext' => TPL ), 
								'include' =>  array( 'path' => PAGE_INCLUDES, 'ext' => EXT )
							);
	
    /**
     * Type of path requested
     *
     * @var string
     */
	protected $_type;
	
    /**
     * Type of path requested
     *
     * @var string
     */
	protected $_uriPlaceholder = '%%URI%%';

    /**
     * Constructor
     *
     * @param string
     * 
     */
    public function __construct( $type = null )
    {
		$this->setType( $type );
    }

    /**
     * Sets the _types
     *
     * @param array
     */
    public function getTypes()
    {
		return (array) $this->_types;
    }

    /**
     * Sets the type of path requested
     *
     * @return string
     */
    public function getType()
    {
		return $this->_type;
    }

    /**
     * Sets the type of path requested
     *
     * @param string
     * 
     */
    public function setType( $type )
    {
		$this->_type = $type;
    }
	
    public function getPathTemplates( $uri = null )
    {
		if( null === $this->_pathTemplates )
		{
			$this->setPathTemplates( $uri );
		}
		return $this->_pathTemplates;
    }

    /**
     * Sets the type of path available
     *
     * @param array
     * 
     */
    public function setPathTemplates( $uri = null )
    {
		$paths = array();
		foreach( $this->getTypes() as $key => $values )
		{
			//	Change path to document path
			if( false != strpos( basename( $uri ), '.' ) )
			{
				$values['path'] = DOCUMENTS_DIR;
				$values['ext'] = null;
			}
			$paths[$key] = $values['path'] . $this->getUriPlaceholder() . $values['ext'];
		}
		return $this->_pathTemplates = $paths;
	}

    /**
     * Returns _uriPlaceholder
     *
     * @param void
     */
    public function getUriPlaceholder()
    {
		return $this->_uriPlaceholder;
	}

    public function filter( $value )
	{
		$paths = $this->getPathTemplates( $value );
	//	var_export( $paths );
		$result = array();
		require_once 'Ayoola/Loader.php';
		foreach( $paths as $name => $path )
		{
			$result[$name] = str_ireplace( $this->getUriPlaceholder(), $value, $path );
			if( $filePath = Ayoola_Loader::checkFile( $result[$name] ) )
			{
			//	$result[$name] = $filePath;
			}
		}
		return array_key_exists( $this->getType(), $result ) ? $result[$this->getType()] : $result;
	}
 
}
