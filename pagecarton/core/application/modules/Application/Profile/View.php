<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Profile_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: View.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Profile_Abstract
 */
 
require_once 'Application/Profile/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Profile_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Profile_View extends Application_Profile_Abstract
{
 	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'View Profile'; 
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
    protected static $_accessLevel = 0;

    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
			if( ! $data = $this->getIdentifierData() )
			{
		//		if( )
			}
			if( ! @$this->_parameter['markup_template'] ) 
			{  
                $displayName = '{{{display_name}}}';
                if( Ayoola_Application::getRuntimeSettings( 'real_url' ) != '/profile' )
                {
                    $displayName = '<a href="' . Ayoola_Application::getUrlPrefix() . '/' . $data['profile_url'] . '">{{{display_name}}}</a>';
                }
				$this->_parameter['markup_template'] = '
                <div class="" style="">
                    <div class="' . $this->getParameter( 'css_class_of_inner_content' ) . '">
                        <div class="pc-profile-image-div" style="background-image: url(\'{{{display_picture}}}\'); margin-right:1em;">&nbsp;</div>
                        <div style="">
                            <h3 style="margin-top:0;">' . $displayName . '</h3>
                            <p>{{{profile_description}}}</p>
                            <p><i class="fa fa-share-alt"></i> {{{link_to_view_profile}}}</p>
                        </div>
                        <div style="clear:both;"></div>
                    </div>
                </div>';
			}  
            $this->_parameter['content_to_clear_internal'] .= '
            <p></p>
            background-image: url(\'\');
            ';    
			$this->_objectTemplateValues = array_merge( $data ? : array(), $this->_objectTemplateValues ? : array() );
			$this->_objectTemplateValues['display_picture'] = $this->_objectTemplateValues['display_picture'] ?  Ayoola_Application::getUrlPrefix() . $this->_objectTemplateValues['display_picture'] : null;
			$this->_objectTemplateValues['profile_banner'] = $this->_objectTemplateValues['profile_banner'] ? Ayoola_Application::getUrlPrefix() . $this->_objectTemplateValues['profile_banner'] : null;
			$this->_objectTemplateValues['profile_link'] = Ayoola_Page::getHomePageUrl() . '/' . $this->_objectTemplateValues['profile_url'];
      //      var_export( Ayoola_Application::getPresentUri() );
       //     self::v( $data );
            if( Ayoola_Application::getPresentUri() !== $data['profile_url'] )
            {
		    	$this->_objectTemplateValues['link_to_view_profile'] = '<a style="font-size:x-small;" href="' . $this->_objectTemplateValues['profile_link']  . '"> ' . $this->_objectTemplateValues['profile_link'] . '</a>';
		//	var_export( $this->_objectTemplateValues );
            }

            // store

		}
		catch( Exception $e )
		{ 
			$this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>', true );
		}
	//	var_export( $this->_xml );
    } 
	// END OF CLASS
}
