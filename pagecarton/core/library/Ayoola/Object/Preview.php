<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Object_Preview
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Preview.php Wednesday 4th of October 2017 10:20PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Ayoola_Object_Preview extends PageCarton_Widget
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99, 98 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Preview Widget'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
     //       header( 'Content-Type: text/xml' );
            //  Output demo content to screen
            $classHtml = Ayoola_Abstract_Viewable::viewObject( $_REQUEST['class_name'], $_REQUEST );

            $html = null;
            switch( $_REQUEST['content_type'] )
            {
                case 'js':
                    $html .= Application_Javascript::getCodes( true );
                break;
                default:
		            Application_Javascript::addFile( '' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/' . __CLASS__ . '/?class_name=' . $_REQUEST['class_name'] . '&v=' . filemtime( __FILE__ ) . '&content_type=js&' . http_build_query( $_POST ) );
                    $html .= $classHtml;
                    $html .= Application_Style::getAll(); 
                    $html .= '<!--PC-HTML-DEMARCATION-->';

/*                    $html .= '
                    <script type="text/javascript"> 
                            alert( Math.random() );   
                    </script>'; 
*/                    $html .= Application_Javascript::getFiles(); 
                    $doc = new Ayoola_Xml();
                    $fragment = $doc->createDocumentFragment();
                    @$doc->loadHTML( '<?xml encoding="utf-8" ?>' . $html );
                    $xml = $doc->saveXML( $doc->documentElement->firstChild );
                break;
            }
            
             echo $html;
             exit();

             // end of widget process
          
		}  
		catch( Exception $e )
        { 
      //      echo $e->getMessage();
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( 'Theres an error in the code', true ); 
            return false; 
        }
	}
	// END OF CLASS
}
