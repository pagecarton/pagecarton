<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Slideshow_Template_Template
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Template.php date time ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton
 * @package    Application_Slideshow_Template_Template
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Slideshow_Template_Template extends Ayoola_Dbase_Table_Abstract_Xml_Protected
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.02';

	protected $_dataTypes = array
	( 
		'template_name' => 'INPUTTEXT,UNIQUE',
		'template_label' => 'INPUTTEXT', 
		'template_screenshot' => 'INPUTTEXT', 
		'markup_template' => 'INPUTTEXT', 
		'markup_template_prefix' => 'INPUTTEXT', 
		'markup_template_suffix' => 'INPUTTEXT', 
		'template_options' => 'JSON', 
		'javascript_files' => 'JSON',  
		'css_files' => 'JSON', 
	);
	// END OF CLASS
}
