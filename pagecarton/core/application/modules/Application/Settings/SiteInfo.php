<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
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
     * Calls this after every successful settings change
     *
     */
	public static function callback()
    {
        $settings = self::retrieve();
        $from = Ayoola_Doc_Browser::getDocumentsDirectory() . $settings['logo'];
        if( is_file( $from ) )
        {
            $to = Ayoola_Doc_Browser::getDocumentsDirectory() . '/img/logo.png';
            if( filesize( $from ) !== @filesize( $to ) )
            {
            //    var_export( $from );
            //    var_export( $to );
                Ayoola_Doc::createDirectory( dirname( $to ) );
                copy( $from, $to );
            }
        }
        $from = Ayoola_Doc_Browser::getDocumentsDirectory() . $settings['favicon'];
        if( is_file( $from ) )
        {
            $to = Ayoola_Doc_Browser::getDocumentsDirectory() . '/favicon.ico';
            if( filesize( $from ) !== @filesize( $to ) )
            {
            //    var_export( $from );
            //    var_export( $to );
                Ayoola_Doc::createDirectory( dirname( $to ) );
                copy( $from, $to );
            }
        }

    }

    /**
     * creates the form for creating and editing
     *
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
    //    $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
//		self::v( $values );
	//	$settings = unserialize( @$values['settings'] );
		$settings = @$values['data'] ? : unserialize( @$values['settings'] );
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;

//		var_export( $settings );

		//	Company Info
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'site_headline', 'placeholder' => 'E.g. My Web', 'label' => 'Headline', 'value' => @$settings['site_headline'], 'type' => 'InputText' ) );
		$fieldset->addElement( array( 'name' => 'site_description', 'label' => 'Description', 'placeholder' => 'What is this site about?', 'value' => @$settings['site_description'], 'type' => 'TextArea' ) );

    //    var_export();

        if( Ayoola_Abstract_Table::hasPriviledge( array( 99, 98 ) ) )
        {
            $fieldset->addElement( array( 'name' => 'cover_photo', 'label' => 'Banner Image', 'data-document_type' => 'image', 'type' => 'Document', 'value' => @$settings['cover_photo'] ) );
            $fieldset->addElement( array( 'name' => 'logo', 'label' => 'Brand Logo', 'data-document_type' => 'image', 'type' => 'Document', 'value' => '/img/logo.png' ) );
            $fieldset->addElement( array( 'name' => 'favicon', 'label' => 'Favicon', 'data-document_type' => 'image', 'type' => 'Document', 'value' => @$settings['favicon'] ? : '/favicon.ico' ) );
        }

        $options = Ayoola_Page_Layout_Repository::getMenuOptions();
  //      var_export( $options );
		$fieldset->addElement( array( 'name' => 'site_type', 'label' => 'Theme Type', 'value' => @$settings['site_type'], 'type' => 'Select' ), array( '' => 'Generic' ) + array_column( $options, 'title', 'category_name' ) ? : array() );
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
