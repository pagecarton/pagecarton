<?php

class PageCarton_Widget_Sample extends PageCarton_Widget
{   
	
    /**
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Sample Widget Title'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...

            // to outputs something to the screen
            {
                $this->setViewContent( '<h1>Hello World</h1>' ); 
                $this->setViewContent( '
                <p>This is sample PageCarton Widget. 
                Create a widget file on 
                <a onClick="ayoola.spotLight.showLinkInIFrame( \'' 
                . Ayoola_Application::getUrlPrefix() .
                 '/tools/classplayer/get/name/Ayoola_Object_List/\' 
                 href="javascript:;">Widgets</a>
                </p>' ); 
                $this->setViewContent( '<p>Here is a sample code of 
                what a widget could do</p>' ); 
                $this->setViewContent( '<p style="max-height:200px; 
                overflow:auto; font-size:smaller;">' 
                . highlight_file( __FILE__, true ) . '</p>' ); 

            }
            
            //  we don't intend to run anything below here'
            return true;     

            //  To work with PageCarton_Widget Forms
            {
                //  instantiate the form
                $this->createForm( 'Submit', 'My Form Legend' );

                //  Shows form to screen
                $this->setViewContent( $this->getForm()->view() );
                		
                //  Ends the widget operation here unless form is submited 
			    if( ! $values = $this->getForm()->getValues() ){ return false; }

                // If we get beyond this point, the form has been submited,
                //  The form data is now loaded in array $values

                //  output the form data to the screen
                //  the true parameter ensures previous data sent to screen is cleared.
                $this->setViewContent( '<p class="goodnews">Form Submitted</p>', true ); 
                $this->setViewContent( $values['demo_field_name'] ); 

       //         return true;
  
            }

            //  Insert Data into Database
            {
                //  instantiate the table. 
                //  change PageCarton_Table to your own table class name
                $table = 'PageCarton_Table';
                $table = new $table();

                //  prepares data to send to db
                $whatToInsert = array(
                    'sample_field_name' => 'Ayoola',
                    'another_sample_field' => 'Tolu',
                ); 

                //  inserts data
                $table->insert( $whatToInsert );
  
            }

            //  Retrieves Data from Database
            {
                //  instantiate the table. 
                //  change PageCarton_Table to your own table class name
                $table = 'PageCarton_Table';
                $table = new $table();

                //  fetches all records
                $allDbData = $table->select();

                //  $allDbData now is an array that holds all db data 

                //  fetches just one data record. Fetches the last record inserted
                $lastRecordInserted = $table->selectOne();

                //  filters DB records
                {
                    //  fetches all records
                    $where = array(
                        'sample_field_name' => 'Ayoola',
                        'another_sample_field' => 'Tolu',
                    ); 

                    //  returns records where 'sample_field_name' field 
                    //  is  'Ayoola' and 'another_sample_field' is 'Tolu'
                    $allDbData = $table->select( null, $where );

                    //  fetches just one data record. 
                    //  Fetches the last record inserted
                    $lastRecordInserted = $table->selectOne( null, $where );
                }

                //  updates and delete DB records
                {
                    //  update records
                    $where = array(
                        'sample_field_name' => 'Ayoola',
                        'another_sample_field' => 'Tolu',
                    ); 
                    $updatedData = array(
                        'sample_field_name' => 'Ayoola',
                        'another_sample_field' => 'Falola',
                    ); 

                    //  update records where 'sample_field_name' field
                    //   is  'Ayoola' and 'another_sample_field' is 'Tolu'
                    $table->update( $updatedData, $where );

                    //  delete records
                    $table->delete( $where );
                }
            }

          
		}
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( '<p class="badnews">Theres an error in the code</p>', true ); 
            return false; 
        }
	}
	
	  
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )  
    {
        //  Initialize the form
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );

        //  Set the submit button value
		$form->submitValue = $submitValue ;

        //  switch whether to display all fieldsets at once or not
		$form->oneFieldSetAtATime = true;

        //  Defines the code chunk for a complete fieldset. 
        //  repeat this to have more fieldsets
        {
            //  Initialize a new fieldset
            $fieldset = new Ayoola_Form_Element;

            //  Wether to display label or not
            $fieldset->placeholderInPlaceOfLabel = false;

            //  Add input element of text type
            $fieldset->addElement( array( 'name' => 'demo_field_name', 
            'placeholder' => 'Enter a value here...', 
            'label' => 'Demo Input Field', 'type' => 'InputText', 
            'value' => @$values['demo_field_name'] ) );

            //  Add element of Select type

            //  Prepare the select options
            $options = array( 
                                'foo' => 'bar', 
                                'demo_option_name' => 'demo option value',
                             );

            //  Add select element
            $fieldset->addElement( array( 'name' => 'field_name',
             'placeholder' => 'Enter value here...',
              'label' => 'Demo Select Field',
               'type' => 'Select',
                'value' => @$values['field_name'] ), $options );

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
