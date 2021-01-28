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

class Application_Article_PostList_Add extends Application_Article_PostList
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
	protected static $_objectTitle = 'Add to List'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 

			if( ! $data = $this->getIdentifierData() )
			{
				return false;				
			}
			if( $this->getParameter( 'show_to_editors_only' ) && ! self::isAllowedToEdit( $data ) )
			{
				$this->_parameter['markup_template'] = null; 
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
				
				return $this->setViewContent(  '' . self::__( '<p class="badnews">The requested article was not found on the server. Please check the URL and try again.</p>' ) . '', true  );
            }
            $eachPostTypeInfo = Application_Article_Type_Abstract::getOriginalPostTypeInfo( $data['article_type'] );
            if( $eachPostTypeInfo['article_type'] === 'post-list' || in_array( 'post-list', $eachPostTypeInfo['post_type_options'] ) )
            {
                $this->setViewContent( '<p class="badnews">' . sprintf( self::__( '"%s" is a list and cannot be added to another list' ), $data['article_title'] ) . '</p>' ); 
                return false;
            }

			$this->createForm( 'Continue...', 'Add "' . $data['article_title'] . '" to list', $data );
			$this->setViewContent( $this->getForm()->view() );
            if( ! $values = $this->getForm()->getValues() ){ return false; }
            

            $this->setViewContent( '<p class="goodnews">' . self::__( 'Lists saved' ) . '</p>', true ); 

            if( $values['new'] )
            {
                $post = array(
                    'article_title' => $values['new'],
                    'article_type' => $_REQUEST['article_type'] ? : 'post-list',
                );
                $post['post_list'][] = $data['article_url'];
                $class = new Application_Article_Creator( array( 'fake_values' => $post ) );
                $class->initOnce();
                if( $class->getForm()->getBadnews() )
                {
                    $this->setViewContent( '<a href="' . Ayoola_Application::getUrlPrefix() . '/widgets/Application_Article_Creator?article_type=' . $post['article_type'] . '" class="pc-notify-info">' . sprintf( self::__( 'List could not be automatically created. Click here to create it manually.' ), $post['article_title'] ) . '</a>' ); 
                }
                else
                {
                    $this->setViewContent( $class->view() ); 
                }
            }
            foreach( $values['lists'] as $each )
            {
                $post = self::loadPostData( $each );
                if( ! in_array( $data['article_url'], $post['post_list'] ) )
                {
                    $post['post_list'][] = $data['article_url'];
                    self::saveArticle( $post );
                    $this->setViewContent( '<p class="">' . sprintf( self::__( 'Post added to %s' ), '<a href="' . Ayoola_Application::getUrlPrefix() . '' . $post['article_url'] . '">' . $post['article_title'] . '</a>' ) . '</p>' ); 
                }
                else
                {
                    $this->setViewContent( '<p class="">' . sprintf( self::__( 'Post already in %s' ), '<a href="' . Ayoola_Application::getUrlPrefix() . '' . $post['article_url'] . '">' . $post['article_title'] . '</a>' ) . '</p>' ); 
                }

            }


		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( self::__( '<p class="badnews">Theres an error in the code</p>' ) ); 
            return false; 
        }
	}

    /**
     * creates the form for creating and editing page
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )  
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => true ) );
		$form->submitValue =  'Save Lists';

        $fieldset = new Ayoola_Form_Element;

        $parameters = array( 
                        'true_post_type' => 'post-list', 
                        'return_object_data' => true, 
                    );
                        
        $class = new Application_Article_ShowAll( $parameters );
        $class->initOnce() ;
        
        $response = $class->view();
        $ref = array();
        foreach( $response as $each )
        {
            $ref[$each['article_url']] = $each['article_title'];
        }
        if( $ref )
        {
            $fieldset->addElement( 
                array( 
                'name' => 'lists', 
                'label' => 'Add to Existing lists', 
                'multiple' => 'multiple', 
                'type' => 'SelectMultiple'
                )
                ,
                $ref
            ); 
        }
        if( empty( $ref ) || $_REQUEST['new_list'] )
        {
            $fieldset->addElement( 
                array( 
                'name' => 'new', 
                'label' => 'Create New list', 
                'placeholder' => "List name e.g. Bello's Favorite", 
                'type' => 'InputText'
                )
            ); 
    
        }


		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 
	// END OF CLASS
}
