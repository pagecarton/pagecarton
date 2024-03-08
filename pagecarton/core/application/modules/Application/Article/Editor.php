<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Article_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Article_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Editor extends Application_Article_Abstract
{
    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = self::getIdentifierData() ){ return false; }

            //	Only the owner can edit or priviledged user can edit
			//	Check settings
			$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
			if( ! self::isOwner( $data['user_id'] ) && ! self::isAllowedToEdit( $data ) && ! self::hasPriviledge( $articleSettings['allowed_editors'] ? : 98 ) && Ayoola_Application::getUserInfo( 'username' ) !== strtolower( $data['username'] ) ){ return false; }  

            if( ! $this->requireRegisteredAccount() )
            {
                return false;
            }
			if( ! $this->requireProfile() )
			{
				return false;
			}
			
			$this->createForm( 'Save...', 'Editing "' . $data['article_title'] . '"', $data );
			$this->setViewContent( $this->getForm()->view() );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			if( empty( $values['document_url_base64'] ) )
			{
				//	we are not interested in changing cover photo
				unset( $values['document_url_base64'] );
			}
			if( empty( $values['document_url'] ) )
			{
				//	we are not interested in changing cover photo
				unset( $values['document_url'] );
			}
			if( empty( $values['download_url'] ) )
			{
				//	we are not interested in changing download doc
				unset( $values['download_url'] );
			}
			if( empty( $values['download_base64'] ) )
			{
				//	we are not interested in changing download doc
				unset( $values['download_base64'] );
			}
			$access = new Ayoola_Access();
			if( $userInfo = $access->getUserInfo() )
			{
				$data['article_editor_user_id'] = $userInfo['user_id'];
				@$data['article_editor_username'] = is_array( @$data['article_editor_username'] ) ? $data['article_editor_username'] : array();
				array_push( $data['article_editor_username'], $userInfo['username'] );
			}
			//	Old owner is still the new owner
			$values['user_id'] = $data['user_id'];
			$values['username'] = $data['username'];
			if( isset( $values['datetime'] ) )
			{
				$values['article_creation_date'] = strtotime( $values['datetime'] );
			}
			$values['article_modified_date'] = time();
						
			//	making options that have been disabled to still be active.
			$values = array_merge( $data, $values );  
						
			self::saveArticle( $values );
	
			// Share
			$fullUrl = 'http://' . Ayoola_Page::getDefaultDomain() . '' . Ayoola_Application::getUrlPrefix() . '' . $values['article_url'] . '';
			$this->setViewContent(  '<div class="goodnews pc_give_space_top_bottom">' . self::__( 'Post successfully saved.' ) . '</div>', true  );
			$this->_objectData['article_url'] = $values['article_url'];  

            $this->setViewContent( '<div class="pc_give_space_top_bottom pc-btn-parent">' . self::getQuickPostLinks( $values ) . '</div>' );
        }
		catch( Application_Article_Exception $e )
		{ 
			$this->getForm()->oneFieldSetAtATime = false;
			$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $this->getForm()->view(), true );
			return false; 
		}
    } 
	// END OF CLASS
}
