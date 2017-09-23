<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Browser
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Browser.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Ayoola_Doc_Abstract
 */
 
require_once 'Ayoola/Doc/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Browser
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Doc_Browser extends Ayoola_Doc_Abstract
{
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 0 );
 	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'File Manager'; 
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
			$docSettings = Ayoola_Doc_Settings::getSettings( 'Documents' );
			@$docSettings['allowed_viewers'] = $docSettings['allowed_viewers'] ? : array();
			$docSettings['allowed_viewers'][] = 98;
			if( ! Ayoola_Abstract_Table::hasPriviledge( $docSettings['allowed_viewers'] ) )
			{ 
				return false;
			}
			//	make a form to select directory
			$form = new Ayoola_Form();
			$form->submitValue = 'Browse';
			$fieldset = new Ayoola_Form_Element();

			$options = array(
								'mine' => 'My Documents',
						//		'pictures' => 'Images Only',
								'directory' => 'Directory Browser',
			);
			$fieldset->addElement( array( 'name' => 'mode', 'type' => 'Select', 'label' => 'Filter', ), $options );

			if( ( Ayoola_Form::getGlobalValue( 'mode' ) && 'directory' == Ayoola_Form::getGlobalValue( 'mode' ) ) )
			{
				$options = Ayoola_Doc::getDirectoriesRecursive( self::getDocumentsDirectory() );
				foreach( $options as $key => $value )
				{
					$dir = str_ireplace( self::getDocumentsDirectory(), '', $value );
					$dir = str_ireplace( DS, '/', $dir );
					$options[$dir] = $dir;
					unset( $options[$key] );
				}
				ksort( $options );
				$fieldset->addElement( array( 'name' => 'directories', 'type' => 'SelectMultiple', 'label' => 'Select Directory', ), $options );
			}
			$form->addFieldset( $fieldset );
			
		//	var_export( $options );
			$this->setViewContent( $form->view(), true );

			//	upload
			$html = '<a class="pc-btn" rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Doc_Upload_Link/" title="Upload a file">Upload File</a>';
			$html = Ayoola_Object_Wrapper_Abstract::wrap( $html, 'white-background' );
			$this->setViewContent( $html );

			$filterTime = new Ayoola_Filter_Time();
			$filterSize = new Ayoola_Filter_FileSize();
			$data = array();
			$values = $form->getValues();
			switch( empty( $values['directories'] ) )
			{
				case false:
				foreach( $values['directories'] as $each )
				{
				//	var_export( $values );
				//	var_export( $each );
					$files = Ayoola_Doc::getFilesRecursive( self::getDocumentsDirectory() . $each );
					$dir = self::getDocumentsDirectory();
					$dir = str_ireplace( DS, '/', $dir );
					foreach( $files as $eachFile )
					{
						$url = str_ireplace( $dir, '', $eachFile );
						$ext = array_pop( explode( '.', $url ) );
						if( ! is_file( $eachFile ) || isset( $data[$url] ) || $ext == $url )
						{
							continue;
						}
						$data[$url] = array( 'url' => $url, 'basename' => basename( $url ), 'ext' => strtoupper( $ext ), 'filesize' => $filterSize->filter( filesize( $eachFile ) ), 'modified' => $filterTime->filter( filemtime( $eachFile ) ), 'created' => $filterTime->filter( filectime( $eachFile ) ), 'by' => '' );
					}
				}
				break;
				default:
					if( Ayoola_Application::getUserInfo( 'username' ) )
					{
						$table = new Ayoola_Doc_Table();
				//		var_export( $table->select( null, array( 'username' => Ayoola_Application::getUserInfo( 'username' ) ) ) );
						foreach( $table->select( null, array( 'username' => Ayoola_Application::getUserInfo( 'username' ) ) ) as $each )
						{
							$url = $each['url'];
							$eachFile = self::getDocumentsDirectory() . $each['url'];
							$ext = array_pop( explode( '.', $url ) );
							if( ! is_file( $eachFile ) || isset( $data[$url] ) || $ext == $url )
							{
								continue;
							}
							$data[$url] = array( 'url' => $url, 'basename' => basename( $url ), 'ext' => strtoupper( $ext ), 'filesize' => $filterSize->filter( filesize( $eachFile ) ), 'modified' => $filterTime->filter( filemtime( $eachFile ) ), 'created' => $filterTime->filter( filectime( $eachFile ) ), 'by' => '' );
							if( strlen( $data[$url]['basename'] ) > 12 )
							{
								$data[$url]['basename'] = ( trim( substr( $data[$url]['basename'], 0, 12 ) ) . '...' );
							}
						}
					}
				break;
			}
		//	$data = array_unique( $data );
			ksort( $data );
			$html = Ayoola_Object_Wrapper_Abstract::wrap( $this->createList( $data )->view(), 'white-background' );
			$this->setViewContent( $html );
		}
		catch( Exception $e )
		{ 
		//	var_export( $e->getMessage() );
			$form->setBadnews( $e->getMessage() );
			$this->setViewContent( $form->view(), true );
			return false; 
		}
    } 
	
    /**
     * creates the Browser 
     * 
     */
	public function createList( $data )
    {
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
//		$list->listTitle = self::getObjectTitle();


		$list->setData( $data );
		$list->setListOptions( array( 'Creator' => ' ' ) );
//		$list->setListOptions( array( 'Creator' => '<a rel="spotlight;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Doc_Upload/" title="Upload a new document.">Upload File</a>' ) );

		$list->setKey( 'url' );
		$list->setNoRecordMessage( 'There is no file in the selected directory.' );
		$select = empty( $_REQUEST['field_name'] ) ? null : '<a  class="pc-btn pc-btn-small" style="" href="javascript:" onclick="ayoola.div.setFormElementValue( \'' . $_REQUEST['field_name'] . '\', \'%KEY%\', \'' . @$_REQUEST['unique_id'] . '\' );; ">select</a>';

		$list->createList(  
			array(
				'  ' => array( 'field' => 'basename', 'value' => '<div style="text-align:center;font-size:x-small;">
								<div style="padding-bottom:5px;text-align:center;">
								%FIELD%
								</div>
								<img src="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_IconViewer/?url=%KEY%" alt="" >
								<div style="padding:5px;"><a class="pc-btn pc-btn-small" style="" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '%KEY%">view</a> ' . $select . '</div>
								</div>' ), 
				'url' => '%FIELD%', 
				'ext' => '%FIELD%', 
				'filesize' => '%FIELD%', 
				'created' => '%FIELD%', 
				'modified' => '%FIELD%', 
		//		'Download' => '<a rel="shadowbox;height=300px;width=300px;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Doc_Download/?' . $this->getIdColumn() . '=%KEY%">Download</a>', 
				' ' => array( 'field' => 'url', 'value' => '<a title="Delete" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Doc_Delete/?url=%FIELD%"> x </a>'), 
			)
		);
		//var_export( $list );
		return $list;
    } 
	// END OF CLASS
}
