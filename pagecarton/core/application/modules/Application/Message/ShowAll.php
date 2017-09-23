<?php
/**
 * PageCarton Content Management System
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
	protected static $_accessLevel = 1; 
	
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
	//	$this->setViewContent( $this->getHtml(), true );
    } 
	
    /**
     * 
     * 
     */
	public static function getNoOfNewMessages()
    {
		$table = new Application_Message();
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
 		switch( $this->getParameter( 'messages_to_show' ) )
		{
			case 'mine':
				//	hard-coded message page
				$this->_dbWhereClause = array( 'reference' => Ayoola_Application::getUserInfo( 'username' ) );
			break;
			case 'received':
				//	hard-coded message page
				$this->_dbWhereClause = array( 'to' => Ayoola_Application::getUserInfo( 'username' ) );
			break;
			default:
				$reference = array( Ayoola_Application::getUserInfo( 'username' ), @Ayoola_Application::$GLOBAL['username'] );
				$this->_dbWhereClause = array( 
												'from' => $reference,
												'to' => $reference,
												'reference' => Ayoola_Application::getUserInfo( 'username' ),
											);
			break;
		}
/* 		elseif( $this->getParameter( 'reference_type' ) === 'post' )
		{
			//	Get the article url
			$reference = Ayoola_Application::$GLOBAL['article_url'];		
		}
		elseif( Ayoola_Application::$GLOBAL['username'] )
		{
			//	profile message page
			$reference = Ayoola_Application::$GLOBAL['username'];		
		}
 */ 	//	var_export( $reference );
		$updates = $this->getDbData();
		krsort( $updates );
	//	$updates = $table->select();
		
	//	self::v( $updates );
//		var_export( $this->_dbWhereClause );
		
		if( ! @$this->_parameter['markup_template'] ) 
		{
			$template = 
			'
				<span style="background-color:#def">
					<p>
						<a href="' . Ayoola_Application::getUrlPrefix() . '/{{{from}}}">
							<img src="{{{display_picture}}}" style="float:right; max-height:60px;">
						</a>
						<a href="' . Ayoola_Application::getUrlPrefix() . '/{{{from}}}">
							<strong>{{{display_name}}}</strong>
						</a>
						→
						<a href="' . Ayoola_Application::getUrlPrefix() . '/{{{to}}}">
							<strong>{{{to}}}</strong>
						</a>
						<br>
						{{{message}}}
						<br>
						<span style="font-size:small;">{{{timestamp}}}</span>
					</p>
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
			if( empty( $info['to'] ) || empty( $info['from'] ) )
			{ 
				continue; 
			}
		//	self::v( $each['reference']['from'] );
		//	self::v( Ayoola_Access::getAccessInformation( $each['reference']['from'] ) );
			$subjectInfo[$each['reference']['from']] = @$subjectInfo[$each['reference']['from']] ? : Ayoola_Access::getAccessInformation( $each['reference']['from'] );
			$info = $subjectInfo[$each['reference']['from']] ? : array() + $each ? : array();
			$info['from'] = $each['reference']['from'];
			$info['from'] = $each['reference']['from'];
			$filter = new Ayoola_Filter_Time();
			$info['timestamp'] = $filter->filter( $info['timestamp'] ? : ( time() - 3 ) );  
			$info['second_party'] = strtolower( $info['from'] ) === strtolower( Ayoola_Application::getUserInfo( 'username' ) ) ? $info['to'] : $info['from'];  
			
			if( $template )
			{
				$html .= self::replacePlaceholders( $template, $info + array( 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', ) );
			} 
			
		
		}
//		$this->_parameter['markup_template'] = $html ? : '<p class="badnews boxednews">No recent updates...</p>';
		$this->_parameter['markup_template'] = $html ? : null;
	}
	// END OF CLASS
}
