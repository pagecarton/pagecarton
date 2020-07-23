<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_Layout_Preview
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Preview.php Saturday 16th of September 2017 10:23PM  $
 */

/**
 * @see PageCarton_Widget
 */

class Ayoola_Page_Layout_Preview extends Ayoola_Page_Layout_Abstract
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 0 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Theme Preview'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
            if( ! $data = $this->getIdentifierData() ){ return false; }
            
            header( 'Location: ' . Ayoola_Application::getUrlPrefix() . '/?pc_page_layout_name=' . $data['layout_name'] . '' );
            exit();
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent(  '' . self::__( 'Theres an error in the code' ) . '', true  ); 
            return false; 
        }
	}
	// END OF CLASS
}
