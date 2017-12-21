<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_Table
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Table.php Thursday 7th of September 2017 01:13AM  $
 */

/**
 * @see PageCarton_Table
 */


class Application_Article_Table extends PageCarton_Table
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
  'article_url' => 'INPUTTEXT',
  'category_name' => 'JSON',
  'username' => 'INPUTTEXT',
  'auth_level' => 'JSON',
  'article_type' => 'INPUTTEXT',
  'true_post_type' => 'INPUTTEXT',
  'article_modified_date' => 'INT',
  'article_creation_date' => 'INT',
);


	// END OF CLASS
}
