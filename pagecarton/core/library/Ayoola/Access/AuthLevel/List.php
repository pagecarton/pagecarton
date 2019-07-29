<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Access_AuthLevel_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Ayoola_Access_AuthLevel_Abstract
 */
 
require_once 'Ayoola/Object/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Access_AuthLevel_List
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Access_AuthLevel_List extends Ayoola_Access_AuthLevel_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Priviledges'; 

    /**	Whether to translate widget inner conetent
     *
     * @var bool
     */
	public $translateInnerWidgetContent = true;
		
	
    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
		$table = $this->getDbTable();
		$table->getDatabase()->setAccessibility( $table::SCOPE_PROTECTED );  
		$all = $table->select( null, null, array( 'worsssk-arwrouddddnss00d-1-333' => true ) );
		$table->getDatabase()->setAccessibility( $table::SCOPE_PRIVATE );

		$my = $table->select( null, null, array( 'workww---acrrwwwosssuwdnd-1-333' => true ) );

		
		foreach( $all as $key => $value )
		{
			if( in_array( $value, $my ) )
			{
				unset( $all[$key] );
			}
		}
	//	krsort( $my );
	//	krsort( $all );
		$this->setViewContent( $this->createPrivateList( $my ), true );		
		$this->setViewContent( $this->createList( $all ) );		
    } 
	
    /**
     * creates the list of the available subscription packages on the Ayoola
     * 
     */
	public function createList( $data )
    {
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
		$list->listTitle = 'Preset ' . self::getObjectTitle();;
		$list->setData( $data );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'You have not created any access level' );
		$list->createList(  
			array(
				'auth_name' => null, 
				'auth_level' => null, 
		//		'edit' => '<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Access_AuthLevel_Editor/?' . $this->getIdColumn() . '=%KEY%">edit</a>', 
		//		'X' => '<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Access_AuthLevel_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	
    /**
     * creates the list of the available subscription packages on the Ayoola
     * 
     */
	public function createPrivateList( $data )
    {    
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
		$list->listTitle = 'My ' . self::getObjectTitle();;
		$list->setData( $data );
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'You have not created any access level' );
		$list->createList(  
			array(
				'auth_name' => null, 
				'auth_level' => null, 
				'edit' => '<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Access_AuthLevel_Editor/?' . $this->getIdColumn() . '=%KEY%">edit</a>', 
				'X' => '<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Access_AuthLevel_Delete/?' . $this->getIdColumn() . '=%KEY%">X</a>', 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
