<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Editor_Layout
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Layout.php 10-26-2011 9.13pm ayoola $
 */

/**
 * @see Ayoola_Page_Editor_Abstract
 */
 
require_once 'Ayoola/Page/Editor/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Editor_Layout
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Editor_Text extends Ayoola_Page_Editor_Abstract
{
 	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'HTML Content'; 
	
    /**
     * For editable div in layout editor 
     * REMOVED BECAUSE IT CONFLICTS WITH THE EDITOR
     * 
     * @var string
     */
//	protected static $_editableTitle = "Open HTML editor";  

    /**
     * The View Parameter From Layout Editor
     *
     * @var string
     */
	protected $_viewParameter;
	
	
    /**	
     *
     * @var boolean
     */
	public static $openViewParametersByDefault = true;
	
    /**
     * This method
     *
     * @param 
     * @return 
     */
    public function init()
    {
		//	codes first because it wont be there if they didnt opt to enter codes
		$content = $this->getParameter( 'codes' ) ? : ( $this->getParameter( 'editable' ) ? : $this->getParameter( 'view' ) );
		if( $this->getParameter( 'markup_template_object_name' ) )
		{
	//		$parameters = array( 'markup_template' => $content, 'markup_template_namespace' => 'x1234' . time(), 'editable' => $this->getParameter( 'markup_template_object_name' ) ) + $this->getParameter();
			
			//	Removing time() from namespace because it doesn't allow the post to cache
			$parameters = array( 'markup_template' => $content, 'markup_template_namespace' => 'x1234', 'editable' => $this->getParameter( 'markup_template_object_name' ) ) + $this->getParameter();
			$class = new Ayoola_Object_Embed( $parameters );
			$content = $class->view();
			$this->clearParametersThatMayBeDuplicated();
			$content .= '<div style="clear:both;"></div>';  
			$content .= '<div style="clear:both;"></div>';  
		//	$content = Ayoola_Object_Embed::viewInLine( $parameters );
		//	var_export( $parameters );
		//	var_export( $content );
		}
		if( $this->getParameter( 'update_page_metadata' ) )
		{
			$metaData = strip_tags( $content );
			Ayoola_Page::$title = $this->getParameter( 'page_title' ) ? $metaData : Ayoola_Page::$title;
			Ayoola_Page::$description = $this->getParameter( 'page_description' ) ? $metaData : Ayoola_Page::$description;
	//		Ayoola_Page::$description = $userInfo['profile_description'];
	//		Ayoola_Page::$thumbnail = $userInfo['display_picture'];
		}

		//	Refreshes the url prefix just in case we have imported new site
		if( $this->getParameter( 'url_prefix' ) !== Ayoola_Application::getUrlPrefix() )
		{
		//	$search = array( '"' . $this->getParameter( 'url_prefix' ), "'" . $this->getParameter( 'url_prefix' ), );
		//	$replace = array( '"' . Ayoola_Application::getUrlPrefix(), "'". Ayoola_Application::getUrlPrefix(), );
			$search = array( '"' . $this->getParameter( 'url_prefix' ), "'" . $this->getParameter( 'url_prefix' ), '"' . Ayoola_Application::getUrlPrefix(), "'" . Ayoola_Application::getUrlPrefix(), );
			$replace = array( '"', "'", '"', "'", );
			$content = str_ireplace( $search, $replace, $content );
			$search = array( '"/', "'/", );
			$replace = array( '"' . Ayoola_Application::getUrlPrefix() . '/', "'". Ayoola_Application::getUrlPrefix() . '/', );
			$content = str_ireplace( $search, $replace, $content );
	//		$replace = Ayoola_Application::getUrlPrefix();
		}
		
		$this->setParameter( array( 'editable' => $content ) );
		$html = $this->getParameter( 'editable' ) . $this->getParameter( 'raw_html' );
	//	$this->
		$this->_parameter['no_view_content_wrap'] = true;
		$this->setViewContent( $html );

	//	var_export( $this->_parameter );
     //   return $content . $this->getParameter( 'raw_html' );
    } 

    /**
	 * Returns text for the "interior" of the Layout Editor
	 * The default is to display view and option parameters.
	 * 		
     * @param array Object Info
     * @return string HTML
     */
     public static function getHTMLForLayoutEditor( $object )
	{
/* 		Application_Style::addCode( 'div.editable
					{
						border: solid 2px #90F;
						min-height: 1em;
					}

					div.editable:hover
					{
						border-color: black;
					}' );
					<script src="//cdn.ckeditor.com/4.6.2/basic/ckeditor.js"></script>
 */		Application_Javascript::addFile( '' . Ayoola_Application::getUrlPrefix() . '/js/objects/ckeditor/ckeditor.js?x' );    
	//	Application_Javascript::addFile( '//cdn.ckeditor.com/4.6.2/full-all/ckeditor.js' );  
		Application_Javascript::addCode
										( 	'
												CKEDITOR.plugins.addExternal( "uploadimage", "' . Ayoola_Application::getUrlPrefix() . '/js/objects/ckeditor/plugins/uploadimage/plugin.js", "" );
												CKEDITOR.plugins.addExternal( "confighelper", "' . Ayoola_Application::getUrlPrefix() . '/js/objects/ckeditor/plugins/confighelper/plugin.js", "" );
												CKEDITOR.config.extraPlugins = "confighelper,uploadimage";
												CKEDITOR.config.removePlugins = "maximize,resize,elementspath";
												CKEDITOR.config.allowedContent  = true;
												CKEDITOR.dtd.$removeEmpty["i"] = false;
												CKEDITOR.dtd.$removeEmpty["a"] = false;
												CKEDITOR.config.filebrowserUploadUrl = "' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Doc_Upload_Ajax/?";   
												
												//	We need to disable auto-inline to correct some content that manipulate after load
												CKEDITOR.disableAutoInline = true;
												CKEDITOR.config.toolbar = 
															[
														//		{ items: [ "Source", "-", "Save", "NewPage", "Preview", "Print", "-", "Templates" ] },
																{ name: "basicstyles", groups: [ "basicstyles", "cleanup" ], items: [ "Bold", "Italic", "Underline", "-", "RemoveFormat" ] },
//																{ name: "basicstyles", groups: [ "basicstyles", "cleanup" ], items: [ "Bold", "Italic", "Underline", "Strike", "Subscript", "Superscript", "-", "RemoveFormat" ] },
																{ name: "paragraph", groups: [ "list", "indent", "blocks", "align" ], items: [ "NumberedList", "BulletedList", "-", "Outdent", "Indent", "-", "Blockquote", "-", "CreateDiv", "-", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock", "-" ] },
																{ name: "links", items: [ "Link", "Unlink", "Anchor" ] },
																{ name: "styles", items: [ "Format", "Font", "FontSize" ] },
																{ name: "colors", items: [ "TextColor", "BGColor" ] },
																{ name: "insert", items: [ "Image", "Table", "HorizontalRule", "SpecialChar", "Iframe" ] }
																
															];
												function replaceDiv( div )  
												{
													//	reset
													div.onclick = "";
													div.setAttribute( \'contentEditable\', \'true\' ); 

													if ( ayoola.div.wysiwygEditor )
													{
													//	ayoola.div.wysiwygEditor.destroy();
													}
													//	destroy all instances of ckeditor everytime state changes.
													for( name in CKEDITOR.instances )
													{
													//	CKEDITOR.instances[name].destroy();
													}
													ayoola.div.wysiwygEditor = CKEDITOR.inline
													( 
														div,
														{

														}
													);  
													
												}
											' 
										);    
		$html = null;
		@$object['view'] = $object['view'] ? : $object['view_parameters'];    
		@$object['option'] = $object['option'] ? : $object['view_option'];
		if( @$object['url_prefix'] !== Ayoola_Application::getUrlPrefix() )
		{
			$search = array( '"' . $object['url_prefix'], "'" . $object['url_prefix'], '"' . Ayoola_Application::getUrlPrefix(), "'" . Ayoola_Application::getUrlPrefix(), );
			$replace = array( '"', "'", '"', "'", );
			$object['codes'] ? $object['codes'] = str_ireplace( $search, $replace, @$object['codes'] ) : null;
			$object['editable'] ? $object['editable'] = str_ireplace( $search, $replace, @$object['editable'] ) : null;
			$search = array( '"/', "'/", );
			$replace = array( '"' . Ayoola_Application::getUrlPrefix() . '/', "'". Ayoola_Application::getUrlPrefix() . '/', );
			$object['codes'] ? $object['codes'] = str_ireplace( $search, $replace, $object['codes'] ): null;
			$object['editable'] ? $object['editable'] = str_ireplace( $search, $replace, $object['editable'] ): null;
	//		$replace = Ayoola_Application::getUrlPrefix();
		}

		if( ! @$object['codes'] )
		{
			$html .= '<div style=" cursor: text;" data-parameter_name="editable" title="You may click to edit the content here..." contentEditable="true" class="ckeditor" onDblClick="replaceDiv( this );">' . ( isset( $object['editable'] ) ? $object['editable'] : '
			<div style="">
			<h3>Enim cupidatat eiusmod non ipsum exercitation labore pariatur proident consectetur.</h3>
			<p>Nisi fugiat laboris consequat nulla magna duis laboris ipsum ut officia aliquip. Dolor aliquip irure laborum proident. Eu cillum esse tempor in sit sunt tempor.</p>
			</div>
			' ) . '</div>';  
		}
		else
		{
			$html .= '<textarea data-parameter_name="codes" style="display:block; width:100%;" title="You may click to edit the content here..." >' . @$object['codes'] . '</textarea>';  
		}

		//	Use this to clean the URL prefix from the codes
		$html .= '<input data-parameter_name="url_prefix" type="hidden" value="' . Ayoola_Application::getUrlPrefix() . '" >';  
		$html .= '<div style="clear:both;"></div>';  
		
		//	status bar
	//	$html .= '<div name="" style="" title="" class="status_bar">'; 
				
		//	Code View
	//	$html .= '<a class="title_button" title="Switch the editing mode" name="" href="javascript:;" onclick="e.preventDefault();divToCodeEditor( this );">Code View</a>'; 
	//	var_export( @$object['codes'] );
		//									f.outerHTML = \'<div data-parameter_name="editable" title="You may click to edit the content here..." contentEditable="true" class="ckeditor"  onClick="replaceDiv( this );" onDblClick="replaceDiv( this );">' . @$object['codes'] . '</div>\';  

		Application_Javascript::addCode
		(
			'
				var divToCodeEditor = function( trigger )
				{
					// create textarea
					var e = trigger.parentNode.parentNode.getElementsByTagName( \'textarea\'); 
				//	if( e.length )
					{
						var c = false;
 						for( var b = 0; b < e.length; b++ )
						{ 
							if( e[b].name == \'' . __CLASS__ . '_code_editor\' )
							{
								var c = e[b];  
							}
							else if( e[b].getAttribute( \'data-parameter_name\' ) == \'codes\' )
							{
								//	saving as codes makes us not have the ckeditor again
								var f = document.createElement( \'div\' ); 
								f.className = \'ckeditor\'; 
								f.outerHTML = \'<div data-parameter_name="editable" title="You may click to edit the content here..." contentEditable="true" class="ckeditor"  onClick="replaceDiv( this );" onDblClick="replaceDiv( this );">\' + e[b].value +  \'</div>\';  
						//		f. = 5; 
								f.setAttribute( \'onClick\', \'replaceDiv( this );\' ); 
								f.setAttribute( \'contentEditable\', \'true\' ); 
								
								//	new ckeditor 
								e[b].parentNode.insertBefore( f, e[b] ); 
								var c = e[b];
							}
						}
 					}
					if( ! c )
					{						
							//	saving this is causing conflicts, so new textarea for each request
							var c = document.createElement( \'textarea\' ); 
							c.name = \'' . __CLASS__ . '_code_editor\'; 
							c.rows = 5; 
							c.setAttribute( \'style\', \'display:block; width:100%;\' ); 
					}
					var a = trigger.parentNode.parentNode.getElementsByClassName( \'ckeditor\'); 
					for( var b = 0; b < a.length; b++ )
					{  
						if( trigger.innerHTML == \'WYSIWYG\' )
						{ 
							a[b].innerHTML = c.value ? c.value : a[b].innerHTML;  
							
							a[b].style.display = \'block\'; 
							c.style.display = \'none\'; 
							trigger.innerHTML = \'Code View\'; 
							c.setAttribute( \'data-parameter_name\', \'\' ); 
							a[b].setAttribute( \'data-parameter_name\', \'editable\' ); 
							c.parentNode.removeChild( c );
							
						} 
						else
						{ 
							if( CKEDITOR )
							for( name in CKEDITOR.instances )
							{
								//	Destroy ckeditor so it could clean up the  code for Code Editor
								CKEDITOR.instances[name].destroy();
							}
							a[b].parentNode.insertBefore( c, a[b] ); 
							a[b].style.display = \'none\';  
							trigger.innerHTML = \'WYSIWYG\'; 
							c.value = a[b].innerHTML; 
							c.setAttribute( \'data-parameter_name\', \'codes\' ); 
							a[b].setAttribute( \'data-parameter_name\', \'\' ); 
							c.focus(); 
						} 
					//	trigger.style.display = \'\'; 
					}
				}
			'
		);
						
//		$html .= '<p style="clear:both;"></p>';
	//	$html .= '</div>';	//	 status bar
	//	$html .= '<button href="javascript:;" title="Launch the HTML Editor" class="normalnews boxednews" onclick="ayoola.div.makeEditable( this.previousSibling ); replaceDiv( this.previousSibling ); this.innerHTML = this.innerHTML == \'edit\' ? \'preview\' : \'edit\'">HTML Editor</button>';
	//	$html .= '<button href="javascript:;" title="Launch the HTML Editor" class="" onclick="ayoola.div.makeEditable( this.previousSibling ); replaceDiv( this.previousSibling ); this.innerHTML = \'Edit or Preview\'">HTML Editor</button>'; 
	//	$html .= '<button href="javascript:;" title="Launch the HTML Editor" class="ckeditor" onclick="replaceDiv( this.previousSibling ); this.innerHTML = \'Edit or Preview\'">HTML Editor</button>'; 
	//	$html .= '<a href="javascript:;" title="Close editor the preview content" class="normalnews boxednews" style="padding:1em;" onclick="ayoola.div.wysiwygEditor.destroy();"> preview </a>';
		return $html;
	}
 
	
    /**
     * This method
     *
     * @param 
     * @return 
     */
    public function getStatusBarLinks( $object )
    {
/* 		$links = array
		(
			'<a class="title_button" title="Switch the editing mode" name="" href="javascript:;" onclick="divToCodeEditor( this );">Code View</a>'
		);
 */		return '<a class="title_button" title="Switch the editing mode" name="" href="javascript:;" onclick="divToCodeEditor( this );return true;">' . ( isset( $object['codes'] ) ? 'WYSIWYG' : 'Code View' ) . '</a>';
	}
	
    /**
     * This method
     *
     * @param 
     * @return 
     */
/*    public function view()
    {
        return $this->getParameter( 'editable' ) . $this->getParameter( 'raw_html' );  
    } 
*/	// END OF CLASS
}
