<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Level_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Subscription_Level_Abstract
 */
 
require_once 'Application/Subscription/Level/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Level_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Level_List extends Application_Subscription_Level_Abstract
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'subscription_id' );
	
    /**
     * Performs the whole process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
	//	$this->setViewContent( self::__( '<h3>Options:</h3>' ) );		
	//	$this->setViewContent( self::__( '<h4></h4>' ) );		
		$this->setViewContent( $this->getList() );		
    } 
	
    /**
     * Overrides the parent's || Sets _identifierData
     * 
     */
	public function setIdentifierData( $identifier = NULL )
    {
		$table = $this->getDbTable();
		$this->_identifierData = (array) $table->select( null, $this->getIdentifier() );
    } 
	
    /**
     * creates the list of the available subscription packages on the application
     * 
     */
	public function createList()
    {
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
		$this->setIdentifierData();
	//	var_export( $this->_identifierData );
		$list->listTitle = 'List of Subscription Levels ' . ( @$this->_identifierData[0]['subscription_label'] ? "for \"{$this->_identifierData[0]['subscription_label']}\"" : null );
		$list->setListOptions( array( 'Creator' => '<a rel="spotlight;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Level_Creator/?' . http_build_query( $this->getIdentifier() ) . '" title="Add a new category for this product or service."> + </a>' ) );
		$list->setData( $this->getIdentifierData() );
		$list->setKey( 'subscriptionlevel_id' );
		$list->setNoRecordMessage( 'This subscription package does not have a level yet.' );
		$list->createList(  
			array(
				'subscriptionlevel_name' => '<a title="%FIELD%" rel="shadowbox;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Level_Editor/subscriptionlevel_id/%KEY%/?' . http_build_query( $this->getIdentifier() ) . '">%FIELD%</a>', 
				'document_url' => '<img height="32" alt="%FIELD%" title="%FIELD%" src="%FIELD%" />',
				'+' => '<a  title="Add a price for this product category." rel="shadowbox;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Price_Creator/subscriptionlevel_id/%KEY%/">+</a>', 
				'-' => '<a title="Show available prices for this product category." rel="shadowbox;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Price_List/subscriptionlevel_id/%KEY%/">-</a>', 
				'X' => '<a title="Delete this product category." rel="shadowbox;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Subscription_Level_Delete/subscriptionlevel_id/%KEY%/?' . http_build_query( $this->getIdentifier() ) . '">X</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
