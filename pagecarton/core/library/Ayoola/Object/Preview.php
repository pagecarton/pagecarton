<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
            $class = $_REQUEST['class_name'];
            if( ! Ayoola_Loader::loadClass( $class ) )
            {
                exit;
            }
            $classHtml = null;
          //  var_export( $_POST );
			$parameters = Ayoola_Page_Editor_Layout::prepareParameters( $_POST );
   //         var_export( $class );

            if( $class === 'Ayoola_Object_Embed' && $parameters['editable'] == 'Ayoola_Object_Play' )
            {

                //  Ayoola_Object_Play should display in widget mode, but it cannot play in widget
                //  unset players variables here to stop infinite loop
   //          var_export( $parameters );
                unset( $_REQUEST['name'], $_REQUEST['object_name'], $_REQUEST['pc_module_url_values'] );
            //    return false;
            }
    //        exit();
            set_time_limit( 0 );
            if( ! empty( $_REQUEST['rebuild_widget_box'] ) )   
            {
            //    var_export( $_REQUEST );
             //   $classHtml .= Ayoola_Abstract_Viewable::getViewableObjectRepresentation( $_REQUEST );

                //  using Ayoola_Abstract_Viewable is not allow us access the real class parameters
                $classHtml .= $class::getViewableObjectRepresentation( $_REQUEST );
            }
            else
            {
                $classHtml .= Ayoola_Abstract_Viewable::viewObject( $class, $parameters );
            }

            $html = null;
            switch( $_REQUEST['content_type'] )
            {
                case 'js':
                    header( 'Content-Type: application/javascript' );
                    $html .= Application_Javascript::getCodes( true );
                break;
                default:
                    unset( $parameters['editable'], $parameters['codes'] );
		            Application_Javascript::addFile( '' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/' . __CLASS__ . '/?class_name=' . $_REQUEST['class_name'] . '&v=' . filemtime( __FILE__ ) . '&content_type=js&' . http_build_query( $parameters ) );
                    $html .= $classHtml;
                    $html .= Application_Style::getAll(); 
                    $html .= '<!--PC-HTML-DEMARCATION-->';
                    $html .= Application_Javascript::getFiles(); 
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
            $this->setViewContent(  '' . self::__( 'Theres an error in the code' ) . '', true  ); 
            return false; 
        }
	}
	// END OF CLASS
}
