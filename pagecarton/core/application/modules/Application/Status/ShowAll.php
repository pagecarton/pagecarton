<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @Status     Ayoola
 * @package    Application_Status_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ShowAll.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Status_Abstract
 */
 
require_once 'Application/Status/Abstract.php';


/**
 * @Status   Ayoola
 * @package    Application_Status_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Status_ShowAll extends Application_Status_Abstract
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
	protected static $_accessLevel = 0; 
	
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
	//	var_export( $this->_dbWhereClause ); 
		$this->setParameter( array( 'markup_template_no_cache' => true ) ); 
		$this->getHtml();
	//	$this->setViewContent( $this->getHtml(), true );
    } 
	
    /**
     * 
     * 
     */
	public static function getNoOfNewUpdates()
    {
		$table = Application_Status::getInstance();
	//	var_export( Ayoola_Application::getUserInfo( 'username' ) );
		if( Ayoola_Application::getUserInfo( 'username' ) AND $newMessages = $table->select( null, array( 'read_time' => 0, 'reference' => Ayoola_Application::getUserInfo( 'username' ),  ) ) )
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
		if( $this->getParameter( 'reference' ) )
		{
			//	hard-coded status page
			$reference = $this->getParameter( 'reference' );		
		}
		elseif( $this->getParameter( 'reference_type' ) === 'post' && @Ayoola_Application::$GLOBAL['post']['article_url'] )
		{
			//	Get the article url
			$reference = Ayoola_Application::$GLOBAL['post']['article_url'];		
		}
		elseif( $this->getParameter( 'show_post_by_me' ) )
		{
			//	Show for the current session user
			$reference = Ayoola_Application::getUserInfo( 'username' );	 	
		}
		elseif( @Ayoola_Application::$GLOBAL['profile']['username'] )
		{
			//	profile status page
			$reference = Ayoola_Application::$GLOBAL['profile']['username'];		
		}
		$this->_dbWhereClause = @$reference ? array( 'reference' => $reference ) : $this->_dbWhereClause;
	//	var_export( $this->_dbWhereClause ); 
		$updates = $this->_dbWhereClause ? $this->getDbData() : array();
	//	self::v( count( $updates ) );   
		krsort( $updates );
	//	$updates = $table->select();
		
	//	self::v( $updates );
		
		if( ! @$this->_parameter['markup_template'] ) 
		{
			$template = 
			'
				<span style="background-color:#def">
					<p>
						<a href="' . Ayoola_Application::getUrlPrefix() . '/{{{subject}}}">
							<img src="{{{display_picture}}}" style="float:right; max-height:60px;">
						</a>
						<a href="' . Ayoola_Application::getUrlPrefix() . '/{{{subject}}}">
							<strong>{{{display_name}}}</strong>
						</a>
						→
						<a href="' . Ayoola_Application::getUrlPrefix() . '/{{{object}}}">
							<strong>{{{object}}}</strong>
						</a>
						<br>
						{{{status}}}
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
		$j = 5; //	5 is our max articles to show
		$j = is_numeric( $this->getParameter( 'no_of_post_to_show' ) ) ? intval( $this->getParameter( 'no_of_post_to_show' ) ) : $j;
		foreach( $updates as $each )
		{
			if( $i++ >= $j )
			{ 
			//	var_export( $i );
			//	var_export( $j );
				break; 
			}
		//	self::v( $each['reference']['subject'] );
		//	self::v( Ayoola_Access::getAccessInformation( $each['reference']['subject'] ) );
			$subjectInfo[$each['reference']['subject']] = @$subjectInfo[$each['reference']['subject']] ? : Ayoola_Access::getAccessInformation( $each['reference']['subject'] );
			
			$info = ( $subjectInfo[$each['reference']['subject']] ? : array() ) + ( $each  ? : array() );
			$info['subject'] = $each['reference']['subject'];
			$info['object'] = $each['reference']['object'];  
			if( $info['class_name'] )
			{
				$class = $info['class_name'];
				if( Ayoola_Loader::loadClass( $class ) )
				{
					if( method_exists( $class, 'sanitizeStatus' ) )
					{
						$info = $class::sanitizeStatus( $info );
					}
				}
			
			}
			else
			{
		//		self::v( $each );
			}
			$filter = new Ayoola_Filter_Time();
			$info['timestamp'] = $filter->filter( $info['timestamp'] ? : ( time() - 3 ) );  
			
			if( $template )
			{
				$html .= self::replacePlaceholders( $template, $info + array( 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', ) );
			} 
			
		
		}
	//	$this->_parameter['markup_template'] = $html ? : '<p class="badnews boxednews">No recent updates...</p>';
		$this->_parameter['markup_template'] = $html ? : '';
	}
	// END OF CLASS
}
