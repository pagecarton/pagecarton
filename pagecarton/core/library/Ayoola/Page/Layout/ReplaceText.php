<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_ReplaceText
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ReplaceText.php Thursday 27th of September 2018 11:57PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Ayoola_Page_Layout_ReplaceText extends Ayoola_Page_Layout_Abstract
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 98 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Update Text';     
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_textUpdates;     

    /**
     * Performs the whole widget running process
     * 
     */
	public static function getUpdates( $getSiteData = false )
    { 
        if( null !== static::$_textUpdates )
        {
            return static::$_textUpdates;
        }
        $settingsName = __CLASS__;
    //    $table = Application_Settings::getInstance();
        $themeName =  ( ( @$_REQUEST['pc_page_editor_layout_name'] ? : @$_REQUEST['pc_page_layout_name'] ) ? : @$_REQUEST['layout_name'] ) ? : Ayoola_Page_Editor_Layout::getDefaultLayout();
    //    var_export( $themeName );
        $themeInfo = Ayoola_Page_PageLayout::getInstance()->selectOne( null, array( 'layout_name' => $themeName ) );
    //    var_export( $themeName );
    //    var_export( $themeInfo );
    //    var_export( Ayoola_Page_PageLayout::getInstance( 'xx' )->select() );
        if( $getSiteData AND $previousData = Application_Settings::getInstance()->selectOne( null, array( 'settingsname_name' => $settingsName ) ) )
        {
    //      var_export( $settingsName );
        //  var_export( $previousData['settings'] );
            $previousData =  $previousData['data'] ? : ( json_decode( $previousData['settings'], true ) ? : unserialize( base64_decode( base64_encode( $previousData['settings'] ) ) ) );
    //    var_export( $previousData );
           if( is_array( $previousData['dummy_title'] ) && is_array( $previousData['dummy_search'] ) && is_array( $previousData['dummy_replace'] ) )
            {
                $themeInfo['dummy_title'] = array_merge( $previousData['dummy_title'] ? : array(), $themeInfo['dummy_title'] ? : array() );
                $themeInfo['dummy_search'] = array_merge( $previousData['dummy_search'] ? : array(), $themeInfo['dummy_search'] ? : array() );
                $themeInfo['dummy_replace'] = array_merge( $previousData['dummy_replace'] ? : array(), $themeInfo['dummy_replace'] ? : array() );
                $record = array();
                foreach( $themeInfo['dummy_search'] as $key => $each )
                {
                    if( ! empty( $record[$themeInfo['dummy_search'][$key]] ) )
                    {
                        unset( $themeInfo['dummy_title'][$key] );
                        unset( $themeInfo['dummy_search'][$key] );
                        unset( $themeInfo['dummy_replace'][$key] );
                    }
                    $record[$themeInfo['dummy_search'][$key]] = true;

                }
            }
        }    
        static::$_textUpdates = $themeInfo;
    //    var_export( $themeInfo );
   //     var_export( $previousData );
    return $themeInfo;   
    }

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
            try
            { 
                if( ! $this->getIdentifier() )
                {
                    $this->_identifier['layout_name'] = Ayoola_Page_Editor_Layout::getDefaultLayout();
                }
            }
            catch( Exception $e )
            { 
                $this->_identifier['layout_name'] = Ayoola_Page_Editor_Layout::getDefaultLayout();
            //	return false; 
            }
            if( ! $identifierData = self::getIdentifierData() ){ return false; }
        //    var_export( $identifierData );
            $settingsName = __CLASS__;

            $this->createForm( 'Continue..', '' );
            if( empty( $_GET['editing_dummy_text'] ) )
            {
                $this->setViewContent( '<div class="pc-notify-info" style="text-align:center;">Update text on the site! <a style="font-size:smaller;" onclick="location.search+=\'&editing_dummy_text=1\'" href="javascript:">Advanced mode</a></div>' );
            }
			$this->setViewContent( $this->getForm()->view() );
		//	self::v( $_POST );
            if( ! $values = $this->getForm()->getValues() ){ return false; }
         //   self::v( $identifierData );
    //        $identifierData += $values;
       //     self::v( $values );
            foreach( $values['dummy_replace'] as $key => $each )
            {
                if( '' === $each )
                {
                    $values['dummy_replace'][$key] = trim( $values['dummy_search'][$key], '{}' );
                }

            }
        //    self::v( $values );
            Ayoola_Page_PageLayout::getInstance()->update( $values, $this->getIdentifier() );
        //    $this->updateDb( $values );
            $previousData = Ayoola_Page_Layout_ReplaceText::getUpdates( true );
            $table = Application_Settings::getInstance();
        //    var_export( $previousData );
            if( $previousData )
            {
                $table->delete( array( 'settingsname_name' => $settingsName ) );
                $values['dummy_title'] = array_merge( $values['dummy_title'] ? : array(), $previousData['dummy_title'] ? : array() );
                $values['dummy_search'] = array_merge( $values['dummy_search'] ? : array(), $previousData['dummy_search'] ? : array() );
                $values['dummy_replace'] = array_merge( $values['dummy_replace'] ? : array(), $previousData['dummy_replace'] ? : array() );
            }
            $record = array();
            foreach( $values['dummy_search'] as $key => $each )
            {
                if( ! empty( $record[$values['dummy_search'][$key]] ) )
                {
                    unset( $values['dummy_title'][$key] );
                    unset( $values['dummy_search'][$key] );
                    unset( $values['dummy_replace'][$key] );
                }
                $record[$values['dummy_search'][$key]] = true;

            }
        //    var_export( $values );
			$table->insert( array( 'data' => $values, 'settings' => json_encode( $values ), 'settingsname_name' => $settingsName ) );

            
			$this->setViewContent( '<div class="goodnews" style="xtext-align:center;">Update saved successfully. Further text update could be done in <a href="/tools/classplayer/get/name/Ayoola_Page_List">Pages</a>. </div>', true );
            // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
        //    $this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>' ); 
            $this->setViewContent( '<p class="badnews">Theres an error in the code</p>' ); 
            return false; 
        }
	}
	
    /**
     * creates the form for creating and editing page
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )  
    {
		//	
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() . $values['page_id'] . $values['url'], 'data-not-playable' => true ) );
		$form->submitValue = $submitValue ;
	//	$form->oneFieldSetAtATimeJs = true;

    //    if( ! $data = self::getIdentifierData() ){ return false; }
        $data = Ayoola_Page_Layout_ReplaceText::getUpdates( ! empty( $_GET['editing_dummy_text'] ) );
        
    //    var_export( $data );

        $i = 0;
        $record = array();
        do
        {
            $fieldset = new Ayoola_Form_Element;
            if( ! empty( $record[$data['dummy_search'][$i]] ) )
            {
                ++$i;
                continue;
            }
            $record[$data['dummy_search'][$i]] = true;
            if( empty( $data['dummy_title'][$i] ) || ! empty( $_REQUEST['editing_dummy_text'] ) )
            {
                $fieldset->addElement( array( 'name' => 'dummy_title', 'multiple' => 'multiple', 'label' => 'Title', 'placeholder' => 'Name for dummy text', 'type' => 'InputText', 'value' => @$data['dummy_title'][$i] ? : $data['dummy_search'][$i] ) );
                $fieldset->allowDuplication = true;
                $fieldset->duplicationData = array( 'add' => '+ Add New Text Below', 'remove' => '- Remove Above Text', 'counter' => 'subgroup_counter', );
                $fieldset->container = 'span';
                $fieldset->placeholderInPlaceOfLabel = false;
            }
            if( empty( $data['dummy_search'][$i] ) || ! empty( $_REQUEST['editing_dummy_text'] ) )
            {
                $fieldset->addElement( array( 'name' => 'dummy_search', 'multiple' => 'multiple', 'label' => 'Dummy Text', 'placeholder' => @$data['dummy_search'][$i], 'type' => 'TextArea', 'value' => @$data['dummy_search'][$i] ) );
            }
            $info = array( 'name' => 'dummy_replace', 'multiple' => 'multiple', 'label' => $data['dummy_title'][$i] ? : ' ', 'placeholder' => @$data['dummy_search'][$i], 'type' => 'TextArea', 'value' => ( @$data['dummy_replace'][$i] || ! empty( $_REQUEST['editing_dummy_text'] ) ) ? $data['dummy_replace'][$i] : trim( @$data['dummy_search'][$i], '{}' ) );
            if( strip_tags( $data['dummy_search'][$i] ) !== $data['dummy_search'][$i] )
            {
                $info['data-html'] = '1';
            //    var_export( $info );
            }
            if( ! empty( $_REQUEST['editing_dummy_text'] ) )
            {
                $info['label'] = 'Default Replacement';
                $info['label'] = 'Default Replacement';
            //    var_export( $info );
            }
            $fieldset->addElement( $info );
            $form->addFieldset( $fieldset );
            ++$i;
        }
        while( isset( $data['dummy_search'][$i] )  );
        Application_Article_Abstract::initHTMLEditor();
    
		$this->setForm( $form );
    } 
		
    /**
     * 
     * 
     */
	public static function getPercentageCompleted()
    {
        $percentage = 0;
        $themeInfo = self::getUpdates();
        $themeInfoAll = Application_Settings::getInstance()->selectOne( null, array( 'settingsname_name' => __CLASS__ ) );
        $themeInfoAll = $themeInfoAll['data'];
		if( empty( $themeInfo['dummy_search'] )  )
		{
			$percentage += 100;
		}
		elseif( ! @array_diff( $themeInfo['dummy_search'], $themeInfoAll['dummy_search'] ) )
		{
			$percentage += 100;
		}
	//	var_export( $percentage );
 //   var_export( self::getUpdates() );
//    var_export( $themeInfoAll['dummy_search'] );
		return $percentage;
	}
	// END OF CLASS
}
