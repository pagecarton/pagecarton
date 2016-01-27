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
			$parameters = array( 'markup_template' => $content, 'markup_template_namespace' => 'x1234' . time(), 'editable' => $this->getParameter( 'markup_template_object_name' ) ) + $this->getParameter();
			$class = new Ayoola_Object_Embed( $parameters );
			$content = $class->view();
			$content .= '<div style="clear:both;"></div>';  
			$content .= '<div style="clear:both;"></div>';  
		//	$content = Ayoola_Object_Embed::viewInLine( $parameters );
		//	var_export( $parameters );
		//	var_export( $content );
		}
		$this->setParameter( array( 'editable' => $content ) );
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
 */	//	Application_Javascript::addFile( '/js/objects/ckeditor/ckeditor.js' );
		Application_Javascript::addFile( '//cdn.ckeditor.com/4.5.6/full-all/ckeditor.js' );  
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
												CKEDITOR.config.toolbar = 
															[
																{ items: [ "Source", "-", "Save", "NewPage", "Preview", "Print", "-", "Templates" ] },
																{ name: "basicstyles", groups: [ "basicstyles", "cleanup" ], items: [ "Bold", "Italic", "Underline", "Strike", "Subscript", "Superscript", "-", "RemoveFormat" ] },
																{ name: "paragraph", groups: [ "list", "indent", "blocks", "align" ], items: [ "NumberedList", "BulletedList", "-", "Outdent", "Indent", "-", "Blockquote", "-", "CreateDiv", "-", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock", "-" ] },
																{ name: "links", items: [ "Link", "Unlink", "Anchor" ] },
																{ name: "styles", items: [ "Format", "Font", "FontSize" ] },
																{ name: "colors", items: [ "TextColor", "BGColor" ] },
																{ name: "insert", items: [ "Image", "Flash", "Table", "HorizontalRule", "Smiley", "SpecialChar", "Iframe", "div" ] },
														//		"/",
																{ name: "forms", items: [ "Form", "Checkbox", "Radio", "TextField", "Textarea", "Select", "Button", "ImageButton", "HiddenField" ] },
																{ name: "tools", items: [ "Maximize" ] }
																
															];
												function replaceDiv( div )  
												{
													//	reset
													div.onclick = "";
												//	div.contentEditable = "true";
													div.setAttribute( \'contentEditable\', \'true\' ); 

													if ( ayoola.div.wysiwygEditor )
													{
													//	ayoola.div.wysiwygEditor.destroy();
													}
													//	destroy all instances of ckeditor everytime state changes.
												//	for( name in CKEDITOR.instances )
													{
												//		CKEDITOR.instances[name].destroy();
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
		if( ! @$object['codes'] )
		{
			$html .= '<div data-parameter_name="editable" title="You may click to edit the content here..." contentEditable="true" class="ckeditor"  onClick="replaceDiv( this );" onDblClick="replaceDiv( this );">' . ( isset( $object['editable'] ) ? $object['editable'] : "HTML Content! You may edit this content by clicking here..." ) . '</div>';  
		}
		else
		{
			$html .= '<textarea data-parameter_name="codes" style="display:block; width:100%;" title="You may click to edit the content here..." >' . @$object['codes'] . '</textarea>';  
		}
		$html .= '<div style="clear:both;"></div>';  
		
		//	status bar
	//	$html .= '<div name="" style="" title="" class="status_bar">'; 
				
		//	Code View
	//	$html .= '<a class="title_button" title="Switch the editing mode" name="" href="javascript:;" onclick="e.preventDefault();divToCodeEditor( this );">Code View</a>'; 
		
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
								f.outerHTML = \'<div data-parameter_name="editable" title="You may click to edit the content here..." contentEditable="true" class="ckeditor"  onClick="replaceDiv( this );" onDblClick="replaceDiv( this );">' . ( @$object['codes'] ) . '</div>\'; 
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
							a[b].parentNode.insertBefore( c, a[b] ); 
							a[b].style.display = \'none\';  
							trigger.innerHTML = \'WYSIWYG\'; 
							c.innerHTML = a[b].innerHTML; 
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
    public function view()
    {
		//	codes first because it wont be there if they didnt opt to enter codes
/* 		$content = $this->getParameter( 'codes' ) ? : ( $this->getParameter( 'editable' ) ? : $this->getParameter( 'view' ) );
		if( $this->getParameter( 'markup_template_object_name' ) )
		{
			$parameters = array( 'markup_template' => $content, 'markup_template_namespace' => 'x1234' . time(), 'editable' => $this->getParameter( 'markup_template_object_name' ) ) + $this->getParameter();
			$class = new Ayoola_Object_Embed( $parameters );
			$content = $class->view();
			$content .= '<div style="clear:both;"></div>';  
			$content .= '<div style="clear:both;"></div>';  
		//	$content = Ayoola_Object_Embed::viewInLine( $parameters );
		//	var_export( $parameters );
		//	var_export( $content );
		}
 */		
	//	var_export( $this->_parameter );
        return $this->getParameter( 'editable' ) . $this->getParameter( 'raw_html' );  
    } 
	// END OF CLASS
}
