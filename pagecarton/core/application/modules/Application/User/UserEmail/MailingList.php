<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_User_UserEmail_MailingList
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: MailingList.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton
 * @package    Application_User_UserEmail_MailingList
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_UserEmail_MailingList extends Ayoola_Dbase_Table_Abstract_Xml
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.02';

	protected $_dataTypes = array
	( 
		'email' => 'INPUTTEXT, UNIQUE',	
		'class' => 'JSON',	//	Use this to use class to process links
		'invitees' => 'JSON',	//	Use this to store invitees
		'data' => 'JSON',	//	Other random data that may be needed by classes.
	);
	// END OF CLASS
}
