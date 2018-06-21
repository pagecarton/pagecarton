<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_Repository
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Repository.php Monday 14th of May 2018 07:32PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Ayoola_Page_Layout_Repository extends Ayoola_Extension_Import_Repository
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99, 98 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Browse Themes'; 
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_site = 'themes.pagecarton.org'; 
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_pluginType = 'theme'; 
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_pluginClass = 'Ayoola_Page_Layout_Creator'; 

	
    
    /**
     * Overides the parent class
     * 
     */
	public static function getOtherInstallOptions( $filename )
    {
        $options =  array( 
                            'auto_section',  
                            'auto_menu', 
                        ); 
        $values = array( 
                            'path' => $filename,
                            'layout_label' => $_GET['title'],
                            //   'theme_url' => 'cccccc',
                            'layout_options' => $options,
                            'layout_type' => 'upload',
        );
        return $values;
    }
	// END OF CLASS
}
