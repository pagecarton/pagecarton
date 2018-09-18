<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_CommentBox_Abstract
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Friday 22nd of December 2017 12:46PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_CommentBox_Abstract extends PageCarton_Widget
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'table_id' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'table_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_CommentBox_Table';
	
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
	protected static $_objectTitle = 'Abstract'; 

    /**
     * 
     * 
     * param array 
     * return array 
     */
	public function filterCommentData( & $data )  
    {
        if( $data['profile_url'] )
        {
            $profileData = Application_Profile_Abstract::getProfileInfo( $data['profile_url'] ) ? : array();
            $data = array_merge( $data, $profileData );
      //      var_export( $profileData );
       //     var_export( $data );
            if( ! empty( $profileData['user_id'] ) )
            {
                if( $info = Application_User_Abstract::getUserInfo( $profileData['user_id'] ) )
                {
                    $data = array_merge( $info, $data );
                }
            }
            $data['website'] =  Ayoola_Page::getHomePageUrl() . '/' . $data['profile_url'] . '';
        }
   //     var_export( $data );
  //      var_export( $data['display_name'] );
        if( empty( $data['display_name'] ) )
        {
            $data['display_name'] = $data['profile_url'];
        }
        if( strpos( $data['website'], ':' ) === false )
        {
            $data['website'] = 'http://' . $data['website'];
        }

        $data['comment'] = strip_tags( $data['comment'] );
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
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;
		$fieldset->placeholderInPlaceOfLabel = false;

    //      var_export( Ayoola_Application::getUrlPrefix() );
        $fieldset->addElement( array( 'name' => 'comment', 'style' => '', 'label' => '', 'placeholder' => 'Add a comment', 'type' => 'TextArea', 'value' => @$values['comment'] ) ); 
//$fieldset->addFilter( 'directory','Username' );
        $fieldset->addRequirement( 'comment', array( 'NotEmpty' => array( 'badnews' => 'Comment cannot be left blank.', ),'WordCount' => array( 2, 1000 ), ) );
        $currentUrl = rtrim( Ayoola_Application::getRuntimeSettings( 'real_url' ), '/' ) ? : '/';
//         var_export( $currentUrl );
        $fieldset->addElement( array( 'name' => 'url', 'type' => 'Hidden', 'value' => $currentUrl ) );
        $articleUrl = Ayoola_Application::$GLOBAL['post']['article_url'] ? : $_REQUEST['article_url'];
        $fieldset->addElement( array( 'name' => 'article_url', 'type' => 'Hidden', 'value' => $articleUrl ) ); 
        
		$fieldset->addLegend( $legend );
		$fieldset->addFilters( 'StripTags::Trim' );
		$form->addFieldset( $fieldset ); 

        $access = new Ayoola_Access();
        $userInfo = $access->getUserInfo();
//        var_export( $this->getGlobalValue( 'display_name' ) );
        if( ! @$userInfo['profile_url'] && ( ! $this->getGlobalValue( 'display_name' ) || ! $this->getGlobalValue( 'email' ) || ! $this->getGlobalValue( 'website' ) ) )
        {
            $fieldset = new Ayoola_Form_Element;
            $fieldset->placeholderInPlaceOfLabel = false;
            $fieldset->addElement( array( 'name' => 'display_name', 'style' => '', 'label' => 'Display Name', 'placeholder' => 'e.g. John Adegoke', 'type' => 'InputText', 'value' => @$values['display_name'] ) ); 
            $fieldset->addRequirement( 'display_name', array( 'NotEmpty' => null, 'WordCount' => array( 2, 100 ), ) );
            $fieldset->addElement( array( 'name' => 'email', 'style' => '', 'label' => 'Email Address ', 'placeholder' => 'e.g.  me@example.com', 'type' => 'InputText', 'value' => @$values['email'] ) ); 
            $fieldset->addRequirement( 'email', array( 'NotEmpty' => null, 'EmailAddress' => null, ) );
            $fieldset->addElement( array( 'name' => 'website', 'style' => '', 'label' => 'Web Address', 'placeholder' => 'e.g.  www.example.com', 'type' => 'InputText', 'value' => @$values['website'] ) ); 
            
            $fieldset->addFilters( 'StripTags::Trim' );
            $form->addFieldset( $fieldset ); 
        }

        

		$this->setForm( $form );
    } 
	// END OF CLASS
}
