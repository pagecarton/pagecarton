<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_Locale_OriginalString_List
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php Wednesday 20th of December 2017 03:21PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PageCarton_Locale_OriginalString_List extends PageCarton_Locale_OriginalString_Abstract
{
 	
    /**
     * 
     * 
     * @var string 
     */
	  protected static $_objectTitle = 'Word List';    

    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
      $this->setViewContent( $this->getList() );		
    } 
	
    /**
     * Paginate the list with Ayoola_Paginator
     * @see Ayoola_Paginator
     */
    protected function createList()
    {
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
        $list->listTitle = self::getObjectTitle();
        $data = array();
        if( @$_REQUEST['not_yet_translated'] )
        {
            $list->listTitle = 'Locale words not yet translated';
            $translated = PageCarton_Locale_Translation::getInstance()->select();
            $translatedIds = array();
        //    var_export( $translated );
            foreach( $translated as $each )
            {
                $translatedIds[] = $each['originalstring_id'];
            }
            $data = PageCarton_Locale_OriginalString::getInstance()->select( null, array( 'originalstring_id' => $translatedIds ), array( 'originalstring_id_operator' => '!=' ) );
        }
        else
        {
            $data = $this->getDbData();
        //    self::v( count( $data ) );
        //    exit();
        }
		$list->setData( $data );
		$list->setListOptions( 
								array( 
                                    '<a onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PageCarton_Locale_Translation_AutoPopulateWords/\' );" title="">Populate Words Automatically</a>',    
                                    '<a onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PageCarton_Locale_Translation_List/\' );" title="">Translated Words</a>',    
									) 
							);
		$list->setKey( $this->getIdColumn() );
        $list->setNoRecordMessage( 'No data added to this table yet.' );
        $translate = null;
        if( @$_REQUEST['locale_code'] )
        {

            $translate = 
            '<a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PageCarton_Locale_Translation_Editor/?' . $this->getIdColumn() . '=%KEY%&locale_code=' . @$_REQUEST['locale_code'] . '">translate</a>';
            $list->setListOptions( 
								array( 
                                    '<a href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PageCarton_Locale_OriginalString_List/?not_yet_translated=' . $_REQUEST['locale_code'] . '&locale_code=' . $_REQUEST['locale_code'] . '" >Words Not Yet Translated</a>',    
									) 
							);
        }
        
		$list->createList
		(
			array(
                    'originalstring_id' => array( 'field' => 'originalstring_id', 'value' =>  '%FIELD% ', 'filter' =>  'Ayoola_Filter_HtmlSpecialChars' ), 
                    'string' => array( 'field' => 'string', 'value' =>  '%FIELD% ' . $translate, 'filter' =>  'Ayoola_Filter_HtmlSpecialChars' ), 
                    'pages' => array( 'field' => 'pages', 'value' =>  '<span style="font-size:small;">%FIELD% <a target="_blank" href="' . Ayoola_Application::getUrlPrefix() . '%FIELD%">preview</a></span> <br>', 'filter' =>  '' ), 
                    'Added' => array( 'field' => 'creation_time', 'value' =>  '%FIELD%', 'filter' =>  'Ayoola_Filter_Time' ), 
                    '' => '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PageCarton_Locale_OriginalString_Editor/?' . $this->getIdColumn() . '=%KEY%"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>', 
                    ' ' => '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PageCarton_Locale_OriginalString_Delete/?' . $this->getIdColumn() . '=%KEY%"><i class="fa fa-trash" aria-hidden="true"></i></a>', 
				)
		);
		return $list;
    } 
	// END OF CLASS
}
