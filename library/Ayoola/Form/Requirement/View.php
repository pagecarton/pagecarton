<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @requirement   Ayoola
 * @package    Ayoola_Form_Requirement_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: View.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Form_Requirement_Abstract
 */
 
require_once 'Ayoola/Form/Requirement/Abstract.php';


/**
 * @requirement   Ayoola
 * @package    Ayoola_Form_Requirement_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Form_Requirement_View extends Ayoola_Form_Requirement_Abstract
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'requirement' );
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = self::getIdentifierData() ){ return false; }
			$html = null;
			$adminOptions = null;
	//		include_once 'D:\\Documents\\Avalanche\\Jamb Questions\\AutomationCode.php';
			if( self::hasPriviledge() )
			{
				$adminOptions .= '<span class="goodnews" >
									<a class="" title="Add a new sub requirement" rel="spotlight;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_Requirement_Creator/parent_requirement_name/' . $data['requirement_name'] . '/"> + </a> 
									<a class="badnews" title="Edit ' . $data['requirement_label'] . '" rel="spotlight;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_Requirement_Editor/requirement_name/' . $data['requirement_name'] . '/"> - </a>
									<a class="badnews" title="Delete ' . $data['requirement_label'] . '" rel="spotlight;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_Requirement_Delete/requirement_name/' . $data['requirement_name'] . '/"> x </a>
								</span>';
			}
			$html .= '<h1><a title="' . $data['requirement_label'] . '" href="' . Application_Article_Abstract::getPostUrl() . '/requirement/' . $data['requirement_name'] . '/">' . $data['requirement_label'] . '</a> ' . $adminOptions . '</h1>';
			if( $image = Ayoola_Doc::uriToDedicatedUrl( $data['cover_photo'] ) )
			{
				$html .= 	'<a title="' . $data['requirement_label'] . '" href="' . Application_Article_Abstract::getPostUrl() . '/requirement/' . $data['requirement_name'] . '/">
								<img style="width:100%;margin:0.5em;" src="' . $image . '" alt="" title="Cover photo for ' . $data['requirement_label'] . '" />
							</a>';
			}
			$html .= $data['requirement_description'] ? '<blockquote>' . $data['requirement_description'] . '</blockquote>' : null;
			$this->setViewContent( $html, true );
		}
		catch( Ayoola_Form_Requirement_Exception $e ){ return false; }
    } 
    /**
     * Overides the parent class
     * 
     */
	public function setIdentifierData()
    {
		$requirement = $this->getParameter( 'requirement_name' );
		if( $this->getParameter( 'allow_dynamic_requirement_selection' ) && @$_REQUEST['requirement'] )
		{
			$requirement = $_REQUEST['requirement'];
		}
/* 		var_export( $this->getParameter( 'allow_dynamic_requirement_selection' ) );
		var_export( $_REQUEST['requirement'] );
		var_export( $requirement );
 */		if( ! $requirement )
		{
			//	Showing Random Info from allowed categories from POST module
			$class = new Application_Article_Requirement();
			$data = $class->getPublicDbData();
			$data = $data[array_rand( $data )];
		}
		else
		{
			$data = $this->getDbTable()->selectOne( null, array( 'requirement_name' => $requirement ) );
		}
		$this->_identifierData = $data;
    } 
	
    /**
	 * Returns text for the "interior" of the Layout Editor
	 * The default is to display view and option parameters.
	 * 		
     * @param array Object Info
     * @return string HTML
     */
    public static function getHTMLForLayoutEditor( $object )
	{
		$html = null;
		@$object['view'] = $object['view'] ? : $object['view_parameters'];
		@$object['option'] = $object['option'] ? : $object['view_option'];
	//	$html .= "<span data-parameter_name='view' >{$object['view']}</span>";
		
		//	Implementing Object Options
		//	So that each objects can be used for so many purposes.
		//	E.g. One Class will be used for any object
	//	var_export( $object );
		$options = new Ayoola_Form_Requirement;
		$options = $options->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'requirement_name', 'requirement_label');
		$options = array( 0 => '*Random*' ) + $filter->filter( $options );
		$html .= '<span style=""> Show info of </span>';
		
		$html .= '<select data-parameter_name="requirement_name">';
		foreach( $options as $key => $value )
		{ 
			$html .=  '<option value="' . $key . '"';  
		//	var_export( $object['view'] );
			if( @$object['requirement_name'] == $key ){ $html .= ' selected = selected '; }
			$html .=  '>' . $value . '</option>';  
		}
		$html .= '</select>';
		$html .= '<span style=""> requirement. </span>';
		$html .= '<label style=""> Allow dynamic selection: </label>';
		
		$html .= '<select data-parameter_name="allow_dynamic_requirement_selection">';
		$options = array( 'No', 'Yes' );
		foreach( $options as $key => $value )
		{ 
			$html .=  '<option value="' . $key . '"';  
		//	var_export( $object['view'] );
			if( @$object['allow_dynamic_requirement_selection'] == $key ){ $html .= ' selected = selected '; }
			$html .=  '>' . $value . '</option>';  
		}
		$html .= '</select>';
		if( static::$_editableTitle )
		{
			$html .= '<a href="javascript:;" title="' . static::$_editableTitle . '" onclick="ayoola.div.makeEditable( this.nextSibling ); this.nextSibling.style.display=\'block\';"> edit </a>';
				//	var_export( $object );
			$html .= '<span data-parameter_name="editable" style="padding:1em;display:none;" onclick="this.nextSibling.style.display=\'block\';">' . @$object['editable'] . '</span>';
			$html .= '<a href="javascript:;" style="display:none;" title="' . static::$_editableTitle . '" onclick="this.previousSibling.style.display=\'none\';this.style.display=\'none\';"> hide </a>';
		}
		return $html;
	}
	// END OF CLASS
}
