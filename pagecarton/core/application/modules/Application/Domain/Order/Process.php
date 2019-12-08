<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Domain_Order_Process
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Process.php Wednesday 20th of December 2017 03:23PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_Domain_Order_Process extends Application_Domain_Order_Abstract
{

    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Process Domain Order'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
			$parameters = $this->getParameter();
			if( empty( $parameters['domain_name'] ) )
			{
				return false;
			}
			$parameters['domain_name'] = strtolower( $parameters['domain_name'] );
		//	$parameters['domain_name'] = $parameters['domain_name'] . time();
			$values = $parameters;
			$values['username'] = $values['full_order_info']['username'];
			$values['user_id'] = $values['full_order_info']['user_id'];
		//	var_export( $values );
			if( $this->getDbTable()->select( null, array( 'domain_name' => $values['domain_name'], 'active' => 1 ) ) )
			{ 
				return false;
			}
			switch( strtolower( $values['order_status'] ) )
			{ 
				case 'payment successful':
				case '99':
				case '100':
					if( $this->insertDb( $values ) )
					{ 
						$this->setViewContent( '<div class="goodnews">' . sprintf( self::__( 'Domain name "%s" has been added to your account successfully.' ), $parameters['domain_name'] ) . '</div>', true  ); 
					}
					$domainArray = explode( '.', $values['domain_name'] );
					$firstPart = array_shift( $domainArray );
					$ext = implode( '.', $domainArray );
					$apiInfo = Application_Domain_Registration_Api::getInstance()->select( null, array( 'extension' => $ext ) );
		//	var_export( $ext );
		//	var_export( $apiInfo );
					foreach( $apiInfo as $eachApi )
					{
						if( ! Ayoola_Loader::loadClass( $eachApi['class_name'] ) )
						{ 
							continue;
						}
						$class = $eachApi['class_name'];
					//	$class = new $class( $parameters );
                        if( $class::register( $values ) )
                        {
                            $this->setViewContent( '<div class="goodnews">' . sprintf( self::__( '%s has been activated successfully.' ), $parameters['domain_name'] ) . '</div>', true );
                       //     var_export( $this->getDbData() );
                            if( $this->getDbTable()->update( array( 'active' => 1, 'api' => $class ), array( 'domain_name' => $values['domain_name'] ) ) )
                            { 
                                
                            }
                            if( ! static::getInfo( $values ) )
                            {
                                return false;
                            }
                            
                            //	set default DNS
                            $class::setDNS( $values );
                            $class::setEmailForwarding( $values );
                        }
                        else
                        {
                            $this->setViewContent( '<div class="goodnews">' . sprintf( self::__( '%s is currently being processed.' ), $parameters['domain_name'] ) . '</div>', true );
                            if( $this->getDbTable()->update( array( 'api' => $eachApi['class_name'] ), array( 'domain_name' => $values['domain_name'] ) ) )
                            { 
                                
                            }
                        }
                //			var_export( $this->getDbTable()->select() );  
			//			var_export( $this->getDbTable()->select() );  
					//	$this->setViewContent( $class->view() ); 
						break;
					}
			
					//	Notify Admin
					$mailInfo = array();
					$mailInfo['subject'] = __CLASS__;
					$mailInfo['body'] = $this->view();
					try
					{
						@Ayoola_Application_Notification::mail( $mailInfo );
					}
					catch( Ayoola_Exception $e ){ null; }

					//	notification
					$mailInfo = array();
					$mailInfo['subject'] = 'Your domain order';
					$mailInfo['to'] = $values['full_order_info']['email'];
					$mailInfo['body'] = $this->view();

					self::sendMail( $mailInfo );

				break;   
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
	// END OF CLASS
}
