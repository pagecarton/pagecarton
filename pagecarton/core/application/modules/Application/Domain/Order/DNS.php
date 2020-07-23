<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Domain_Order_DNS
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: DNS.php Wednesday 20th of December 2017 03:23PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_Domain_Order_DNS extends Application_Domain_Order_Abstract
{

    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'DNS Settings'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            if( ! $data = $this->getIdentifierData() ){ return false; }

			if( empty( $data['active'] ) || empty( $data['expiry_date'] ) )
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
            $this->createForm( 'Save', '', $data );
            $this->setViewContent( '<h3 class="pc-heading">' . sprintf( self::__( 'DNS Settings for %s' ), $data['domain_name'] ). '</h3>', true );             
			$this->setViewContent( $this->getForm()->view() );
            if( ! $values = $this->getForm()->getValues() ){ return false; }
            
            if( ! $this->updateDb() )
            {
                $this->setViewContent( '<p class="badnews">' . self::__( 'Domain DNS could not be updated.' ) . '</p>' ); 
                return false;
            }
            $data = $values + $data;
            if( Ayoola_Loader::loadClass( $data['api'] ) && method_exists( $data['api'], 'setDNS' ) )
            {
                $class = $data['api'];
                if( $class::setDNS( $data ) )
                {
                    $this->setViewContent( '<p class="goodnews">' . self::__( 'Domain DNS updated successfully.' ) . '</p>', true ); 

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
                                        'subject' => '' . self::__( 'Domain DNS updated successfully.' ) . '',
                                        'body' => '' . self::__( 'Domain DNS updated successfully for your domain name' ) . '',
                    
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
                    $this->setViewContent( '<p class="badnews">' . self::__( 'DNS settings could not be updated on the upstream server' ) . '</p>' ); 
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
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => true ) );
		$form->submitValue = $submitValue ;

		$fieldset = new Ayoola_Form_Element;

        $fieldset->addElement( array( 'name' => 'dns_mode', 'label' => 'DNS Mode', 'type' => 'Select', 'value' => @$values['dns_mode'] ), array( '0' => 'Use default servers', '1' => 'Custom Nameservers', ) ); 
        if( ! $data = $this->getIdentifierData() ){ return false; }
        if( @$values['dns_mode'] || Ayoola_Form::getGlobalValue( 'dns_mode' ) )
        {
        //    var_export( $data );
            if( Ayoola_Loader::loadClass( $data['api'] ) && method_exists( $data['api'], 'getNameservers' ) )
            {
                $class = $data['api'];
                if( $nameservers = $class::getNameservers( $data ) )
                {
                    @$values['nameserver1'] = @$nameservers['nameserver1'] ? : @$values['nameserver1'];
                    @$values['nameserver2'] = @$nameservers['nameserver2'] ? : @$values['nameserver2'];
                }
            }
            $fieldset->addElement( array( 'name' => 'nameserver1', 'label' => 'Primary Nameserver', 'placeholder' => 'e.g. ns1.example.com', 'type' => 'InputText', 'value' => @$values['nameserver1'] ) ); 
            $fieldset->addElement( array( 'name' => 'nameserver2', 'label' => 'Secondary Nameserver', 'placeholder' => 'e.g. ns2.example.com', 'type' => 'InputText', 'value' => @$values['nameserver2'] ) ); 
			$fieldset->addRequirement( 'nameserver1', array( 'NotEmpty' => true ) );
			$fieldset->addRequirement( 'nameserver2', array( 'NotEmpty' => true ) );
        }
        else
        {
        //    var_export( $data );
            if( Ayoola_Loader::loadClass( $data['api'] ) && method_exists( $data['api'], 'getDns' ) )
            {
                $class = $data['api'];
            //    var_export( $data );

                if( $dns = $class::getDns( $data ) )
                {
                //    var_export( $dns );
                }
            }
            $fieldset->addElement( array( 'name' => 'nameserver1', 'type' => 'Hidden', 'value' => @$values['nameserver1'] ) ); 
            $fieldset->addElement( array( 'name' => 'nameserver2', 'type' => 'Hidden', 'value' => @$values['nameserver2'] ) ); 
        }

		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 
	// END OF CLASS
}
