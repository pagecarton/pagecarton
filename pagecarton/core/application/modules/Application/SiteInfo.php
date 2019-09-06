<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_SiteInfo
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: SiteInfo.php Tuesday 3rd of October 2017 12:30AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Application_SiteInfo extends PageCarton_Widget
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
	protected static $_objectTitle = 'Site Info'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public static function getInfo( $key = null )
    {  
        $settings = Application_Settings_Abstract::getSettings( 'SiteInfo' );
     //   self::v( Ayoola_Application::getDomainSettings() );
        @$settings['site_headline'] = $settings['site_headline'] ? : Ayoola_Application::getDomainSettings( 'display_name' );
    //    @$settings['site_description'] = $settings['site_description'] ? : Ayoola_Application::getDomainSettings( 'profile_description' );
        @$settings['cover_photo'] = $settings['cover_photo'] ? : Ayoola_Application::getDomainSettings( 'display_picture' );
        @$settings['cover_photo'] = $settings['cover_photo'] ? : '/img/placeholder-image.jpg';
  //     self::v( $settings );      
      if( empty( $settings['site_headline'] ) )
        {
        //    var_export( explode( '.', DOMAIN ) );
            $settings['site_headline'] = ucwords( array_shift( explode( '.', DOMAIN ) ) ) ? : 'My Site';
        }

        if( empty( $settings['site_description'] ) && self::hasPriviledge( array( 99, 98 ) ) )
        {
            $settings['site_description'] = $settings['site_description'] ? : 'Description for this site has not been set. Site Description will appear here when they become available.';
        }
        return $key ? @$settings[$key] : $settings;
    }  

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
		    $settings = self::getInfo();
            
            if( self::hasPriviledge( 98 ) )
            {
                $settings['link_to_edit'] = '<a  style="font-size:x-small; color:inherit;text-transform:uppercase;display:inline-block;" onclick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/SiteInfo/?' . '&xpc_form_element_whitelist=site_headline,site_description,cover_photo\', \'page_refresh\' );" href="javascript:">[' . self::__( 'edit site headline and description' ) . ']</a>';
                $settings['pc_no_data_filter'] = true;

            }

//     var_export( Ayoola_Page::getCurrentsettings() );
            $html = '<div class="pc_theme_parallax_background" style="background-image:     linear-gradient(      rgba(0, 0, 0, 0.7),      rgba(0, 0, 0, 0.7)    ),    url(\'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_IconViewer/?url=' . ( $settings['cover_photo'] ) . '&crop=1&max_width=1500&max_height=600\');">';
            $html .= $this->getParameter( 'css_class_of_inner_content' ) ? '<div class="' . $this->getParameter( 'css_class_of_inner_content' ) . '">' : null;
            $html .= '<h1>' . $settings['site_headline'] . '</h1>';
            $html .= $settings['site_description'] ? '<br><br><p>' . $settings['site_description'] . '</p>' : null;
            $html .= self::hasPriviledge( array( 99, 98 ) ) ? '<br><br><p style="font-size:x-small;">' . $settings['link_to_edit'] . '</p>' : null;
            $html .= $this->getParameter( 'css_class_of_inner_content' ) ? '</div>' : null;
            $html .= '</div>';
            $this->_objectTemplateValues = array_merge( $settings ? : array(), $this->_objectTemplateValues ? : array() );
            $this->setViewContent( $html ); 

             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent(  '' . self::__( 'Theres an error in the code' ) . '', true  ); 
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
    //    $html .= self::viewInLine(); 
	//	return $html;
	}
	// END OF CLASS
}
