<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Template_Editor_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 10-26-2011 9.13pm ayoola $
 */

/**
 * @see 
 */
 
require_once 'Ayoola/Abstract/Table.php';


/**
 * @category   PageCarton
 * @package    Application_Article_Template_Editor_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Article_Template_Abstract extends Ayoola_Abstract_Template
{	
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Article_Template'; 
	
	// END OF CLASS
}
