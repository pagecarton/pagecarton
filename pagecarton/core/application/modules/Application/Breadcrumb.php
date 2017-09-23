<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Breadcrumb
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Breadcrumb.php 5.11.2012 10.465am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Breadcrumb
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Breadcrumb extends Ayoola_Abstract_Table
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
     * Performs the process
     * 
     */
	public function init()
    {
		try
		{
		//	var_export( $this->_parameter );
			$this->setParameter( array( 'markup_template_no_cache' => true ) ); 
			$breadcrumb = Ayoola_Page::getBreadcrumb();
			if( count( $breadcrumb ) < 2 )
			{
				return false;
			}
			
		//	var_export( $breadcrumb );
			if( ! @$this->_parameter['markup_template'] ) 
			{
				$this->_parameter['markup_template_prefix'] = 
				'				
					<style>
						.pc-breadcrumb {
							list-style: none;
							border-radius: 4px;
							margin:0;
							padding:0;
						}
						.pc-breadcrumb > li {
							display: inline-block;
							font-size: x-small;   
						}
						.pc-breadcrumb > li + li::before {  
							padding: 0px 5px;
							content: ">";
						}
						.pc-breadcrumb > .pc-breadcrumb-active {     
						}
					</style>
					<div style="margin-top:0.5em;margin-bottom:0.5em;">   
						<ol class="pc-breadcrumb">
							<li><a href="' . Ayoola_Application::getUrlPrefix() . '/" title="Home Page">Home</a></li>
				';
				$this->_parameter['markup_template_suffix'] = 
				'
						</ol>
					</div>
				';
				$this->_parameter['markup_template_active'] = '<li title="{{{description}}}">{{{title}}}</li>';
				$template =   
				'
						<li title="{{{description}}}"><a href="{{{url}}}" class="pc-breadcrumb-active" title="{{{description}}}">{{{title}}}</a></li>
				';
			
			}
			else 
			{
				$template = $this->_parameter['markup_template'];
			}
			$this->_parameter['markup_template'] = null;  
			$html = null;
			$i = 0; //	counter
			$j = 10; //	5 is our max articles to show
			$j = is_numeric( $this->getParameter( 'no_of_post_to_show' ) ) ? intval( $this->getParameter( 'no_of_post_to_show' ) ) : $j;
			$urlLog = array();
			$presentUri = Ayoola_Application::getPresentUri();
		//	self::v( $breadcrumb );
			foreach( $breadcrumb as $each )
			{
				if( $i++ >= $j )
				{ 
					break; 
				}	
				if( isset( $urlLog[$each['url']] ) || $each['url'] == '/' || $each['url'] == '/object' || $each['url'] == '/tools/classplayer' )
				{   					
					continue; 
				}
				elseif( ! $each['title'] )
				{
					$each['title'] = array_pop( array_map( 'ucwords', explode( '/', $each['url'] ) ) );  
				}
				$urlLog[$each['url']] = $each['url'];
				if( $template )
				{
					//	var_export( $each['url'] );
					//	var_export( trim( $each['url'], '/' ) );
					//	var_export( trim( $presentUri, '/' ) );
					if( ( trim( $each['url'], '/' ) === trim( $presentUri, '/' ) || trim( $each['url'], '/' ) == '404' ) && @$this->_parameter['markup_template_active'] )
					{ 
						$tempTemplate = @$this->_parameter['markup_template_active']; 
					}	
					else
					{
						$tempTemplate = $template; 
					}
					$each['url'] = Ayoola_Application::getUrlPrefix() . $each['url'];
					$html .= self::replacePlaceholders( $tempTemplate, $each + array( 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', ) );
				} 
			}
			$this->_parameter['markup_template'] = $html ? : null;
		}
		catch( Ayoola_Exception $e )
		{ 
		//	var_export( $e->getMessage() );
			return false; 
		}
	}
	
	// END OF CLASS
}
