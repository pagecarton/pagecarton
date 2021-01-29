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

class Application_Article_PostList extends Application_Article_ShowAll
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
	protected static $_objectTitle = 'Post List'; 

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
				
				return $this->setViewContent(  '' . self::__( '<p class="badnews">The requested article was not found on the server. Please check the URL and try again.</p>' ) . '', true  );
			}

            $parameters = $this->getParameter();
            $parameters['article_url'] = $data['post_list'];
            $parameters['no_of_post_to_show'] = 100;

            if( $this->getParameter( 'post_list_spotlight' ) )
            {  
              
                if( empty( $_REQUEST['x_url'] ) )
                {                
                    $parameters['no_of_post_to_show'] = 1;

                    $class = new Application_Article_ShowAll( $parameters + array( 'return_object_data' => true ) );
                    $class->init();
                    $response = $class->view();
    
                    $parameters['data'] = $response[0];
                }
                $class = new Application_Article_View( $parameters );
                $class->init();
            }
            else
            {
                $parameters['single_post_pagination'] = true;
                $parameters['post_list_article_url'] = $data['article_url'];
                $parameters['no_init'] = true;
    
    
                $class = new Application_Article_ShowAll( $parameters );
                $class->init();
                $this->setObjectTemplateValues( array( 'post_list_article_url' => $data['article_url'] ) );
            }

            $this->setViewContent( $class->view() );

            $this->setParameter( $class->getParameter() );

            if( $class->getParameter( 'markup_template' ) )
            $this->_parameter['markup_template'] = $class->view();

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
		$form->submitValue =  'Save List';

        $fieldset = new Ayoola_Form_Element;

        $v = array();
        if( $values['post_list'] )
        {   
            foreach( $values['post_list'] as $each )
            {
                $record = Application_Article_Table::getInstance()->selectOne( null, array( 'article_url' => $each ) );
                $v[$each] = $record['article_title'];
            }
        }

        $fieldset->addElement( 
            array( 
            'name' => 'post_list', 
            'label' => 'List', 
            'config' => array( 
                'ajax' => array( 
                    'url' => '' . Ayoola_Application::getUrlPrefix() . '/widgets/Application_Article_Search',
                    'delay' => 1000
                ),
                'placeholder' => 'e.g. Post Title',
                'minimumInputLength' => 2,   
            ), 
            'multiple' => 'multiple', 
            'type' => 'Select2', 
            'value' => $v 
            )
            ,
            $v
        ); 
    //    $fieldset->addRequirements( array( 'NotEmpty' => null ) );

		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 
	// END OF CLASS
}
