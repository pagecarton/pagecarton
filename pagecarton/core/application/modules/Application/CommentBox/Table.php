<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_CommentBox_Table
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Table.php Sunday 17th of June 2018 02:30AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class Application_CommentBox_Table extends PageCarton_Table
{

    /**
     * The table version (SVN COMPATIBLE)
     *
     * @param string
     */
    protected $_tableVersion = '0.0';  

    /**
     * Table data types and declaration
     * array( 'fieldname' => 'DATATYPE' )
     *
     * @param array
     */
	protected $_dataTypes = array (
  'comment' => 'INPUTTEXT',
  'url' => 'INPUTTEXT',
  'article_url' => 'INPUTTEXT',
  'profile_url' => 'INPUTTEXT',
  'display_name' => 'INPUTTEXT',
  'email' => 'INPUTTEXT',
  'website' => 'INPUTTEXT',
  'creation_time' => 'INPUTTEXT',
  'parent_comment' => 'INPUTTEXT',
  'hidden' => 'INT',
  'enabled' => 'INT',
  'approved' => 'INT',
);


	// END OF CLASS
}
