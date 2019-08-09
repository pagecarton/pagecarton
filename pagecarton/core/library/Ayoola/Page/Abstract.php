<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_Editor_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 10-26-2011 9.13pm ayoola $
 */

/**
 * @see 
 */
 
require_once 'Ayoola/Abstract/Table.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Page_Editor_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Page_Abstract extends Ayoola_Abstract_Table
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
	protected static $_accessLevel = array( 99, 98 );
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'url' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'url';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Ayoola_Page_Page';
	
    /**
     * Default Page Files
     * 
     * @var array
     */
	protected static $_defaultPageFiles = array();
	
    /**
     * The path to page files
     * 
     * @var array
     */
	protected $_pageFilesPaths;
	
    /**
     *
     * @param 
     * @return  
     */
    public static function resetCacheForPage( $url )
    {
		//	self::v( Ayoola_Page::getInfo( $url ) ); 
		if( $pageInfo = Ayoola_Page::getInfo( $url ) )
		{
			$storage = unserialize( $pageInfo['cache_info'] );
			if( $storage )
			{
			//	self::v( $storage );
			//	self::v( Ayoola_Page::getInfo( $url ) );
		//		try
				
				$storage->clear();
			}
		}
		//	self::v( Ayoola_Page::getInfo( $url ) ); 
	} 
	
    /**
     * This method sets the path to the Default Page Data File
     *
     * @param void
     * @return string Full Path 
     */
    public static function setDefaultPageFiles( $defaultUrl = null )
    {
		$url = $defaultUrl ? : '/default';
		$filter = new Ayoola_Filter_UriToPath;
		$defaultDataFiles =  $filter->filter( ( $url == '/' ? '' : $url ) );
	//	var_export( $defaultUrl );
	//	var_export( $defaultDataFiles );
		foreach( $defaultDataFiles as $key => $defaultDataFile )
		{
			if( $filePath = Ayoola_Loader::checkFile( $defaultDataFile ) )
			{
				$defaultDataFiles[$key] = $filePath; 
			}
		}
		self::$_defaultPageFiles[$defaultUrl] = $defaultDataFiles;   
	} 
	
    /**
     * This method returns the path to the Default Page Data File
     *
     * @param void
     * @return string Full Path 
     */
    public static function getDefaultPageFiles( $defaultUrl = null )
    {
		if( ! @self::$_defaultPageFiles[$defaultUrl] ){ self::setDefaultPageFiles( $defaultUrl ); }
		//	var_export( $defaultUrl );
		//	var_export( self::$_defaultPageFiles );
		return self::$_defaultPageFiles[$defaultUrl];
    } 
	
    /**
     * This method sets _pageFilesPaths property to a value
     *
     * @param void
     * @return array The Path to the Page Files
     */
    public function setPageFilesPaths( $url = null )
    {
		$urlToUse = $url;
		if( is_null( $urlToUse ) )
		{
			$values = $this->_form->getValues();
			if( empty( $values['url'] ) )
			{ 
				return false;
			}
			$urlToUse =  $values['url'];
		}
		$urlToUse =  rtrim( $urlToUse, '/' );
	//	var_export( Ayoola_Application::getDomainSettings( APPLICATION_PATH ) );
	//	var_export( $url );
		require_once 'Ayoola/Filter/UriToPath.php';
		$filter = new Ayoola_Filter_UriToPath;
		$files = $filter->filter( $urlToUse );
		require_once 'Ayoola/Loader.php';
		foreach( $files as $key => $file ){ $files[$key] = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $file; }
	//	var_export( $files );
		$this->_pageFilesPaths[$url] = $files;
    } 
	     
    /**
     * This method returns _pageFilesPaths property
     *
     * @param void
     * @return array The Path to the Page Files
     */
    public function getPageFilesPaths( $url = null )
    {
		if( is_null( @$this->_pageFilesPaths[$url] ) ){ $this->setPageFilesPaths( $url ); }
	//	var_export( $this->_pageFilesPaths );
	//	var_export( $url );
	//	var_export( $this->_pageFilesPaths[$url] );

        return (array) @$this->_pageFilesPaths[$url];  
    } 
	
    /**
     * Formats the Page Data as an XML Document
     *
     * @param 
     * @return boolean
     */
    protected function _createXml( $creation = false )
    {
		require_once 'Ayoola/Xml.php';
        $xml = new Ayoola_Xml();
		$default = self::getDefaultPageFiles();
		$default = $default['data'];
		//exit();
		$xml->load( $default );
		$data = $this->getForm()->getValues();
	//		$data = $this->getIdentifierData();
		//	var_export( $this->getForm()->getValues() );
			//var_export( $this->getIdentifierData() );
		if( ! $creation ){ $data = array_merge( $data, $this->getIdentifierData() ); }
	//	var_export( $data );
		$xml->arrayAsCData( $data );
		$data['url'] = rtrim( $data['url'], '/' );
		$this->setPageFilesPaths( $data['url'] );
		$files = $this->getPageFilesPaths();
		$filename = $files['data'];

		// If the dir does not exist make one
		Ayoola_Doc::createDirectory( dirname( $filename ) );
		//var_export( $filename );
		//exit();
		// If file does not exist, make one
		if( ! is_file( $filename ) ){ file_put_contents( $filename, '' ); }
		$xml->save( $filename );
		return true;
    } 
	
    /**
     * creates the form for creating and editing page
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )  
    {
	//	var_export( $values );
		if( $this->fakeValues )
		{
			//	if we have this, we need to use all values present.
		}
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() . $values['page_id'] . $values['url'], 'data-not-playable' => true ) );
		$fieldset = new Ayoola_Form_Element;
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = false;
		$fieldset->placeholderInPlaceOfLabel = false;
		$js = '';
		if( empty( $_REQUEST['url'] ) )
		{
			$js = '	var cfdx = document.getElementsByClassName( "pc_page_url_field" )[0];
					if( ! cfdx )
					{
						return false;
					}
					var element = document.getElementById( "pc_element_to_show_url" );
					element = element ? element : document.createElement( "div" );
					element.id = "pc_element_to_show_url";
					var a = false;
					var xry = target.value;
					xry = xry.toLowerCase();
			//		xry = xry.replace( /[^a-zA-Z0-9\/]/gi, "-" );
					xry = xry.replace( /[ _.=]/gi, "-" );
					xry = xry.replace( /([-])+/gi, "-" );
					xry = xry.replace( /[-\/]$/gi, "" );
					xry = xry.replace( /^[-\/]/gi, "" );
					xry = "/" + xry;
					if( xry )
					{
						a = true;
					}
					var xm = "Please enter a valid name in the space provided... (e.g. My New Page)";
					if( a )
					{
						xm = "<a href=\'javascript:\' onclick=\'var cfd = document.getElementsByClassName( \"pc_page_url_field\" )[0]; cfd.type = \"text\"; cfd.type = \"text\"; cfd.focus();\'>Use Custom URL</a> or The URL for the new page will be: <a target=\"_blank\" href=\'' . Ayoola_Page::getRootUrl() . Ayoola_Application::getUrlPrefix() . '" + xry + "\'>' . Ayoola_Page::getRootUrl() . Ayoola_Application::getUrlPrefix() . '<strong>" + xry + "</strong></a>";   
					}  
					cfdx.value = xry;
					element.innerHTML = "<span style=\'font-size:x-small;\' class=\'\'>" + xm + "</span>";
					target.parentNode.insertBefore( element, target.nextSibling );';

					
		}
		Application_Javascript::addCode
		(
			'
				ayoola.addShowAutoUrl = function( target )
				{
					' . $js . '
				}
			'
		);
	//      var_export( Ayoola_Application::getUrlPrefix() );
		$fieldset->addElement( array( 'name' => 'title', 'label' => 'Page Title', 'placeholder' => 'e.g. My New Page', 'type' => 'InputText', 'onchange' => 'ayoola.addShowAutoUrl( this );', 'onkeyup' => 'ayoola.addShowAutoUrl( this );', 'value' => @$values['title'] ) );
		if( is_null( $values ) )
		{
			$fieldset->addElement( array( 'name' => 'url', 'class' => 'pc_page_url_field', 'style' => '', 'label' => '', 'placeholder' => '/page', 'onchange' => 'ayoola.addShowAutoUrl( this );', 'type' => 'Hidden', 'value' => @$values['url'] ) ); 

		//	$url =

            if( empty( $_REQUEST['url'] ) )
            {
                $fieldset->addFilter( 'url', array( 'Transliterate' => null, 'SimplyUrl' => null, 'Uri' => null, 'CharacterWhitelist' => array( 'character_list' => '^\w\-\/', 'replace' => '-', ) ) );
            }

			$fieldset->addRequirement( 'url', array( 'DuplicateRecord' => array( 'Ayoola_Page_Page', 'url', 'badnews' => '"%variable%" already exist as a page.', ),'CharacterWhitelist' => array( 'badnews' => 'The allowed characters are lower case alphabets (a-z), numbers (0-9), underscore (_) and hyphen (-).', 'character_list' => '^\w\-\/', ), 'NotEmpty' => null, 'Uri' => null ) );
		}
		$fieldset->addElement( array( 'name' => 'description', 'label' => 'Page Description', 'placeholder' => 'Enter a short description of the content of this page. The description will be displayed in search results and page preview...', 'type' => 'TextArea', 'value' => @$values['description'] ) );
		
		//	Set the layout_name to null first to 
		//	PREVENT EDITOR FROM STILL PARADING THE OLD TEMPLATE
		
		//	Auth Level

		if(  $this->fakeValues || ! empty( $values ) )
		{
			
			$authLevel = new Ayoola_Access_AuthLevel;  
			$authLevel = $authLevel->select();
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'auth_level', 'auth_name');
			$authLevel = $filter->filter( $authLevel );
		//	self::v( $values['auth_level'] );
		//	self::v( $authLevel );
			unset( $authLevel[97] );
			unset( $authLevel[98] );
			if( self::hasPriviledge() )
			{
		//		unset( $authLevel[98] );
			}
			$authLevel[99] = 'Admin Only';
			$authLevel[98] = 'Owner';
			$authLevel[1] = 'Registration Required';
			$authLevel[0] = 'Public';
			$fieldset->addElement( array( 'name' => 'auth_level', 'label' => 'Page Privacy', 'type' => 'SelectMultiple', 'value' => @$values['auth_level'] ? : array( 0 ) ), $authLevel );    
			unset( $authLevel );

			$fieldset->addElement( array( 'name' => 'cover_photo', 'label' => 'Page Header Image', 'type' => 'Document', 'value' => @$values['cover_photo'] ) );    

		}
		else
		{
			$fieldset->addElement( array( 'name' => 'auth_level', 'type' => 'hidden', 'value' => '0' ) );    
		}	
		//	allows to set system pages here
		$fieldset->addElement( array( 'name' => 'system', 'type' => 'hidden', 'value' => null ) );    

		if( $this->fakeValues || ! empty( $values ) )
		{
			
			$options =  array( 
								'template' => 'Use separate theme (Over-rides default theme)', 
								'redirect' => 'Redirect this page to another', 
								'module' => 'Direct "' . rtrim( @$values['url'], '/' ) . '/*" to this page if they do not exist.',  
								);
			
			$fieldset->addElement( array( 'name' => 'page_options', 'onchange' => 'this.form.submit();', 'label' => 'Page Options', 'type' => 'Checkbox', 'value' => @$values['page_options'] ), $options );
		}
	
		$fieldset->addLegend( $legend );
		$fieldset->addFilters( 'StripTags::Trim' );
		$form->addFieldset( $fieldset );   
		$pageOptions = $this->getGlobalValue( 'page_options' ) ? : @$values['page_options'];
		if( is_array( $pageOptions ) && in_array( 'redirect', $pageOptions ) )
		{
			$fieldset = new Ayoola_Form_Element;
	//		$fieldset->addLegend( 'Redirect this page to another page' );
			$fieldset->addElement( array( 'name' => 'redirect_url', 'placeholder' => 'e.g. http://example.com/page.html', 'type' => 'InputText', 'value' => @$values['redirect_url'] ) );
			$fieldset->addRequirement( 'redirect_url', array( 'NotEmpty' => null ) );
			
			$form->addFieldset( $fieldset );
		}  
		else
		{
			$fieldset->addElement( array( 'name' => 'redirect_url', 'type' => 'hidden', 'value' => '' ) );    
		}	
	//	self::v( $this->getGlobalValue( 'page_options' ) );
		if( is_array( $pageOptions ) && in_array( 'template', $pageOptions ) )
		{
			$fieldset = new Ayoola_Form_Element;
	//		$fieldset->addLegend( 'Choose a layout template to use for this page' );
			$option = new Ayoola_Page_PageLayout;
			$option = $option->select( array( 'pagelayout_id', 'layout_name', 'layout_label' ) );
		//	require_once 'Ayoola/Filter/SelectListArray.php';
		//	$filter = new Ayoola_Filter_SelectListArray( 'layout_name', 'layout_name');
		//	$option = $filter->filter( $option );
			$class = null;
		//	$option[] = array( 'document_url' => 'http://placehold.it/100x100&text=Default Template' );
		//	var_export( $this->getGlobalValue( 'layout_name' ) );
		//	var_export( $this->getGlobalValue( 'layout_name' ) );
		//	var_export( $this->getGlobalValue( 'page_options' ) );
			$layouts = array();
			
			//	Allow for default template
		//	$layouts[''] = '';
			foreach( $option as $each ) 
			{
				$class = ( $each['layout_name'] === $values['layout_name'] || $this->getGlobalValue( 'layout_name' ) === $each['layout_name'] ) ? 'defaultnews' : 'normalnews';
				$layouts[$each['layout_name']] = '
			<div class="' . $class . '" name="layout_screenshot" onClick="this.parentNode.parentNode.click(); ayoola.div.selectElement( { element: this, selectMultiple: false } ); " style="display:inline-block;text-align:center;padding:3em 2em 3em 2em; xwidth:100px; overflow:hidden;  background:     linear-gradient(      rgba(0, 0, 0, 0.7),      rgba(0, 0, 0, 0.7)    ),    url(\'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_PhotoViewer/?layout_name=' . ( $each['layout_name'] ) . '\');  background-size: cover;  color: #fff !important; cursor:pointer; ">
			' . $each['layout_label'] . '
			</div>';
			//	$layouts[$each['layout_name']] = $each['layout_name']; 
			}
			$fieldset->addElement( array( 'name' => 'layout_name', 'label' => 'Page Theme', 'type' => 'Radio', 'style' => 'display:none;', 'required' => 'required', 'value' => @$values['layout_name'] ), $layouts );
	//		var_export( $values['layout_name'] );
	//		var_export( $_POST );
	//		var_export( $layouts );
			$fieldset->addRequirement( 'layout_name', array( 'InArray' => array_keys( $layouts ) + array( 'badnews' => 'Please select a theme for this page' ), 'NotEmpty' => array( 'badnews' => 'Please select a theme for this page' ) ) );
			$form->addFieldset( $fieldset );
		}
		else
		{
			$fieldset->addElement( array( 'name' => 'layout_name', 'type' => 'hidden', 'value' => '' ) );    
		}	
		$this->setForm( $form );
    } 
	// END OF CLASS
}
