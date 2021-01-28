<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_PostList
 * @copyright  Copyright (c) 2021 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: PostList.php Tuesday 26th of January 2021 11:56AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_Article_PostList_Sort extends Application_Article_PostList
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 1 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Sort List'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            $this->setParameter( array( 'no_auto_url_prefix' => true ) );
            if( ! $this->getParameter( 'markup_template_no_data' ) )
            {
                $this->_parameter['markup_template_no_data'] = self::__( '<p class="badnews">Cannot sort invalid list</p>' ); 
            }
                if( ! $data = $this->getIdentifierData() )
			{
                $this->_parameter['markup_template'] = false; 
				return false;				
			}
			if( $this->getParameter( 'show_to_editors_only' ) && ! self::isAllowedToEdit( $data ) )
			{
				$this->_parameter['markup_template'] = false; 
				return false;				
			}
			if( ! $data  
				|| ( ! @$data['publish'] && ! self::isOwner( @$data['user_id'] ) && ! @in_array( 'publish', @$data['article_options'] ) && Ayoola_Application::getUserInfo( 'username' ) !== strtolower( $data['username'] ) )   
				|| ( ! self::hasPriviledge( @$data['auth_level'] ) && ! self::isOwner( @$data['user_id'] ) )
				|| ! self::isAllowedToView( $data ) 
			)
			{
				if( Ayoola_Application::$GLOBAL['post']['article_url'] === $data['article_url'] )
				{
					
					//	IF WE ARE HERE, WE ARE NOT AUTHORIZED
					$access = Ayoola_Access::getInstance();          
					$access->logout();
					$login = new Ayoola_Access_Login();  
					$login->getObjectStorage( 'pc_coded_login_message' )->store( '' . self::__( 'You are not authorized to view this post. Please log in with an authorized account to continue' ) . '' . self::__( '' ) . '' );
					
					header( 'Location: ' . Ayoola_Application::getUrlPrefix() . '/accounts/signin/?pc_coded_login_message=1&previous_url=' . $data['article_url'] );
					exit();
				}
                $this->_parameter['markup_template'] = false; 
				return $this->setViewContent(  '' . self::__( '<p class="badnews">The requested article was not found on the server. Please check the URL and try again.</p>' ) . '', true  );
            }

            if( ! empty( $_POST['post_list'] ) && is_array( $_POST['post_list'] ) )
            {
                $this->_playMode = self::PLAY_MODE_JSON;
                foreach( $_POST['post_list'] as $key => $each )
                {
                    if( ! $post = self::loadPostData( $each ) )
                    {
                        unset( $_POST['post_list'][$key] );
                    }
                    $eachPostTypeInfo = Application_Article_Type_Abstract::getOriginalPostTypeInfo( $post['article_type'] );
                    if( $eachPostTypeInfo['article_type'] === 'post-list' || in_array( 'post-list', $eachPostTypeInfo['post_type_options'] ) )
                    {
                        unset( $_POST['post_list'][$key] );        
                    }
        
                }
                $data['post_list'] = $_POST['post_list'];
                self::saveArticle( $data );
				$this->_parameter['markup_template'] = false; 
                return false;
            }

            $id = 'sortable-' . time();
            $itemClass = $id . '-item';

            //  make sure this js goes after theme jquery
            Application_Javascript::addFile( 'https://code.jquery.com/ui/1.12.1/jquery-ui.js' );
            Application_Javascript::addCode( '
            $(document).ready(function() {
                $( function() {
                    $( "#' . $id . '" ).sortable(
                        {
                            update: function( event, ui ) {
                                var e = document.getElementsByClassName( "' . $itemClass . '" );
                                data = "";
                                for( var f = 0; f < e.length; f++ )
                                {
                                    data += ( "&post_list[]=" + e[f].getAttribute( "id" ) );
                                }
                                var splash = ayoola.spotLight.splashScreen();
                                $.post("",
                                data,
                                function(data, status){
                                    splash.close();
                                });
                            }
                        }
                    );
                    $( "#' . $id . '" ).disableSelection();
                  } );
            });
            ' 
            );
            $this->_objectTemplateValues = $data;
            foreach( $data['post_list'] as $each )
            {
                $post = self::loadPostData( $each );
                $post['item_class'] = $itemClass;

                $this->_objectTemplateValues['list'][] = $post;
            }
            $this->_objectTemplateValues['id'] = $id;

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
