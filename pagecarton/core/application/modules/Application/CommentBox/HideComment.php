<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_CommentBox_HideComment
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: HideComment.php Tuesday 26th of December 2017 12:32PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_CommentBox_HideComment extends Application_CommentBox_Abstract
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
	protected static $_objectTitle = 'Hide Comment'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
			if( ! $data = self::getIdentifierData() )
			{ 
                return false;
            }
            $this->createConfirmationForm( 'Hide Comment', '"' . $data['comment'] . '"' );
            $this->setViewContent( $this->getForm()->view(), true );
            if( ! $values = $this->getForm()->getValues() ){ return false; }

            if( $this->updateDb( array( 'hidden' => 1 ) ) )
            { 
                $this->setViewContent(  '' . self::__( '<p class="goodnews">Comment successfully hidden</p>' ) . '', true  ); 
            }
            //  Code that runs the widget goes here...

             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent(  '' . self::__( '<p class="badnews">Theres an error in the code</p>' ) . '', true  ); 
            return false; 
        }
	}
	// END OF CLASS
}
