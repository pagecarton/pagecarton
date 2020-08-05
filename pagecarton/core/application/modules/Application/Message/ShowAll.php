<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @Message     Ayoola
 * @package    Application_Message_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ShowAll.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Message_Abstract
 */
 
require_once 'Application/Message/Abstract.php';


/**
 * @Message   Ayoola
 * @package    Application_Message_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Message_ShowAll extends Application_Message_Abstract
{

    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Direct Message';      
	
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
	protected static $_accessLevel = array( 1, 98 );
	
    /**
     * Sort the dbData
     *
     * @var string
     */
	protected $_sortColumn = 'timestamp'; 
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		//	No cache for the template engine
		$this->setParameter( array( 'markup_template_no_cache' => true ) ); 
		$this->getHtml();
    } 
	
    /**
     * 
     * 
     */
	public static function getNoOfNewMessages()
    {
		$table = Application_Message::getInstance();
		if( Ayoola_Application::getUserInfo( 'username' ) AND $newMessages = $table->select( null, array( 'read_time' => 0, 'to' => Ayoola_Application::getUserInfo( 'username' ),  ) ) )
		{
			return count( $newMessages );
		}
		else
		{
			return 0;
		}
	}
	
    /**
     * Builds the html to output
     * 
     */
	public function getHtml()
    {
        $sendMessageForm = Application_Message_Creator::viewInLine();
		$profiles = array();
        $profiles = Application_Profile_Abstract::getMyProfiles();
        if( ! empty( @$_GET['message_reference'] ) )
        {
            $profiles = array();
			if( $profileInfo = Application_Profile_Abstract::getProfileInfo( @$_GET['message_reference'] ) )
			{
                $profiles[] = $profileInfo['profile_url'];
                if( $profileInfo['username'] !== Ayoola_Application::getUserInfo( 'username' ) )
                {
                    return false;
                }
            }
        }
 		switch( $this->getParameter( 'messages_to_show' ) )
		{
			case 'mine':
				//	hard-coded message page
				$this->_dbWhereClause = array( 'reference' => $profiles );
			break;
			case 'received':
				//	hard-coded message page
				$this->_dbWhereClause = array( 'to' => $profiles );
			break;
            default:
                
                if( ! empty( Ayoola_Application::$GLOBAL['profile']['profile_url'] ) )
                {
                    $profiles[] = Ayoola_Application::$GLOBAL['profile']['profile_url']; 
                }
				$reference = array_map( 'strtolower', $profiles );
				$this->_dbWhereClause = array( 
												'reference' => $reference,
											);
			break;
		}
        $updates = $this->getDbData();
        $this->_objectData = $updates;
	//	krsort( $updates );
	//	$updates = $table->select();
		
	//	self::v( $updates );
		
		if( ! @$this->_parameter['markup_template'] ) 
		{
			$template = 
			'
				<span style="background-color:#def">
					<div class="pc_message_box pc-well {{{pc_message_box_mine}}}" style=" ">
						{{{message}}}
						<div style="clear:both;"></div>
						<div style="font-size:x-small;float:right;">{{{timestamp}}}</div>
					</div>
					<div style="clear:both;"></div>
				</span>				
			';
		
		}
		else
		{
			$template = $this->_parameter['markup_template'];
		}
		$this->_parameter['markup_template'] = null;  
		$html = null;
		$to = @Ayoola_Application::$GLOBAL['profile']['profile_url'] ? : @$_GET['to'];
		$myProfiles = array_map( 'strtolower', Ayoola_Application::getUserInfo( 'profiles' ) );
		if( in_array( strtolower( $to ), $myProfiles ) )
		{ 
			$this->setViewContent(  '' . self::__( '<div class="badnews">You cannot send message to yourself</div>' ) . '', true  );
			return false;
		}
		if( ! empty( $to ) AND $profileInfoTo = Application_Profile_Abstract::getProfileInfo( $to ) )
		{
		//	var_export( $profileInfoTo );
			$html .= '<div style="padding:0.5em;"><a href="' . Ayoola_Application::getUrlPrefix() . '/' . $profileInfoTo['profile_url'] . '"><div class="pc-profile-image-div" style="background-image: url(\'' . Ayoola_Application::getUrlPrefix() . '/widgets/Application_IconViewer/?url=' . ( $profileInfoTo['display_picture'] ? : '/img/placeholder-image.jpg' ) . '\'); margin-right:1em; width:48px; height:48px;">&nbsp;</div></a>Private Message <br> <a href="' . Ayoola_Application::getUrlPrefix() . '/' . $profileInfoTo['profile_url'] . '">@' . $profileInfoTo['profile_url'] . '</a></div>
			<div style="clear:both;"></div>
			
			';
		}
		$subjectInfo = array();
		$i = 0; //	counter
		$j = 10; //	5 is our max articles to show
		$j = is_numeric( $this->getParameter( 'no_of_post_to_show' ) ) ? intval( $this->getParameter( 'no_of_post_to_show' ) ) : $j;
		foreach( $updates as $each )
		{
			if( $i++ >= $j )
			{ 
			//	var_export( $i );
			//	var_export( $j );
				break; 
			}
			if( empty( $each['to'] ) || empty( $each['from'] ) )
			{ 
				continue; 
			}
			if( in_array( strtolower( $each['from'] ), $myProfiles ) )
			{ 
				$each['pc_message_box_mine'] = 'pc_message_box_mine';
			}
			
		//	var_export( $each );
		//	self::v( $each['reference']['from'] );
		//	self::v( Ayoola_Access::getAccessInformation( $each['reference']['from'] ) );
		//	$sender = @$subjectInfo[$each['reference']['from']] ? : Application_Profile_Abstract::getProfileInfo( $each['reference']['from'] );
		//	$each = $subjectInfo[$each['reference']['from']] ? : array() + $each ? : array();
		//	$each['from'] = $each['reference']['from'];
	//		$each['to'] = $each['reference']['to'];
			$filter = new Ayoola_Filter_Time();
			$each['timestamp'] = $filter->filter( $each['timestamp'] ? : ( time() - 3 ) );  
		//	$each['second_party'] = strtolower( $each['from'] ) === strtolower( Application_Profile_Abstract::getMyDefaultProfile() ) ? $each['to'] : $each['from'];  
		//	var_export( $each );
			
			if( $template )
			{
				$html .= self::replacePlaceholders( $template, $each + array( 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', ) );
			} 
			
		
		}
		$html .= '<div style="padding:0.5em;"></div>';
		$html .= $sendMessageForm;
//		$this->_parameter['markup_template'] = $html ? : '<p class="badnews boxednews">No recent updates...</p>';
		$this->_parameter['markup_template'] = $html ? : null;
	}
	// END OF CLASS
}
