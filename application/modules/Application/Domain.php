<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Domain
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Domain.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Domain
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Domain extends Ayoola_Dbase_Table_Abstract_Xml_Public 
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.08'; 

	protected $_dataTypes = array
	( 
		'domain_name' => 'INPUTTEXT, UNIQUE',
		'domain_default' => 'INPUTTEXT, UNIQUE',
		'sub_domain' => 'INPUTTEXT',
		'domain_options' => 'JSON',
		'application_dir' => 'INPUTTEXT',
		'domain_type' => 'INPUTTEXT',
		'redirect_destination' => 'INPUTTEXT',
		'redirect_code' => 'INPUTTEXT',
	);
	// END OF CLASS
}
