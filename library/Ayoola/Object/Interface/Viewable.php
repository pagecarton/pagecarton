<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Object_Interface_Viewable
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Interface.php 11-9-2011 8.53pm ayoola $
 */

/**
 * @see 
 */
 
	//require_once 'Ayoola/';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Object_Interface_Viewable
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

interface Ayoola_Object_Interface_Viewable
{
	
    /**
     * Returns html content that is useful for display. 
     * Depends on the situation and environment, it will return different content
     * @param void
     * @return string Mark-Up for the view template
     */
    public function view();

    /**
	 * Just incoporating this - So that the layout can be more interative
	 * The layout editor will be able to pass a parameter to the viewable object				
     * @param mixed Parameter set from the layout editor
     * @return null
     */
    public function setViewParameter( $parameter );

    /**
	 * Just incoporating this - So that the layout can be more interative
	 * The layout editor will be able to pass a parameter to the viewable object				
     * @param mixed Parameter set from the layout editor
     * @return null
     */
    public function setViewOption( $parameter );
 
}
