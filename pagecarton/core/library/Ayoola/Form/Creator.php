<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
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
 * @category   PageCarton CMS
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
			$this->createForm( 'Continue..', 'Create a new form' );
			$this->setViewContent( $this->getForm()->view() );
		//	self::v( $_POST );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			//	Notify Admin
			$link = 'http://' . Ayoola_Page::getDefaultDomain() . '' . Ayoola_Application::getUrlPrefix() . '/object/name/Ayoola_Form_View/?form_name=' . $values['form_name'] . '';
			$mailInfo = array();
			$mailInfo['subject'] = 'A new form created';
			$mailInfo['body'] = 'A new form has been created on your website with the following information: "' . htmlspecialchars_decode( var_export( $values, true ) ) . '". 
			
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
				$this->setViewContent( '<div class="goodnews">Form created successfully. <a class="" href="' . Ayoola_Application::getUrlPrefix() . '/object/name/Ayoola_Form_View/?form_name=' . $values['form_name'] . '"> Preview it!</a></div>', true ); 
	//			$this->setViewContent( '<a class="" href="' . Ayoola_Application::getUrlPrefix() . '/object/name/Ayoola_Form_View/?form_name=' . $values['form_name'] . '"> Preview it!</a>' ); 
			}
		}
		catch( Exception $e )
		{ 
			$this->_parameter['markup_template'] = null;
			$this->setViewContent( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>', true );
		//	return $this->setViewContent( '<p class="blockednews badnews centerednews">Error with article package.</p>' ); 
		}
    } 
}
