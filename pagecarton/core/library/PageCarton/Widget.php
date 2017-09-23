<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PageCarton_Widget
 * @copyright  Copyright (c) 2011-2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Widget.php 8.25.2017 12:14pm ayoola $
 */

/**
 * @see Ayoola_Abstract_Table
 */

class PageCarton_Widget extends Ayoola_Abstract_Table
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player. Defaults to just admin users
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99, 98 );
	


	// END OF CLASS
}
