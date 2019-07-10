<?php
 
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Type_Download
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Download.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Type_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Article_Type_Download
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Type_Download extends Application_Article_Type_Abstract
{
 	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Download File'; 

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
			if( ! $data = $this->getParameter( 'data' ) )
			{
				$data = $this->getIdentifierData();
			}
			
			//	check if we must be logged inn
			if( @in_array( 'require_user_info', $data['download_options'] ) || intval( @$data['item_price'] ) )
			{
			//	var_export( @$data['download_options'] );
				if( ! Ayoola_Application::getUserInfo() ) 
				{
					@$urlToGo = '' . Ayoola_Application::getUrlPrefix() . '/accounts/signin/?previous_url=' . htmlentities( $data['article_url'] . '&x_url=' . $_REQUEST['x_url'] );
	//				header( 'Location: /accounts/signin/?previous_url=' . $data['article_url'] );
	//				exit();
					$this->setViewContent( '<p>You are required to sign in before you can access this document.</p>', true );
					$this->setViewContent( self::__( '<input type="button" value="Sign in to download" onClick="window.location=\'' . $urlToGo . '\'" >' ) );
					return false;
				}
			}
			//	check if we have enough money to download file
			if( intval( @$data['item_price'] ) )
			{
				$userInfo = Ayoola_Access::getAccessInformation();
			//	var_export( $data['item_price'] );
			//	var_export( $userInfo['wallet_balance'] );
				if( @floatval( $data['item_price'] ) > floatval( @$userInfo['wallet_balance'] ) )
				{
					$amount = @$data['item_price'] - @$userInfo['wallet_balance'];
					$filter = 'Ayoola_Filter_Currency';
					$filter = new $filter();
					$filter::$symbol = Application_Settings_Abstract::getSettings( 'Payments', 'default_currency' );
					$neededFunds = $filter->filter( $amount );

					$this->setViewContent( self::__( '<p class="boxednews badnews">You need an additional ' . $neededFunds . ' in your wallet to download this file.</p>' ) );
					$this->setViewContent( Application_Wallet_Fund::viewInLine( array( 'amount' => $amount, 'checkout_requirements' => @$data['article_requirements'], 'button_value' => $this->getParameter( 'button_value' ) ? : 'Add funds to download', 'return_url' => 'http://' . Ayoola_Page::getDefaultDomain() . '' . Ayoola_Application::getUrlPrefix() . '' . $data['article_url'] ) ) );
					return false;
				}
			}
			
			$this->createForm( 'Download', 'Download "' . $data['article_title'] . '" to your device.' );
			$form = $this->getForm()->view();
			$this->setViewContent( $form, true );
		
			$values = $this->getForm()->getValues();
			if( $values || @$_REQUEST['auto_download'] )
			{ 
				if( intval( @$data['item_price'] ) && ! self::hasPriviledge() )
				{
					//	Administrators don't pay for downloads for testing purposes.
					//	charge the user
				//	var_export( $orderInfo );
					$transferInfo = array();
					
					// send to the owner of the article
					$transferInfo['to'] = $data['username'];
					$transferInfo['from'] = Ayoola_Application::getUserInfo( 'username' );
					$transferInfo['amount'] = intval( @$data['item_price'] );
					$transferInfo['notes'] = $data['article_title'];
					Application_Wallet::transfer( $transferInfo );
			//		var_export( $transferInfo );
			//		exit();
				}
				$_GET['article_url'] = @$values['article_url'];
				$data = $this->getIdentifierData() ? : $this->getParameter( 'data' );
				$this->createForm( 'Download', '', $data );
				$this->setViewContent( $this->getForm()->view(), true );
				
				//	LOG
				
				//	Notify Admin
				$mailInfo['subject'] = 'Download Attempted';
				$mailInfo['body'] = 'A document titled "' . $data['article_title'] . '", has been downloaded by a user. You can view the file by clicking this link: http://' . Ayoola_Page::getDefaultDomain() . '' . Ayoola_Application::getUrlPrefix() . '' . strtolower( $data['article_url'] ) . '.
				
				Here is a captured information of the user: ' . var_export( Ayoola_Application::getUserInfo(), true ) . '.
				Here is a captured information provided by the user when accessing the file: ' . self::arrayToString( $values ) . '.
				';
				Application_Log_View_General::log( array( 'type' => 'Download', 'info' => array( $mailInfo ) ) );
				
				//	Log into the database 
				$table = Application_Article_Type_Download_Table::getInstance();
				$table->insert( array(
										'username' => Ayoola_Application::getUserInfo( 'username' ),
										'article_url' => $data['article_url'],
										'timestamp' => time(),
								) 
				);
				$secondaryValues = array( 'article_url' => $data['article_url'], 'download_count_total' => @++$data['download_count_total'] );
				self::saveArticleSecondaryData( $secondaryValues );
						//	var_export
				if( is_array( $data['download_options'] ) && in_array( 'download_notification', $data['download_options'] ) && @$data['username'] )
				{
					//	Retrieve the information of the uploader
			//		if( $data['username'] )
				//	{
						$class = new Application_User_List();
						$class->setIdentifier( array( 'username' => $data['username'] ) );
						$userInfo = $class->getIdentifierData();
				//	$class = new Application_User_List( array( 'user_id' => $data['user_id'] ) );
				//	$userInfo = $class->getIdentifierData(); 
			//		exit( var_export( $userInfo ) );
			
					//	Notify Uploader
					$mailInfo['subject'] = 'Download Attempted';
					$mailInfo['to'] = $userInfo['email'];
					$mailInfo['body'] = 'A file titled "' . $data['article_title'] . '", has been downloaded by a user. You can view the file by clicking this link: http://' . Ayoola_Page::getDefaultDomain() . '' . Ayoola_Application::getUrlPrefix() . '' . strtolower( $data['article_url'] ) . '.
					
					Here is a captured information provided by the user when accessing the file: ' . self::arrayToString( $values ) . '.
					';
					try
					{
						@self::sendMail( $mailInfo );
					}
					catch( Ayoola_Exception $e ){ null; }
				}
				
				if( @$values['download_password'] != @$data['download_password'] )
				{
				
			//		$this->getForm()->setBadnews( 'Invalid Download Password' );
			//		$this->setViewContent( $this->getForm()->view(), true );
					return false;
				}
				static::getDownloadContent( $data );
			}
			else
			{
				
			}
		}
		catch( Exception $e )
		{ 
			$this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>', true );
			$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $this->getForm()->view() );
			return false;
		}
    } 
		
    /**
     * Form to display Download
     * 
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'enctype' => 'multipart/form-data', 'data-not-playable' => 'data-not-playable' ) );
		$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;
		$fieldset->hashElementName = true;
		$form->submitValue = $submitValue ;
	//	$fieldset->placeholderInPlaceOfLabel = true;
	//	$_GET['article_url'] = Ayoola_Form::getGlobalValue( 'article_url' );
		$data = $values ? : $this->getParameter( 'data' ); 
	//	var_export( $data );
	
		if( @$data['download_password'] ) 
		{
			$fieldset->addElement( array( 'name' => 'download_password', 'type' => 'InputPassword', 'label' => 'This document requires a password. Please enter the password in this field', 'value' => '' ) );
			$fieldset->addRequirement( 'download_password', array( 'DefiniteValueSilent' => $data['download_password'] ) );
		}
		//	download
		$fieldset->addElement( array( 'name' => 'article_url', 'type' => 'Hidden', 'value' => @$data['article_url'] ) );
	//	$fieldset->addRequirement( 'Download_answer', array( 'ArrayKeys' => $data['Download_options'] ) );
	//	$fieldset->addLegend( $legend );
		$form->setParameter( array( 'requirements' => @$data['article_requirements']) );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	
	// END OF CLASS
}
