<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_CommentBox
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: CommentBox.php Friday 22nd of December 2017 11:03AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_CommentBox extends Application_CommentBox_Abstract
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
	protected static $_objectTitle = 'Add a comment'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...

            //  Code that runs the widget goes here...
			$fieldset = null;
			$submitValue = 'Post';
			if( ! empty( $_REQUEST['parent_comment'] ) )
			{
				$data = $this->getDbTable()->selectOne( null, array( 'table_id' => $_REQUEST['parent_comment'] ) );
				$fieldset = $data['comment'];
				$submitValue = 'Post Reply';
			}
			$this->createForm( $submitValue, $fieldset );
			$this->setViewContent( $this->getForm()->view() );

            if( ! $values = $this->getForm()->getValues() ){ return false; }
            $currentUrl = rtrim( Ayoola_Application::getRuntimeSettings( 'real_url' ), '/' ) ? : '/';
            $values['article_url'] = Ayoola_Application::$GLOBAL['post']['article_url'] ? : $_REQUEST['article_url'];
            $values['url'] = $currentUrl;


            $values['display_name'] = $values['display_name'] ? : $this->getGlobalValue( 'display_name' );	
            $values['email'] = $values['email'] ? : $this->getGlobalValue( 'email' );	
            $values['website'] = $values['website'] ? : $this->getGlobalValue( 'website' );	
			

            $values['creation_time'] = time();
            $values['parent_comment'] = $_REQUEST['parent_comment'];

			$url = $values['url'] ? : $values['article_url'];
			
			if( $values['article_url'] )
			{
				$postData = Application_Article_Abstract::loadPostData( $values );
				$title = $postData['article_title'];
				$url = $values['article_url'];
			}
			else
			{
				$pageInfo = Ayoola_Page::getInfo( $values['url'] );
				if( ! $pageInfo['title'] )
				{
					$pageInfo['title'] = array_pop( array_map( 'ucwords', explode( '/', str_replace( '-', ' ',	 $pageInfo['url'] ) ) ) );  
				}
				$title = $pageInfo['title'];
			}
            $defaultProfile = Application_Profile_Abstract::getMyDefaultProfile();
            $defaultProfile = $defaultProfile['profile_url'];
			$values['profile_url'] = $defaultProfile;
            
			
			//	Notify Admin
			$link = '' . Ayoola_Page::getHomePageUrl() . '' . $url;
			$mailInfo = array();
			$mailInfo['subject'] = 'A new comment added';
			$mailInfo['body'] = 'A new comment has been added on your site with the following information: "' . self::arrayToString( $values ) . '". 
			
			View the page where the comment was posted: ' . $link . '

			Moderate Comments: ' . Ayoola_Page::getHomePageUrl() . '/widgets/Application_CommentBox_Table_List
			';
			try
			{
		//		var_export( $mailInfo );
				@Ayoola_Application_Notification::mail( $mailInfo );
			}
			catch( Ayoola_Exception $e ){ null; }
		//	if( ! $this->insertDb() ){ return false; }
			if( $this->insertDb( $values ) )
			{ 
				$this->createForm( 'Post Another Comment', $fieldset );
				$this->setViewContent( $this->getForm()->view(), true );
				$this->setViewContent( self::__( '<div class="goodnews">Comment added successfully.</div>' ) ); 
				if( empty( $values['profile_url'] ) )
				{
					$this->setViewContent( self::__( '<div class="pc-notify-info">Save your profile to be able to manage your future comments on this website and also post a display picture. <a onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/widgets/Application_Profile_Creator\' );" href="javascript:" >Save Profile!</a></div>' ) ); 
				}
			}

			//	confirmation email for commenter
            self::filterCommentData( $values );
			$mailInfo['to'] = $values['email'];
			$mailInfo['subject'] = 'Your comment on "' . $title  . '"';
			$mailInfo['body'] = 'Your comment on "' . $title  . '" (' . $link  . ') has successfully been posted. Here is your comment below:

			"' . $values['comment']  . '"
			';
			self::sendMail( $mailInfo );

			//	notify previous commenters
			$where = array( 'url' => $values['url'] );
			if( $values['article_url'] )
			{
				$where = array( 'article_url' => $values['article_url'] );
			}
			$previousComments = $this->getDbTable()->select( null, $where );
			foreach( $previousComments as $each )
			{
				self::filterCommentData( $each );
				$mailInfo['subject'] = 'Someone also commented on "' . $title  . '"';
				$mailInfo['to'] = $each['email'];
				$mailInfo['body'] = '' . $values['display_name']  . ' also commented on "' . $title  . '" on ' . $link  . '';
				self::sendMail( $mailInfo );
			}

            // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent(  '' . self::__( '<p class="badnews">Theres an error in the code</p>' ) . '', true  ); 
            $this->setViewContent(  '' . self::__( '<p class="badnews">' . $e->getMessage() . '</p>' ) . '', true  ); 
            return false; 
        }
	}
	// END OF CLASS
}
