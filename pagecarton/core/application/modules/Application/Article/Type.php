<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_Type
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt       
 * @version    $Id: Types.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Article_Abstract
 */
 
require_once 'Application/Article/Abstract.php';  


/**
 * @category   PageCarton CMS
 * @package    Application_Article_Type
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Type extends Ayoola_Dbase_Table_Abstract_Xml_Private  
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.07';

	protected $_dataTypes = array
	( 
		'post_type' => 'INPUTTEXT, PRIMARY',  
		'post_type_id' => 'INPUTTEXT',
		'article_type' => 'INPUTTEXT',
		'post_type_options' => 'JSON',
		'preset_keys' => 'JSON',
		'preset_values' => 'JSON',
		'supplementary_form' => 'INPUTTEXT',
		'post_type_custom_fields' => 'INPUTTEXT',
	);
	// END OF CLASS
}
