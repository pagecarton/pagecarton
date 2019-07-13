<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_Copy
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Copy.php date time ayoola $
 */

/**
 * @see Ayoola_Page_Abstract
 */
 
require_once 'Ayoola/Page/Abstract.php';  


/**
 * @category   PageCarton
 * @package    Ayoola_Page_Copy
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Page_Copy extends Ayoola_Page_Abstract
{
	
    /**
     * This method starts the chain for update
     *
     * @param void
     * @return null
     */
    public function init()
    {
		try
		{
	 //		var_export( $data ); 
			$this->createForm( 'Continue...', 'Copy contents of one page to another' );     
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			$this->setViewContent( '<h3 class="goodnews">Page copied successfully</h3>', true ); 
			
			$origin = $this->getPageFilesPaths( $values['origin'] );
			$destination = $this->getPageFilesPaths( $values['destination'] );
			if( $values['theme'] )
			{
				$parameters = array( 
										'fake_values' => 
															array( 
																'old_page' => '/', 
																'new_page' => $values['destination'] 
															),
										'no_init' => true,
										);

				$class = new Ayoola_Page_Layout_Pages_Duplicate( $parameters );
				if( $class->init() )
				{
				//	$this->setViewContent( self::__( '<div>' . $class->view() . '</div>' ) ); 
				}
			//	var_export( $class->view() );

            //    $fPaths = Ayoola_Page_Layout_Pages_Duplicate::getPagePaths( $themeName, $values['old_page'] );
				$tPaths = Ayoola_Page_Layout_Pages::getPagePaths( Application_Settings_Abstract::getSettings( 'Page', 'default_layout' ), $values['destination'] );
			//	var_export( $values);
			//	var_export( $tPaths);
				foreach( $origin as $key => $file )
				{			
					$to = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $tPaths[$key];
					Ayoola_Doc::createDirectory( dirname( $to ) );
					if( $origin[$key] === $to || ! $tPaths[$key] || ! $origin[$key] )
					{
						continue;
					}
				//	var_export( $origin[$key] );
				//	var_export( $tPaths[$key] );
						//	Create the Directory  
					if( is_file( $origin[$key] ) &&  ! @copy( $origin[$key], $to ) )  
					{
						$this->setViewContent( self::__( '<p>Contents "' . $origin[$key] . '" could not be copied to "' . $tPaths[$key] . '".</p>' ) ); 
					}
				
				}
	
			}
			
		//	$values['default_url'] = $values['default_url'] == '/' ? '' : $values['default_url'];
		//	var_export( $pageInfo );
		//	var_export( $values );
	//		var_export( $origin );
		//	var_export( $destination );
		//	var_export( $default );
			foreach( $destination as $key => $file )
			{			
				if( $origin[$key] === $destination[$key] )
				{
					continue;
				}
				//	Create the Directory  
				Ayoola_Doc::createDirectory( dirname( $file ) );
				if( is_file( $origin[$key] ) && ! @copy( $origin[$key], $destination[$key] ) )  
				{
					$this->setViewContent( self::__( '<p>Contents of "' . $origin[$key] . '" could not be copied to "' . $destination[$key] . '".</p>' ) ); 
				}
			
			}
			
			
	//		var_export( $data );
			$this->setViewContent( self::__( '<p>Contents of "' . $values['origin'] . '" was copied to "' . $values['destination'] . '".</p>' ) ); 
			
		}
		catch( Exception $e )
		{ 
		//	return false; 
			$this->setViewContent( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>', true );
		}
		
    } 
	
	//	This is to implement the abstract method of the parent class. Not all inheriting classes needs a form
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
	{
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
	//	$form->oneFieldSetAtATime = true;
		$form->submitValue = $submitValue ;
		$fieldset = new Ayoola_Form_Element;
	
		//	URL to clone
		$option = new Ayoola_Page_Page;
		$option = $option->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'url', 'url');
		$option = $filter->filter( $option );
		$fieldset->addElement( array( 'name' => 'origin', 'label' => 'Origin Page', 'type' => 'Select', 'value' => @$settings['origin'] ), array( '' => 'Please Select' ) + $option );
		$fieldset->addElement( array( 'name' => 'destination', 'label' => 'Destination Page', 'type' => 'Select', 'value' => @$settings['destination'] ), array( '' => 'Please Select' ) + $option );


		$fieldset->addElement( array( 'name' => 'theme', 'label' => 'Copy to Theme Page', 'type' => 'Select', 'value' => @$settings['theme'] ), array( '' => 'No', 1 => 'Yes' ) );

		$fieldset->addRequirement( 'origin', array( 'InArray' => $option + array( 'badnews' => 'Please select a page. ' ) ) ); 
		$fieldset->addRequirement( 'destination', array( 'InArray' => $option + array( 'badnews' => 'Please select a page. ' ) ) ); 
		
		
	//	$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
	}
}
