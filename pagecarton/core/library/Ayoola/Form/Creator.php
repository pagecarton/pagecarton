<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Form_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: filename.php date time username $ 
 */

/**
 * @see Ayoola_Form_Abstract
 */
 
require_once 'Ayoola/Page/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Form_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Form_Creator extends Ayoola_Form_Abstract
{
		
    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
		try
		{
			@$mode = $_REQUEST['mode'] ? : 'new';
			$table = Ayoola_Form_Table::getInstance( Ayoola_Form_Table::SCOPE_PROTECTED );
			$table->getDatabase()->setAccessibility( $table::SCOPE_PROTECTED );  
			
			if( $options = $table->select( null, null, array( 'xxx' ) ) )
			{

				$this->setViewContent( '
								<a class="pc-btn" href="?mode=new"> <i class="fa fa-plus pc_give_space"></i>New Form <i class="pc_give_space"></i></a>
								<a class="pc-btn" href="?mode=duplicate"><i class="fa fa-edit pc_give_space"></i> Duplicate Existing Form <i class="pc_give_space"></i></a>
								
								', array( 'translate' => true, 'refresh_content' => true ) );
			}
			switch( $mode )
			{
				case 'duplicate':

					$filter = new Ayoola_Filter_SelectListArray( 'form_name', 'form_title' );
					$options = $filter->filter( $options );  
					$form = new Ayoola_Form();
					$form->submitValue = 'Duplicate';
					$fieldset = new Ayoola_Form_Element();
					$fieldset->addElement( array( 'name' => 'form_to_duplicate', 'type' => 'Select', 'value' => @$values['form_to_duplicate'] ), $options );
					$fieldset->addElement( array( 'name' => 'new_form_title', 'type' => 'InputText', 'value' => @$values['new_form_title'] ) );
					$form->addFieldset( $fieldset );
					$this->setViewContent( $form->view() );
		
				//	self::v( $_POST );
					if( ! $values = $form->getValues() ){ return false; }
					//	var_export( $table->select() );
				
					$formData = $table->selectOne( null, array( 'form_name' => $values['form_to_duplicate'] ) );

					$formData['form_title'] = $values['new_form_title'];
					$filter = new Ayoola_Filter_Name();
					$filter->replace = '-';
					$formData['form_name'] = strtolower( $filter->filter( $formData['form_title'] ) );

					$table->insert( $formData );

				//	$creator = new Ayoola_Form_Creator( array( 'fake_values' => $formData ) );
					header( 'Location: ' . Ayoola_Application::getUrlPrefix() . '/widgets/Ayoola_Form_Editor/?form_name=' . $formData['form_name'] . '' );
					exit();
				//	$this->setViewContent(  '' . self::__( '<div class="goodnews">Form created successfully. <a class="" href="' . Ayoola_Application::getUrlPrefix() . '/widgets/Ayoola_Form_View/?form_name=' . $formData['form_name'] . '"> Preview it!</a> or <a class="" href="' . Ayoola_Application::getUrlPrefix() . '/widgets/Ayoola_Form_Editor/?form_name=' . $formData['form_name'] . '"> Update it!</a></div>' ) . '', true  ); 


				//	self::v( $values );

				break;
				default:
					$this->createForm( 'Continue..', 'Create a new form' );
					$this->setViewContent( $this->getForm()->view() );
		
				//	self::v( $_POST );
					if( ! $values = $this->getForm()->getValues() ){ return false; }
					case 'new':
					$this->createForm( 'Continue..', 'Create a new form' );
					$this->setViewContent( $this->getForm()->view() );
		
				//	self::v( $_POST );
					if( ! $values = $this->getForm()->getValues() ){ return false; }
				//	self::v( $values );
		
					if( ! empty( $_REQUEST['form_name'] ) )
					{
						$filter = new Ayoola_Filter_Name();
						$values['form_name'] = strtolower( $filter->filter( '' . $_REQUEST['form_name'] ) );
					}
					if( $this->getDbTable()->selectOne( null, array( 'form_name' => $values['form_name'] ) ) )
					{
						$this->getForm()->setBadnews( 'Please enter a different name for this form. There is a form with the same name: ' . $values['form_name'] );
						$this->setViewContent( $this->getForm()->view(), true );
						return false; 
					}
					
					//	Notify Admin
					$link = 'http://' . Ayoola_Page::getDefaultDomain() . '' . Ayoola_Application::getUrlPrefix() . '/widgets/Ayoola_Form_View/?form_name=' . $values['form_name'] . '';
					$mailInfo = array();
					$mailInfo['subject'] = 'A new form created';
					$mailInfo['body'] = 'A new form has been created on your website with the following information: "' . self::arrayToString( $values ) . '". 
					
					Preview the form on: ' . $link . '
					';
					try
					{
				//		var_export( $mailInfo );
						@Ayoola_Application_Notification::mail( $mailInfo );
					}
					catch( Ayoola_Exception $e ){ null; }
				//	if( ! $this->insertDb() ){ return false; }
					if( $this->insertDb( $values ) )
					{ 
						$this->setViewContent(  '' . self::__( '<div class="goodnews">Form created successfully. <a class="" href="' . Ayoola_Application::getUrlPrefix() . '/widgets/Ayoola_Form_View/?form_name=' . $values['form_name'] . '"> Preview it!</a></div>' )  ); 
					}
				break;
			}
		}
		catch( Exception $e )
		{ 
			$this->_parameter['markup_template'] = null;
			$this->setViewContent( $e->getMessage() );
		}
    } 
}
