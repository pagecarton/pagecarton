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

                $form = $this->getForm();
                if( is_object( $form ) && method_exists( $form, 'view' ) )
                {
                    $this->setViewContent( $form->view() );
                }
                return false;
            }

            $parameters = array( 
                                    'return_object_data' => true, 
                                    'no_init' => true, 
                                    'search_mode' => true,
                                    'no_of_post_to_show' => 50, 
                                    'q' => $_GET['q'] 
                                );

            if( ! empty( $_GET['article_type'] ) )
            {
                $parameters['article_types'] = $_GET['article_type'];
            }
            if( ! empty( $_GET['true_post_type'] ) )
            {
                $parameters['true_post_type'] = $_GET['true_post_type'];
            }
            //var_export( $parameters );          
            $class = new Application_Article_ShowAll( $parameters );
            $class->init() ;
            
            $response = $class->view();

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
