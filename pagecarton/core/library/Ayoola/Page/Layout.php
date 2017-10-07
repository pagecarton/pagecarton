<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Layout.php 10.25.2011 2.42pm ayoola $
 */

/**
 * @see Ayoola_Page_Layout_Abstract
 */
 
require_once 'Ayoola/Page/Layout/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Layout extends Ayoola_Page_Layout_Abstract
{

    /**
     * This method creates a list of Available layouts
     *
     * @param void
     * @return Ayoola_Page_Layout_List
     */
/*    public static function getList()
    {
        return( new Ayoola_Page_Layout_List() );
    } 
*/	
    /**
     * This method edits an existing page layout
     *
     * @param void
     * @return Ayoola_Page_Layout_Editor
     */
    public static function edit()
    {
        return( new Ayoola_Page_Layout_Editor() );
    } 
	
    /**
     * This method deletes an existing page layout
     *
     * @param void
     * @return Ayoola_Page_Layout_Delete
     */
    public static function delete()
    {
        return( new Ayoola_Page_Layout_Delete() );
    } 
	
    /**
     * This method creates a new page layout
     *
     * @param void
     * @return Ayoola_Page_Layout_Creator
     */
    public static function create()
    {
        return( new Ayoola_Page_Layout_Creator() );
    } 
	// END OF CLASS
}
