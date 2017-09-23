<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Object_Interface_Playable
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Playable.php 4.25.2012 10.58pm ayoola $
 */

/**
 * @see Ayoola_Object_Interface_Viewable
 */
 
require_once 'Ayoola/Object/Interface/Viewable.php';
//var_export( __FILE__ );

/**
 * @category   PageCarton CMS
 * @package    Ayoola_Object_Interface_Playable
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

interface Ayoola_Object_Interface_Playable extends Ayoola_Object_Interface_Viewable
{

    /**
	 * A class method that will complete the view process
	 * 
     * @param mixed The View Parameter
     * @param mixed The View Option
     * @return string
     */
    public static function viewInLine( $viewParameter, $viewOption );
 
}
