<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
     * @var int
     */
	protected static $_dataVersion = 1;
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_defaultTexts = array (
        'dummy_title' => 
        array (
          0 => 'Organization Name',
          1 => 'Short Description About Organization',
          2 => 'More About Organization',
          3 => 'Background History of Organization',
          4 => 'Street Address',
          5 => 'City',
          6 => 'State/Province',
          7 => 'Country',
          8 => 'Phone Number 1',
          9 => 'Email Address',
          10 => 'Facebook URL',
          11 => 'Twitter URL',
          12 => 'Instagram URL',
          13 => 'WhatsApp Number',
          14 => 'Descriptive Video Embed Url',
          15 => 'Google Maps Embed URL',
          16 => 'Youtube Channel URL',
        ),
        'dummy_search' => 
        array (
          0 => '{Organization Name}',
          1 => '{Short About Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eius repellat, dicta at laboriosam, nemo exercitationem itaque eveniet architecto cumque, deleniti commodi molestias repellendus quos sequi hic fugiat asperiores illum. Atque, in, fuga excepturi corrupti error corporis aliquam unde nostrum quas.}',
          2 => '{More About Accusantium dolor ratione maiores est deleniti nihil? Dignissimos est, sunt nulla illum autem in, quibusdam cumque recusandae, laudantium minima repellendus.}',
          3 => '{Background History Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt labore et magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi aliquip ex ea consequat.}',
          4 => '{Street Address}',
          5 => '{City}',
          6 => '{State}',
          7 => '{Country}',
          8 => '{+234 800 000 0000}',
          9 => '{info@example.com}',
          10 => '{https://www.facebook.com/PageCarton}',
          11 => '{https://www.twitter.com/PageCarton}',
          12 => '{https://www.instagram.com/PageCarton}',
          13 => '{2348054449535}',
          14 => '{https://vimeo.com/channels/staffpicks/93951774}',
          15 => '{https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.7573964262942!2d3.862950314775586!3d7.381062994674082!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x10398d11e9ff4445%3A0x27cd60c4ec4cbd97!2sNustreams+Conference+%26+Culture+Centre!5e0!3m2!1sen!2sng!4v1550769560125}',
          16 => '{https://www.youtube.com/channel/UCMjkDODU47J8iKKbaidQpEw?view_as=subscriber}',
        ),
        'dummy_replace' => 
        array (
          0 => 'Organization Name',
          1 => 'Short About Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eius repellat, dicta at laboriosam, nemo exercitationem itaque eveniet architecto cumque, deleniti commodi molestias repellendus quos sequi hic fugiat asperiores illum. Atque, in, fuga excepturi corrupti error corporis aliquam unde nostrum quas.',
          2 => 'More About Accusantium dolor ratione maiores est deleniti nihil? Dignissimos est, sunt nulla illum autem in, quibusdam cumque recusandae, laudantium minima repellendus.',
          3 => 'Background History Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt labore et magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi aliquip ex ea consequat.',
          4 => 'Street Address',
          5 => 'City',
          6 => 'State',
          7 => 'Country',
          8 => '+234 800 000 0000',
          9 => 'info@example.com',
          10 => 'https://www.facebook.com/PageCarton',
          11 => 'https://www.twitter.com/PageCarton',
          12 => 'https://www.instagram.com/PageCarton',
          13 => '2348054449535',
          14 => 'https://vimeo.com/channels/staffpicks/93951774',
          15 => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.7573964262942!2d3.862950314775586!3d7.381062994674082!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x10398d11e9ff4445%3A0x27cd60c4ec4cbd97!2sNustreams+Conference+%26+Culture+Centre!5e0!3m2!1sen!2sng!4v1550769560125',
          16 => 'https://www.youtube.com/channel/UCMjkDODU47J8iKKbaidQpEw?view_as=subscriber',
        ),
      );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Update Site Static Text';     
	
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
	public static function getDefaultTexts()
    { 
        return static::$_defaultTexts;
    }

    /**
     * get replaceable text
     * 
     * @return array
     */
	public static function getUpdates( $getSiteData = false )
    { 
        if( null !== @static::$_textUpdates[$getSiteData][self::$_dataVersion] )
        {
            return static::$_textUpdates[$getSiteData][self::$_dataVersion];
        }
        $settingsName = __CLASS__;
        $themeName =  ( ( @$_REQUEST['pc_page_editor_layout_name'] ? : @$_REQUEST['pc_page_layout_name'] ) ? : @$_REQUEST['layout_name'] ) ? : Ayoola_Page_Editor_Layout::getDefaultLayout();
        $themeInfo = Ayoola_Page_PageLayout::getInstance()->selectOne( null, array( 'layout_name' => $themeName ) );

        //  Add default info
        if( $getSiteData AND $previousData = Application_Settings::getInstance()->selectOne( null, array( 'settingsname_name' => $settingsName ) ) )
        {
            $previousData =  $previousData['data'] ? : ( json_decode( $previousData['settings'], true ) ? : unserialize( base64_decode( base64_encode( $previousData['settings'] ) ) ) );
           if( is_array( $previousData['dummy_title'] ) && is_array( $previousData['dummy_search'] ) && is_array( $previousData['dummy_replace'] ) )
            {
                $themeInfo['dummy_title'] = array_merge( $previousData['dummy_title'] ? : array(), $themeInfo['dummy_title'] ? : array() );
                $themeInfo['dummy_search'] = array_merge( $previousData['dummy_search'] ? : array(), $themeInfo['dummy_search'] ? : array() );
                $themeInfo['dummy_replace'] = array_merge( $previousData['dummy_replace'] ? : array(), $themeInfo['dummy_replace'] ? : array() );
                foreach( $themeInfo['dummy_search'] as $key => $each )
                {
                    if( ! empty( $record[$themeInfo['dummy_search'][$key]] ) )
                    {
                        unset( $themeInfo['dummy_title'][$key] );
                        unset( $themeInfo['dummy_search'][$key] );
                        unset( $themeInfo['dummy_replace'][$key] );
                    }

                }
            }
        }    
        static::$_textUpdates[$getSiteData][self::$_dataVersion] = $themeInfo;
        return static::$_textUpdates[$getSiteData][self::$_dataVersion];   
    }

    /**
     * Clear Values
     * 
     */
	public static function clearTexts( $themeName = null )
    {
        $themeName =  ( ( @$_REQUEST['pc_page_editor_layout_name'] ? : @$_REQUEST['pc_page_layout_name'] ) ? : @$_REQUEST['layout_name'] ) ? : Ayoola_Page_Editor_Layout::getDefaultLayout();
        unset( $values['layout_name'] );
        $previousData = Ayoola_Page_PageLayout::getInstance()->selectOne( array( 'layout_name' => $themeName ) );
        if( isset( $previousData['dummy_replace'] ) && is_array( $previousData['dummy_replace'] ) )
        {
            $previousData['dummy_replace'] = array_fill_keys( $previousData['dummy_replace'], null );

        }
        Ayoola_Page_PageLayout::getInstance()->update( $previousData, array( 'layout_name' => $themeName ) );
        Application_Settings::getInstance()->delete( array( 'settingsname_name' => __CLASS__ ) );
        self::$_dataVersion++;
    }

    /**
     * Save Values
     * 
     */
	public static function saveTexts( $values, $themeName = null )
    {
        $themeName =  ( ( @$_REQUEST['pc_page_editor_layout_name'] ? : @$_REQUEST['pc_page_layout_name'] ) ? : @$_REQUEST['layout_name'] ) ? : Ayoola_Page_Editor_Layout::getDefaultLayout();
        unset( $values['layout_name'] );
        Ayoola_Page_PageLayout::getInstance()->update( $values, array( 'layout_name' => $themeName ) );
        $previousData = Ayoola_Page_Layout_ReplaceText::getUpdates( true );
        $table = Application_Settings::getInstance();

        //  merge now with settings
        if( $previousData )
        {
            $table->delete( array( 'settingsname_name' => __CLASS__ ) );
            $values['dummy_title'] = array_merge( $values['dummy_title'] ? : array(), $previousData['dummy_title'] ? : array() );
            $values['dummy_search'] = array_merge( $values['dummy_search'] ? : array(), $previousData['dummy_search'] ? : array() );
            $values['dummy_replace'] = array_merge( $values['dummy_replace'] ? : array(), $previousData['dummy_replace'] ? : array() );
        }
        $record = array();

        //  delete duplicates
        foreach( $values['dummy_search'] as $key => $each )
        {
            if( ! empty( $record[$values['dummy_search'][$key]] ) )
            {
                unset( $values['dummy_title'][$key] );
                unset( $values['dummy_search'][$key] );
                unset( $values['dummy_replace'][$key] );
            }
            $record[$values['dummy_search'][$key]] = true;

            // this needs to be more tested.
            if( 
                ! empty( $values['dummy_replace'][$key] ) 
                && strip_tags( $values['dummy_replace'][$key] ) === $values['dummy_replace'][$key] 
                && strpos( trim( $values['dummy_replace'][$key] ), "\n" )
            )
            {
                $values['dummy_replace'][$key] = trim( nl2br( $values['dummy_replace'][$key] ) );
            }
        }
        if( count( $values['dummy_replace'] ) !== count( $values['dummy_search'] ) )
        {
            return false;
        }
        else
        {
            
            $response = $table->insert( array( 'data' => $values, 'settings' => json_encode( $values ), 'settingsname_name' => __CLASS__ ) );
            return true;
        }
        self::$_dataVersion++;
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
            if( @$_GET['editing_dummy_text'] === 'ddd' )
            {
                $this->createConfirmationForm( 'Delete Static Text', 'Delete Static Text Contents..' );
                $this->setViewContent( $this->getForm()->view() );
                if( ! $values = $this->getForm()->getValues() ){ return false; }
                self::clearTexts( $this->_identifier['layout_name'] );

                $this->setViewContent(  '' . self::__( '<div class="goodnews" style="xtext-align:center;">Static texts cleared.</div>' ) . '', true  );

                return false;
            }
            if( ! $identifierData = self::getIdentifierData() ){ return false; }
        //    var_export( $identifierData );

            $this->createForm( 'Continue..', '' );
			$this->setViewContent( $this->getForm()->view() );
            if( empty( $_GET['editing_dummy_text'] ) )
            {
                $this->setViewContent( self::__( '
                <div class="pc-notify-info" style="text-align:center;">
                    Update text on the site! 
                <a style="font-size:smaller;" onclick="location.search+=\'&editing_dummy_text=1\'" href="javascript:">Advanced mode</a> or 
                <a style="font-size:smaller;" onclick="location.search+=\'&editing_dummy_text=ddd\'" href="javascript:">Clear saved information</a>
                </div>' ) );
            }
		//	self::v( $_POST );
            if( ! $values = $this->getForm()->getValues() ){ return false; }
         //   self::v( $identifierData );
            $dataForHiddenFields = Ayoola_Page_Layout_ReplaceText::getUpdates( ! empty( $_GET['editing_dummy_text'] ) );
        //    self::v( $dataForHiddenFields );
         //   exit();
        //    static::$_defaultTexts;
            if( empty( $dataForHiddenFields['dummy_search'] ) )
            {
                $dataForHiddenFields = static::$_defaultTexts + $dataForHiddenFields;
            //    var_export( $data );
            }
            elseif( ! empty( $_GET['editing_dummy_text'] ) )
            {
                $dataForHiddenFields['dummy_title'] = array_merge( static::$_defaultTexts['dummy_title'] ? : array(), $dataForHiddenFields['dummy_title'] ? : array() );
                $dataForHiddenFields['dummy_search'] = array_merge( static::$_defaultTexts['dummy_search'] ? : array(), $dataForHiddenFields['dummy_search'] ? : array() );
                $dataForHiddenFields['dummy_replace'] = array_merge( static::$_defaultTexts['dummy_replace'] ? : array(), $dataForHiddenFields['dummy_replace'] ? : array() );
            }
            foreach( $values['dummy_replace'] as $key => $each )
            {

                //  cause when this wasnt sent by form
                if( empty( $values['dummy_search'][$key] ) )
                {
                    $values['dummy_search'][$key] = $dataForHiddenFields['dummy_search'][$key];
                }
                if( empty( $values['dummy_title'][$key] ) )
                {
                    $values['dummy_title'][$key] = $dataForHiddenFields['dummy_title'][$key];
                }
                $values['dummy_search'][$key] = trim( $values['dummy_search'][$key] );
                $values['dummy_replace'][$key] = trim( $values['dummy_replace'][$key] );
                if( '' === $each )
                {
                    $values['dummy_replace'][$key] = trim( $values['dummy_search'][$key], '{-}' );
                }

            }
        //  self::v( $values );
           //    var_export( $values );
            if( ! self::saveTexts( $values, $this->_identifier['layout_name'] ) )
            {
            //    var_export( $values );
                $this->setViewContent(  '' . self::__( '<div class="badnews" style="xtext-align:center;">Something went wrong. Please go back and try again. </div>' ) . '', true  );
                $this->setViewContent( $this->getForm()->view() );
            }
            else
            {
                $this->setViewContent(  '' . self::__( '<div class="goodnews" style="xtext-align:center;">Update saved successfully. Further text update could be done in <a href="/tools/classplayer/get/name/Ayoola_Page_List">Pages</a>. </div>' ) . '', true  );
            //    $this->setViewContent( $this->getForm()->view() );
            }
        //    var_export( $values );

            
            // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
        //    $this->setViewContent( self::__( '<p class="badnews">' . $e->getMessage() . '</p>' ) ); 
            $this->setViewContent( self::__( '<p class="badnews">Theres an error in the code</p>' ) ); 
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

        $data = Ayoola_Page_Layout_ReplaceText::getUpdates( ! empty( $_GET['editing_dummy_text'] ) );
        
        if( empty( $data['dummy_search'] ) )
        {
            $data = static::$_defaultTexts + $data;
        //    var_export( $data );
        }
        elseif( ! empty( $_REQUEST['editing_dummy_text'] ) )
        {
            $data['dummy_title'] = array_merge( static::$_defaultTexts['dummy_title'] ? : array(), $data['dummy_title'] ? : array() );
            $data['dummy_search'] = array_merge( static::$_defaultTexts['dummy_search'] ? : array(), $data['dummy_search'] ? : array() );
            $data['dummy_replace'] = array_merge( static::$_defaultTexts['dummy_replace'] ? : array(), $data['dummy_replace'] ? : array() );
        }

        $i = 0;
        $record = array();
        $allMyData = Ayoola_Page_Layout_ReplaceText::getUpdates( true );
        do
        {
            $fieldset = new Ayoola_Form_Element;
            if( ! empty( $record[$data['dummy_search'][$i]] ) )
            {
                ++$i;
                continue;
            }
            $data['dummy_search'][$i] = trim( $data['dummy_search'][$i] );
            $data['dummy_replace'][$i] = trim( $data['dummy_replace'][$i] );
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

            $data['dummy_replace'][$i] = ( @$data['dummy_replace'][$i] || ! empty( $_REQUEST['editing_dummy_text'] ) ) ? $data['dummy_replace'][$i] : trim( @$data['dummy_search'][$i], '{-}' );

            //  always default to own prefilled data
            if( ! empty( $allMyData['dummy_search'] ) && empty( $_GET['clear_user_settings'] )  )
            {
                if( ! is_array($allMyData['dummy_search'] ) )
                {
                    $allMyDataDummySearch = array();
                }
                else
                {
                    $allMyDataDummySearch = $allMyData['dummy_search'];
                }
                $myReplacementKey = array_search( $data['dummy_search'][$i], $allMyDataDummySearch);
            //    var_export( $myReplacementKey );
                if( $myReplacementKey !== false && ! empty( $allMyData['dummy_replace'][$myReplacementKey] ) )
                {
                   $data['dummy_replace'][$i] = $allMyData['dummy_replace'][$myReplacementKey];
                }
            }
            $info = array( 'name' => 'dummy_replace', 'multiple' => 'multiple', 'label' => $data['dummy_title'][$i] ? : ' ', 'placeholder' => @$data['dummy_search'][$i], 'type' => 'TextArea', 'value' => $data['dummy_replace'][$i] );
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
        $themeInfoX = self::getUpdates( true );
        $themeInfoAll = Application_Settings::getInstance()->selectOne( null, array( 'settingsname_name' => __CLASS__ ) );
        @$themeInfoAll = $themeInfoAll['data'];

        if( ! is_array($themeInfoAll['dummy_replace']) )
        {
            $themeInfoDummyReplace = array();
        }
        else
        {
            $themeInfoDummyReplace = $themeInfoAll['dummy_replace'];
        }
        if( !is_array($themeInfo['dummy_replace']) )
        {
            $infoDummyReplace = array();
        }
        else
        {
            $infoDummyReplace = $themeInfo['dummy_replace'];
        }

		if( empty( $themeInfo['dummy_search'] ) )
		{
		//	$percentage += 100;
		}
       
        elseif( 
            ( @array_intersect_assoc( $infoDummyReplace, $themeInfoDummyReplace ) === $themeInfoAll['dummy_replace'] && $themeInfoAll['dummy_replace'] !== $themeInfo['dummy_replace'] ) 
            || array_intersect( $infoDummyReplace, $themeInfoX['dummy_replace'] ) !== $themeInfo['dummy_replace'] )
		{
		//	$percentage += 100;
		}
		elseif( array_intersect( $infoDummyReplace, $themeInfoX['dummy_replace'] ) === $themeInfo['dummy_replace'] )
		{
	    //	var_export( $percentage );
			$percentage += 100;
		}
		return $percentage;
	}
	// END OF CLASS
}
