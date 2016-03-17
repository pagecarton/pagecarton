<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Blog_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: View.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Blog_Abstract
 */
 
require_once 'Application/Blog/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Blog_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Blog_View extends Application_Blog_Abstract
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'blog_name',  );

    /**
     * The xml document
     * 
     * @var Ayoola_Xml
     */
	protected $_xml;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
			if( ! $data = $this->getIdentifierData() ){ return $this->setViewContent( 'Blog Not Found', true ); }
			$pageInfo = array(
				'description' => $data['blog_description'],
				'title' => trim( $data['blog_title'] . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' )
			);
	//	var_export( Ayoola_Page::getCurrentPageInfo( 'title' ) );
			Ayoola_Page::setCurrentPageInfo( $pageInfo );
			$this->setViewContent( self::getXml(), true );
		}
		catch( Exception $e ){ return $this->setViewContent( 'Error with blog package', true ); }
	//	var_export( $this->_xml );
    } 
	
    /**
     * Returns the Xml
     * 
     * @return Ayoola_Xml
     */
	public function getXml()
    {
		if( is_null( $this->_xml ) ){ $this->setXml(); }
		return $this->_xml;
    } 
	
    /**
     * Sets the xml
     * 
     */
	public function setXml()
    {
		$data = $this->getIdentifierData();
		$this->_xml = '<ul class="' . __CLASS__ . '_UL" style="list-style:none;">';
		
		// image
		$this->_xml .= '<li style=""><img class="' . __CLASS__ . '_IMG" style="float:right;max-width:50%;" src="' . Ayoola_Doc::uriToDedicatedUrl( $data['document_url'] ) . '" alt="' . $data['blog_title'] . "'s photo" . '"/></li>';
		Ayoola_Page::$thumbnail = $data['document_url'];
		
		//	title
		$this->_xml .= '<li style=""><h1><a href="' . Ayoola_Application::getUrlPrefix() . '/' . strtolower( $data['blog_name'] ) . '/">' . $data['blog_title'] . '</a></h1></li>';

		$filename = self::getFilePath( $data['blog_directory'], $data['blog_name'] );
		if( $data['enabled'] )
		{
			$content = file_get_contents( $filename );	
		}
		else
		{
			$content = self::$_notEnabledMessage;
		}
		
		//	content
		$this->_xml .= '<li style=""><span style="">' . $content .  '</span></li>';
		
		//	date
		$filter = new Ayoola_Filter_Time();
		$data['blog_modified_date'] = $data['blog_modified_date'] ? 'Modified ' . $filter->filter( $data['blog_modified_date'] ) : 'Created ' . $filter->filter( $data['blog_creation_date'] );
		$this->_xml .= '<li style=""><p>' . $data['blog_modified_date'] . '</p></li>';
		
		$this->_xml .= '</ul>';
    } 
	// END OF CLASS
}
