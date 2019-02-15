<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_Layout_Pages_Delete
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php Friday 15th of September 2017 03:02PM  $
 */

/**
 * @see Ayoola_Page_Layout_Pages
 */

class Ayoola_Page_Layout_Pages_Delete extends Ayoola_Page_Layout_Pages
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
	protected static $_objectTitle = 'Duplicate a theme page'; 

    /**
     * 
     * 
     * @var Ayoola_Page_Page 
     */
	protected static $_table; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...

            //  Output demo content to screen
			if( ! $data = $this->getIdentifierData() ){ return false; }
		
		//	var_export( $this->getIdentifier() );
            $url = $this->getParameter( 'url' ) ? : @$_REQUEST['url'];
            
            $allPages = self::getPages( $data['layout_name'], 'list' );
            $allPages = array_combine( $allPages, $allPages );
            if( ! in_array( $url, $allPages ) )
            {
                $this->setViewContent( '<p class="badnews">Page not found in theme.</p>' ); 
                return false;   
            }
            if( $url === '/' )
            {
                $this->setViewContent( '<p class="badnews">You can not delete the index page.</p>' ); 
                return false;   
            }
            
			$this->createConfirmationForm( 'Delete', 'Completely delete theme page  "' . $url . '" in "' . $data['layout_label'] . '"' );

			$this->setViewContent( $this->getForm()->view(), true);
			if( ! $values = $this->getForm()->getValues() ){ return false; }


       //     return;
            $from = 'documents/layout/' . $data['layout_name'] . '' . $url . '.html';
        //    var_export( $from );
            if( ! $from = Ayoola_Loader::getFullPath( $from, array( 'prioritize_my_copy' => true ) ) )
            {
                $this->setViewContent( '<p class="badnews">Page not found in theme.</p>' ); 
                return false;   
            }

            if( unlink( $from ) )
            {
                $this->setViewContent( '<p class="goodnews">"' . $url . '" deleted successfully.</p>', true ); 
            }
            else
            {
                $this->setViewContent( '<p class="badnews">Theme Page could not be deleted.</p>' ); 
            }

             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( 'Theres an error in the code', true ); 
            return false; 
        }
	}
	// END OF CLASS
}
