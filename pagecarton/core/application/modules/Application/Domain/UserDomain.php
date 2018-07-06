<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Domain_UserDomain
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: UserDomain.php Friday 6th of July 2018 07:44AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class Application_Domain_UserDomain extends PageCarton_Table
{

    /**
     * The table version (SVN COMPATIBLE)
     *
     * @param string
     */
    protected $_tableVersion = '0.1';  

    /**
     * Table data types and declaration
     * array( 'fieldname' => 'DATATYPE' )
     *
     * @param array
     */
	protected $_dataTypes = array (
  'domain_name' => 'INPUTTEXT',
  'user_id' => 'INPUTTEXT',
  'username' => 'INPUTTEXT',
  'profile_url' => 'INPUTTEXT',
  'expiry' => 'INT',
);


	// END OF CLASS
}
