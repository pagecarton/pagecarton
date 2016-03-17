<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_SearchBox
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: SearchBox.php 5.11.2012 10.465am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Application_SearchBox
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_SearchBox extends Ayoola_Abstract_Table
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
		//	$this->setViewContent( '<p></p>' );
			$html = '
						<style>
							/*--------------------------------------------------------------
							1.0 - BASE SITE STYLES
							--------------------------------------------------------------*/
							*,*:after,*:before {
							  box-sizing:border-box;
							  -moz-box-sizing:border-box;
							  -webkit-box-sizing:border-box;
							}

							.cf:before,
							.cf:after {
								content:"";
								display:table;
							}
							.cf:after {
								clear:both;
							}  

							/*--------------------------------------------------------------
							2.0 - SEARCH FORM
							--------------------------------------------------------------*/
							.searchform {
							  /*z-index:5000;*/  
							  background:#f4f4f4;
							  background:rgba(244,244,244,.79);
							  border: 1px solid #d3d3d3;
								left: 0;
							  padding: 2px 5px;
							  /*position: ' . ( $this->getParameter( 'position' ) ? : 'relative' ) . ';*/
								margin-left: 5%;
								margin-right: 5%;
								top: 40%;
							  width:90%;
							  box-shadow:0 4px 9px rgba(0,0,0,.37);
							  -moz-box-shadow:0 4px 9px rgba(0,0,0,.37);
							  -webkit-box-shadow:0 4px 9px rgba(0,0,0,.37);
							  border-radius: 10px;
							  -moz-border-radius: 10px;
							  -webkit-border-radius: 10px;
							  height: 50px;
							}

							.searchform input, .searchform button {
								float: left
							}
							.searchform input {
								background:#fefefe;
								border: none;
								margin-right: 5px;
								padding: 10px;
								width: 80%;
								height: 100%;
								box-shadow: 0 0 4px rgba(0,0,0,.4) inset, 1px 1px 1px rgba(255,255,255,.75);
								-moz-box-shadow: 0 0 4px rgba(0,0,0,.4) inset, 1px 1px 1px rgba(255,255,255,.75);
								-webkit-box-shadow: 0 0 4px rgba(0,0,0,.4) inset, 1px 1px 1px rgba(255,255,255,.75);
							  border-radius: 9px;
							  -moz-border-radius: 9px;
							  -webkit-border-radius: 9px
							  
							}
								.searchform input:focus {
									outline: none;
									box-shadow:0 0 4px #0d76be inset;
									-moz-box-shadow:0 0 4px #0d76be inset;
									-webkit-box-shadow:0 0 4px #0d76be inset;
								}
								.searchform input::-webkit-input-placeholder {
								font-style: italic;
								line-height: 15px
								}

								.searchform input:-moz-placeholder {
								  font-style: italic;
								line-height: 15px
								}

							.searchform button {
								background: rgb(52,173,236);
								background: -moz-linear-gradient(top, rgba(52,173,236,1) 0%, rgba(38,145,220,1) 100%);
								background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(52,173,236,1)), color-stop(100%,rgba(38,145,220,1)));
								background: -webkit-linear-gradient(top, rgba(52,173,236,1) 0%,rgba(38,145,220,1) 100%);
								background: -o-linear-gradient(top, rgba(52,173,236,1) 0%,rgba(38,145,220,1) 100%);
								background: -ms-linear-gradient(top, rgba(52,173,236,1) 0%,rgba(38,145,220,1) 100%);
								background: linear-gradient(to bottom, rgba(52,173,236,1) 0%,rgba(38,145,220,1) 100%);
								filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#34adec", endColorstr="#2691dc",GradientType=0 );
								border: none;
								color:#fff;
								cursor: pointer;
								font: 13px/13px "HelveticaNeue", Helvetica, Arial, sans-serif;
								padding: 10px;
								width:17%;
								height: 100%;
								box-shadow: 0 0 2px #2692dd inset;
								-moz-box-shadow: 0 0 2px #2692dd inset;
								-webkit-box-shadow: 0 0 2px #2692dd inset;
							  border-radius: 9px;
							  -moz-border-radius: 9px;
							  -webkit-border-radius: 9px;
							}
								.searchform button:hover {
									opacity:.9;
								}
						</style>
						<form data-not-playable="true" method="get" action="' . ( $this->getParameter( 'action' ) ? : '/search' ) . '" class="searchform cf">
						  <input name="q" type="text" placeholder="' . htmlentities( $this->getParameter( 'placeholder' ) ? : 'What are you looking for?', ENT_QUOTES, "UTF-8", false ) . '">
						  <button type="submit">Search</button>
						</form>
			';
/* 			if( $this->getParameter( 'full_screen' ) )
			{
				$this->setViewContent( '<div style="display:block; height:100%;width:100%;padding:0.5em;background-color: ' . Application_Settings_CompanyInfo::getSettings( 'Page', 'background_color' ) . ';"></div>' ); 
			}
 */			$this->setViewContent( $html ); 
			//	make SearchBoxr
		//	copy( $installerFilenamePhp, Ayoola_Application::$SearchBoxr );
			
		//	header( 'Location: /' . Ayoola_Application::$SearchBoxr );
		//	exit();
			
	//		file_get_contents( Ayoola_Application::$SearchBoxr );
		}
		catch( Ayoola_Exception $e ){ return false; }
	}
	
	// END OF CLASS
}
