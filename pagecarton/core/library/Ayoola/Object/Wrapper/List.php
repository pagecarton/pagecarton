<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Object_Wrapper_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Ayoola_Object_Wrapper_Abstract
 */
 
require_once 'Ayoola/Object/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Object_Wrapper_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Object_Wrapper_List extends Ayoola_Object_Wrapper_Abstract
{
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$table = $this->getDbTable();
	//	var_export( $table->select() );
		$table->getDatabase()->setAccessibility( $table::SCOPE_PROTECTED );  
		$all = $table->select( null, null, array( 'worsssk-arwrouddddnss00d-1-333' => true ) );
	//	var_export( $all );
		$table->getDatabase()->setAccessibility( $table::SCOPE_PRIVATE );

		$my = $table->select( null, null, array( 'workww---acrrwwwosssuwdnd-1-333' => true ) );
	//	var_export( $my );
	//	var_export( $all );

		
		foreach( $all as $key => $value )
		{
			if( in_array( $value, $my ) )
			{
				unset( $all[$key] );
			}
		}
	//	var_export( $all );
		
	//	$otherThemes = array_diff( $my, $all );
	//	var_export( $otherThemes );
		$this->setViewContent( $this->createPrivateList( $my ), true );		
	//	$this->setViewContent( '<h3>All Themes</h3>' );		
		$this->setViewContent( $this->createList( $all ) );		
    } 
	
    /**
     * creates the list of the available subscription packages on the Ayoola
     * 
     */
	public function createList( $data = array() )
    {
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
		$list->listTitle = 'My Object Wrappers';
//		$list->setData( $this->getDbData() );
		$list->setData( $data );
		$list->setKey( $this->getIdColumn() );
	//	var_export( $this->getDbData() );
		$list->setNoRecordMessage( 'You have not created any wrappers yet' );
		$list->createList(  
			array(
				'wrapper_label' => '%FIELD% <a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Object_Wrapper_Editor/?' . $this->getIdColumn() . '=%KEY%">(preview code)</a>', 
		//		'X' => '<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Object_Wrapper_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	
    /**
     * creates the list of the available subscription packages on the Ayoola
     * 
     */
	public function createPrivateList( $data = array() )
    {
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
		$list->listTitle = 'Other Object Wrappers';
	//	$list->setData( $this->getDbData() );
		$list->setData( $data );
		$list->setKey( $this->getIdColumn() );
	//	var_export( $this->getDbData() );
		$list->setNoRecordMessage( 'You have not created any wrappers yet' );
		$list->createList(  
			array(
				'wrapper_label' => '%FIELD% <a rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Object_Wrapper_Editor/?' . $this->getIdColumn() . '=%KEY%">(Edit)</a>', 
				'X' => '<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Object_Wrapper_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
