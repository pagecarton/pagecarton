<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Editor_Image
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Image.php 10-26-2011 9.13pm ayoola $
 */

/**
 * @see Ayoola_Page_Editor_Abstract
 */
 
require_once 'Ayoola/Page/Editor/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Editor_Image
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Editor_Image extends Ayoola_Page_Editor_Text
{
 	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'HTML Content with Image'; 
	
    /**
     * For editable div in Image editor 
     * REMOVED BECAUSE IT CONFLICTS WITH THE EDITOR
     * 
     * @var string
     */
//	protected static $_editableTitle = "Open HTML editor";  

    /**
     * The View Parameter From Image Editor
     *
     * @var string
     */
	protected $_viewParameter;
	
    /**
     * Differentiates each of this instance
     *
     * @var int
     */
	protected static $_counter = 0;
	
    /**
	 * Returns text for the "interior" of the Image Editor
	 * The default is to display view and option parameters.
	 * 		
     * @param array Object Info
     * @return string HTML
     */
    public static function getHTMLForLayoutEditor( $object )
	{
		$object['editable'] = @$object['editable'] ? : '<img title="Double-click this picture to change it." alt="" src="' . Ayoola_Application::getUrlPrefix() . '/img/placeholder-image.jpg?document_time=1" >';
		return parent::getHTMLForLayoutEditor( $object );
	}
 
	// END OF CLASS
}
