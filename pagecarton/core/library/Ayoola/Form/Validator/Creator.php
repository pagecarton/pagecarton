<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Form_Validator_Creator
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php Wednesday 20th of December 2017 03:23PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Ayoola_Form_Validator_Creator extends Ayoola_Form_Validator_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Add new'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
			$this->createForm( 'Submit...', 'Add new' );
			$this->setViewContent( $this->getForm()->view() );

			if( ! $values = $this->getForm()->getValues() ){ return false; }
	//		self::v( $values );
			if( empty( $values['validator_name'] ) &&  $values['validator_title'] )
			{
				$filter = new Ayoola_Filter_Name();
				$values['validator_name'] = strtolower( $filter->filter( '_' . $values['validator_title'] ) );
			} 
			if( $this->getDbTable()->selectOne( null, array( 'validator_name' => $values['validator_name'] ) ) )
			{
				$this->getForm()->setBadnews( 'Please enter a different name for validator: ' . $values['validator_title'] );
				$this->setViewContent( $this->getForm()->view(), true );
				return false; 
			}

			foreach( $values['parameters'] as $key => $each )
			{
				$values['parameters'][$key] = json_decode( $values['parameters'][$key], true );
			}
			
			//	Notify Admin
			$mailInfo = array();
			$mailInfo['subject'] = __CLASS__;
			$mailInfo['body'] = 'Form submitted on your PageCarton Installation with the following information: "' . self::arrayToString( $values ) . '". 
			
			';
			try
			{
		//		var_export( $mailInfo );
				@Ayoola_Application_Notification::mail( $mailInfo );
			}
			catch( Ayoola_Exception $e ){ null; }
		//	if( ! $this->insertDb() ){ return false; }
		//	self::v( $values );
		//	self::v( $this->insertDb( $values ) );
			if( $this->insertDb( $values ) )
			{ 
				$this->setViewContent(  '' . self::__( '<div class="goodnews">Added successfully. </div>' ) . '', true  ); 
			}
		//	$this->setViewContent( $this->getForm()->view() );
            


            // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent(  '' . self::__( '<p class="badnews">Theres an error in the code</p>' ) . '', true  ); 
            return false; 
        }
	}
	// END OF CLASS
}
