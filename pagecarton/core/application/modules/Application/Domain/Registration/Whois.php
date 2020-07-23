<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @advert   Ayoola
 * @package    Application_Domain_Registration_Whois
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Whois.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Domain_Registration_Exception 
 */
 
require_once 'Application/Domain/Exception.php';


/**
 * @advert   Ayoola
 * @package    Application_Domain_Registration_Whois
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Domain_Registration_Whois extends Ayoola_Dbase_Table_Abstract_Xml_Protected
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.02';

	protected $_dataTypes = array
	( 
		'extension' => 'INPUTTEXT, UNIQUE',
		'server' => 'INPUTTEXT',
		'badnews_length' => 'INPUTTEXT',
		'badnews_content' => 'INPUTTEXT',
	);
	// END OF CLASS
}
