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
                $this->setIdentifier();
            }
            catch( Exception $e )
            { 
                $this->_identifier[$this->getIdColumn()] = Ayoola_Page_Editor_Layout::getDefaultLayout();
            //	return false; 
            }
            if( ! $identifierData = self::getIdentifierData() ){ return false; }
			$this->createForm( 'Continue..', '' );
			$this->setViewContent( '<div class="pc-notify-info" style="text-align:center;">Update text on the site! <a style="font-size:smaller;" href="?editing_dummy_text=1">Advanced mode</a></div>' );
			$this->setViewContent( $this->getForm()->view() );
		//	self::v( $_POST );
            if( ! $values = $this->getForm()->getValues() ){ return false; }
         //   self::v( $identifierData );
      //      self::v( $values );
    //        $identifierData += $values;
            $this->updateDb( $values );


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
		$form->oneFieldSetAtATimeJs = true;

        if( ! $data = self::getIdentifierData() ){ return false; }

        $i = 0;
        do
        {
            $fieldset = new Ayoola_Form_Element;
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
                $fieldset->addElement( array( 'name' => 'dummy_search', 'multiple' => 'multiple', 'label' => 'Dummy Text', 'placeholder' => @$data['dummy_search'][$i], 'type' => 'InputText', 'value' => @$data['dummy_search'][$i] ) );
            }
            $info = array( 'name' => 'dummy_replace', 'multiple' => 'multiple', 'label' => $data['dummy_title'][$i] ? : ' ', 'placeholder' => @$data['dummy_search'][$i], 'type' => 'TextArea', 'value' => @$data['dummy_replace'][$i] ? : @$data['dummy_search'][$i] );
            if( strip_tags( $data['dummy_search'][$i] ) !== $data['dummy_search'][$i] )
            {
                $info['data-html'] = '1';
            //    var_export( $info );
            }
            if( ! empty( $_REQUEST['editing_dummy_text'] ) )
            {
                $info['label'] = 'Default Replacement';
            //    var_export( $info );
            }
            $fieldset->addElement( $info );
            $form->addFieldset( $fieldset );
            ++$i;
        }
        while(  isset( $data['dummy_search'][$i] )  );
        Application_Article_Abstract::initHTMLEditor();
    
		$this->setForm( $form );
    } 
	// END OF CLASS
}
