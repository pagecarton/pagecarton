<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Access_Bar
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Bar.php 3.6.2012 8.36am ayoola $
 */

/**
 * @see Ayoola_Access_Abstract
 */
 
require_once 'Ayoola/Access/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Access_Bar
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Access_Bar extends Ayoola_Access_Login
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Options to show in layout editor
     *
     * @var array
     */
	protected $_classOptions = array( 'Dark Bottom' => 'Dark Bottom', 'Dark Top' => 'Dark Top', 'In-line Dark' => 'In-line Dark', 'Transparent Top' => 'Transparent Top', 'Transparent' => 'Transparent', );
	
    /**
     * Style to use for individual class option
     *
     * @var boolean
     */
	protected static $_classOptionsStyle = array
	(
		'Dark Bottom' => 'position:fixed;bottom:0px;left:0px;right:0px;background-color:#eee;color:#000;',
		'Dark Top' => 'position:fixed;top:0px;left:0px;right:0px;background-color:#eee;color:#000;' ,
		'Transparent Top' => 'position:fixed;top:0px;left:0px;right:0px;' ,
		'In-line Dark' => 'background-color:#eee;color:#000;' ,
		'Transparent' => '' ,
	);
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
		
    /**
     * Whether to show remember me option
     *
     * @var string
     */
	public static $showRememberMe = false;
	
    /**
     * This method performs the class' essense.
     *
     * @param void
     * @return boolean
     */
    public function init()
    {
		$this->setViewContent( $this->getMessage() );
		//	Try to login with the form
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		parent::init();
		$this->setViewContent( $this->getMessage(), true );
	//	$this->setViewContent(  );
	//	$this->setViewContent( $this->getForm()->view() );
    } 
	
    /**
     * Returns the displayed message
     *
     * @param void
     * @return boolean
     */
    public function getMessage()
    {
		$message = null;
		$styleToUse = @self::$_classOptionsStyle[$this->getParameter( 'option' )] ? : $this->getParameter( 'option' );
		$message .= '<span style="min-width:100%;z-index:1;overflow:hidden;display:block;' . $styleToUse . '"><span style="">';
		
		//	Check if there is a logged in user
		if( $userInfo = Ayoola_Application::getUserInfo() )
		{ 
		//	var_export( $userInfo );
		//	$signOutLink = '<form action="/accounts/signout/?previous_url=' . Ayoola_Page::getCurrentUrl() . '"><input type="submit" value="Sign Out" /></form>';
			$signOutLink = '<a href="' . Ayoola_Application::getUrlPrefix() . '/account/"><input value="My Account" type="button" /></a>  <a href="' . Ayoola_Application::getUrlPrefix() . '/accounts/signout/?previous_url=' . Ayoola_Page::getCurrentUrl() . '"><input value="Sign Out" type="button" /></a>';
			$message .= "<span style='float:left;'>Welcome {$userInfo['firstname']}! You are logged in as {$userInfo['username']}</span>";
			$message .= "<span style='float:right;padding:1em;'>{$signOutLink}</span>";    
		}
		else
		{
	//		$message .= 'Login ';
		//	$signUpLink = '<form action="/accounts/signup/?previous_url=' . Ayoola_Page::getCurrentUrl() . '"><input type="submit" value="Sign Up" /></form>';
			$signUpLink = '<a href="' . Ayoola_Application::getUrlPrefix() . '/accounts/signup/?previous_url=' . Ayoola_Page::getCurrentUrl() . '"><input value="Sign Up" type="button" /></a>';
			$message .= "<span style='float:right;padding:1em;'>{$signUpLink}</span>";
			$message .= $this->getForm()->view();
		//	$message .= "<span style='float:left;'>{$this->getForm()->view()}</span>";
		}
		$message .= '</span></span>';
		return $message;
	//	$this->setViewContent(  );
	//	$this->setViewContent( $this->getForm()->view() );
    } 
	
    /**
     * Returns object_name will become the form name or id
     * 
     */
	protected function getClassOptions()
	{
		return (array) $this->_classOptions;
	}
	// END OF CLASS
}
