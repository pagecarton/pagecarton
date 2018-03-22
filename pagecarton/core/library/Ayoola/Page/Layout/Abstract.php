<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt 
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
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
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
	protected static $_accessLevel = array( 99, 98 );
 	
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
     * 
     * 
     * var string
     */
	protected static $_placeholders = array( '#PC_OBJECT_URL_PREFIX', '#PC_URL_PREFIX', 'PC_URL_PREFIX', 'PC_PLACEHOLDER_FOR_ORG_LOGO', '/../', 'PC_URL_SUFFIX' );
	
    /**
     * 
     * 
     * var string
     */
	protected static $_placeholderValues = array( "' . Ayoola_Application::getUrlPrefix() . '", "<?php echo Ayoola_Application::getUrlPrefix(); ?>", "<?php echo Ayoola_Application::getUrlPrefix(); ?>", '<?php echo Ayoola_Doc::getLogo(); ?>', '/', '<?php echo Ayoola_Application::getUrlSuffix(); ?>' );		
	
    /**
     * 
     * 
     * var string
     */
	protected static $_placeholderValues2;		
	
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
	protected function updateFile( array $values = null )
    {
		
		if( ! $values )
		{
			if( ! $values = $this->getForm()->getValues() ){ return false; }
		}
	//	if( ! @$values[self::VALUE_CONTENT] ){ return false; }
		try
		{
			if( $identifierData = $this->getIdentifierData() )
			{
				$values = $values + $identifierData;
			//	return false; 
			}
		}
		catch( Exception $e )
		{
			null;
		}
		require_once 'Ayoola/Doc.php';
		Ayoola_Doc::createDirectory( dirname( $this->getMyFilename() ) );

/* 		//	update screenshot
		$screenshot = ayoola_doc::getdocumentsdirectory() . $values['screenshot'];
		if( is_file( $screenshot ) )
		{
			$screenshotfile = dirname( $this->getmyfilename() ) . '/screenshot.jpg';
			file_put_contents( $screenshotfile, file_get_contents( $screenshot ) );
		}
 */		@$content = $values['plain_text'] ? : $values['wysiwyg'];
	//	var_export( $content );
		if( ! $content ){ return false; }
		
		//	Save raw content
		file_put_contents( $this->getMyFilename() . 'raw', $content );

		
		//	Sanitize
		$sectionsToSave = null;

		$content = self::sanitizeTemplateFile( $content, $values, $sectionsToSave );
		
		//	 use alternate files to determine that this is a theme so that we can retain only common data
		$alternateFile = null;
		$dir = dirname( $this->getMyFilename() );
		$files = Ayoola_Doc::getFiles( $dir );
	//	var_export( $files );
		foreach( $files as $each )
		{
			$ext = array_pop( explode( '.', $each ) );
			switch( $ext )
			{
				case 'html':
					switch( basename( $each ) )
					{
						case 'index.html':
						case 'home.html':

						break;
						default:
							$alternateFile = $each;
							break 3;
						break;
					}
				break;
			}
		}
	//	var_export( $files );
	//	var_export( $alternateFile );
	//	exit();
		$alternateNavigation = null;
		$altNavigationPlaceholder = null;
	//			var_export( $alternateFile );
		$navTag = '</nav>';
		if( ! stripos( $content, $navTag ) )
		{
			$navTag = '</ul>';
		}
		if( $alternateFile )
		{
			$alternateFile = file_get_contents( $alternateFile );
			$alternateFile = self::sanitizeTemplateFile( $alternateFile, $values );
		//	pick navigation from another page in case the navigation of home page contains other content.			

			$matches = Ayoola_Page_Layout_Abstract::getThemeFilePlaceholders( $alternateFile );
			foreach( $matches as $count => $match )
			{
				preg_match( '/{@@@' . $match . '([\S\s]*)' . $match . '@@@}/i', $alternateFile, $placeholder );
			//	var_export( $placeholder[1] );
				if( empty( $alternateNavigation ) && stripos( $placeholder[1], $navTag ) )
				{
					//	check navigation
					$alternateNavigation = $placeholder[1];
					$altNavigationPlaceholder = $match;
					break;
				}
			}
		}
		$matches = Ayoola_Page_Layout_Abstract::getThemeFilePlaceholders( $content );
//		preg_match_all( "/@@@([0-9A-Za-z_]+)@@@/", $content, $matches );
		
//		preg_match_all( '/{@@@\w([\S\s]*)\w@@@}/i', $content, $matches );
	// 	var_export( $matches );

		foreach( $matches as $count => $match )
		{
			preg_match( '/{@@@' . $match . '([\S\s]*)' . $match . '@@@}/i', $content, $placeholder );
		//	var_export( $match );
	//		var_export( $placeholder[1] );
	//		var_export( $alternateFile );
	//		var_export( $alternateNavigation );
	//		var_export( stripos( $placeholder[1], '©' ) );
			if( empty( $navigationReplaced ) && $alternateNavigation && stripos( $placeholder[1], $navTag ) )
			{
	//			var_export( strlen( $alternateNavigation ) );
	//			var_export( $altNavigationPlaceholder );
		//		var_export( $match );
	//			var_export( strlen( $placeholder[1] ) );
		//		var_export( $placeholder[1] );
				if(  ( strlen( $alternateNavigation ) + 20 ) < strlen( $placeholder[1] ) )
				{
				//	$placeholder[1] = $alternateNavigation;
					//	put the two navigation there.
					$before = '{@@@' . $match . $placeholder[1] . $match . '@@@}';
					$altMatch = $match . '_alt';
					$after = '
								{@@@' . $match . $placeholder[1] . $match . '@@@}
								@@@' . $altMatch . '@@@
								{@@@' . $altMatch . $alternateNavigation . $altMatch . '@@@}
								
							';

					$content = str_ireplace( $before, $after, $content );
					$navigationReplaced = true;
				//	$content = str_ireplace( '@@@' . $match . '@@@', '@@@' . $altNavigationPlaceholder . '@@@', $content );
				}
				//	we have alternate navigation
				//	check navigation
			}
			if( empty( $realNavigationDone ) && stripos( $placeholder[1], $navTag ) )
			{
				$isRealNavigation = true;
				$realNavigationDone = true;
			}

			// Excempt the header content, and the nav and footer
			if( $placeholder[1] && ! stripos( $alternateFile, $placeholder[1] ) && ( empty( $isRealNavigation ) ) && ! stripos( $placeholder[1], '©' ) && ! stripos( $placeholder[1], '&copy' ) && ! stripos( $placeholder[1], '&amp;copy' ) )
			{
		//		var_export( stripos( $placeholder[1], '©' ) );
		//		var_export( $match );
				//	remove sections that are not common to all files
				$content = preg_replace('/{@@@' . $match . '([\S\s]*)' . $match . '@@@}/i', '', $content );
			//	var_export( $match );
			}
			$isRealNavigation = false;     
		}

		file_put_contents( $this->getMyFilename() . 'sections', '<?php return ' . var_export( $sectionsToSave, true ) . ';' );
	//	var_export( $sectionsToSave );
		
		file_put_contents( $this->getMyFilename(), $content );
		
		//	update theme files
		static::refreshThemePage( $values['layout_name'] );
	//	var_export( $values['layout_name'] );
	//	var_export( $class->getValues() );
	//	var_export( $class->view() );
		return true;
    } 

    /**
	 * Sets the _filename
	 *
     */
    public static function refreshThemePage( $themeName )
	{
		$class = new Ayoola_Page_Editor_Layout( array( 'no_init' => true ) );
		$class->setPageInfo( array( 'url' => '/layout/' . $themeName . '/template' ) );
		$class->updateLayoutOnEveryLoad = true;
		$class->setPagePaths();
		$class->setValues();
		
		$class->init(); // invoke the template update for this page.
	}

    /**
	 * Sets the _filename
	 *
     */
    public function setFilename( $data = null )
	{
	//	if( ! $values = $this->getForm()->getValues() )
		{
			
		}
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
    public static function getThemeFilePlaceholders( $content )
	{
		preg_match_all( "/@@@([0-9A-Za-z-_]+)@@@/", $content, $placeholdersInPageThemeFile );
		return @$placeholdersInPageThemeFile[1];
	}

    /**
	 * 
	 *
     */
    public static function sanitizeTemplateFile( $content, $values, & $sectionsToSave = array() )
	{
		//	Strip the php content from it.
		$content = preg_replace( '#<\?.*?(\?>|$)#s', '', $content );
	
		//	This somehow make it impossible to work with other template file content. Should we retain it?
	//	$previousContent = preg_replace( '#/layout/[a-zA-Z0-9-_]*/#', '', $previousContent );
		
		//	This was also added automatically
		$content = str_ireplace( array( '/layout/' . $values['layout_name'] . '/', '/layout//' ), '', $content );

	
	//	http://stackoverflow.com/questions/2869844/regex-to-replace-relative-link-with-root-relative-link
/* 		$linkForPrefix = "<?php echo Ayoola_Application::getUrlPrefix(); ?>";
 *///		$linkForPrefix = preg_quote( $linkForPrefix, '$' );
		//	workaround for the bug causing space to be replaced with 	%5Cs in preg_replace
/*		$placeholders = array( '#PC_OBJECT_URL_PREFIX', '#PC_URL_PREFIX', 'PC_URL_PREFIX', 'PC_PLACEHOLDER_FOR_ORG_LOGO', '/../' );
 		$placeholderValues = array( "' . Ayoola_Application::getUrlPrefix() . '", "<?php echo Ayoola_Application::getUrlPrefix(); ?>", "<?php echo Ayoola_Application::getUrlPrefix(); ?>", '<?php echo Ayoola_Doc::getLogo(); ?>', '/' );
*/ 	//	$content = str_ireplace( $placeholders, $placeholderValues, $content );
		$content = preg_replace('#(href|src)[\s]*=[\s]*["\']([^/\#\{][^:"]*)(?:["\'\.])#', '$1="PC_URL_PREFIX/layout/' . $values['layout_name'] . '/$2"', $content ); 
		
	//	var_export( $content );
		   
		//	Fix url();  
		$content = preg_replace('#url\(([^/\#\{][^:"\(\);]*)\)#', 'url(PC_URL_PREFIX/layout/' . $values['layout_name'] . '/$1)', $content );

		// Instantiate the object
		$xml = new Ayoola_Xml();
		
		// Build the DOM from the input (X)HTML snippet
		@$xml->loadHTML( $content );

		//	add ayoola layout header
		
		//	Append css and other things to the head
		$head = $xml->getElementsByTagName( 'head' );
		foreach( $head as $each )
		{
			$each->insertBefore( $xml->createCDATASection( "<?php include_once( LAYOUT_PATH . DS . 'htmlHeader' . TPL ) ?>\r\n" ), $each->firstChild );
		}

		//	remove title tags
		$title = $xml->getElementsByTagName( 'title' );
		foreach( $title as $each )
		{
			$each->parentNode->removeChild( $each );
		}
		$body = $xml->getElementsByTagName( 'body' );
		$bodyChildren = array();
		$allSections = false;
		$firstElement = false;

		//	Check if allsection is inserted already
		$xpath = new DOMXpath($xml);

		$pAllSections = $xpath->query('//section[@data-pc-all-sections="1"]');
		$nodes = $xpath->query('//section[@data-pc-all-sections="1"]');
	//	var_export( $pAllSections->length );
	//	var_export( $nodes->length );
		if( $pAllSections->length ) 
		{ 
			$allSections = true;
		} 
		
		$createSections = function( $eachSection ) use ( &$bodyChildren, &$allSections, &$firstElement )
		{
			$bodyChildren = array();
		//	$allSections = false;
			foreach( $eachSection->childNodes as $eachDiv )
			{
			//	$i++;
				switch( @strtolower( $eachDiv->tagName ) )
				{
					case "footer":
					case "header":
					case "section":
						if( $eachDiv->getAttribute( "data-pc-all-sections" ) )   
						{
							//	find out if default sections was inserted
							$allSections = true;
							continue;
						}
					case "div":
						@++$countDiv;
						if( ! $eachDiv->getAttribute( "data-pc-section-name" ) && ! $eachDiv->getAttribute( "id" ) )   
						{
							@$eachDiv->setAttribute( "data-pc-section-autonamed", $countDiv );
						}
						@$eachDiv->setAttribute( "data-pc-section-name", $eachDiv->getAttribute( "data-pc-section-name" ) ? : ( $eachDiv->getAttribute( "id" ) ? : ( "pc-body-" . $countDiv ) ) );       
						$bodyChildren[] = $eachDiv;

						//	Determine element after which to put all default sections						
						@$firstElement = $firstElement ? : $eachDiv;   
						$hasNav = $eachDiv->getElementsByTagName( 'nav' );
						if( $hasNav->length )
						{
						//	var_export( $eachDiv->getElementsByTagName( 'nav' ) );
							$firstElement = $eachDiv;
						}   
					//	$lastElement = $eachDiv;
					break;
				}
				//	var_export( $i );
				//	var_export( $each->childNodes->length );
				//	var_export( " "  );
					//	var_export( $firstElement );
					//	var_export( $allSections );
			}
			return $bodyChildren;
		};
		foreach( $body as $eachSection )   
		{
			//	give body a name in case we are trying to edit the whoe page
			$eachSection->setAttribute( 'data-pc-section-name', 'body' );
			$i = 5;
			
			while( --$i > 0 )
			{
				//	reset first element
				$firstElement = false;
				$bodyChildren = $createSections( $eachSection );
			//	var_export(  count( $bodyChildren ) );
				if( count( $bodyChildren ) > 1 )
				{
					break;
				}
				else
				{
					//	Avoid a single editable section
					$eachSection = array_pop( $bodyChildren );
				}
			}
			if( @$allSections == false && @$firstElement )
			{
				$newElement = $xml->createElement( "section" );
				$newElement->setAttribute( "data-pc-all-sections", "1" );
				$newElement->setAttribute( "class", "container" );
				try
				{
					$eachSection->insertBefore( $newElement, $firstElement->nextSibling );    
				}
				catch( Exception $e )
				{
					$eachSection->appendChild( $newElement );    
				}
			}
			if( $bodyChildren )
			{
			//	$section = $bodyChildren;
			}
		}
		
		//	Auto build section
		if( ! empty( $values['layout_options'] ) && in_array( 'auto_section', $values['layout_options'] ) )
		{
			$section = $xml->getElementsByTagName( 'section' );
			$footer = $xml->getElementsByTagName( 'footer' );
			$header = $xml->getElementsByTagName( 'header' );
			if( ! $section->length && ! $footer->length && ! $header->length  && ! $bodyChildren && ! preg_match( "/@@@([0-9A-Za-z_]+)@@@/", $content ) )
			{
				//	if we don't have a section and we have no placeholder.
				//	Try to edit the whole page
				$section = $body;
			}
			$sectionsToSave = array();
			
			$editableSectionCounter = 0;  
			$allSectionsCounter = null;
			$sectionsToUse = array( $section, $footer, $header, $bodyChildren );
			foreach( $sectionsToUse as $key => $section )
			{
				if( $sectionsToUse[$key] === $bodyChildren )
				{
					if( $editableSectionCounter )
					{
						//	if no section is found, body is section
						break;
					}
				}
			//	$sectionsToUse[$key]
				foreach( $section as $each )
				{
					//	now doing this on the fly in the Ayoola_Page_Editor_Layout 
					//	Better we do it here. We will create db fieldname for sections for easy access in Ayoola_Page_Editor_Layout
					$name = str_ireplace( array( ' ', '-' ), '_', ( $each->getAttribute( 'data-pc-section-name' ) ? : $each->getAttribute( 'id' ) ) );
				
					if( $each->getAttribute( 'data-pc-section-autonamed' ) && $sectionsToUse[$key] !== $bodyChildren )
					{
						$name = false;
					}
					
				//	var_export( $name );
				//	var_export( $each->getAttribute( 'data-pc-all-sections' ) );
					if( $each->getAttribute( 'data-pc-all-sections' ) )   
					{
						//	can't add this here because then it hides whole section'
				//		$each->setAttribute( 'class', $each->getAttribute( 'class' ) . ' pc_page_object_specific_item' );
						$each->parentNode->insertBefore( $xml->createCDATASection( '@@@' . $allSectionsCounter . 'oneness@@@' ), $each );
						$each->parentNode->insertBefore( $xml->createCDATASection( '@@@' . $allSectionsCounter . 'lastoneness@@@' ), $each->nextSibling );
						//	put bootstrap sections in here
							$each->appendChild( 
								$xml->createCDATASection
								( 
									'	
									<div class="">
										<div class="row">
											<div class="pc_page_layout_grid col-md-12 12u">
												@@@' . $allSectionsCounter . 'middlebar@@@
										   </div>
										</div> 
										<div class="row">
											<div class="pc_page_layout_grid col-sm-6 6u 6u$(medium) 12u$(xsmall) 12u$(mobile)">
												@@@' . $allSectionsCounter . 'twosome1@@@
											</div>
											<div class="pc_page_layout_grid col-sm-6 6u 6u$(medium) 12u$(xsmall) 12u$(mobile)">
												@@@' . $allSectionsCounter . 'twosome2@@@
											</div> 
										</div> 
										<div class="row">
											<div class="pc_page_layout_grid col-sm-4 4u 12u$(medium) 12u$(xsmall) 12u$(mobile)"> 
												@@@' . $allSectionsCounter . 'threesome1@@@
										   </div>
											<div class="pc_page_layout_grid col-sm-4 4u 12u$(medium) 12u$(xsmall) 12u$(mobile)">
												@@@' . $allSectionsCounter . 'threesome2@@@
											</div> 
											<div class="pc_page_layout_grid col-sm-4 4u 12u$(medium) 12u$(xsmall) 12u$(mobile)">
												@@@' . $allSectionsCounter . 'threesome3@@@
											</div> 
										</div> 
										<div class="row">
											<div class="pc_page_layout_grid col-sm-3 3u 6u$(medium) 12u$(xsmall) 12u$(mobile)">
												@@@' . $allSectionsCounter . 'foursome1@@@
										   </div>
											<div class="pc_page_layout_grid col-sm-3 3u 6u$(medium) 12u$(xsmall) 12u$(mobile)">
												@@@' . $allSectionsCounter . 'foursome2@@@
											</div> 
											<div class="pc_page_layout_grid col-sm-3 3u 6u(medium) 12u$(xsmall) 12u$(mobile)">
												@@@' . $allSectionsCounter . 'foursome3@@@
											</div> 
											<div class="pc_page_layout_grid col-sm-3 3u 6u$(medium) 12u$(xsmall) 12u$(mobile)">    
												@@@' . $allSectionsCounter . 'foursome4@@@
											</div> 
										</div> 
										<div class="row">
											<div class="pc_page_layout_grid col-sm-4 4u 12u$(medium) 12u$(xsmall) 12u$(mobile)">
												@@@' . $allSectionsCounter . 'leftbar@@@
										   </div>
											<div class="pc_page_layout_grid col-sm-8 8u 12u$(medium) 12u$(xsmall) 12u$(mobile)">
												@@@' . $allSectionsCounter . 'leftbarright@@@
											</div> 
										</div> 
										<div class="row">
											<div class="pc_page_layout_grid col-sm-8 8u 12u$(medium) 12u$(xsmall) 12u$(mobile)">
												@@@' . $allSectionsCounter . 'rightbarleft@@@
											</div> 
											<div class="pc_page_layout_grid col-sm-4 4u 12u$(medium) 12u$(xsmall) 12u$(mobile)">
												@@@' . $allSectionsCounter . 'rightbar@@@
											</div>   
										</div> 
									</div> 
								' 
							 
								) 
							 );  
							// var_export( $allSectionsCounter );
							$allSectionsCounter++;
							continue;
					}  
				//	var_export( $name );
					if( $each->getAttribute( 'data-pc-object-name' ) )
					{						    
						
						//	Make all the parameters advanced parameters to allow them editable
						$advancedParametersToUse = array();
					//	var_expor
				//		unset( $eachParameters['object_name'] );
				
						//	this ensures we have an object interior thats built with parameter 
						//	simulating the way they would appear LIVE
						$eachParameters = ( json_decode( $each->getAttribute( 'data-pc-object-parameters' ), true ) ? : array() );
						$eachParameters['object_interior'] = true;
						$objectName = $each->getAttribute( 'data-pc-object-name' );   
						
						//	remove this so they don't contaminate markup
						$each->removeAttribute( 'data-pc-object-parameters' );
						$each->removeAttribute( 'data-pc-object-name' );

					//	var_export( $eachParameters );
						
						//	this needs to be preserved.
				//		$each->removeAttribute( 'data-pc-markup_template_no_data' );
						
						$eachParameters['markup_template'] = $xml->saveXml( $each, LIBXML_NOEMPTYTAG );
						
						//	Some placeholders were causing issues so lefts hardcode them here
						if( $name )
						{
						//	$placeholderValues2 = array( Ayoola_Application::getUrlPrefix(), Ayoola_Application::getUrlPrefix(), Ayoola_Application::getUrlPrefix(), Ayoola_Doc::getLogo() );
							$eachParameters['markup_template'] = str_ireplace( self::getPlaceholders(), static::getPlaceholderValues2(), $eachParameters['markup_template'] );
						}
						else
						{
						//	$placeholderValues = array( "' . Ayoola_Application::getUrlPrefix() . '", "' . Ayoola_Application::getUrlPrefix() . '", "' . Ayoola_Doc::getLogo() . '" );
					//		$placeholderValues2 = array( "", "", "" );
						}
						if( $each->getAttribute( 'data-pc-markup_template_no_data' ) )
						{
							$eachParameters['markup_template_no_data'] = $eachParameters['markup_template'];  
						}
						if( ! $name )
						{
							//	if no section is defined, we should look at embedding the object
						//	$placeholderValues = array( "' . Ayoola_Application::getUrlPrefix() . '", "' . Ayoola_Application::getUrlPrefix() . '", "' . Ayoola_Doc::getLogo() . '" );
							$each->parentNode->replaceChild( 
								$xml->createCDATASection( 
								 '<?php echo Ayoola_Abstract_Viewable::viewObject( \'' . $objectName . '\', ' . var_export( $eachParameters, true ) . ' ); ?>' ), $each ); 
							
						}
						else
						{
							foreach( $eachParameters as $eachKey => $eachValue )
							{
								
								$advancedParametersToUse['advanced_parameter_name'][] = $eachKey;
								$advancedParametersToUse['advanced_parameter_value'][] = $eachValue;
							}
							unset( $eachParameters );
							$eachParameters['advanced_parameters'] = http_build_query( $advancedParametersToUse );   
							$eachParameters['object_name'] = $objectName;   
					//	var_export( $advancedParametersToUse );
						//	reset this so that we only retain advanced_parameters.
							$each->parentNode->replaceChild( 
								$xml->createCDATASection( 
								 '<!-- section: ' . $name . '; object_name:' . $objectName . '; -->' ), $each ); 
							$sectionsToSave[$name][] = $eachParameters;
						}
						continue;
					}
					if( ! $name || $each->getAttribute( 'data-pc-section-skip' ) || $each->getAttribute( 'data-pc-section-created' )  )
					{
						continue;
					}
					
				//	var_export( $each->tagName );
				
					//	ensure we don't have nested editable regions
					foreach( array( $each->getElementsByTagName( 'section' ), $each->getElementsByTagName( 'header' ), $each->getElementsByTagName( 'footer' ) ) as $eachSectionGroup )
					{
						foreach( $eachSectionGroup as $eachSection )
						{
							$eachSection->setAttribute( 'data-pc-section-skip', '1' );
						}
					}
					
					//	ensure we don't have navigations integrated in an editable region
					foreach( $each->getElementsByTagName( 'nav' ) as $eachNav )
					{
					//	foreach( $eachSectionGroup as $eachSection )
						{
							$eachNav->setAttribute( 'data-pc-menu-ignore', '1' );
						}
					}
				
					if( $each->nextSibling )
					{
						$each->parentNode->insertBefore( $xml->createCDATASection( "\r\n{$name}@@@}\r\n" ), $each->nextSibling );
					}
					else
					{
						$each->parentNode->appendChild( $xml->createCDATASection( "\r\n{$name}@@@}\r\n" ) );
					}
					$each->parentNode->insertBefore( $xml->createCDATASection( "\r\n@@@{$name}@@@\r\n" ), $each );   
					$each->parentNode->insertBefore( $xml->createCDATASection( "\r\n{@@@{$name}\r\n" ), $each );
			
				//	$each->setAttribute( 'data-pc-section-created', '1' );
					$each->removeAttribute( 'data-pc-section-autonamed' );
					$each->removeAttribute( 'data-pc-section-created' );
					$each->removeAttribute( 'data-pc-section-name' );
					$editableSectionCounter++;
				}
			}
			
		}
		
		//	load js after the body
		foreach( $body as $each )   
		{
			$each->appendChild( $xml->createCDATASection( "<?php include_once( LAYOUT_PATH . DS . 'footerJs' . TPL ) ?>" ) );
		}
		
		//	build links
		$links = array();
		if( ! empty( $values['layout_options'] ) && in_array( 'auto_menu', $values['layout_options'] ) )   
		{
			$links = $xml->getElementsByTagName( 'a' );
		}
	//		var_export( $links );  		
		foreach( $links as $navCount => $each )
		{
			$url = $each->getAttribute( 'href' );
		//	var_export( $url );
			if( ! self::isThemePage( $url, $values['layout_name'] ) )
			{
				continue;
			}

			//	change links with /page.html to /page
			$url = self::themePageToUrl( $url, $values['layout_name'] );
		//		var_export( $url );
			$each->setAttribute( 'href', 'PC_URL_PREFIX' . $url . '' );

		}
		
		//	Build navigation system
		$nav = array();
		if( ! empty( $values['layout_options'] ) && in_array( 'auto_menu', $values['layout_options'] ) )   
		{
			$nav = $xml->getElementsByTagName( 'nav' );
		}
		foreach( $nav as $navCount => $each )
		{
			//	The name must not have spaces   
			$filter = new Ayoola_Filter_Name();
			$menuName = $filter->filter( ( ( $each->getAttribute( 'data-pc-menu-name' ) ? : $each->getAttribute( 'name' ) ) ? : $each->getAttribute( 'id' ) ) ? : $each->getAttribute( 'class' ) );
			if( ! $menuName || $each->getAttribute( 'data-pc-menu-ignore' ) )   
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
					
					$ulParent->appendChild( $xml->createCDATASection( "<?php echo Ayoola_Menu_Demo::viewInLine( array( 'option' => '{$menuName}', 'li-active-class' => '{$activeClass}', 'ul-class' => '{$ulClass}', 'ul-id' => '{$ulId}', )  ); ?>" ) ); 
				}
			}
			else
			{
			
			//	$each->innerHTML = '';
				$activeClass = $each->getAttribute( 'data-pc-menu-li-active-class' ) ? : 'active';
				$ulClass = $each->getAttribute( 'data-pc-menu-ul-class' ) ? : '';
				$each->appendChild( $xml->createCDATASection( "<?php echo Ayoola_Menu_Demo::viewInLine( array( 'option' => '{$menuName}', 'li-active-class' => '{$activeClass}', 'ul-class' => '{$ulClass}', )  ); ?>" ) ); 
			}
		}
		//	Build logo
		$img = $xml->getElementsByTagName( 'img' );
		foreach( $img as $each )
		{
			//	clear interior first
			switch( strtolower( $each->getAttribute( 'name' ) ) )
			{ 
				case 'pc-logo':
				case 'pc_logo':
					$each->setAttribute( 'src', "PC_PLACEHOLDER_FOR_ORG_LOGO" );	
				break;
				//	This won't work in dom
/* 				$each->setAttribute( 'src', "<?php echo Ayoola_Doc::getLogo(); ?>" );
 */			}
		}
		
		// empty anchor not doing well in CKEDITOR
//		$anchor = $xml->getElementsByTagName( 'a' );
	//	foreach( $anchor as $each )
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
	//	$icons = $xml->getElementsByTagName( 'i' );
	//	foreach( $icons as $each )
		{
			//	check if empty
			
			//	http://stackoverflow.com/questions/29714291/removing-elements-with-no-children-dom-php
			$xpath = new DOMXpath($xml);

			$empty_anchors = $xpath->evaluate('//i[not(*) and not(text()[normalize-space()])]');
			$i = $empty_anchors->length - 1; 
			while ($i > -1) 
			{ 
				$element = $empty_anchors->item($i);  
				$element->nodeValue = '&nbsp;';       
				$i--;    
			} 
		}
		
		//	 empty icons in span of "skel" not doing well in CKEDITOR
	//	$icons = $xml->getElementsByTagName( 'span' );   
//		foreach( $icons as $each )
		{
			//	check if empty
			
			//	http://stackoverflow.com/questions/29714291/removing-elements-with-no-children-dom-php
			$xpath = new DOMXpath($xml);

			$empty_anchors = $xpath->evaluate('//span[not(*) and not(text()[normalize-space()])]');
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

		//	refresh docs on update
		/*$content = preg_replace( '|(#)?PC_URL_PREFIX([^\#\{][^:"]*)(")|', '<?php echo Ayoola_Doc::uriToDedicatedUrl( \'$2\' ); ?>$4', $content );*/ 
		
		//	workaround for the bug causing space to be replaced with 	%5Cs in preg_replace $placeholder
		$content = str_ireplace( self::getPlaceholders(), self::getPlaceholderValues(), $content );
	
		return $content;
	}
	
    /**
     * 
     * 
     */
	public static function isThemePage( $url, $themeName )
    {
		if( stripos( $url, '/layout/' . $themeName ) === false || stripos( $url, '.html' ) === false )
		{
			return false;
		}
		return true;
	}
	
    /**
     * 
     * 
     */
	public static function themePageToUrl( $url, $themeName )
    {
		//	change links with /page.html to /page
		if( strpos( $url, ':' ) !== false || strpos( $url, '//' ) !== false )
		{
			return $url;
		}
	//	var_export( $url );
		if( $url[0] == '#' )
		{
			return $url;
		}
		$url = array_pop( explode( '/layout/' . $themeName, $url ) );
		$url = '' . array_shift( explode( '.html', $url ) );
		$url = str_ireplace( array( '/index', '/home', '/.php', '/.php', '/index.php/', '//', ), '/', '/' . trim( $url, '/' ) );
//		var_export( $url );
		return $url ;
	}
	
    /**
     * 
     * 
     */
	public function getPreviousContent( $themeName )
    {
		//	use raw template as previous content where available
		@$previousContent = file_get_contents( $this->getMyFilename() . 'raw' );
		if( $previousContent )
		{

		}
		else
		{
			//	compatibility
			$previousContent = @file_get_contents( $this->getMyFilename() );
			
			
		//	var_export( $this->getMyFilename() );
			
			//	Strip the php content from it.
			$previousContent = preg_replace( '#<\?.*?(\?>|$)#s', '', $previousContent );
			
			//	This somehow make it impossible to work with other template file content. Should we retain it?
		//	$previousContent = preg_replace( '#/layout/[a-zA-Z0-9-_]*/#', '', $previousContent );
			
			//	This was also added automatically
			$previousContent = str_ireplace( array( '/layout/' . $themeName . '/', '/layout//' ), '', $previousContent );
		}
		return $previousContent;
	}
	
    /**
     * 
     * 
     */
	public static function getPlaceholders()
    {
		return static::$_placeholders;
	}
	
    /**
     * 
     * 
     */
	public static function getPlaceholderValues()
    {
		return static::$_placeholderValues;
	}
	
    /**
     * 
     * 
     */
	public static function getPlaceholderValues2()
    {
		if( ! static::$_placeholderValues2 )
		{
			static::$_placeholderValues2 = array( Ayoola_Application::getUrlPrefix(), Ayoola_Application::getUrlPrefix(), Ayoola_Application::getUrlPrefix(), Ayoola_Doc::getLogo(), '/', Ayoola_Application::getUrlSuffix() );
		}
		return static::$_placeholderValues2;
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
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => true ) );
		$form->setParameter( array( 'no_fieldset' => true ) );
		$fieldset = new Ayoola_Form_Element;
		$form->submitValue = 'Save' ;
		do 
		{
			$options = array( 'plain_text' => 'Paste Plain HTML Text' );
			if( is_null( $values ) )
			{
				//	If this a creator, we can upload
				$options += array( 'upload' => 'Upload ZIP/Tar Archive', );
			}
		//	if( @$_REQUEST['layout_type'] )
			$fieldset->addElement( array( 'name' => 'layout_type', 'label' => 'How are you adding a new theme', 'onClick' => 'this.form.submit();', 'type' => ( @$_REQUEST['layout_type'] || @$values ) ? 'Hidden' : 'Radio', 'value' => @$values['layout_type'] ? : 'plain_text' ), $options );
			$fieldset->addRequirement( 'layout_type', array( 'ArrayKeys' => $options ) );
		//	$previousContent = file_get_contents( $this->getMyFilename() );
		//	var_export( $_POST );
			//	Choose a layout type first  
			
				//	Labels are expected to be inside uploaded document.
				$fieldset->addElement( array( 'name' => 'layout_label', 'label' => 'Theme name', 'placeholder' => 'E.g. Super Theme', 'type' => 'InputText', 'value' => @$values['layout_label'] ? : $values['layout_name'] ) );
				$fieldset->addRequirement( 'layout_label', array( 'WordCount' => array( 2,100 ) ) );   

			//	Load this before we break so some image JS can run
			//	Screenshot
		//	var_export( $values['screenshot_url'] );
			$preview = @$values['screenshot_url'] ? : '' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_PhotoViewer/?layout_name=' . $values['layout_name'];
			$values ? $fieldset->addElement( array( 'name' => 'screenshot_url', 'label' => 'Theme screenshot', 'data-document_type' => 'image', 'type' => 'Document', 'data-previous-url' => $preview, 'value' => null, 'autocomplete' => 'off' ) ) : null;
//			$fieldset->addElement( array( 'name' => 'screenshot', 'label' => 'Theme screenshot', 'data-allow_base64' => true, 'data-document_type' => 'image', 'type' => 'Document', 'data-previous-url' => '' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Layout_PhotoViewer/?layout_name=' . $values['layout_name'], 'value' => null, 'autocomplete' => 'off' ) );
			if( ! $this->getGlobalValue( 'layout_type' ) )
			{
			//	break;
			}
			
			//	All types now need labels
		//	if( $this->getGlobalValue( 'layout_type' ) != 'upload' )  
			{
				
				//	We don't allow editing UNIQUE Keys
				//	Now doing it within the creator
			//	if( is_null( $values ) )
				{		
				//	$fieldset->addElement( array( 'name' => 'layout_name', 'type' => 'Hidden', 'value' => @$values['layout_name'] ) );
				//	$fieldset->addFilter( 'layout_name', array( 'DefiniteValue' => $this->getGlobalValue( 'layout_label' ) ,'Name' => null ) );
					
				}
			}
			
			//	use raw template as previous content where available
			@$previousContent = $this->getPreviousContent( $values['layout_name'] );
			
			//	var_export( $previousContent );
			$fieldset->addElement( array( 'name' => self::VALUE_CONTENT, 'type' => 'Hidden', 'value' => null ) );
			switch( $this->getGlobalValue( 'layout_type' ) ? : @$_REQUEST['layout_type'] )  
			{
				case 'wysiwyg':
					$fieldset->addElement( array( 'name' => 'wysiwyg', 'label' => 'Use this editor to design your layout template', 'rows' => 10, 'placeholder' => 'Enter the template text here...', 'type' => 'Textarea', 'value' => $previousContent ) );
			// 		$fieldset->addRequirement( 'wysiwyg', array( 'WordCount' => array( 10,50000 ) ) );
					$fieldset->addFilter( self::VALUE_CONTENT, array( 'DefiniteValue' => $this->getGlobalValue( 'wysiwyg' ) ) );  
				break;
				case 'upload':
			//		$fieldset->addElement( array( 'name' => 'upload', 'label' => 'Theme file (.zip, .tar or .tar.gz archives)', 'data-allow_base64' => true, 'data-document_type' => '', 'type' => 'Document', 'data-previous-url' => '' . Ayoola_Application::getUrlPrefix() . '/open-iconic/png/file-8x.png', 'value' => @$values['upload'] ) );
					$fieldset->addElement( array( 'name' => 'theme_url', 'label' => 'Theme file (.zip, .tar or .tar.gz archives)', 'data-document_type' => '', 'type' => 'Document', 'data-previous-url' => '' . Ayoola_Application::getUrlPrefix() . '/open-iconic/png/file-8x.png', 'value' => @$values['theme_url'] ) );  
				break; 
				default:
			//	case 'plain_text':
			//	var_export( $previousContent );
					$fieldset->addElement( array( 'name' => 'plain_text', 'label' => 'HTML Code', 'rows' => 10, 'style' => 'width:100%;', 'placeholder' => 'Enter the template text here...', 'type' => 'Textarea', 'value' => $previousContent ) );
					
					$filter = new Ayoola_Filter_HighlightCode();    
					
			//		$fieldset->addElement( array( 'name' => 'internal', 'label' => 'Internal HTML Used', 'readonly' => 'readonly', 'rows' => 10, 'style' => 'width:100%;', 'placeholder' => 'Enter the template text here...', 'type' => 'Html', 'value' => null ), array( 'html' => '<div style="max-height:200px;overflow:scroll;border: 2px solid #eee;">' . @$filter->filter( file_get_contents( $this->getMyFilename() . '' ), true ) . '</div>' ) );            
				//	$fieldset->addRequirement( 'plain_text', array( 'WordCount' => array( 10,50000 ) ) );
					$fieldset->addFilter( self::VALUE_CONTENT, array( 'DefiniteValue' => $this->getGlobalValue( 'plain_text' ) ) );
				break;  
			}
		}
		while( false );
		
		$options =  array( 
							'auto_section' => 'Build editable sections from "' . htmlentities( '<section>' ) . ' tag" automatically',  
							'auto_menu' => 'Integrate navigations automatically' 
							); 
							
	//	var_export( $values['layout_options'] );
		$defaultOptions = @$values['layout_options'];
		if( is_null( $values ) )
		{
			//	If this a creator, we can preset all options
			$defaultOptions = array_keys( $options );
		}
		$fieldset->addElement( array( 'name' => 'layout_options', 'label' => 'Theme Update Options', 'type' => 'Checkbox', 'value' => @$values['layout_options'] ? : $defaultOptions ), $options );

		$fieldset->addFilters( array( 'Trim' => null ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
