<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_Hook_Sample
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Sample.php Monday 14th of May 2018 09:49AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PageCarton_Hook_Sample extends PageCarton_Widget implements PageCarton_Hook_Interface
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
	protected static $_objectTitle = 'Hook Sample'; 

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
             $this->setViewContent( self::__( '<h1>Hello PageCarton Widget</h1>' ) ); 
             $this->setViewContent( self::__( '<p>Customize this widget (' . __CLASS__ . ') by editing this file below:</p>' ) ); 
             $this->setViewContent( self::__( '<p style="font-size:smaller;">' . __FILE__ . '</p>' ) ); 

             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent(  '' . self::__( '<p class="badnews">Theres an error in the code</p>' ) . '', true  ); 
            return false; 
        }
	}


    /**
     * Hook to another widget
     * 
     * param PageCarton_Widget The Widget to Hook to
     * param string Current Method Where Hook is Running
     * param array Arguments Passed to the method
     * 
     */
	public static function hook( Ayoola_Abstract_Viewable $object, $functionName = null, $arguments = null )
    {   
        switch( $functionName )
        {
            case '__construct':
            
            break;
            case 'setParameter':
            
            break;
            case 'setViewContent':
            
            break;
            case 'view':
            
            break;
            case 'setForm':
                $form = $arguments[0];
                $fieldset = new Ayoola_Form_Element;
            //	$fieldset->placeholderInPlaceOfLabel = false;       
                $fieldset->addElement( array( 'name' => 'field_name', 'type' => 'InputText', 'value' => @$values['field_name'] ) ); 
                $form->addFieldset( $fieldset );   
       //     var_export( $arguments[0] );

            break;
        }
    } 
    
	// END OF CLASS
}
