<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    My_First_Widget
 * @copyright  Copyright (c) 2020 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Widget.php Thursday 9th of April 2020 11:28AM santamichello@gmail.com $
 */

/**
 * @see PageCarton_Widget
 */

class My_First_Widget extends PageCarton_Widget
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
    protected static $_accessLevel = array( 0 );

    /**
     * 
     * 
     * @var string 
     */
    protected static $_objectTitle = 'My first widget'; 

    /**
     * Performs the whole widget running process
     * 
     */
    public function init()
    {    
      try
      { 
            //  Code that runs the widget goes here...

            //  Output demo content to screen
         $this->setViewContent( '<h1>Hello, This is my first pagecarton widget</h1>' ); 
         $this->setViewContent( '<p>i customized this widget (' . __CLASS__ . ') by editing the file below:</p>' ); 
         $this->setViewContent( '<p style="font-size:smaller;">' . __FILE__ . '</p>' ); 
         $this->createForm( 'Submit', 'My Form Legend' );

                //  Shows form to screen
         $this->setViewContent( $this->getForm()->view() );
         if( ! $values = $this->getForm()->getValues() ){ return false; }
         $this->setViewContent(  '' . self::__( '<p class="goodnews">Form Submitted</p>' ) . '', true  ); 
         $this->setViewContent( '<h1 style="text-align:center;font-size:50px"> Why hello there, '.$values['name'] .'</h1>'); 
         $this->setViewContent( '<h1 style="text-align:center"> We welcome our newest '.$values['occupation'].' to PageCarton</h1>'); 
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

public function createForm( $submitValue = null, $legend = null, Array $values = null )  
{
        //  Initialize the form
    $form = new Ayoola_Form( array( 'formname' => $this->getObjectName() ) );

        //  Set the submit button value
    $form->submitValue = 'proceed' ;

        //  switch whether to display all fieldsets at once or not
    $form->oneFieldSetAtATime = false;

        //  Defines the code chunk for a complete fieldset. 
        //  repeat this to have more fieldsets
    {
            //  Initialize a new fieldset
        $fieldset = new Ayoola_Form_Element;

            //  Wether to display label or not
        $fieldset->placeholderInPlaceOfLabel = true;

            //  Add input element of text type
        $fieldset->addElement( array( 'name' => 'username', 
            'placeholder' => 'Enter your name here...', 
            'label' => 'Demo Input Field', 'type' => 'InputText', 
            'value' => @$values['name'] ) );

            //  Add element of Select type

            //  Prepare the select options
        $options = array( 
            'none' => 'please select', 
            'Developer' => 'Developer',
            'Mechanic' => 'Mechanic',
            'Tutor' => 'Tutor',
        );

            //  Add select element
        $fieldset->addElement( array( 'name' => 'occupation',
           'placeholder' => 'Enter value here...',
           'label' => 'What do you do?',
           'type' => 'Select',
           'value' => @$values['occupation'] ), $options );


        $fieldset->addElement( array( 'name' => 'email',
           'placeholder' => 'Enter your email...',
           'label' => 'Enter Email',
           'type' => 'InputText',
           'value' => @$values['email'] ) );
            //  other input types are InputText, Hidden, Select, 
            //  Radio, Checkbox, SelectMultiple, Document, Password, TextArea

            //  Adds the legend
        $fieldset->addLegend( $legend );

            //  Adds fieldset to form
        $form->addFieldset( $fieldset );
    }

        //  Finalizes form creation
    $this->setForm( $form );
}
	// END OF CLASS
}
