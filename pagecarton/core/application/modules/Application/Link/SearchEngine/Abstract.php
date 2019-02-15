<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Link_SearchEngine_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Link_SearchEngine_Exception 
 */
 
require_once 'Application/Link/SearchEngine/Exception.php';


/**
 * @category   PageCarton
 * @package    Application_Link_SearchEngine_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Link_SearchEngine_Abstract extends Ayoola_Abstract_Table
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
	protected static $_accessLevel = 99;
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'searchengine_name' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Link_SearchEngine';
	
	
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		if( is_null( $values ) )
		{
			$fieldset->addElement( array( 'name' => 'searchengine_name', 'description' => 'Name of the Search Engine or <a rel="spotlight;height=300px;width=600px;" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Link_SearchEngine_List/">Show all Search Engines</a>', 'type' => 'InputText', 'value' => @$values['searchengine_name'] ) );
		}
		$fieldset->addElement( array( 'name' => 'searchengine_url', 'description' => 'URL of the search engine', 'type' => 'InputText', 'value' => @$values['searchengine_url'] ) );
		$fieldset->addElement( array( 'name' => 'searchengine_sitemap_url', 'description' => 'Ping url for site map', 'type' => 'InputText', 'value' => @$values['searchengine_sitemap_url'] ) );
		$fieldset->addFilters( array( 'trim' => null ) );
		$fieldset->addRequirements( array( 'WordCount' => array( 6, 200 ) ) );
		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
