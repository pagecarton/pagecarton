<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Info
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Info.php Monday 2nd of October 2017 09:34PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Ayoola_Page_Info extends PageCarton_Widget
{
	
    /**	
     *
     * @var boolean
     */
	public static $editorViewDefaultToPreviewMode = true;
	
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
	protected static $_objectTitle = 'Page Info'; 

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
		    $currentUrl = rtrim( Ayoola_Application::getPresentUri(), '/' ) ? : '/';
         //   if( )
			switch( $currentUrl )
			{
				case '/tools/classplayer':
				case '/object':
		//		case '/pc-admin':
				case '/widget':
		//		case true:
					//	Do nothing.
					//	 had to go through this route to process for 0.00
					if( @$_REQUEST['url'] )
                    {
                        $currentUrl = $_REQUEST['url'];
                    }
				break;
				default:

				break;
			}

            //  Output demo content to screen
		    $settings = Application_Settings_Abstract::getSettings( 'SiteInfo' );
     //       self::v( $settings );   
            $url = $this->getParameter( 'url' ) ? : $currentUrl;
            $pageInfo = Ayoola_Page::getInfo( $url );
        //    $pageInfo = Ayoola_Page_Page::getInstance()->selectOne( null, array( 'url' => $url ) );
            
   //        self::v( $this->getParameter( 'url' ) );   
   //       self::v( $currentUrl );   
   //       self::v( $pageInfo );   

            if( empty( $pageInfo['title'] ) )
            {
                $pageInfo['title'] = ucwords( str_replace( '-', ' ', basename( $pageInfo['url'] ? : $currentUrl ) ) ? : 'Home Page' );
            }
            if( ! $pageInfo['url'] )
            {
                $currentUrl = rtrim( Ayoola_Application::getRuntimeSettings( 'real_url' ), '/' ) ? : '/';
                $pageInfo = Ayoola_Page::getInfo( $this->getParameter( 'url' ) ? : $currentUrl );
            }

            if( empty( $pageInfo['description'] ) && self::hasPriviledge( array( 99, 98 ) ) )
            {
                $pageInfo['description'] = $pageInfo['description'] ? : 'Description for this page has not been set. Page Description will appear here when they become available.';
            }

//     var_export( Ayoola_Page::getCurrentPageInfo() );
            $html = '<div class="pc_theme_parallax_background" style="background-image:     linear-gradient( rgba(0, 0, 0, 0.5),      rgba(0, 0, 0, 0.5)    ), url(\'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_IconViewer/?url=' . ( $pageInfo['cover_photo'] ? : $settings['cover_photo'] ) . '\');">';
            $html .= $this->getParameter( 'css_class_of_inner_content' ) ? '<div class="' . $this->getParameter( 'css_class_of_inner_content' ) . '">' : null;
            $html .= '<h1>' . $pageInfo['title'] . '</h1>';
            $html .= $pageInfo['description'] ? '<br><br><p>' . $pageInfo['description'] . '</p>' : null;
            $html .= self::hasPriviledge( array( 99, 98 ) ) ? '<br><br><p style="font-size:x-small;"><a  style="color:inherit;text-transform:uppercase;" onclick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Page_Editor/?url=' . $pageInfo['url'] . '&pc_form_element_whitelist=title,description,cover_photo\', \'page_refresh\' );" href="javascript:">[edit page headline and description]</a></p>' : null;
            $html .= $this->getParameter( 'css_class_of_inner_content' ) ? '</div>' : null;
           
            $html .= '</div>';
            $this->setViewContent( $html );   
            $this->_objectTemplateValues = array_merge( $pageInfo ? : array(), $this->_objectTemplateValues ? : array() );

             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( 'Theres an error in the code', true ); 
            return false; 
        }
	}
	
    /**
	 * Returns text for the "interior" of the Layout Editor
	 * The default is to display view and option parameters.
	 * 		
     * @param array Object Info
     * @return string HTML
     */
    public static function getHTMLForLayoutEditor( & $object )
	{
	//	$html = null;
     //   $html .= self::viewInLine( array( 'url' => @$_REQUEST['url'] ) ); 
	//	return $html;
	}
	// END OF CLASS
}
