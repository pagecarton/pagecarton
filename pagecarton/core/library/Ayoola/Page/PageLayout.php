<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_PageLayout
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: PageLayout.php date time ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml_Protected
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml/Protected.php'; 


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_PageLayout
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_PageLayout extends Ayoola_Dbase_Table_Abstract_Xml_Protected
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.10';  

	protected $_dataTypes = array
	( 
		'layout_name' => 'INPUTTEXT,UNIQUE',  
		'layout_label' => 'INPUTTEXT',
		'layout_options' => 'JSON',
		'pagelayout_filename' => 'INPUTTEXT',
		'dummy_title' => 'JSON',
		'dummy_search' => 'JSON',
		'dummy_replace' => 'JSON',
		'article_url' => 'INPUTTEXT',
	);
	// END OF CLASS
}
