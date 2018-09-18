<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Domain_Registration_Api_Namecheap
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Namecheap.php Saturday 25th of August 2018 09:45PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_Domain_Registration_Api_Namecheap extends Application_Domain_Order_Process
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
	protected static $_objectTitle = 'Register Domain on Namecheap'; 

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
			$parameters = $this->getParameter();
      //      $parameters = array ( 'domain_name' => 'xdter' . time() . 'esdted.com' , 'exx' => '', 'suggestions' => array ( 0 => 'xdterest.com', ), 'firstname' => 'Ayoola', 'lastname' => 'Falola', 'organization_name' => '', 'street_address' => '10 Road 9 Adebisi Layout', 'street_address2' => 'Apata', 'city' => 'Ibadan', 'province' => 'Oyo', 'zip' => '234022', 'country' => 'Nigeria', 'email' => 'ayoola.falola@gmail.com', 'country_code' => '234', 'phone_number' => '2348162081195', 'subscription_name' => 'xdterest.com (Domain Name Registration)', 'subscription_label' => 'xdterest.com (Domain Name Registration)', 'price' => '0', 'cycle_name' => 'Per/Year', 'cycle_label' => 'year(s)', 'price_id' => 'xdterest.com_0', 'subscription_description' => 'Domain name registration charges for (xdterest.com)', 'url' => 'javascript:;', 'classplayer_link' => '/tools/classplayer/get/object_name/Application_Domain_Registration/', 'object_id' => 'xdterest.com', 'multiple' => '1', 'item_time' => 1535243665, 'classplayer_url' => NULL, );
			if( empty( $parameters['domain_name'] ) )
			{
				return false;
			}
			$values = $parameters;
			$values['username'] = $values['full_order_info']['username'];
			$values['user_id'] = $values['full_order_info']['user_id'];
        //    var_export( $values['order_status'] );
			switch( strtolower( $values['order_status'] ) )
			{ 
				case 'payment successful':
				case '99':
				case '100':
        //    var_export( $values );
           //     var_export( $values );
                    $data = array();
                    $data['ApiUser'] = 'joywealth';
                    $data['UserName'] = 'joywealth';
                    $data['ApiKey'] = '33192f5370ae4db1bb0c8f148b1e7c1b';
                    $data['Command'] = 'namecheap.domains.create';
                    $data['ClientIp'] = $_SERVER['SERVER_ADDR'];
                    $data['DomainName'] = $values['domain_name'];
                    $data['DomainName'] = time() . $values['domain_name'];
                    $data['Years'] = $values['multiple'] ? : '1';

                    //  set addresses
                    $addresses = array( 'AuxBilling', 'Tech', 'Registrant', 'Admin', );
                    foreach( $addresses as $address )
                    {
                        $data[ $address . 'OrganizationName'] = $values['organization_name'];
                        $data[ $address . 'FirstName'] = $values['firstname'];
                        $data[ $address . 'LastName'] = $values['lastname'];
                        $data[ $address . 'Address1'] = $values['street_address'];
                        $data[ $address . 'Address2'] = $values['street_address2'];
                        $data[ $address . 'City'] = $values['city'];
                        $data[ $address . 'StateProvince'] = $values['province'];
                        $data[ $address . 'PostalCode'] = $values['zip'];
                        $data[ $address . 'Country'] = $values['country'];
                        $data[ $address . 'Phone'] = '+' . $values['country_code'] . '.' . $values['phone_number'];
                        $data[ $address . 'EmailAddress'] = $values['email'] ? : ( $values['checkout_info']['email'] ? : $values['checkout_info']['email_address'] );
                    }
               //     $data['ApiKey'] = '56b4c87ef4fd49cb96d915c0db68194';
                    $options = array();
                    $options['post_fields'] = $data;
               //     $url = 'https://api.namecheap.com/xml.response';
                    $url = 'https://api.sandbox.namecheap.com/xml.response';
                    $response = self::fetchLink( $url, $options );
                    $allFeed = (array) simplexml_load_string( $response );
                    $allFeed = (array) $allFeed['CommandResponse'];
                    $allFeed = (array) $allFeed['DomainCreateResult'];
                    $allFeed = (array) $allFeed['@attributes'];
            //        if( $allFeed['Domain'] == $values['domain_name'] && $allFeed['Registered'] == 'true' )
                    if( $allFeed['Registered'] == 'true' )
                    {
						$this->setViewContent( '<div class="goodnews">' . $values['domain_name'] . ' is activated.</div>', true );
                   //     var_export( $this->getDbData() );
                        if( $this->getDbTable()->update( array( 'active' => 1 ), array( 'domain_name' => $values['domain_name'] ) ) )
                        { 
                            
                        }
                         
                    }
                    else
                    {
						$this->setViewContent( '<div class="goodnews">' . $values['domain_name'] . ' is currently being processed.</div>', true ); 
                    }

            //        var_export( $this->getViewContent() );
          //        self::v( $values['domain_name'] );
               //     self::v( $allFeed['CommandResponse']['DomainCreateResult']  );
           //      var_export( $allFeed );
               //     echo( $response );
                //    exit();

              //      https://api.namecheap.com/xml.response?ApiUser=apiexample&ApiKey=56b4c87ef4fd49cb96d915c0db68194&UserName=apiexample&Command=namecheap.domains.create&ClientIp=192.168.1.109&DomainName=aa.us.com&Years=1&AuxBillingFirstName=John&AuxBillingLastName=Smith&AuxBillingAddress1=8939%20S.cross%20Blv&AuxBillingStateProvince=CA&AuxBillingPostalCode=90045&AuxBillingCountry=US&AuxBillingPhone=+1.6613102107&AuxBillingEmailAddress=john@gmail.com&AuxBillingOrganizationName=NC&AuxBillingCity=CA&TechFirstName=John&TechLastName=Smith&TechAddress1=8939%20S.cross%20Blvd&TechStateProvince=CA&TechPostalCode=90045&TechCountry=US&TechPhone=+1.6613102107&TechEmailAddress=john@gmail.com&TechOrganizationName=NC&TechCity=CA&AdminFirstName=John&AdminLastName=Smith&AdminAddress1=8939%cross%20Blvd&AdminStateProvince=CA&AdminPostalCode=9004&AdminCountry=US&AdminPhone=+1.6613102107&AdminEmailAddress=joe@gmail.com&AdminOrganizationName=NC&AdminCity=CA&RegistrantFirstName=John&RegistrantLastName=Smith&RegistrantAddress1=8939%20S.cross%20Blvd&RegistrantStateProvince=CS&RegistrantPostalCode=90045&RegistrantCountry=US&RegistrantPhone=+1.6613102107&RegistrantEmailAddress=jo@gmail.com&RegistrantOrganizationName=NC&RegistrantCity=CA&AddFreeWhoisguard=no&WGEnabled=no&GenerateAdminOrderRefId=False&IsPremiumDomain=True&PremiumPrice=206.7&EapFee=0
				break;   
			}

             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            echo $e->getMessage();
        //    $this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>' ); 
            $this->setViewContent( '<p class="badnews">Theres an error in the code</p>' ); 
            return false; 
        }
	}
	// END OF CLASS
}
