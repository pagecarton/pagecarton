<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Editor_Image
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Image.php 10-26-2011 9.13pm ayoola $
 */

/**
 * @see Ayoola_Page_Editor_Abstract
 */
 
require_once 'Ayoola/Page/Editor/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Editor_Image
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Editor_Image extends Ayoola_Page_Editor_Abstract
{
	
    /**
     * For editable div in Image editor 
     * REMOVED BECAUSE IT CONFLICTS WITH THE EDITOR
     * 
     * @var string
     */
//	protected static $_editableTitle = "Open HTML editor";  

    /**
     * The View Parameter From Image Editor
     *
     * @var string
     */
	protected $_viewParameter;
	
    /**
     * Differentiates each of this instance
     *
     * @var int
     */
	protected static $_counter = 0;
	

    /**
	 * 	performs the class process	
	 * 		
     * @param void
     * @return void
     */
    public function init()
	{
		try
		{
			$docSettings = Ayoola_Doc_Settings::getSettings( 'Documents' );
			if( Ayoola_Abstract_Table::hasPriviledge() )     
			{   
				$parameters = array( 'field_name' =>  __CLASS__ . '_' . ++self::$_counter, 'field_name_value' => 'dedicated_url', ) + ( $this->getParameter() ? : array() );
			//	self::v( $parameters );
				$this->setViewContent( Ayoola_Doc_Upload_Link::viewInLine( $parameters ) );
				$this->setViewContent( '<input style="display:none;" name="' . __CLASS__ . '_' . self::$_counter . '" value="' . $this->getParameter( 'image_url' ) . '" title="Enter the image link here" placeholder="http://' . Ayoola_Page::getDefaultDomain() . '/path/to/image.jpg" />' );
			} 
			else
			{ 
			//	var_export( $this->getParameter( 'image_url' ) );
				
				$class = new Ayoola_Doc( array( 'option' => $this->getParameter( 'image_url' ), 'view' => $this->getParameter( 'image_url' ) ) );
				$this->setViewContent( $class->view() );
			}
		}
		catch( Exception $e )
		{
		
		}
	}
	
    /**
	 * Returns text for the "interior" of the Image Editor
	 * The default is to display view and option parameters.
	 * 		
     * @param array Object Info
     * @return string HTML
     */
     public static function getHTMLForLayoutEditor( $object )
	{
		$html = null;
		@$object['view'] = $object['view'] ? : $object['view_parameters'];
		$html .= "<span data-parameter_name='view' >{$object['view']}</span>";
	//	$html .= str_replace( array( "\r", "\n" ), " ", Ayoola_Doc_Upload_Link::viewInLine( array( 'image_preview' => ( @$object['image_url'] ? : '' ), 'field_name' =>  __CLASS__ . '_' . ++self::$_counter, 'field_name_value' => 'url', 'preview_text' => 'Default Image', 'call_to_action' => 'Change image' ) ) );
	//	$html .= '<input style="display:none;" name="' .  __CLASS__ . '_' . self::$_counter . '" value="' . @$object['image_url'] . '" data-parameter_name="image_url" title="Enter the image link here" placeholder="http://' . Ayoola_Page::getDefaultDomain() . '/path/to/image.jpg" />'; 
	
		self::$_counter++;
		$html .= '<span>  
					<img name="' .  __CLASS__ . '_' . self::$_counter . '_preview_zone_image' . '" src="' . Ayoola_Application::getUrlPrefix() . '' . ( @$object['image_url'] ? : 'http://placehold.it/60x60&text=Preview' ) . '"  class="defaultnews blocknews centerednews" onClick="" style=""  > 
				</span>'; 
		$html .= '<input data-parameter_name="image_url" title="Enter the image link here" onClick="ayoola.image.formElement = this; ayoola.image.fieldNameValue = \'url\'; ayoola.image.fieldName = \'' .  __CLASS__ . '_' . self::$_counter . '\';  ayoola.image.cropping.crop = false; ayoola.image.clickBrowseButton( { accept: \'image/*\' } );" type=\'button\' value="' . ( @$object['image_url'] ? : 'Upload New'  ) . '" />'; 
		return $html;
	}
 
	// END OF CLASS
}
