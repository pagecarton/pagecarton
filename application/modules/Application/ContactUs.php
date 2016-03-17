<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_ContactUs
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ContactUs.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_ContactUs
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_ContactUs extends Ayoola_Dbase_Table_Abstract_Xml
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.02';

	protected $_dataTypes = array
	( 
		'contactus_firstname' => 'INPUTTEXT',
		'contactus_lastname' => 'INPUTTEXT',
		'contactus_company' => 'INPUTTEXT',
		'contactus_email' => 'INPUTTEXT',
		'contactus_web_address' => 'INPUTTEXT',
		'contactus_phone_number' => 'INPUTTEXT',
		'contactus_subject' => 'INPUTTEXT',
		'contactus_message' => 'TEXTAREA',
		'contactus_creation_date' => 'INT',
		'contactus_first_view_date' => 'INT',
		'contactus_last_view_date' => 'INT',
		'contactus_creator_user_id' => 'INT',
	);
	// END OF CLASS
}
