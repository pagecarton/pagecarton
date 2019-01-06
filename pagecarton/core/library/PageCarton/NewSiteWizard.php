<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PageCarton_NewSiteWizard
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: NewSiteWizard.php Monday 24th of December 2018 01:10AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PageCarton_NewSiteWizard extends PageCarton_Widget
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
	protected static $_objectTitle = 'New Site Wizard'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
            if( ! self::hasPriviledge( 98 ) )
            {
                $this->setViewContent( Ayoola_Access_Login::viewInLine() ); 
                return false;
            }

            Application_Personalization::viewInLine();

            $stages = array(
                array( 'key' => 'Basic Information', 'title' => 'Set site basic information', 'class' => 'Application_Personalization' ),
                array( 'key' => 'Browse Themes', 'title' => 'Choose from hundreds of great themes for your site', 'class' => 'Ayoola_Page_Layout_Repository' ),
                array( 'key' => 'Choose a Theme', 'title' => 'Make a theme the default site theme', 'class' => 'Ayoola_Page_Layout_List' ),
                array( 'key' => 'Create Content', 'title' => 'Replace dummy text content', 'class' => 'Ayoola_Page_Layout_ReplaceText' ),
                array( 'key' => 'Update Images', 'title' => 'Change some theme dummy pictures', 'class' => 'Ayoola_Page_Layout_Images' ),
                array( 'key' => 'Start Publishing', 'title' => 'Start building up the site by adding some structured posts', 'class' => 'Application_Article_Publisher' ), 
                array( 'key' => 'Share Website', 'title' => 'Your are done building your site. Next is to share with the world with social tools', 'class' => 'Application_Share_Website' ), 
            );
            
            if( @$_GET['mode'] === 'publisher' || $this->getParameter( 'publisher_mode' ) )
            { 
                unset( $stages[1], $stages[2] );
            }

            //  reset keys because those that left
            $stages = array_values( $stages );
            $html = null;
            $html .= '<ol class="cd-multi-steps text-bottom count">';  
            $lastCompleted = false;
            $break = false;
        //  $class = $stages[0]['class'];
            
            foreach( $stages as $key => $each )
            {   
                $xT[$each['class']] = $each;
                $xT[$each['class']]['id'] = $key;
            //    $html .= '<li><a rel="" href="?stage=' . $each['class'] . '">' . $each['title'] . '</a></li>';
                $percentageText = '';
                $percentage = 0;
                if( Ayoola_Loader::loadClass( $each['class'] ) )
                {
                    if( method_exists( $each['class'], 'getPercentageCompleted' ) )
                    {
                        $percentage = $each['class']::getPercentageCompleted();
                        $percentageText = '(' . $percentage . '%)';
                    }
                }
           //     $link = ' '; 
            //    var_export( $lastCompleted );
            //    var_export( $each['class'] );
                if( $percentage == 100 && ! $break )
                {
                    $lastCompleted = true;
                    $html .= '<li class="visited"><a onclick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/' . $each['class'] . '?mini_info=1\', \'page_refresh\' );" href="javascript:;">' . $each['key'] . '</a></li>';
                }
                elseif( $lastCompleted == true || $key === 0 )
                {
                    $class = $each['class'];
                    $lastCompleted = false;
                    $break = true;
                    $html .= '<li class="current"><em><a onclick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/' . $each['class'] . '?mini_info=1\', \'page_refresh\' );" href="javascript:;">' . $each['key'] . '</a></em></li>';

                }
                else
                {
                    $html .= '<li><em>' . $each['key'] . '</em></li>';
                }
            }
            $html .= '</ol>';
            if( @$_GET['stage'] )
            {
                
                $class = $xT[$_GET['stage']]['class'];
            }

            $weAreOn = @$xT[$class]['id'] + 1;
            if( $this->getParameter( 'hide_if_stages_completed' ) && $weAreOn == count( $stages ) )
            {
                return false;
            }

            //  Output demo content to screen
            $this->setViewContent( $html ); 
            if( Ayoola_Loader::loadClass( $class ) )
            {
               $this->setViewContent( '<div style="text-align:center;">
               Step ' . ( $weAreOn ) . ' of ' . count( $stages ) . ' <br><br>
               ' . $xT[$class]['title'] . ' <br><br>
                <a class="pc-btn" onclick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/' . $xT[$class]['class'] . '?mini_info=1\', \'page_refresh\' );" href="javascript:;"> <i  style="margin:5px;" class="fa fa-external-link"></i> ' . $xT[$class]['key'] . '</a><br><br>
                <a style="font-size:x-small;" class="" href="' . Ayoola_Application::getUrlPrefix() . '/" target="_blank"> <i  style="margin:5px;" class="fa fa-external-link"></i> Preview Site </a><br><br>
                
                </div>' ); 
            //    $this->setViewContent( $class::viewInLine() ); 
            }
            // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
        //    $this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>' ); 
            $this->setViewContent( '<p class="badnews">Theres an error in the code</p>' ); 
            return false; 
        }
	}
	// END OF CLASS
}
