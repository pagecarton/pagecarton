<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_HybridAuth_SignIn
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: SignIn.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_HybridAuth_SignIn
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_HybridAuth_SignIn extends Ayoola_Abstract_Table
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
     * "Plays" the class
     *
     * @param 
     * 
     */
    public function init()
    {
/* 		try
		{
			$hybridAuth = new Ayoola_HybridAuth();
			$config = $hybridAuth::getConfig();
		//	var_export( $config );
			$domainName = null;
			if( count( $config['providers'] ) < 4 )
			{
				//	Use the default authentication server
				$domainName = $hybridAuth::DEFAULT_AUTH_SERVER;
//				return true;
			}
		//	var_export( $domainName );
			$provider  = @ $_GET["provider"];
			$return_to = @ $_GET["return_to"] ? : Ayoola_Page::getPreviousUrl();
			
			if( ! $return_to ){
				$return_to = '/account/';
			//	echo "Invalid params!";
			}

			if( ! empty( $provider ) && $hybridAuth->isConnectedWith( $provider ) )
			{
				//	see if we can sign up a new user
				Ayoola_HybridAuth_SignUp::viewInLine();
			//	exit( 'done' );
				$return_to = $return_to . ( strpos( $return_to, '?' ) ? '&' : '?' ) . "connected_with=" . $provider ;
				$this->setViewContent
				( 
					'<script language="javascript"> 
						if(  window.opener ){
							try { window.opener.parent.$.colorbox.close(); } catch(err) {} 
							window.opener.parent.location.href = "' . $return_to . '";
						}

						window.self.close();
					</script>'
				);
				return;
			}
		if( ! empty( $provider ) )
		{
			$params = array();

			if( $provider == "OpenID" ){
				$params["openid_identifier"] = @ $_REQUEST["openid_identifier"];
			}

			if( isset( $_REQUEST["redirect_to_idp"] ) ){
				$adapter = $hybridAuth->authenticate( $provider, $params );
			}
			else
			{
				$this->setViewContent
				( 
					'<table width="100%" border="0">
					  <tr>
						<td align="center" height="190px" valign="middle"><img src="/layout/widget_authentication/widget/images/loading.gif" /></td>
					  </tr>
					  <tr>
						<td align="center"><br /><h3>Loading...</h3><br /></td> 
					  </tr>
					  <tr>
						<td align="center">Contacting <b>' . ucfirst( strtolower( strip_tags( $provider ) ) ) . '</b>. Please wait.</td> 
					  </tr> 
					</table>
					<script>
						window.location.href = window.location.href + "&redirect_to_idp=1";
					</script>'
				);
			}//	var_export( $_REQUEST );
			return;
		}
			$this->setViewContent( '<h4>Log in with your favorite Social Media:</h4>' );
			$this->setViewContent
			(
				'
						<style>
					.idpico{
						cursor: pointer;
						cursor: hand;
					}
					#openidm{
						margin: 7px;
					}
				</style>
				<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
				<script>
					var idp = null;

					$(function() { 
						$(".idpico").click(
							function(){ 
								idp = $( this ).attr( "idp" );

								switch( idp ){
									case "google"  : case "twitter" : case "yahoo" : case "facebook": case "aol" : 
									case "vimeo" : case "myspace" : case "tumblr" : case "lastfm" : case "twitter" : 
									case "linkedin" : case "live" : 
													start_auth( "' . $domainName . '/tools/classplayer/get/object_name/Ayoola_HybridAuth_SignIn/?provider=" + idp );
													break; 
									case "wordpress" : case "blogger" : case "flickr" :  case "livejournal" :  
													if( idp == "blogger" ){
														$("#openidm").html( "Please enter your blog name" );
													}
													else{
														$("#openidm").html( "Please enter your username" );
													}
													$("#openidun").css( "width", "220" );
													$("#openidimg").attr( "src", "/layout/widget_authentication/widget/images/icons/" + idp + ".png" );
													$("#idps").hide();
													$("#openidid").show();  
													break;
									case "openid" : 
													$("#openidm").html( "Please enter your OpenID URL" );
													$("#openidun").css( "width", "350" );
													$("#openidimg").attr( "src", "/layout/widget_authentication/widget/images/icons/" + idp + ".png" );
													$("#idps").hide();
													$("#openidid").show();  
													break;

									default: alert( "u no fun" );
								}
							}
						); 

						$("#openidbtn").click(
							function(){
								oi = un = $( "#openidun" ).val();

								if( ! un ){
									alert( "nah not like that! put your username or blog name on this input field." );
									
									return false;
								}

								switch( idp ){ 
									case "wordpress" : oi = "http://" + un + ".wordpress.com"; break;
									case "livejournal" : oi = "http://" + un + ".livejournal.com"; break;
									case "blogger" : oi = "http://" + un + ".blogspot.com"; break;
									case "flickr" : oi = "http://www.flickr.com/photos/" + un + "/"; break;   
								}
								
								start_auth( "' . $domainName . '/tools/classplayer/get/object_name/Ayoola_HybridAuth_SignIn/?provider=OpenID&openid_identifier=" + escape( oi ) ); 
							}
						);  

						$("#backtolist").click(
							function(){
								$("#idps").show();
								$("#openidid").hide();

								return false;
							}
						);  
					});

					function start_auth( params ){
						start_url = params + "&return_to=' . urlencode( $return_to ) . '" + "&_ts=" + (new Date()).getTime();
						window.open(
							start_url, 
							"hybridauth_social_sing_on", 
							"location=0,status=0,scrollbars=0,width=800,height=500"
						);  
					}
				</script>'
			);
			$listStyle = "display:inline-block;";
			$this->setViewContent
			(
			'	
				<div id="idps">
					<ul style="display:inline-block;">
						<li style="' . $listStyle . '"><img class="idpico" idp="google" src="/layout/widget_authentication/widget/images/icons/google.png" title="google" /></li>
						<li style="' . $listStyle . '"><img class="idpico" idp="twitter" src="/layout/widget_authentication/widget/images/icons/twitter.png" title="twitter" /></li>
						<li style="' . $listStyle . '"><img class="idpico" idp="facebook" src="/layout/widget_authentication/widget/images/icons/facebook.png" title="facebook" /></li>
						<li style="' . $listStyle . '"><img class="idpico" idp="yahoo" src="/layout/widget_authentication/widget/images/icons/yahoo.png" title="yahoo" /></li>
						<li style="' . $listStyle . '"><img class="idpico" idp="live" src="/layout/widget_authentication/widget/images/icons/live.png" title="Windows Live" /></li>
						<li style="' . $listStyle . '"><img class="idpico" idp="aol" src="/layout/widget_authentication/widget/images/icons/aol.png" title="Aol" /></li>
						<li style="' . $listStyle . '"><img class="idpico" idp="openid" src="/layout/widget_authentication/widget/images/icons/openid.png" title="openid" /></li>  
						<li style="' . $listStyle . '"><img class="idpico" idp="flickr" src="/layout/widget_authentication/widget/images/icons/flickr.png" title="flickr" /></li>
						<li style="' . $listStyle . '"><img class="idpico" idp="myspace" src="/layout/widget_authentication/widget/images/icons/myspace.png" title="myspace" /></li>  
						<li style="' . $listStyle . '"><img class="idpico" idp="linkedin" src="/layout/widget_authentication/widget/images/icons/linkedin.png" title="linkedin" /></li>
						<li style="' . $listStyle . '"><img class="idpico" idp="blogger" src="/layout/widget_authentication/widget/images/icons/blogger.png" title="blogger" /></li> 
						<li style="' . $listStyle . '"><img class="idpico" idp="wordpress" src="/layout/widget_authentication/widget/images/icons/wordpress.png" title="wordpress" /></li>
						<li style="' . $listStyle . '"><img class="idpico" idp="livejournal" src="/layout/widget_authentication/widget/images/icons/livejournal.png" title="livejournal" /></li>  
					</ul> 
				</div>
				<div id="openidid" style="display:none;">
					<ul style="">
						<li style="' . $listStyle . '"><img id="openidimg" src="/layout/widget_authentication/widget/images/loading.gif" /></li>
						<li style="' . $listStyle . '"><h3 id="openidm">Please enter your user or blog name</h3></li>
						<li style="' . $listStyle . '"><input type="text" name="openidun" id="openidun" style="padding: 5px; margin:7px;border: 1px solid #999;width:240px;" /></li>
						<li style="' . $listStyle . '">
							<input type="submit" value="Login" id="openidbtn" style="height:33px;width:85px;" />
							<br />
							<small><a href="#" id="backtolist">back</a></small>
						</li>
					</ul> 
				</div>
	'
			);
		}
		catch( Ayoola_Exception $e )
		{
			echo $e->getMessage();
			$this->setViewContent( '<p class="badnews">We encountered an error.</p>', true );
		}
  */	
   }
	
	// END OF CLASS
}
