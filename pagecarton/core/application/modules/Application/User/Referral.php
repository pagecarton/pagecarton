<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Application
 * @package    Application_User_Referral
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Referral.php date time username $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract
 */
 
require_once 'Ayoola/Dbase/Table/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_Referral
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Referral extends Ayoola_Dbase_Table_Abstract_Xml_Protected
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.17';

	protected $_dataTypes = array    
	( 
		'referrer' => 'INPUTTEXT',
		'referral' => 'INPUTTEXT, UNIQUE',
		'r_time' => 'INT',
	);
	// END OF CLASS
}
