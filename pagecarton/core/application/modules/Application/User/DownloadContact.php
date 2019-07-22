<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_User_DownloadContact
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: DownloadContact.php Monday 18th of September 2017 10:50AM  $
 */

/**
 * @see PageCarton_Widget
 */

class Application_User_DownloadContact extends Application_User_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Download Contact'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...

			if( ! $data = self::getIdentifierData() ){ return false; }
			$this->createConfirmationForm( 'Download Contact',  'Download VCARD Contact ' . $data['username'] );
			$this->setViewContent( $this->getForm()->view(), true );
			$namespace = 'Application_User_';
			if( ! $values = $this->getForm()->getValues() ){ return false; }

            $values = $data;

$content = "BEGIN:VCARD\r\n";
$content .= "VERSION:3.0\r\n";
$content .= "CLASS:PUBLIC\r\n";
$content .= "FN:{$values['firstname']} {$values['lastname']}\r\n";
$content .= "N:{$values['lastname']};{$values['firstname']} ;;;\r\n";
$content .= "TITLE:{$values['title']}\r\n";
$content .= "ORG:{$values['organization']}\r\n";
$content .= "ADR;TYPE=work:;;{$values['address']};{$values['city']} ;{$values['province']};{$values['zip']};\r\n";
$content .= "EMAIL;TYPE=internet,pref:{$values['email']}\r\n";
$content .= "TEL;TYPE=HOME,voice:{$values['phone_number']}\r\n";
$content .= "URL:{$values['website']}\r\n";
$content .= "END:VCARD\r\n";   
            header('Content-Type: text/vcard');
 //         header('Content-Length: ' . filesize($file));
            header('Content-Disposition: attachment; filename="' . $values['firstname'] . ' ' . $values['lastname'] . '.vcf"' );
            echo $content;
            exit();
            // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent(  '' . self::__( 'Theres an error in the code' ) . '', true  ); 
            return false; 
        }
	}
	// END OF CLASS
}
