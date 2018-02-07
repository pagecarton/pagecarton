<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Form_Inspect
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Inspect.php date time username $ 
 */

/**
 * @see Ayoola_Form_Abstract
 */
 
require_once 'Ayoola/Page/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Form_Inspect
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Form_Inspect extends Ayoola_Form_Abstract
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'form_name' );
		
    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
		try
		{
			try
			{
				if( ! $data = $this->getIdentifierData() ){ null; }
			}
			catch( Exception $e  )
			{
				null;
			}
			if( ! $data )     
			{
		//		if( ! $data = $this->getIdentifierData() ){  }
				if(  ! self::hasPriviledge() )
				{
				//	var_export( $data );
					$this->setViewContent( '<p class="boxednews badnews">The requested form was not found on the server. Please check the URL and try again. </p>', true );
					return false;
				//	self::setIdentifierData( $data );
				}
			//	var_export( $this->getDbData() );
			//	var_export( $data );
				$table = new Ayoola_Form_Table_Data();
				$this->setViewContent( $table->view(), true );
				return false;
			}
		//	var_export( $data );
			$list = new Ayoola_Paginator();
			$list->pageName = $this->getObjectName();
			$list->listTitle = self::getObjectTitle();
			$table = new Ayoola_Form_Table_Data();

			//	Filter the result to save time
			$sortFunction2 = create_function
			( 
				'& $key, & $values', 
				'
					$time = $values["creation_time"];
					$values = $values["form_data"];
					$values["creation_time"] = $time;
				//	$key = $values["username"];
				'
			); 
			$formData = $table->select( null, array( 'form_name' => $data['form_name'] ), array( 'result_filter_function' => $sortFunction2 ) );
		//	var_export( $formData[0] );
			krsort( $formData );
			$list->setData( $formData );
			$list->setListOptions( 
									array( 
								//			'Form Settings' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/Forms/\' );" title="Update form settings.">Form Settings </a>',    
										) 
								);
			$list->setKey( $this->getIdColumn() );
			$list->setNoRecordMessage( 'There are no responses to this form yet.' );

			foreach( $data['element_title'] as $key => $each )
			{
				switch( $data['element_type'][$key] )
				{
					case 'file':
					case 'document':
					case 'image':
					case 'audio':
					case 'video':
						$value = '<a href="' . Ayoola_Application::getUrlPrefix() . '%FIELD%"><img src="' . Ayoola_Application::getUrlPrefix() . '/widgets/Application_IconViewer/?url=%FIELD%&max_width=64&max_height=64;" alt="%FIELD%" ></a>';
					break;
					default:
						$value = '%FIELD%';
					break;
				}
				$listColumn[$each] = array( 'value' => $value, 'field' => $data['element_name'][$key] ? : $each );
			}
			$listColumn['creation_time'] = array( 'value' => '%FIELD%', 'filter' => 'Ayoola_Filter_Time' );
			
			$list->createList
			(
				$listColumn
			);
			$this->setViewContent( $list->view(), true );
			return true;
		}
		catch( Exception $e )
		{ 
			$this->_parameter['markup_template'] = null;
			$this->setViewContent( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>', true );
		//	return $this->setViewContent( '<p class="blockednews badnews centerednews">Error with article package.</p>' ); 
		}
    } 
	
}
