<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    http://pagecarton.com/about/license
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $  
 */

/**
 * @see Ayoola_Page_Layout_Exception 
 */
 
require_once 'Ayoola/Page/Layout/Exception.php';


/**
 * @category   PageCarton CMS  
 * @package    Ayoola_Page_Layout_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    http://pagecarton.com/about/license
 */

abstract class Ayoola_Page_Layout_Abstract extends Ayoola_Abstract_Table
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
	protected static $_accessLevel = 99;
	
    /**
     * 
     *
     * @var string
     */
	protected $_idColumn = 'layout_name';  
	
    /**
     * Identifier for the column to edit
     * 
     * param string
     */
	protected $_identifierKeys = array( 'layout_name' );
 		
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Ayoola_Page_PageLayout';
	
    /**
     * The filename of the Layout
     * 
     * var string
     */
	protected $_filename;
	
    /**
     * Key for the id column
     * 
     * param string
     */
	const VALUE_CONTENT = 'FRESH_CONTENT';
	
    /**
     * Inserts the Data into Storage
     * 
     * @return bool
     */
	protected function updateFile()
    {
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		if( ! @$values[self::VALUE_CONTENT] ){ return false; }
		if( $identifierData = self::getIdentifierData() )
		{
			$values = $values + $identifierData;
		//	return false; 
		}
/* 	//	var_export( $values );
		var_export( strlen( $values[self::VALUE_CONTENT] ) );
		var_export( '<br />' );
		var_export( strlen( $values['plain_text'] ) );
		var_export( '<br />' );
		var_export( strlen( $this->getGlobalValue( 'plain_text' ) ) );
 */		require_once 'Ayoola/Doc.php';
	//	$values[self::VALUE_CONTENT] = htmlspecialchars_decode( str_ireplace( '%%CLASS%%', __CLASS__, $values[self::VALUE_CONTENT] ) );
	//	$values[self::VALUE_CONTENT] = str_ireplace( '%%CLASS%%', __CLASS__, $values[self::VALUE_CONTENT] );
		Ayoola_Doc::createDirectory( dirname( $this->getMyFilename() ) );
//		file_put_contents( $this->getMyFilename(), $values[self::VALUE_CONTENT] );
		//	Update Screenshot
		$screenshot = Ayoola_Doc::getDocumentsDirectory() . $values['screenshot'];
		if( is_file( $screenshot ) )
		{
			$screenshotFile = dirname( $this->getMyFilename() ) . '/screenshot.jpg';
			file_put_contents( $screenshotFile, file_get_contents( $screenshot ) );
		}
		$content = $values['plain_text'] ? : $values['wysiwyg'];
		if( ! $content ){ return false; }
		
		//	Sanitize
		$content = self::sanitizeTemplateFile( $content, $values );
		file_put_contents( $this->getMyFilename(), $content );
		return true;
    } 

    /**
	 * Sets the _filename
	 *
     */
    public function setFilename( $data = null )
	{
	//	if( ! $data = $this->getForm()->getValues() )
		if( ! $data )
		{ 
			try
			{
				if( ! $data = $this->getIdentifierData() ){ return false; }
			}
			catch( Exception $e )
			{ 
				return false;
			}
		}
	//	var_export( $filename );
		$dir = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS;
		
		//	compatibility
		$filename = @$data['pagelayout_filename'];
		if( ! is_file( $dir . $filename ) )
		{ 
			//	We now store templates in the document directory.
			//	First for newbies
		//	$filename = DOCUMENTS_DIR . DS . 'layout' . DS . $data['layout_name'] . DS . 'template.html'; 
		//	if( ! is_file( $dir . $filename ) )  
			{ 
				//	This is the real new template file 
				$filename = DOCUMENTS_DIR . DS . 'layout' . DS . ( @$data['layout_name'] ? : 'workaround-to-avoid-deleting-whole-layout-dir' ) . DS . 'template';
			//	if( ! is_file( $dir . $filename ) )
				{ 
					//	Leave this open because of the "Creator"
				} 
			}
		}
		$this->_filename = str_ireplace( '/', DS, $filename ); 
	//	exit( $this->_filename );
	}

    /**
	 * Gets the _filename
	 *
     */
    public function getFilename()
	{
		if( null == $this->_filename ){ $this->setFilename(); }
		return $this->_filename;
	}

    /**
	 * Gets the _myFilename
	 *
     */
    public function getMyFilename()
	{
		if( ! $filename = $this->getFilename() ){ return false; }
		$filename = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $filename;
		return $filename;
	}

    /**
	 * 
	 *
     */
    public static function sanitizeTemplateFile( $content, $values )
	{
		//	http://stackoverflow.com/questions/2869844/regex-to-replace-relative-link-with-root-relative-link
		$linkForPrefix = "<?php echo Ayoola_Application::getUrlPrefix(); ?>";
//		$linkForPrefix = preg_quote( $linkForPrefix, '$' );
		$content = preg_replace('#(href|src)=["\']([^/\#][^:"]*)(?:["\'])#', '$1="PC_PLACEHOLDER_FOR_URL_PREFIX/layout/' . $values['layout_name'] . '/$2"', $content ); 
		
	//	var_export( $content );
		
		//	Fix url();  
		$content = preg_replace('#url\(([^/\#][^:"]*)\)#', 'url(PC_PLACEHOLDER_FOR_URL_PREFIX/layout/' . $values['layout_name'] . '/$1)"', $content );

		// Instantiate the object
		$xml = new Ayoola_Xml();
		
		// Build the DOM from the input (X)HTML snippet
		@$xml->loadHTML( $content );

		//	add ayoola layout header
		$head = $xml->getElementsByTagName( 'head' );
		foreach( $head as $each )
		{
			$each->insertBefore( $xml->createCDATASection( "\r\n<?php include_once( LAYOUT_PATH . DS . 'htmlHeader' . TPL ) ?>\r\n" ), $each->firstChild );
		}
		$html = $xml->getElementsByTagName( 'html' );
		foreach( $html as $each )
		{
			$each->appendChild( $xml->createCDATASection( "\r\n<?php include_once( LAYOUT_PATH . DS . 'footerJs' . TPL ) ?>\r\n" ) );
		}

		//	remove title tags
		$title = $xml->getElementsByTagName( 'title' );
		foreach( $title as $each )
		{
			$each->parentNode->removeChild( $each );
		}
		
		//	Build navigation system
		$nav = $xml->getElementsByTagName( 'nav' );
		foreach( $nav as $navCount => $each )
		{
			//	The name must not have spaces   
			$filter = new Ayoola_Filter_Name();
			$menuName = $filter->filter( ( ( $each->getAttribute( 'data-pc-menu-name' ) ? : $each->getAttribute( 'name' ) ) ? : $each->getAttribute( 'id' ) ) ? : $each->getAttribute( 'class' ) );
			if( ! $menuName )
			{
				continue;
			}
		//	var_export( $menuName );
			//	clear interior first
			//	no need to clear interior again
			
			//	get the inner parent of ul, if present.
		//	while( $each->hasChildNodes() ) 
			if( $each->getElementsByTagName( 'ul' ) )
			{
				foreach( $each->getElementsByTagName( 'ul' ) as $ulCount => $eachChild ) 
				{
					$ulParent = $eachChild->parentNode;
					$idForMenu = 'pc-menu-' . $menuName . $navCount . '-' . $ulCount;
					if( ! $ulParent->getAttribute( 'id' ) )
					{
						$ulParent->setAttribute( 'id', $idForMenu );
					}
					else
					{
						$idForMenu = $ulParent->getAttribute( 'id' );
					}
					$each->setAttribute( 'data-pc-menu-id-list', $each->getAttribute( 'data-pc-menu-id-list' ) . ',' . $ulParent->getAttribute( 'id' ) );
				//	$each->setAttribute( 'data-pc-menu-id-in', $each->getAttribute( 'data-pc-menu-id-list' ) . ',' . $ulParent->getAttribute( 'id' ) );
					
			//		$content = $xml->saveHTML();
					
				//	$eachChild = $each->firstChild;
				//	if( strtolower( @$eachChild->tagName ) === 'ul' )
					{
						//	Save the class names and other information
						$each->setAttribute( 'data-pc-menu-ul-class-' . $idForMenu, $eachChild->getAttribute( 'class' ) );
						$eachChild->getAttribute( 'id' ) ? $each->setAttribute( 'data-pc-menu-ul-id-' . $idForMenu, $eachChild->getAttribute( 'id' ) ) : null;
						
						//	Go deaper to look for class names of li and sub menus
						while( $eachChild->hasChildNodes() ) 
						{
							$ulChild = $eachChild->firstChild;
							if( strtolower( @$ulChild->tagName ) === 'li' )
							{
								//	Save the class names and other information
								$ulChild->getAttribute( 'class' ) ? $each->setAttribute( 'data-pc-menu-li-active-class-' . $idForMenu, $ulChild->getAttribute( 'class' ) ) : null;
										
								//	Go deaper to look for sub menus
								while( $ulChild->hasChildNodes() ) 
								{
									$liChild = $ulChild->firstChild;
									if( strtolower( @$liChild->tagName ) === 'ul' )
									{
										//	Save the class names and other information
										$liChild->getAttribute( 'class' ) ? $each->setAttribute( 'data-pc-menu-li-ul-class-' . $idForMenu, $liChild->getAttribute( 'class' ) ) : null;
										
										
									}
									$ulChild->removeChild( $liChild );
								}						
							}
							$eachChild->removeChild( $ulChild );
						}						
					}
					$ulParent->removeChild( $eachChild );
				}	
			}
			else
			{
			
			}
			if( $menuList = array_map( 'trim', explode( ',', $each->getAttribute( 'data-pc-menu-id-list' ) ) ) )
			{
				foreach( $menuList as $idForMenu )
				{
					if( ! $idForMenu )
					{
						continue;
					}
					$activeClass = $each->getAttribute( 'data-pc-menu-li-active-class-' . $idForMenu ) ? : 'active';
					$ulClass = $each->getAttribute( 'data-pc-menu-ul-class-' . $idForMenu ) ? : '';
					$ulId = $each->getAttribute( 'data-pc-menu-ul-id-' . $idForMenu ) ? : '';  
					$xml->setId( 'id' );
					$ulParent = $xml->getElementById( $idForMenu );
				//	var_export( $idForMenu );
				//	var_export( $ulParent );
					if( ! $ulParent )
					{
						continue;
					}
					
					$ulParent->appendChild( $xml->createCDATASection( "\r\n<?php echo Ayoola_Menu_Demo::viewInLine( array( 'option' => '{$menuName}', 'li-active-class' => '{$activeClass}', 'ul-class' => '{$ulClass}', 'ul-id' => '{$ulId}', )  ); ?>\r\n" ) ); 
				}
			}
			else
			{
			
			//	$each->innerHTML = '';
				$activeClass = $each->getAttribute( 'data-pc-menu-li-active-class' ) ? : 'active';
				$ulClass = $each->getAttribute( 'data-pc-menu-ul-class' ) ? : '';
				$each->appendChild( $xml->createCDATASection( "\r\n<?php echo Ayoola_Menu_Demo::viewInLine( array( 'option' => '{$menuName}', 'li-active-class' => '{$activeClass}', 'ul-class' => '{$ulClass}', )  ); ?>\r\n" ) ); 
			}
		}
		
		
	//	if( @$values['auto_create_section'] )
		{
			$section = $xml->getElementsByTagName( 'section' );
			$i = 0;
			foreach( $section as $each )
			{
				$name = str_ireplace( array( ' ', '-' ), '_', ( $each->getAttribute( 'data-pc-section-name' ) ? : $each->getAttribute( 'id' ) ) );
				if( ! $name || $each->getAttribute( 'data-pc-section-created' )  )
				{
					continue;
				}
				if( $each->getElementsByTagName( 'section' )->length )
				{
					//	You are not allowed to have a child section if we must autogenerate section
				//	var_export( $name );
				//	var_export( $each->getElementsByTagName( 'section' )->length );
			//		var_export(  $each->getElementsByTagName( 'section' ) );
					continue;
				}
			//	$i++;
			//	foreach( $each->childNodes as $eachChild ) 
				{
			//		if( strtolower( @$eachChild->tagName ) === 'section' )
					{
						//	Break free, a parent section cant be an editable region.
			//			continue 2;
					}
				//	$each->removeChild( $eachChild );
				}						
				
/* 				//	Check if the parent is also a section
				$parentCounter = 0;
				$parentName = $each->parentNode;
				while( $parentCounter < 5 ) 
				{
					if( strtolower( @$parentName->tagName ) === 'section' )
					{
						//	Break free, don't duplicate.
						continue 2;
					}
					$parentName = @$parentName->parentNode;
					$parentCounter++;
				}						
 */			//	$each->innerHTML = '';
			//	$name = str_ireplace( ' ', '_', ( ( ( $each->getAttribute( 'data-pc-section-name' ) ? : $each->getAttribute( 'name' ) ) ? : $each->getAttribute( 'id' ) ) ? : ( $each->getAttribute( 'class' ) . '_' . $i ) ) );
				$each->appendChild( $xml->createCDATASection( "\r\n{$name}@@@}\r\n" ) );
				if( $each->hasChildNodes() )
				{
					$each->insertBefore( $xml->createCDATASection( "\r\n{@@@{$name}\r\n" ), $each->firstChild );
				}
				$each->insertBefore( $xml->createCDATASection( "\r\n@@@{$name}@@@\r\n" ), $each->firstChild );
				$each->setAttribute( 'data-pc-section-created', '1' );
			}
		}
		//	Build logo
		$img = $xml->getElementsByTagName( 'img' );
		foreach( $img as $each )
		{
			//	clear interior first
			if( $each->getAttribute( 'name' ) === 'pc-logo' )
			{
				$each->setAttribute( 'src', "PC_PLACEHOLDER_FOR_ORG_LOGO" );				
				//	This won't work in dom
/* 				$each->setAttribute( 'src', "<?php echo Ayoola_Doc::getLogo(); ?>" );
 */			}
		}
		
		// empty anchor not doing well in CKEDITOR
		$anchor = $xml->getElementsByTagName( 'a' );
		foreach( $anchor as $each )
		{
			//	check if empty
			
			//	http://stackoverflow.com/questions/29714291/removing-elements-with-no-children-dom-php
			$xpath = new DOMXpath($xml);

			$empty_anchors = $xpath->evaluate('//a[not(*) and not(text()[normalize-space()])]');
			$i = $empty_anchors->length - 1; 
			while ($i > -1) { 
				$element = $empty_anchors->item($i);  
			//	$element->parentNode->removeChild($element); 
			
				//	Dont remove, add empty space
			//	var_export( $element );
			//	$element->appendChild( $xml->createElement( '' ) );       
				$element->nodeValue = '&nbsp;';       
				$i--;    
			} 
		}
		
		//	 empty icons not doing well in CKEDITOR
		$icons = $xml->getElementsByTagName( 'i' );
		foreach( $icons as $each )
		{
			//	check if empty
			
			//	http://stackoverflow.com/questions/29714291/removing-elements-with-no-children-dom-php
			$xpath = new DOMXpath($xml);

			$empty_anchors = $xpath->evaluate('//a[not(*) and not(text()[normalize-space()])]');
			$i = $empty_anchors->length - 1; 
			while ($i > -1) 
			{ 
				$element = $empty_anchors->item($i);  
				$element->nodeValue = '&nbsp;';       
				$i--;    
			} 
		}

		//	remove description and keywords tags
		$meta = $xml->getElementsByTagName( 'meta' );
		foreach( $meta as $each )
		{	
			$a = strtolower( $each->getAttribute( 'name' ) );
		//	var_export( $each->getAttribute( 'name' ) );
		//	var_export( $a );
			switch( $a )
			{
				case '':
//					$each->parentNode->removeChild( $each );
				break;
				case 'keywords':
					$each->parentNode->removeChild( $each );
				break;
				case 'description':
					$each->parentNode->removeChild( $each );
				break;
			}
		}
		$content = $xml->saveHTML();
	//	var_export( $content );
		
		//	workaround for the bug causing space to be replaced with 	%5Cs in preg_replace
		$content = str_ireplace( array( 'PC_PLACEHOLDER_FOR_URL_PREFIX', 'PC_PLACEHOLDER_FOR_ORG_LOGO' ), array( $linkForPrefix, '<?php echo Ayoola_Doc::getLogo(); ?>' ), $content );
		
		
		return $content;
	}
	
    /**
     * creates the form for creating and editing subscription package
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
	
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->setParameter( array( 'no_fieldset' => true ) );
		$fieldset = new Ayoola_Form_Element;
		$form->submitValue = $submitValue ;
		//	Use tiny editor
		//	Application_Javascript::addFile( '/js/objects/tinymce/tinymce.min.js' );
	//	Application_Javascript::addFile( '/js/objects/ckeditor/ckeditor.js' );
		$name = Ayoola_Form::hashElementName( 'wysiwyg' );
		Application_Javascript::addCode( 'ayoola.events.add( window, "load", function()
		{ 
			CKEDITOR.replace
			( 
				"' . $name . '",
				{
					filebrowserBrowseUrl: "/ayoola/thirdparty/Filemanager/index.php",
				}
			); 
		} );' );
		Application_Javascript::addCode
		( 
			'
			ayoola.xmlHttp.setAfterStateChangeCallback
			( 
				function()
				{ 
					if (CKEDITOR.instances["' . $name . '"]) { delete CKEDITOR.instances["' . $name . '"] };
					if (CKEDITOR.instances["' . $name . '"]) { CKEDITOR.instances["' . $name . '"].destroy(); } 
					CKEDITOR.replace
					( 
						"' . $name . '",
						{
							filebrowserBrowseUrl: "/ayoola/thirdparty/Filemanager/index.php",
						}
					);
				}
			)
			ayoola.xmlHttp.setBeforeStateChangeCallback
			( 
				function()
				{ 
				//	alert( CKEDITOR.instances["' . $name . '"] );
				//	if (CKEDITOR.instances["' . $name . '"]) { delete CKEDITOR.instances["' . $name . '"] };
			//		alert( CKEDITOR.instances["' . $name . '"] );
					//	Destroy is the only method that gives me what i need
					if (CKEDITOR.instances["' . $name . '"]) { CKEDITOR.instances["' . $name . '"].destroy(); } 
			//		alert( CKEDITOR.instances["' . $name . '"] );
				}
			)
			' 
		);
		do 
		{
		//	$options = array( 'wysiwyg' => 'Use a simple HTML editor',  'plain_text' => 'Use a plain text editor (Advanced)' );
			$options = array( 'plain_text' => 'Use a plain text editor (Advanced)' );
			if( is_null( $values ) )
			{
				//	If this a creator, we can upload
				$options += array( 'upload' => 'Import a template from your computer.', );
			}
			$fieldset->addElement( array( 'name' => 'layout_type', 'label' => 'Choose a design mode', 'onClick' => 'this.form.submit();', 'type' => 'Radio', 'value' => @$values['layout_type'] ? : 'plain_text' ), $options );
			$fieldset->addRequirement( 'layout_type', array( 'ArrayKeys' => $options ) );
		//	$previousContent = file_get_contents( $this->getMyFilename() );
		//	var_export( $_POST );
			//	Choose a layout type first  
			
			//	Load this before we break so some image JS can run
			//	Screenshot
		//	var_export( $link );
			$values['screenshot'] = $this->getGlobalValue( 'screenshot' ) ? : ( @$values['screenshot'] ? : ( @$values['layout_name'] ? ( '/layout/' . $values['layout_name'] . '/screenshot.jpg' ) : null ) );
			if( ! Ayoola_Doc::uriToDedicatedUrl( @$values['screenshot'] ) )
			{
				$values['screenshot'] = null; 
			}
	//		$fieldset->addElement( array( 'name' => 'screenshot', 'label' => '', 'placeholder' => 'Screenshot for this template ', 'type' => 'Hidden', 'value' => $values['screenshot'] ) );
	//		$fieldName = ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'screenshot' ) : 'screenshot' );
	//		$uploadScreenshot = Ayoola_Doc_Upload_Link::viewInLine( array( 'image_preview' => ( @$values['screenshot'] ? : null ), 'field_name' => $fieldName, 'crop' => true, 'width' => 300, 'height' => 300, 'field_name_value' => 'url', 'call_to_action' => 'Change Screenshot...' ) );
	//		$fieldset->addElement( array( 'name' => 'x', 'type' => 'Html' ), array( 'html' => $uploadScreenshot ) );
			$fieldset->addElement( array( 'name' => 'screenshot', 'label' => 'Screenshot for Layout Template', 'data-allow_base64' => true, 'data-document_type' => 'image', 'type' => 'Document', 'value' => @$values['screenshot'] ) );
			if( ! $this->getGlobalValue( 'layout_type' ) )
			{
			//	break;
			}
			
			//	All types now need labels
		//	if( $this->getGlobalValue( 'layout_type' ) != 'upload' )
			{
				//	Labels are expected to be inside uploaded document.
				$fieldset->addElement( array( 'name' => 'layout_label', 'label' => 'Choose a name for this template', 'placeholder' => 'E.g. Super Theme', 'type' => 'InputText', 'value' => @$values['layout_label'] ) );
				$fieldset->addRequirement( 'layout_label', array( 'WordCount' => array( 4,100 ) ) );
				
				//	We don't allow editing UNIQUE Keys
				//	Now doing it within the creator
			//	if( is_null( $values ) )
				{		
				//	$fieldset->addElement( array( 'name' => 'layout_name', 'type' => 'Hidden', 'value' => @$values['layout_name'] ) );
				//	$fieldset->addFilter( 'layout_name', array( 'DefiniteValue' => $this->getGlobalValue( 'layout_label' ) ,'Name' => null ) );
					
				}
			}
	/* 		require_once 'Ayoola/Doc.php';		
			$doc = new Ayoola_Doc_Document;
			$doc = $doc->select();
			$filter = new Ayoola_Filter_FileExtention();
			foreach( $doc as $key => $each )
			{
				if( $filter->filter( $each['document_url'] ) != 'css' ){ unset( $doc[$key] ); }
			}
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'document_url', 'document_name' );
			$doc = $filter->filter( $doc );	
			$doc = array( 'Default CSS' ) + $doc;
			$fieldset->addElement( array( 'name' => 'document_url', 'label' => 'Layout Style', 'type' => 'Select', 'value' => @$values['document_url'] ), $doc );
			$fieldset->addRequirement( 'document_url', array( 'InArray' => array_keys( $doc )  ) );
			unset( $doc );
	 */		
			if( $previousContent = @file_get_contents( $this->getMyFilename() ) )
			{
			//	var_export( $previousContent );
				
				//	Strip the php content from it.
				$previousContent = preg_replace( '#<\?.*?(\?>|$)#s', '', $previousContent );
				$previousContent = preg_replace( '#/layout/[a-zA-Z0-9-_]*/#', '', $previousContent );
				
				//	This was also added automatically
				$previousContent = str_ireplace( array( '/layout/' . $values['layout_name'] . '/', '/layout//' ), '', $previousContent );
			}
			//	var_export( $previousContent );
			$fieldset->addElement( array( 'name' => self::VALUE_CONTENT, 'type' => 'Hidden', 'value' => null ) );
			switch( $this->getGlobalValue( 'layout_type' ) )
			{
				case 'wysiwyg':
					$fieldset->addElement( array( 'name' => 'wysiwyg', 'label' => 'Use this editor to design your layout template', 'rows' => 20, 'placeholder' => 'Enter the template text here...', 'type' => 'Textarea', 'value' => $previousContent ) );
					$fieldset->addRequirement( 'wysiwyg', array( 'WordCount' => array( 10,50000 ) ) );
					$fieldset->addFilter( self::VALUE_CONTENT, array( 'DefiniteValue' => $this->getGlobalValue( 'wysiwyg' ) ) );
				break;
				case 'upload':
				//	$link = '/ayoola/thirdparty/Filemanager/index.php?directory_suffix=tmp&return_full_path=true&field_name=' . Ayoola_Form::hashElementName( 'upload' );
				//	var_export( $link );
				//	$fieldset->addElement( array( 'name' => 'upload', 'label' => '<input type=\'button\' value=\'Select File\' />', 'placeholder' => 'Click here to select a layout template file...', 'onClick' => 'ayoola.spotLight.showLinkInIFrame( \'' . $link . '\' );', 'type' => 'InputText', 'value' => @$values['upload'] ) );
				//	$fieldset->addRequirement( 'upload', array( 'IsFile' => array( 'base_directory' => APPLICATION_DIR, 'allowed_extensions' => array( 'gz', 'zip' ) ) ) );
				//	$name = ( $fieldset->hashElementName ? Ayoola_Form::hashElementName( 'upload' ) : 'upload' );
			//		$link = '/ayoola/thirdparty/Filemanager/index.php?field_name=' . $name;
		//			$fieldset->addElement( array( 'name' => 'upload', 'label' => '<input onClick="ayoola.spotLight.showLinkInIFrame( \'' . $link . '\' ); return true;" type=\'button\' value="Browse Site..." /> <input onClick="ayoola.image.formElement = this; ayoola.image.fieldNameValue = \'url\'; ayoola.image.fieldName = \'' . $name . '\'; ayoola.image.clickBrowseButton( { accept: \'\' } );" type=\'button\' value="Browse Device..." />', 'placeholder' => 'e.g. http://example.com/path/to/file.zip', 'type' => 'InputText', 'value' => @$values['upload'] ) );
				//	$fieldset->addElement( array( 'name' => 'upload', 'label' => 'Upload Layout Template', 'onClick' => 'ayoola.image.formElement = this; ayoola.image.fieldNameValue = \'url\'; ayoola.image.fieldName = \'' . $name . '\'; ayoola.image.clickBrowseButton( { accept: \'\' } );', 'placeholder' => 'e.g. http://example.com/path/to/file.zip', 'type' => 'InputText', 'value' => @$values['upload'] ) );
				//	$fieldset->addRequirement( 'upload', array( 'IsFile' => array( 'base_directory' => Ayoola_Doc::getDocumentsDirectory() , 'allowed_extensions' => array( 'gz', 'zip' ) ) ) );
					$fieldset->addElement( array( 'name' => 'upload', 'label' => 'Layout Template File (.zip, .tar or .tar.gz archives)', 'data-allow_base64' => true, 'data-document_type' => 'application', 'type' => 'Document', 'value' => @$values['upload'] ) );
				break; 
				default:
			//	case 'plain_text':
			//	var_export( $previousContent );
					$fieldset->addElement( array( 'name' => 'plain_text', 'label' => 'Enter the html for the layout template', 'rows' => 20, 'placeholder' => 'Enter the template text here...', 'type' => 'Textarea', 'value' => $previousContent ) );
					$fieldset->addRequirement( 'plain_text', array( 'WordCount' => array( 10,50000 ) ) );
					$fieldset->addFilter( self::VALUE_CONTENT, array( 'DefiniteValue' => $this->getGlobalValue( 'plain_text' ) ) );
				break;
			}
		}
		while( false );
		$fieldset->addFilters( array( 'Trim' => null ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
