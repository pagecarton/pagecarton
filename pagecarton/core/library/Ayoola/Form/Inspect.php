<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_idColumn = 'data_id';
		
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
				if(  ! self::hasPriviledge() )
				{
					$this->setViewContent(  '' . self::__( '<p class="boxednews badnews">The requested form was not found on the server. Please check the URL and try again. </p>' ) . '', true  );
					return false;
				}
				$table = Ayoola_Form_Table_Data::getInstance();
				$this->setViewContent( $table->view(), true );
				return false;
			}
			$list = new Ayoola_Paginator();
			$list->pageName = $this->getObjectName();
			$list->deleteClass = 'Ayoola_Form_Table_Delete';
			$list->showExportLink = true;
			$list->listTitle = $data['form_title'] . ' Responses';
			$table = Ayoola_Form_Table_Data::getInstance();

			//	Filter the result to save time
			$sortFunction2 = create_function
			( 
				'& $key, & $values', 
				'
					$time = $values["creation_time"];
					$values = $values["form_data"] + $values;
				'
			); 
			$formData = $table->select( null, array( 'form_name' => $data['form_name'] ), array( 'result_filter_function' => $sortFunction2 ) );
			$formData = self::sortMultiDimensionalArray( $formData, 'creation_time' );
			

			krsort( $formData );
			$list->setData( $formData );
			$list->setListOptions( 
									array( 
								//			'Form Settings' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/Forms/\' );" title="Update form settings.">Form Settings </a>',    
										) 
								);
			$list->setKey( $this->getIdColumn() );
			$list->pageName = $this->getObjectName();
			$list->setNoRecordMessage( 'There are no responses to this form yet.' );

            $count = 0;
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
                if( ++$count >= 2 )
                {
                    break;
                }
			}
			$listColumn[] = array( 'field' => 'creation_time', 'value' => '%FIELD%', 'filter' => 'Ayoola_Filter_Time' );
			$listColumn[] = array( 'value' => '<a title="" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_View/?data_id=%FIELD%&form_name=' . $data['form_name'] . '"><i class="fa fa-eye" aria-hidden="true"></i></a>', 'field' => 'data_id' );  
			$listColumn[] = array( 'value' => '<a title="" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_View/?data_id=%FIELD%&form_name=' . $data['form_name'] . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>', 'field' => 'data_id' );  
			$listColumn[] = array( 'value' => '<a title="" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_Table_Delete/?data_id=%FIELD%&form_name=' . $data['form_name'] . '"><i class="fa fa-trash" aria-hidden="true"></i>
            </a>', 'field' => 'data_id' );  
			
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
			$this->setViewContent(  '' . self::__( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>' ) . '', true  );
		//	return $this->setViewContent( self::__( '<p class="blockednews badnews centerednews">Error with article package.</p>' ) ); 
		}
    } 
	
}
