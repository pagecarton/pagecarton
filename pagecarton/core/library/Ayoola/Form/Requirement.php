<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Form_Requirement
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Requirement.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Form_Requirement
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Form_Requirement extends Ayoola_Dbase_Table_Abstract_Xml_Protected
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.01';

	protected $_dataTypes = array
	( 
		'requirement_name' => 'INPUTTEXT,UNIQUE',
		'requirement_label' => 'INPUTTEXT',
		'requirement_class' => 'INPUTTEXT',
		'requirement_goodnews' => 'INPUTTEXT',
		'requirement_dependencies' => 'ARRAY',
		'requirement_legend' => 'INPUTTEXT',
	);
	// END OF CLASS
}
