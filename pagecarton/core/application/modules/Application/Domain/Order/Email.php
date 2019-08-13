<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Domain_Order_Email
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Email.php Wednesday 20th of December 2017 03:23PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_Domain_Order_Email extends Application_Domain_Order_Abstract
{

    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Domain Email Settings'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
			if( ! $data = $this->getIdentifierData() ){ return false; }
			if( empty( $data['active'] ) )
			{ 
                if( Ayoola_Loader::loadClass( $data['api'] ) && method_exists( $data['api'], 'getInfo' ) )
                {
                    $class = $data['api'];
                    if( ! $class::getInfo( $data ) )
                    {
                        $this->setViewContent( '<p class="badnews">' . sprintf( self::__( 'Domain [%s] is not yet activated.' ), $data['domain_name'] ) . '</p>' ); 
                        return false;
                    }
                }
                else
                {
                    return false;
                }
            }
			if( ! empty( $data['dns_mode'] ) )
			{ 
                $this->setViewContent( '<p class="badnews">' . sprintf( self::__( 'Emails for %s is managed at %s. You need to switch the DNS to default servers to manage emails here.'  ), $data['domain_name'], $data['nameserver1'] ) . '</p>' ); 
                return false;
            }
            $this->createForm( 'Save', '', $data );
            $this->setViewContent( '<h3 class="pc-heading">' . sprintf( self::__( 'Email forwarding settings for %s' ), $data['domain_name'] ). '</h3>', true );             
			$this->setViewContent( $this->getForm()->view() );
        //    var_export( $data );
            if( ! $values = $this->getForm()->getValues() ){ return false; }
        //    var_export( $this->getDbTable()->select() );
            
            if( ! $this->getDbTable()->update( $values, $this->getIdentifier() ) )
            {
                $this->setViewContent( '<p class="badnews">' . self::__( 'Email forwarding settings could not be updated.' ) . '</p>' ); 
                return false;
            }
            $data = $values + $data;
            if( Ayoola_Loader::loadClass( $data['api'] ) && method_exists( $data['api'], 'setEmailForwarding' ) )
            {
                $class = $data['api'];
                if( $class::setEmailForwarding( $data ) )
                {
                    $this->setViewContent( '<p class="goodnews">' . self::__( 'Email forwarding settings updated successfully.' ) . '</p>', true ); 
                    
                    $emailAddress = array();
                    if( Ayoola_Application::getUserInfo( 'email' ) )
                    {
                        $emailAddress[] = Ayoola_Application::getUserInfo( 'email' );
                    }
                    if( @$data['email'] )
                    {
                        $emailAddress[] = $data['email'];
                    }  
        
                    $emailInfo = array(
                                        'subject' => '' . self::__( 'Domain email settings updated' ) . '',
                                        'body' => '' . self::__( 'Domain email settings updated successfully for your domain name. Here is the full information on the domain:' ) . '',
                    
                    );

                    // user notification
                    $emailInfo['to'] = implode( ',', array_unique( $emailAddress ) );
                    @self::sendMail( $emailInfo );

                    // admin
                    $emailInfo['to'] = Ayoola_Application_Notification::getEmails();
                    @self::sendMail( $emailInfo );
                    return true;
                }
                else
                {
                    $this->setViewContent( '<p class="badnews">' . self::__( 'Email forwarding could not be updated on the upstream server' ) . '</p>' ); 
                    return false;
                }
            }            
            // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( self::__( '<p class="badnews">' . $e->getMessage() . '</p>' ) ); 
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
    //    var_export( $values );
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => true ) );
		$form->submitValue = $submitValue ;

		$fieldset = new Ayoola_Form_Element;

        $fieldset->addElement( array( 'name' => 'mailbox', 'label' => 'Forward', 'placeholder' => 'e.g. example@' . Ayoola_Page::getDefaultDomain() . '', 'multiple' => 'multiple', 'type' => 'InputText', 'value' => @$values['mailbox'] ) );
        $fieldset->addElement( array( 'name' => 'mailbox_destination', 'label' => 'To', 'placeholder' => 'e.g. example@gmail.com', 'multiple' => 'multiple', 'type' => 'InputText', 'value' => @$values['mailbox_destination'] ) ); 

        $i = 0;
        $newForm = new Ayoola_Form( array( 'name' => 'xxx', ) );
        $newForm->setParameter( array( 'no_fieldset' => true, 'no_form_element' => true, 'no_required_fieldset' => true ) );
        $newForm->wrapForm = false;
        //  set default mailboxes
        if( empty( $values['mailbox'] ) )
        {
            $values['mailbox'] = array( 
                'info@' . $values['domain_name'],
                'contact@' . $values['domain_name'],
            );
            $values['mailbox_destination'] = array( 
                $values['email'],
                $values['email'],
            );            
        }
        do
        {
            $newFieldSet = new Ayoola_Form_Element;
            $newFieldSet->container = 'span';
            $newFieldSet->allowDuplication = true;
            $newFieldSet->duplicationData = array( 'add' => '+ New Email Forwarding', 'remove' => '- Remove Above Email', 'counter' => 'cgroup_counter', );
            $newFieldSet->container = 'span';
            $newFieldSet->wrapper = 'white-background';
            $newFieldSet->addLegend( 'Email Forwarding <span class="cgroup_counter">' . ( $i + 1 ) . '</span>' );

            $newFieldSet->addElement( array( 'name' => 'mailbox', 'label' => 'Forward', 'placeholder' => 'e.g. example@' . $values['domain_name'] . '', 'multiple' => 'multiple', 'type' => 'InputText', 'value' => @$values['mailbox'][$i] ) );
            $newFieldSet->addElement( array( 'name' => 'mailbox_destination', 'label' => 'To', 'placeholder' => 'e.g. example@gmail.com', 'multiple' => 'multiple', 'type' => 'InputText', 'value' => @$values['mailbox_destination'][$i] ) ); 
                
            $newForm->addFieldset( $newFieldSet );    
            $i++;
        }
        while( ! empty( $values['mailbox'][$i] ) );
        $fieldset = new Ayoola_Form_Element;
        $fieldset->addElement( array( 'name' => 'xxxx', 'type' => 'Html', 'value' => '' ), array( 'html' => $newForm->view(), 'fields' => 'mailbox,mailbox_destination' ) );


		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 
	// END OF CLASS
}
