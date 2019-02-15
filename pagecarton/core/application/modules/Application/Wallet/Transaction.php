<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Wallet_Transaction
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Wallet.php 4.17.2012 11.53 ayoola $ 
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton
 * @package    Application_Wallet_Transaction
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Wallet_Transaction extends Ayoola_Dbase_Table_Abstract_Xml_Private
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.03';

	protected $_dataTypes = array
	( 
 		'from' => 'INPUTTEXT',
 		'to' => 'INPUTTEXT',
 		'amount' => 'INPUTTEXT',
 		'time' => 'INT',
 		'notes' => 'INPUTTEXT',
 	);
	// END OF CLASS
}
