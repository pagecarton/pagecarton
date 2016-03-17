<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Adapter_Abstract_Image
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Image.php 1.21.2012 5.13pm ayoola $
 */

/**
 * @see Ayoola_Doc_Adapter_Abstract
 */
 
require_once 'Ayoola/Doc/Adapter/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Adapter_Abstract_Image
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Doc_Adapter_Abstract_Image extends Ayoola_Doc_Adapter_Abstract
{

    /**
     * The Default Content Type to be used for the Documents
     *
     * @var string
     */
	protected $_defaultContentType = 'image/';
	
		
    /**
     * This method outputs the document inline with HTML
     *
     * @param void
     * @return mixed
     */
    public function viewInline( $title = null )
    {
		$content = null;
		require_once 'Ayoola/Xml.php';
		$xml = new Ayoola_Xml();
		$div = $xml->createElement( 'div' );
		$xml->appendChild( $div );
		foreach( $this->getPaths() as $path )
		{
			require_once 'Ayoola/Doc.php';
			$uri = Ayoola_Doc::pathToUri( $path );
			if( $dedicatedUri = Ayoola_Doc::uriToDedicatedUrl( $uri ) )  
			{
				$uri = $dedicatedUri;
			} 
		//	var_export( $path );
		//	var_export( $uri );
			$image = $xml->createElement( 'img' );
			$image->setAttribute( 'style', 'max-width:100%;' );
			$image->setAttribute( 'title', $title );
			$image->setAttribute( 'src', $uri );

			// Default method is to include the document
			$xml->documentElement->appendChild( $image );
		}
		//exit( var_export( $xml->saveHTML() ) );
		return $xml->saveHTML(); 
    } 
	// END OF CLASS
}
