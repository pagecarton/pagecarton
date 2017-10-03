<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Settings_SiteInfo
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: CompanyInfo.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Abstract_Playable
 */
 
require_once 'Ayoola/Abstract/Playable.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Settings_SiteInfo
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Settings_SiteInfo extends Application_Settings_Abstract
{
	
    /**
     * Default Database Table
     *
     * @var string
     */
	protected $_tableClass = 'Application_Settings';
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'settingsname_name' );
	
    /**
     * creates the form for creating and editing
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
    //    $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$settings = unserialize( @$values['settings'] );
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
		
	//	var_export( $settings );
		
		//	Company Info
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'site_headline', 'placeholder' => 'E.g. My Web', 'label' => 'Site Headline', 'value' => @$settings['site_headline'], 'type' => 'InputText' ) );
		$fieldset->addElement( array( 'name' => 'site_description', 'label' => 'Site Description', 'placeholder' => 'What is this site about?', 'value' => @$settings['site_description'], 'type' => 'TextArea' ) );

    //    var_export();

        if( Ayoola_Abstract_Table::hasPriviledge( array( 99, 98 ) ) )
        {        
            $fieldset->addElement( array( 'name' => 'cover_photo', 'label' => 'Site Header Image', 'type' => 'Document', 'value' => @$settings['cover_photo'] ) );    
    //        $fieldset->addElement( array( 'name' => 'site_logo', 'label' => 'Site Logo', 'type' => 'Document', 'value' => @$settings['site_logo'] ) );  
        }  
		$fieldset->addLegend( 'Site Information' );
		$form->addFieldset( $fieldset );
		
	//	$form->addFieldset( $fieldset );
				
//		var_export( $fieldsets );
		$this->setForm( $form );
		//		$form->addFieldset( $fieldset );
	//	$this->setForm( $form );
    } 
	// END OF CLASS
}
