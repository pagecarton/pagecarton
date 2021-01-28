<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Search
 * @copyright  Copyright (c) 2021 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Search.php Tuesday 26th of January 2021 12:08PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_Article_Search extends PageCarton_Widget
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 0 );
	
    /**
     * Response mode 
     *
     * @var string
     */
	protected $_playMode = self::PLAY_MODE_JSON;
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Post Search'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
            $destination = $_GET['q'];
            if( empty( $destination ) )
            {
                $this->_objectData['badnews'] = 'No post title has been typed in for autocomplete';
                $this->setViewContent( '<p class="badnews">' . $this->_objectData['badnews'] . '</p>', true );
                $this->setViewContent( $this->getForm()->view() );
                return false;
            }

            $parameters = array( 
                                    'search_mode' => 'phrase', 
                                    'return_object_data' => true, 
                                    'q' => $_GET['q'] 
                                );
                            
            $class = new Application_Article_ShowAll( $parameters );
            $class->initOnce() ;
            
            $response = $class->view();
        //    var_export( $parameters );
        //    var_export( $response );
            if( empty( $_GET['raw_response'] ) )
            {
                $ref = array();
                foreach( $response as $each )
                {
                    $ref[] = array( 
                        'id' => $each['article_url'],
                        'text' => $each['article_title'],
                    );
                }
                $response = array( 'results' => $ref );
            }
            $this->_objectData = $response;

            // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( self::__( '<p class="badnews">Theres an error in the code</p>' ) ); 
            return false; 
        }

    }
	// END OF CLASS
}
