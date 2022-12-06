<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
 * @package    Ayoola_Page_Editor_Layout
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Editor_Layout extends Ayoola_Page_Editor_Abstract  
{

    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Layout Editor'; 

    /**
     * 
     * 
     * @var array 
     */
	protected static $_runLayout; 

    /**
     * 
     * 
     * @var array 
     */
	protected static $_defaultLayout; 

    /**
     * 
     * The objects available as viewable
     *
     * @var string Mark-Up to Display Viewable Objects List
     */
	protected $_viewableObjects = null;

    /**
     * Markup to display the layout editor
     * 
     * @var string 
     */
	protected $_layoutRepresentation = null;

    /**
     * Switch whether to update layout on page load
     * 
     * @var boolean 
     */
	protected $_updateLayoutOnEveryLoad = false;

    /**
     * Switch whether to update layout on page load. Duplicating this so I could make use of it in Ayoola_Page_Creator
     * 
     * @var boolean 
     */
	public $updateLayoutOnEveryLoad = false;

    /**
     * 
     * @var array 
     */
	protected static $_objectInfo;

    /**
     * Gets a page info and creates a page if its not available.
     *
     * @param void
     * @return array $pageInfo;
     */	
    public function sourcePage( $url = null )
    {
		if( $url )
		{ 
			//	source for this specific url
			$this->_dbWhereClause['url'] = $url;
        }
		
		if( ! $page = $this->getPageInfo() )
		{			
            //	Page not found, see if we can create a local copy of this page
			if( ! $this->_dbWhereClause['url'] )
			{
                if( $this->getParameter( 'url' ) )
                {
                    $this->_dbWhereClause['url'] = $this->getParameter( 'url' );
                }
				elseif( ! $url )
				{
				    //	This causes issue where page settings go to 404 page
                    //	If this is no URL id we can help
                    return false;       
				}
				else
				{
					$this->_dbWhereClause['url'] = $url;
				}
			}

			$parentContent = array();

			$pageToCopy = null;
			if( ! $page = Ayoola_Page::getInfo( $this->_dbWhereClause['url'] ) )
			{		
				//	Auto create now...
				$page = $this->_dbWhereClause; 
			}
 			else
			{
				$pageToCopy = $page;
				if( $defaultPage = Ayoola_Page::getInfo( '/' . trim( $this->_dbWhereClause['url'] . '/default', '/' ) ) )
				{  
					$pageToCopy = $defaultPage;

					//	this was making /default copy to child / in pc.com
					unset( $pageToCopy['layout_name'] );
				}				

				//	Copy the parent files
				//$themeName = Application_Settings_Abstract::getSettings( 'Page', 'default_layout' );
                $themeName = self::getDefaultLayout();
                $rPaths = self::getDefaultPageFilesToUse( $pageToCopy['url'], $themeName );
				foreach( $rPaths as $key => $eachX )
				{
					if( ! $each = Ayoola_Loader::checkFile( $eachX ) )
					{
						$this->setViewContent(  '' . self::__( '<p>A new page could not be created because: Some of the files could not be copied. Please go to <a rel="" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Creator/?url=' . $this->_dbWhereClause['url'] . '">Create a fresh page at ' . $this->_dbWhereClause['url'] . '.</a></p>' ) . '', true  );
					}
					else
					{
						$parentContent[$key] = file_get_contents( $each );
					}
				}
				$pageToCopy['url'] = $page['url'];
				$page = $pageToCopy;
			}

			//	Create a new page using the values of the parent application
			//	Ayoola_Page_Page
			//	make sure this is a system file.
			$pageToCopy = array_merge( $pageToCopy ? : $page, array( 'system' => 1 ) );
		
            $class = new Ayoola_Page_Creator( array( 'no_init' => true, 'fake_values' => $pageToCopy ) );
			$class->fakeValues = $pageToCopy;
            $class->init();
            
			if( ! $class->getForm()->getValues() || $class->getForm()->getBadnews() )
			{
				$this->setViewContent(  '' . self::__( '<p>A new page could not be created because: ' . array_shift( $class->getForm()->getBadnews() ) . '. Please go to <a rel="" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Creator/?url=' . $this->_dbWhereClause['url'] . '">Create a fresh page at ' . $this->_dbWhereClause['url'] . '.</a></p>' ) . '', true  );
				return false;
			}

            //	save parent template into the new page
			if( $parentContent )
			{
				foreach( Ayoola_Page::getPagePaths( $page['url'] ) as $key => $each )
				{
					if( $parentContent[$key] )
					{
						$savePath = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $each;

						@Ayoola_Doc::createDirectory( dirname( $savePath ) );
						Ayoola_File::putContents( $savePath, @$parentContent[$key] ); 
						@Ayoola_Doc::createDirectory( dirname( $savePath ) );

					}
				}

				// sanitize so it could refresh with latest template

				//	create this page if not available.

			}
			//	Long journey
			//	reload the page settings to get settings for new page.
			$this->setPageInfo();
            $page = $this->getPageInfo();
		}
		return $page;

	}

    /**
     * 
     *
     * @param string content
     * @return boolean
     */	
    public function showLayout()
    {
    
        //	Create TMP file for the template
        $path = $this->getPagePaths();
        $tmp = $path['template'] . '.tmp';
        $itsMyPage = true;
        if( stripos( $tmp, Ayoola_Application::getDomainSettings( APPLICATION_DIR ) ) !== false );
        {
            $itsMyPage = false;
        }       
        if( ! $itsMyPage || ! is_dir( dirname( $tmp ) ) )
        {
            $tmp = tempnam( CACHE_DIR, __CLASS__ );           	
        }
        $content = $this->_layoutRepresentation ? : $this->getLayoutRepresentation();
        if( ! $this->isSaveMode() )
        {
            Application_Javascript::addFile( '/js/objects/lukeBreuerDragNDrop.js' );
            Application_Javascript::addFile( '/js/objects/webReferenceDragNDrop.js' );
            Application_Javascript::addFile( '/js/objects/dragNDrop.js' );
            Application_Javascript::addCode( $this->javascript() );
        } 
        if( $content && ! $this->noLayoutView && ! $this->isSaveMode() )
        {
            Ayoola_File::putContents( $tmp, $content );
            include_once $tmp;
            unlink( $tmp );
        }
        if( ! $this->_updateLayoutOnEveryLoad && ! $this->updateLayoutOnEveryLoad ){ exit(); }         
    }

    /**
     * Performs the layout process
     *
     * @param void
     * @return boolean
     */	
    public function init()
    {

		//	don't cause infinite loop
		//	makes sure /object and /tools/classplayer works fine
		// those classes were embeding Ayoola_Object_Play
		Ayoola_Object_Embed::ignoreClass( __CLASS__ );
		Ayoola_Object_Embed::ignoreClass( 'Ayoola_Object_Play' );

		// bad domains will not have app path
		if( ! Ayoola_Application::getDomainSettings( APPLICATION_PATH ) )
		{
			return false;
		}


		//	Allows the htmlHeader to get the correct layout name to use for <base>
        $url = @$_REQUEST['url'];
        if( $url && stripos( $url, '/layout/' ) !== 0 && ! $this->isSaveMode())
        {
            //  if there is theme version, always ASK which to edit
            if( ! $this->getPageEditorLayoutName() && empty( $_REQUEST['pc_edit_main_site_page'] ) )
            {
                // check if theres a page specific theme file
                $pageThemeFileUrl = $url;
                if( $pageThemeFileUrl == '/' )
                {
                    $pageThemeFileUrl = '/index';
                }
                $themeDataFile = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . 'documents/layout/' . self::getDefaultLayout() . '/theme' . $pageThemeFileUrl . '/include';

                if( file_exists( $themeDataFile ) )
                {
                    $query = '?' . http_build_query( $_GET );
                    $this->setViewContent(  '' . self::__( '<h3>There are multiple versions of this page that is editable</h3>' ) . ''  );
                    $this->setViewContent(  '
                                    <a class="pc-btn" href="' . $query . '&pc_edit_main_site_page=1">' . sprintf( self::__( 'Edit Main Site %s Page' ), '' . $url . ''  ) . '</a>
                                    <a class="pc-btn" href="' . $query . '&pc_page_editor_layout_name=' . self::getDefaultLayout() . '">' . sprintf( self::__( 'Edit Default Theme %s Page' ), '' . $url . ''  ) . '</a>
                                    ' );
                    return false;
                }

            }
        }
		$ux = $url ? : $this->getParameter( 'url' );

		$pageX = Ayoola_Page::getInfo( $ux );
		//var_export( $ux ); 
		if( 
			! empty( $this->_parameter['theme_variant'] ) 
			|| ! empty( $this->_parameter['preserve_pageinfo'] )
		)
		{	
			$page = $pageX ? : array( 'url' => $ux );
		}
		else
		{
			$page = $this->sourcePage( @$pageX['url'] );
		}


 		if( ! $page )
		{
            $this->setViewContent(  '' . sprintf( self::__( '<p class="badnews">Page files for %s could not be created</p>' ), $url ) . ''  );
			return false;
        }
        $id = Ayoola_Application::getPathPrefix() . $page['url'] . $this->getParameter( 'exec_scope' );
        if( ! empty( static::$_runLayout[$id] ) )
        {
        //    $this->showLayout();
         //   return false;
        }
        static::$_runLayout[$id] = true;
        $this->getLayoutRepresentation();
		if( ! @$_POST )
		{
            //	Change Title
            if( ! $this->isSaveMode() )
            {
                if( stripos( $page['url'], '/layout/' ) !== 0 )
                {
                    $title = 'Editing "' . $page['url'] . '"';
                }
                else
                {
                    $title = 'Editing Theme';
                }

                if( strpos( Ayoola_Page::getCurrentPageInfo( 'title' ), $title ) === false )
                {
                    $pageInfo = array(
                        'title' => trim( $title . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' )
                    );
                    Ayoola_Page::setCurrentPageInfo( $pageInfo );
                }
            }
			$this->showLayout();
			return false;
         }
         return true;
    } 

    /**
     * 
     * @return null
     */
    public static function resetDefaultLayout()   
    {
        self::$_defaultLayout = null;
    }

    /**
     * 
     * @param void
     * @return string
     */
    public static function getDefaultLayout()   
    {

		//	setting wrong layout for subsites
        //if( ! empty( self::$_defaultLayout ) )
        {
            //return self::$_defaultLayout;
        }
		
        $coreDefault = 'pc_layout_miniblog';
		$defaultLayout = $coreDefault;
		if( $layoutName = Application_Settings_Abstract::getSettings( 'Page', 'default_layout' ) )     
		{
			if( Ayoola_Page_PageLayout::getInstance()->selectOne( null, array( 'layout_name' => $layoutName ) ) )
			{
                //self::$_defaultLayout = $defaultLayout;
				$defaultLayout = $layoutName;
				return $defaultLayout;

			}
		}
		//var_export( $layoutName );

		return $defaultLayout;
	}

    /**
     * Returns the site-wide widgets
     * 
     * @param string $section
     * @return array
     */
    public static function getSiteWideWidgets( $section, $url = null )   
    {
        try
        {
            $data['section'] = $section;
            $data['url'] = $url;

            $data['widgets'] = Ayoola_Object_PageWidget::getInstance()->select( null, array( 'section_name' => $section, 'url' =>  '/sitewide-page-widgets' ) );

            self::setHook( static::getInstance(), __FUNCTION__, $data );

            $classes = array();

            foreach( $data['widgets'] as $widget )
            {
                $class = $widget['class_name'];
                if( ! Ayoola_Loader::loadClass( $class ) )
                {
                    continue;
                }
                $class = new $class( $widget['parameters'] + array( 'pc_section_name' => $section ) );
                $class->initOnce();
                $classes[] = $class;
            }

            return $classes;
        }
        catch( Ayoola_Page_Editor_Exception $e  )
        {
            //  now hooks can avoid execution of a class init method
        }

    }

    /**
     * Produces the layout representation and also proccess POSTed data
     * 
     * @param void
     * @return mixed
     */
    public function getLayoutRepresentation()
    {

		$page = $this->getPageInfo();
		//var_export( $page );
		$values = $this->getValues();



		//	debug 
		if( $values == array ( 0 => false, ) )
		{
			$values = array();
		}
		$sectionalValues = array();

		if( ! $paths = $this->getPagePaths() )
		{
			return false;
		}

		// Initialize my contents
		$base = basename( $paths['include'] );
		$date = date('l jS \of F Y h:i:s A');
		$generated = __CLASS__;
		$username = Ayoola_Application::getUserInfo( 'email' );
		$copyright = "/**\n* PageCarton Page Generator\n*\n* LICENSE\n*\n* @category PageCarton\n* @package {$page['url']}\n* @generated {$generated}\n* @copyright  Copyright (c) PageCarton. (http://www.PageCarton.com)\n* @license    http://www.PageCarton.com/license.txt\n* @version \$Id: {$base}	{$date}	{$username} \$ \n*/";
		$comment['template'] = "<?php\n$copyright\n//	Template Content ?>\n";
		$comment['include'] = "<?php\n$copyright\n//	Page Include Content\n";

		//	We are working on two files
		$content = array();
		$content['template'] = null;
		$content['include'] = null;

		require_once 'Ayoola/Filter/LayoutIdToPath.php';
		$filter = new Ayoola_Filter_LayoutIdToPath( $page );   

		//	Get the layout file if any
		$theme = $this->getPageEditorLayoutName() ? : @$page['layout_name'];
		$theme = $theme ? : self::getDefaultLayout();
		if( ! Ayoola_Loader::checkFile( @$page['pagelayout_filename'] ) )	//	Compatibility
		{
			$page['pagelayout_filename'] = $filter->filter( $theme );
		}
		if( ! $filePath = Ayoola_Loader::checkFile( $page['pagelayout_filename'] ) )
		{ 
			$filePath = Ayoola_Loader::checkFile( $filter->filter( self::getDefaultLayout() ) );
        }
		if( ! $filePath )
		{ 
			$filePath = Ayoola_Loader::checkFile( $filter->filter( 'pc_layout_miniblog') );
        }

		$page['pagelayout_filename'] = $filePath; 
		$this->hashListForJs = NULL;
		$this->hashListForJsFunction = NULL;
		if( ! $content['template'] = @file_get_contents( $filePath ) )
		{
			$this->setViewContent( self::__( '<p class="boxednews badnews">You need to select a default page "template" layout. </p><a  class="boxednews goodnews" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Application_Settings_Editor/settingsname_name/Page/?previous_url=/tools/classplayer/get/name/' . __CLASS__ . '/?url=' . $page['url'] . '">Choose a template</a>.' ) );
			return false;
		}

		if( $layoutPreloadedContents = @include $page['pagelayout_filename'] . 'sections' )
		{

		}

		// check if theres a page specific theme file
		$pageThemeFileUrl = $page['url'];
		if( $pageThemeFileUrl == '/' )
		{
			$pageThemeFileUrl = '/index';
		}
		$pageThemeFile = '/layout/' . $theme . '' . $pageThemeFileUrl . '.html';

        $defaultPageThemeFile = '/layout/' . $theme . '/default' . '.html';
        
        
        {
            $whereToGetPlaceholders = $content['template'];
        }
        
		//	Add to the layout on the fly
		//	must be a word because its used as variable in the page files
		$placeholders = Ayoola_Page_Layout_Abstract::getThemeFilePlaceholders( $content['template'] );
		preg_match_all( "/%%([A-Za-z]+)%%/", $content['template'], $placeholders2 );

		$placeholders = array_unique( $placeholders );
		$placeholders2 = array_unique( $placeholders2[1] );

		$placeholders = array_merge( $placeholders, $placeholders2 );
		$placeholders = array_map( 'strtolower', array_unique( $placeholders ) );
		$danglingPlaceholders = array();
		$sectionsForPreservation = explode( ',', trim( @$values['section_list'], ',' ) );
		$sectionsForPreservation = array_combine( $sectionsForPreservation, $sectionsForPreservation );
		unset( $sectionsForPreservation[''] );

		//	i think this makes it to double content so form submit twice
		$hashPlaceholders = array();
		foreach( $placeholders as $each )
		{
			//	make sure the hash is also there

			$hashPlaceholders[] = self::hashSectionName( $each );
		}

		foreach( $sectionsForPreservation as $each )
		{
			if( ! in_array( $each, $placeholders ) && ! in_array( $each, $hashPlaceholders ) )
			{

				$danglingPlaceholders[] = $each;
			}
		}
        $pageThemeFileX = $pageThemeFile;
		if( 
			//	now always run this path because we are trying to get lost js and css every time. 
			//	( empty( $values ) || ! empty( $_REQUEST['pc_load_theme_defaults'] ) )
			//	AND 
				stripos( $page['url'], '/layout/' ) !== 0

				AND

				(
                    //  first check if i have a local copy of themefile
					$pageThemeFile = Ayoola_Loader::checkFile( Ayoola_Doc_Browser::getDocumentsDirectory() . $pageThemeFileX )

					OR

                    //  check if core have a theme file
                    //  because we now want to use theme page file for default themes
					$pageThemeFile = Ayoola_Loader::checkFile( APPLICATION_PATH . '/documents/' . $pageThemeFileX )

					OR

					$pageThemeFile = Ayoola_Loader::checkFile( Ayoola_Doc_Browser::getDocumentsDirectory() . $defaultPageThemeFile )
				)
		)
		{  
			//	We have a page-specific themefile
			// 	we use it to build the default content
            $table = Ayoola_Page_PageLayout::getInstance();
            $whereToGetPlaceholders = file_get_contents( $pageThemeFile );


			$themeInfo = $table->selectOne( null, array( 'layout_name' => $theme ) );

			$whereToGetPlaceholders = Ayoola_Page_Layout_Abstract::sanitizeTemplateFile( $whereToGetPlaceholders, $themeInfo  );


			//		look for dangling placeholders in page theme file
            $placeholdersInPageThemeFile = Ayoola_Page_Layout_Abstract::getThemeFilePlaceholders( $whereToGetPlaceholders );
            
			//var_export( $page );
			//var_export( $placeholders );
			//var_export( $placeholdersInPageThemeFile );

			$danglingPlaceholders = array_merge( array_diff( $placeholdersInPageThemeFile, $placeholders ), $danglingPlaceholders );

			krsort( $danglingPlaceholders );			

			//	compare the contents here with the original file to discard information that's present in the theme file
			$originalFile = Ayoola_Doc_Browser::getDocumentsDirectory() . '/layout/' . $theme . '/template';

			if( ! is_file( $originalFile ) )
			{

                // we reverted to core file in "$pageThemeFile". 
                //  We need to prepare for it here also
                //  So we don't duplicate theme content in pages
                $originalFile = APPLICATION_PATH . '/documents' . '/layout/' . $theme . '/template';
            }
            if( ! $originalFile = file_get_contents( $originalFile ) )
            {

            }

			//	load all js and css that is not in index file whereToGetPlaceholders
			preg_match_all( "/<script[\s\S]*?>[\s\S]*?<\/script>/i", $originalFile, $originalScripts );
            preg_match_all( "/<script[\s\S]*?>[\s\S]*?<\/script>/i", $whereToGetPlaceholders, $pageScripts );
            


			preg_match( "/<body[\s\S]*?>/i", $originalFile, $originalBodyTag );
            preg_match( "/<body[\s\S]*?>/i", $whereToGetPlaceholders, $pageBodyTag );
            
            if( $originalBodyTag[0] !== $pageBodyTag[0] )
            {
                $content['template'] = str_ireplace( $originalBodyTag[0], $pageBodyTag[0], $content['template'] );
            }

			//	remove scripts that are not needed on the page
			//	some where causing issues on sb-mart
			if( $scriptNotNeededInPage = array_diff( $originalScripts[0], $pageScripts[0] ) )
			{
				foreach( $scriptNotNeededInPage as $eachScript )
				{
					//	remove all script thats not needed
					$content['template'] = str_ireplace( $eachScript, '', $content['template'] );
				}
			}

			//	add needed absent scripts
			if( $absentScripts = array_diff( $pageScripts[0], $originalScripts[0] ) )
			{
				$absentScripts = implode( "\r\n", $absentScripts );
				$content['template'] = str_ireplace( '</body>', $absentScripts . "\r\n</body>", $content['template'] );
			}

			preg_match_all( "/<link[\s\S]*?href\s*=\s*[\'\"]([^\'\"]+)[\'\"]/i", $originalFile, $originalScripts );
			preg_match_all( "/<link[\s\S]*?href\s*=\s*[\'\"]([^\'\"]+)[\'\"]/", $whereToGetPlaceholders, $pageScripts );
			if( $absentScripts = array_diff( $pageScripts[1], $originalScripts[1] ) )
			{

				$allLinks = null;
				foreach( $absentScripts as $eachScript )
				{
					$allLinks .= '<link href="' . $eachScript . '" rel="stylesheet" type="text/css">' . "\r\n";
				}
				$content['template'] = str_ireplace( '</head>', $allLinks . "\r\n</head>", $content['template'] );
			}

        }

		// inject the dangling placeholders here. 
        //	this made some placeholder to be double under lastoneness
        $lastPlaceholder = $placeholders[count( $placeholders )-3];
        $basePlaceholder = $lastPlaceholder;
        if( ! stripos( $content['template'], $basePlaceholder ) )
        {
            $basePlaceholder = 'lastoneness';
            if( ! stripos( $content['template'], $basePlaceholder ) )
            {
                $basePlaceholder = 'oneness';
            }
        }
        $basePlaceholder = '@@@' . $basePlaceholder . '@@@';
		foreach( $danglingPlaceholders as $key => $each )
		{
			$newPlaceholder = '@@@' . $each . '@@@';
			$placeholders[] = $each;
			$searchFor = array();
			$replaceWith = array();

			//	can we deal with the duplicate contents here?  
			if( in_array( self::hashSectionName( $each ), $danglingPlaceholders ) )
			{
				continue;
			}

			$searchFor[] = $basePlaceholder;
			$replaceWith[] =   $basePlaceholder . ' ' . $newPlaceholder ;
			$content['template'] = str_ireplace( $searchFor, $replaceWith,  $content['template'] );
        }
		$navTag = '</nav>';
		if( ! stripos( $originalFile, $navTag ) )
		{
			$navTag = '</ul>';
		}
		$placeholders = array_fill_keys( $placeholders, null );

		// 
		$counter = 0;
		$noOfDanglingObjects = 0;
		$hasDanglingObjects = false;
		$totalPlaceholders = count( $placeholders );

		//	record remainders so we stop loosing contents if sections missing
		$this->_layoutRepresentation = $content['template'];
        $pageContent = array();  
        if( $this->isSaveMode() )
        {
            if( $page['url'] === '/sitewide-page-widgets' )
            {
                Ayoola_Object_PageWidget::getInstance()->delete( array( 'url' =>  $page['url'] ) ); 
            }
        }
        $pageUpdateInfo = array();
		foreach( $placeholders as $section => $v )
		{
			$section = strtolower( $section );

			$sectionContent = array();
            $sectionalObjectCollection = null;

			//	We are working on two files
			$sectionContent['template'] = null;
            $sectionContent['include'] = null;
            if( stripos( $page['url'], '/layout/' ) === 0 )
            {
                $siteObjectName = '_sitePageWidget_' . $section;
                $sectionContent['include'] .= "
                if( method_exists( '" . __CLASS__ . "', 'getSiteWideWidgets' ) )
                {
                    \${$siteObjectName} = " . __CLASS__ . "::getSiteWideWidgets( '" . $section . "', Ayoola_Application::getPresentUri() );
                }
                else
                {
                    \${$siteObjectName} = array();
                }
                ";
                $sectionContent['template'] .= "
                foreach( \${$siteObjectName} as \$eachSitePageWidget )
                {
                    echo \$eachSitePageWidget->view();
                }
                ";
            }

			//	hack to fix duplicating content 
			//	especially on new theme
			$hashSectionName = self::hashSectionName( $section );

			//	this was what made to recover template page changes 
			//	on comeriver site
			if( ++$counter >= $totalPlaceholders )
			{
				$noOfDanglingObjects = count( $sectionsForPreservation );
				$hasDanglingObjects = count( $sectionsForPreservation );
			}
			unset( $sectionsForPreservation[$hashSectionName] );
			unset( $sectionsForPreservation[$section] );

			//	set max no of objects in a section
			$maxObjectsPerSection = 10;

			$this->hashListForJs .= ',' . $hashSectionName;
			$this->hashListForJsFunction .= ',"' . $hashSectionName . '"';
			do
			{
				for( $i = 0; $i < $maxObjectsPerSection; $i++ )
				{
					//	Need to hash so the element ID won't conflict in Js
					$numberedSectionName = $hashSectionName . $i;
					$templateDefaults = array();
					if( ! isset( $values[$numberedSectionName] ) && $i )
					{
						$numberedSectionName = $section . $i;
						if( ! isset( $values[$numberedSectionName] ) && $i )
						{
							//	don't continue till ten when we don't have values
							break;
						}
					}
					if( ! isset( $values[$numberedSectionName] ) )
					{ 
						if( stripos( $section, 'ay__ay__' ) === 0 )
						{
							//	fix doublers
							break;	
						}
						//	compatibility
                        $numberedSectionName = $section . $i;
						if( ! isset( $values[$numberedSectionName] ) )
						{
                            preg_match( '/{@@@' . $section . '([\S\s]*)' . $section . '@@@}/i', $whereToGetPlaceholders, $sectionPlaceholder );

							$defaultPlaceHolder = @$sectionPlaceholder[1];
							$defaultPlaceHolder = preg_replace('/<\\?.*(\\?>|$)/Us', '',$defaultPlaceHolder);
							
                            //    var_export( $section );
                            //    var_export( $defaultPlaceHolder );
							if( ! empty( $originalFile ) && @$sectionPlaceholder[1]  )
							{

								$check = $sectionPlaceholder[1];
								if( stripos( $check, $navTag ) && empty( $firstNav ) )
								{
									$firstNav = true;

                                    $defaultPlaceHolder = null;
                                    
                                }

                                //  content with this means it is meant to be in theme file
								if( stripos( $originalFile, $check  ) || stripos( $check, '©' ) || stripos( $check, '&copy;' ) || stripos( $check, '&amp;copy' ) )
								{
									//	Don't duplicate whats in theme file and navigation'

									$defaultPlaceHolder = null;
								}
							}
							if( $defaultPlaceHolder )
							{
								//	clean the code out here so that <php dont show in new themes

							}
							if( ! empty( $_GET['pc_load_theme_defaults'] ) || empty( $values ) || @$values[0] === false )
							{

								if( $i == 0 && $defaultPlaceHolder )
								{ 	

									//	allow templates to inject default content
									//	This is first and only
									$i = $maxObjectsPerSection;
									$defaultPlaceHolder = str_ireplace( Ayoola_Page_Layout_Abstract::getPlaceholderValues(), Ayoola_Page_Layout_Abstract::getPlaceholderValues2(), $defaultPlaceHolder );

									$templateDefaults = array( 'editable' => $defaultPlaceHolder );
									$sectionalValues[$numberedSectionName . '_template_defaults'] = $templateDefaults;
									$sectionalValues[$numberedSectionName] = 'Ayoola_Page_Editor_Text';
								}
								elseif( ! empty( $layoutPreloadedContents[$section][$i] ) )
								{ 	

									$templateDefaults = $layoutPreloadedContents[$section][$i];
									$sectionalValues[$numberedSectionName . '_template_defaults'] = $templateDefaults;
									$sectionalValues[$numberedSectionName] = $layoutPreloadedContents[$section][$i]['object_name'];
								}
								else
								{
									continue 1; 
								}
							}
							else
							{
								continue 1; 
							}
						} 
                    }
					$eachObject = $this->getObjectInfo( $values[$numberedSectionName] ? : $sectionalValues[$numberedSectionName] );

					if( ! isset( $eachObject['object_name'] ) )
					{ 
						continue; 
					} 

					if( $values[$numberedSectionName] === 'Ayoola_Page_Editor_Text' && empty( $eachObject['editable'] ) && empty( $eachObject['code'] ) && ! empty( $values[$numberedSectionName . '_template_defaults'] ) )
					{ 
						if( is_string( $values[$numberedSectionName . '_template_defaults'] ) )
						{
							$values[$numberedSectionName . '_template_defaults'] = array( 'editable' => $values[$numberedSectionName . '_template_defaults'] );
						}

					} 
					$objectName = 'obj' . $numberedSectionName . $eachObject['object_name'];

					//	add domain
					//	conflicting multisites
					//	the same url is theme urls on subdomains
					$objectName .= Ayoola_Page::getDefaultDomain();

					//	add prefix
					//	conflicting multisites
					$objectName .= Ayoola_Application::getUrlPrefix();

					//	The objectname is conflicting in templates and the page
					//	Let's make it a function of the url to fix this
					$objectName .= $page['url'];

					$objectName = '_' . md5( $objectName );  

					$objectParametersAvailable = array_map( 'trim', explode( ',', @$values[$numberedSectionName . '_parameters'] ) );
					$parameters = array();
					foreach( $objectParametersAvailable as $each )
					{
						$parameters[$each] = $values[$numberedSectionName . $each];
					}
					/* For Layout representation */

					@$sectionalValues[$numberedSectionName . '_template_defaults'] = $values[$numberedSectionName . '_template_defaults'] ? : $sectionalValues[$numberedSectionName . '_template_defaults'];
					$sectionalValues[$numberedSectionName . '_template_defaults'] = $sectionalValues[$numberedSectionName . '_template_defaults'] ? : array();

					//	add this here so it can be available in the the include and template files for new theme
					//	not available in save mode so that when item is deleted in edit mode it doest sneak into save mode

					//	now allowing autosave mode so that themes pages could be generated on creation.
					if( ! $this->isSaveMode() || $this->isAutoSaveMode() )
					{

						$parameters = ( is_array( $parameters ) ? $parameters : array() ) + ( is_array( $sectionalValues[$numberedSectionName . '_template_defaults'] ) ? $sectionalValues[$numberedSectionName . '_template_defaults'] : array() );

					}
					$eachObject = array_merge( $eachObject, $parameters );

					//	it was causing in finite loop 
					//	getViewableObjectRepresentation() is calling a view object
					//	/object and Ayoola_Object_Embed seem to be causing the issue
					//	ignoring them in savemode seems to fix this.
					if( ! $this->isSaveMode() )
					{
                        $eachObject['refresh_page_widget'] = false;
						$sectionalObjectCollection .= $this->getViewableObject( $eachObject );
					}

				    //	Inject the parameters.
					//	Calculate advanced parameters at this level so that access levels might work
					$parameters = self::prepareParameters( $parameters );
					if( $this->isSaveMode() )
					{
                        //  widget-based filter
                        //  widget may want to perform a one-time filter onsubmit
                        $eachObject['class_name']::filterParameters( $parameters );

                        $parametersToSave = $parameters + $eachObject;
                        
                        //  takes time to do this
                        //  so don't do this on autosave
                        if( ! $this->isAutoSaveMode() )
                        {
                            Ayoola_Abstract_Viewable::saveWidget( $eachObject['class_name'], $parametersToSave, $page['url'], $section );
                        }

                        if( empty( $parameters['pagewidget_id'] ) || $parameters['pagewidget_id'] !== $parametersToSave['pagewidget_id'] )
                        {
                            $parameters['pagewidget_id'] = $parametersToSave['pagewidget_id'];

                            $pageWidgetIdText = http_build_query( array( 'pagewidget_id' =>  $parametersToSave['pagewidget_id'] ) );
                            if( ! empty( $values ) )
                            {
                                $values[$numberedSectionName . 'advanced_parameters'] .= '&' . $pageWidgetIdText;
                            }
                        }

                        //  send page widgets to DB
                        $pageUpdateInfo['pagewidget_id'][] = $parameters['pagewidget_id'];
                        $pageUpdateInfo['section_name'][] = $section;
					}
					$pageContent[$section][] = 	array( 'class' => $eachObject['class_name'], 'parameters' => $parameters );	

					$parametersArray = $parameters;
					$parameters = var_export( $parameters, true );

					@$parametersArray['wrapper_name'] = $parametersArray['wrapper_name'] ? : null;
					if( @$parametersArray['object_access_level'] )
					{
					    //	Begin to populate the content of the template file
						$accessLevelStr = var_export( $parametersArray['object_access_level'], true );
						$sectionContent['include'] .= "
							\n\${$objectName} = null;\n
							if( Ayoola_Page::hasPriviledge( {$accessLevelStr}, array( 'strict' => true ) ) )
							{
								if( Ayoola_Loader::loadClass( '{$eachObject['class_name']}' ) )
								{
									\n\${$objectName} = new {$eachObject['class_name']}( {$parameters} );\n
								}
							}    
							";
					}
					else
					{
						//	Begin to populate the content of the template file
						$sectionContent['include'] .= "
							\n\${$objectName} = null;\n
							if( Ayoola_Loader::loadClass( '{$eachObject['class_name']}' ) )
							{
								\n\${$objectName} = new {$eachObject['class_name']}( {$parameters} );\n
							}
							";
					} 
					//	Insert the view method in the "template"
					$sectionContent['template'] .= "
					if( empty( \${$objectName} ) || ! is_object( \${$objectName} ) )
					{
						//Ayoola_Page_Layout_Abstract::refreshThemePage( $themeName );
					}
					echo Ayoola_Object_Wrapper_Abstract::wrap( \${$objectName}, '{$parametersArray['wrapper_name']}' );

					";
					//	We need to work on the layout template file if there is any
				}

				if( $hasDanglingObjects )
				{

					$hashSectionName = array_shift( $sectionsForPreservation );

					$noOfDanglingObjects--;
				}
			}

			while( $hasDanglingObjects && $hashSectionName );

			//	refresh this here because its been tampered with in  $noOfDanglingObjects
			$hashSectionName = self::hashSectionName( $section );

			// For some reasons this is no longer available bececause this tempfil is being deleted
			//	before end of the script
			{
				//	 Try to replace contents of the layout
				$search = array( '%%' . $section . '%%', '@@@' . $section . '@@@' );

				//	ALLOWING ADMINISTRATORS TO EDIT TEMPLATES ON THE FLY ( MARCH 29, 2014 )
				$urlPrefix = Ayoola_Application::getUrlPrefix();
				$editLink =  "\" . Ayoola_Application::getUrlPrefix() . \"/tools/classplayer/get/name/" . __CLASS__ . "/?url={$page['url']}";

				$replace = "<?php\n//{$section} {$page['url']} Begins Here\n";
				$replace .= "{$sectionContent['template']}";

				$replace .= "\n//{$section} {$page['url']} Ends Here\n?>";

				if( stripos( $page['url'], '/layout/' ) === 0 || stripos( $page['url'], '/default-layout' ) === 0 )
				{
					//	Template editor need this so pages could use the generated template to build their own templates
					$replace = array( $replace . "%%{$section}%%", $replace . "@@@{$section}@@@" );  
				}
				elseif( stripos( $page['url'], '/tools' ) !== 0 && stripos( $page['url'], '/pc-admin' ) !== 0 && stripos( $page['url'], '/ayoola' ) !== 0 )
				{
					// admin users get the edit button   
				}				

				$content['template'] = str_ireplace( $search, $replace, $content['template'] );

				/* For Layout representation */
				$replace = "<div ondrop='ayoola.dragNDrop.elementDropped( event, this )' ondragover='ayoola.dragNDrop.allowDrop( event )' title='This is the \"{$section}\" section. Drag objects from the draggable pane and drop it here.' class='DragContainer' id='{$hashSectionName}'>$sectionalObjectCollection</div>\n";			

				$this->_layoutRepresentation = str_ireplace( $search, $replace, $this->_layoutRepresentation );

				//	Clear our the orphan placeholders 
				//	This is affecting <section data-pc-all-sections="1"> if it is two in a theme
                $searchT = array( 
                    '/{@@@' . $section . '([\S\s]*)' . $section . '@@@}/i',
                 //   '#<' . $section . '>([\S\s]*)</' . $section . '>#i',
                 );
				$this->_layoutRepresentation = preg_replace( $searchT, '', $this->_layoutRepresentation );
				$content['template'] = preg_replace( $searchT, '', $content['template'] );
			}  

			//	Add the new sectional data to the main content
			$content['include'] .= $sectionContent['include'];

		}
		//	Add the Copyright and page description
		$content['template'] = $comment['template'] . $content['template'];
		$content['include'] = $comment['include'] . $content['include'];

		//	save files
		if( empty( $values ) && empty( $sectionalValues ) )
		{
			$this->noExistingContent = true;
        }

		if( $this->isSaveMode() ) //	create template for POSTed data
		{

			//	Clear our the orphan placeholders
            $dataToSave = static::safe_json_encode( $values ? : $sectionalValues );


			//	Get new relative paths
			$rPaths = Ayoola_Page::getPagePaths( $page['url'] );
            $rPaths['data-backup'] = self::getPageContentsBackupLocation( $page['url'] ) . DS . time();
            
			//	change the place themes are being saved.
			if( stripos( $page['url'], '/default-layout' ) === 0 && ! empty( $this->_parameter['theme_variant'] ) )
			{
				//	don't remember why theme variants are not being used for default layout
				//	need to test it now.
                //$this->_parameter['theme_variant'] = null;
            }
			if( stripos( $page['url'], '/layout/' ) === 0 )
			{
				list(  , $themeName ) = explode( '/', trim( $page['url'], '/' ) );
				$rPaths['include'] = 'documents/layout/' . $themeName . '/theme/include';
				$rPaths['template'] = 'documents/layout/' . $themeName . '/theme/template';
				$rPaths['data_json'] = 'documents/layout/' . $themeName . '/theme/data_json';
				$rPaths['data_json_content'] = 'documents/layout/' . $themeName . '/theme/data_json_content';
				$rPaths['data_page_info'] = 'documents/layout/' . $themeName . '/theme/data_page_info';
				$rPaths['data-backup'] = 'documents/layout/' . $themeName . '/theme/data-backup/' . time();
                if( ! empty( $this->_parameter['theme_variant'] ) )
                {
                    $variant = $this->_parameter['theme_variant'];
                    $rPaths['include'] .= $variant;
                    $rPaths['template'] .= $variant;

                    $rPaths['include'] = 'documents/layout/' . $themeName . '/theme/variant/' . $variant . '/include';
                    $rPaths['template'] = 'documents/layout/' . $themeName . '/theme/variant/' . $variant . '/template';
                    $rPaths['data_json_content'] = 'documents/layout/' . $themeName . '/theme/variant/' . $variant . '/data_json_content';

                    unset( $rPaths['data_page_info'] );
                    unset( $rPaths['data-backup'] );
                    unset( $rPaths['data_json'] );

                }
			}
			elseif( $this->getPageEditorLayoutName() )
			{                  
				$themeName = $this->getPageEditorLayoutName();
				$rPaths['include'] = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/include';
				$rPaths['template'] = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/template';
				$rPaths['data_json'] = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/data_json';
				$rPaths['data_json_content'] = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/data_json_content' ;
                $rPaths['data_page_info'] = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/data_page_info' ;
                $rPaths['data-backup'] = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/data-backup/' . time();

                if( ! empty( $this->_parameter['theme_variant'] ) )
                {
                    $variant = $this->_parameter['theme_variant'];
                    $rPaths['include'] = 'documents/layout/' . $themeName . '/theme/variant/' . $variant . '' . $pageThemeFileUrl . '/include';
                    $rPaths['template'] = 'documents/layout/' . $themeName . '/theme/variant/' . $variant . '' . $pageThemeFileUrl . '/template';
                    $rPaths['data_json_content'] = 'documents/layout/' . $themeName . '/theme/variant/' . $variant . '' . $pageThemeFileUrl . '/data_json_content';

                    //  publisher uses json content
                    unset( $rPaths['data_page_info'] );
                    unset( $rPaths['data-backup'] );
                    unset( $rPaths['data_json'] );

                }
				if( $themeName == self::getDefaultLayout() && empty( $page['layout_name'] ) )
				{
                    $yPath = array();
					foreach( $rPaths as $eachItem => $eachFile )
					{
						$yPath[$eachItem] = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $rPaths[$eachItem];
						@Ayoola_Doc::createDirectory( dirname( $yPath[$eachItem] ) );
					}

					//	saving as well to main pages
					//	don't copy again because we are now loading theme pages automatically
					//	activated again because of index and co that is still autocopied
					if( is_file( $yPath['include'] ) && is_file( $yPath['template'] ) && is_file( $yPath['data_json'] ) 
						// don't update with auto created theme pages
						&& empty( $this->_parameter['theme_variant'] )	  
					)
					{
						//	only update if files exist already
						Ayoola_File::putContents( $yPath['include'], $content['include'] );
						Ayoola_File::putContents( $yPath['template'], $content['template'] );		
						Ayoola_File::putContents( $yPath['data_json'] , $dataToSave );
					}
                }
            }

			foreach( $rPaths as $eachItem => $eachFile )
			{
				//	hardcode the localized  filename
				$rPaths[$eachItem] = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $rPaths[$eachItem];
				@Ayoola_Doc::createDirectory( dirname( $rPaths[$eachItem] ) );
			}

			//var_export( $rPaths['include'] . '<br>');  
			//var_export( $rPaths['template'] . '<br>  ' );

			// if( stripos( $rPaths['include'], 'core' ) )
			// {
			// 	unset( $this->_layoutRepresentation );
			// 	unset( $this->_viewableObjects );
			// 	unset( $this->_viewableSelect );
			// 	unset( $this->hashListForJs );
			// 	unset( $this->hashListForJsFunction ); 
			// 	$trace = debug_backtrace();
			// 	$trace['domain'] = Ayoola_Application::getDomainSettings();   
			// 	$trace['xxx'] = DOMAIN;   
			// 	$content['include'] = var_export( $trace, true );
			// 	//echo $content['include'];
			// 	//exit();
			// }
			Ayoola_File::putContents( $rPaths['include'], $content['include'] );
			Ayoola_File::putContents( $rPaths['template'], $content['template'] );				

			if( $previousData = @file_get_contents( $rPaths['data_json'] ) )  
			{
				//	now saving current data instead of previous data

			}

			//	save default values if no value is set so we can preload themes.
            $rPaths['data_json'] ? Ayoola_File::putContents( $rPaths['data_json'], $dataToSave ) : null;
			$rPaths['data_json_content'] ? Ayoola_File::putContents( $rPaths['data_json_content'], static::safe_json_encode( $pageContent ) ) : null;
			$rPaths['data_page_info'] ? Ayoola_File::putContents( $rPaths['data_page_info'], static::safe_json_encode( $pageUpdateInfo ) ) : null;

			//	back up current data and not previous one
            if( $dataToSave != $previousData )
            {
                $rPaths['data-backup'] ? Ayoola_File::putContents( $rPaths['data-backup'], $dataToSave ) : null;
            }


            if( 
                ( stripos( $page['url'], '/default-layout' ) === 0 ) 
                //&& empty( $this->_parameter['page_refresh_mode'] )
            )     
			{

                //	autosanitize pages
                unset($_REQUEST['pc_page_editor_layout_name']);
                unset($_GET['pc_page_editor_layout_name']);
                $class = new Ayoola_Page_Editor_Sanitize();

                //  don't put theme name
                //  so it sanitize just normal pages and not theme pages
                //  /default-layout is meant to serve just normal page
                //  so it doesn't cause infinite loop

				//	make it run normal pages even if it has been run by main layout
				$class->objNamespace = 'default-layout';
                $ex = $class->sanitize();
            }
			//	Sanitize theme pages!
			if( 
                ( stripos( $page['url'], '/layout/' ) === 0 ) 
                //&& empty( $this->_parameter['theme_variant'] ) 
            )     
			{
                $themeToSanitize = $themeName;

				//	this was causing default layout not to work in first try
                //if( ! empty( $this->_parameter['theme_variant'] )  )
                {
                    //$themeToSanitize = null;
                }

                //	autosanitize pages
                unset( $_REQUEST['pc_page_editor_layout_name'] );
                unset( $_GET['pc_page_editor_layout_name'] );
			    $class = new Ayoola_Page_Editor_Sanitize(); 
			    $ex = $class->sanitize( $themeToSanitize ); 

                //	Sanitize multi sites
				$table = new PageCarton_MultiSite_Table();
				$isChildSite = $table->selectOne( null, array( 'directory' => Ayoola_Application::getPathPrefix() ) );

				$appPath = Ayoola_Application::getDomainSettings( APPLICATION_PATH );
				$isDefaultSite = false;
				if( basename( $appPath ) === 'application' && basename( dirname( $appPath ) ) === 'default' )
				{
					$isDefaultSite = true;
				}

				if( $isDefaultSite && ! $isChildSite )
				{	
					$sites = $table->select();

					//	do for directories
					foreach( $sites as $site )
					{

						Ayoola_Application::reset( array( 'path' => $site['directory'] ) );
                        //  Ayoola_Page_Layout_Abstract::refreshThemePage( $themeToSanitize ); 
                        $class = new Ayoola_Page_Editor_Sanitize(); 
                        $ex = $class->sanitize( $themeToSanitize ); 
					}
					Ayoola_Application::reset();
				}

				if( $isDefaultSite && ! $isChildSite )
				{	
					$sites = Application_Domain::getInstance()->select();

					//	do for directories
					foreach( $sites as $site ) 
					{
						Ayoola_Application::reset( array( 'domain' => $site['domain_name'] ) );
						if( Ayoola_Application::getDomainSettings( APPLICATION_PATH ) === $appPath )
						{
							//	don't cause infinite loop
							continue;
						}

                        //  Ayoola_Page_Layout_Abstract::refreshThemePage( $themeToSanitize ); 
                        $class = new Ayoola_Page_Editor_Sanitize(); 
                        $ex = $class->sanitize( $themeToSanitize ); 

					}
					Ayoola_Application::reset();
				}
			}

        }
		$this->_layoutRepresentation = str_ireplace( '</body>', '<div style="display:none;">'. $this->getViewableObjects() . '</div></body>', $this->_layoutRepresentation );
    	return $this->_layoutRepresentation;
    } 

    public static function safe_json_encode ($value, $options = 0, $depth = 512, $utfErrorFlag = false) {
        $encoded = json_encode($value, $options, $depth);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $encoded;
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found';
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
            case JSON_ERROR_UTF8:
                $clean = static::utf8ize($value);
                if ($utfErrorFlag) {
                    return 'UTF8 encoding error'; // or trigger_error() or throw new Exception()
                }
                return static::safe_json_encode($clean, $options, $depth, true);
            default:
                return 'Unknown error'; // or trigger_error() or throw new Exception()

        }
    }

    public static function utf8ize($mixed) {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = static::utf8ize($value);
            }
        } else if (is_string ($mixed)) {
            return utf8_encode($mixed);
        }
        return $mixed;
    }

    /**
     * Overall DB operation 
     * @param void
     * @return boolean
     */
    public function getObjectInfo( $object_name )
    {

		if( ! empty( self::$_objectInfo[$object_name] ) )
		{
			return self::$_objectInfo[$object_name];  
		}
		$table = Ayoola_Object_Table_ViewableObject::getInstance();
		if( ! self::$_objectInfo[$object_name] = $table->selectOne( null, array( 'object_name' => $object_name ) ) )
		{
			self::$_objectInfo[$object_name] = $table->selectOne( null, array( 'class_name' => $object_name ) );
		}

		return self::$_objectInfo[$object_name];
	} 

    /**
     * Contains the Javascript code as string
     * 
     * @param void
     * @return string
     */
    public function javascript()
    {
		if( ! $page = $this->getPageInfo() )
		{ 
			return false; 
		}
		$sections = trim( $this->hashListForJs, ',' );
		$portion = trim( $this->hashListForJsFunction, ',' );

		$isNotLayoutPage = stripos( $page['url'], '/layout/' ) !== 0;
		if( $isNotLayoutPage )
		{
			//	Always have this here so we can have a template editor link
			$page['layout_name'] = $page['layout_name'] ? : $this->getPageEditorLayoutName();
			$page['layout_name'] = $page['layout_name'] ? : self::getDefaultLayout();

			//	List URL so it can be easy to change editing URL
			$option = Ayoola_Page_Page::getInstance();
			$option = $option->select();

			$option = self::sortMultiDimensionalArray( $option, 'url' );
			$optionHTML = ' <span style="display:inline-block;padding: 0 5px 0 5px;"> <select class="pc-btn" style="display: inline-block;width: initial;width: unset;" onChange="location.href= \\\'?url=\\\' + this.value;">';
			foreach( $option as $eachPage )
			{
				$selected = null;
				if( $eachPage['url'] == $page['url'] )
				{
					$selected = 'selected=selected';
				}
				$optionHTML .= '<option ' . $selected . '>' . $eachPage['url'] . '</option>';
			}
			$optionHTML .= '</select></span>';
			if( $this->getPageEditorLayoutName() )
			{

				$pageThemeFileUrl = $page['url'];
				if( $pageThemeFileUrl == '/' )
				{
					$pageThemeFileUrl = '/index';
				}
				$backupDir = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . 'documents/layout/' . $this->getPageEditorLayoutName() . '/theme' . $pageThemeFileUrl . '/data-backup';
			}	
			else
			{

				$backupDir = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . self::getPageContentsBackupLocation( $page['url'] );

			}

		}
		else
		{
			list( , , $page['layout_name'] ) = explode( '/', $page['url'] );

			$backupDir = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . 'documents/layout/' . $page['layout_name'] . '/theme/data-backup';

			$optionHTML = ' <span style="display:inline-block;padding: 0 5px 0 5px;"> ' . self::__( 'Theme editor' ) . ' </span>';
		}

		// content version
		$pageVersions = array();
		$pageVersionHTML = null;
		if( is_dir( $backupDir ) )
		{
			$pageVersions = Ayoola_Doc::getFiles( $backupDir );
			rsort( $pageVersions );

			$pageVersionHTML = ' <span style="display:inline-block;padding: 0 5px 0 5px;"><select class="pc-btn" onChange="location.search = location.search + \\\'&pc_page_editor_content_version=\\\' + this.value;"><option value="">' . self::__( 'Last saved content' ) . '</option>';
			$filter = new Ayoola_Filter_Time();
			foreach( $pageVersions as $eachVersion )
			{
				if( is_numeric( basename( $eachVersion ) ) )
				{
					$eachVersion = basename( $eachVersion );
				}

				$selected = null;
				if( $_REQUEST['pc_page_editor_content_version'] == $eachVersion )
				{
					$selected = 'selected=selected';
				}
				$pageVersionHTML .= '<option ' . $selected . ' value="' . $eachVersion . '">' . sprintf( self::__( 'Content Saved %s' ), '' . $filter->filter( $eachVersion ) . ''  ) . '</option>';
			}
			$pageVersionHTML .= '</select></span>';
		}

		// Add object from checkbox to selectlist
		$js = '
			var pc_makeInnerSettingsAutoRefresh = function()
			{
				var loadInner = function( e )
				{
					var target = ayoola.events.getTarget( e );
					if( target.value == \'__custom\' ){ return false; }
					var a = ayoola.div.getParentWithClass( target, \'DragBox\' );
					if( a.getAttribute( "data-pc-object-refreshing" ) == "true" )
					{
						//	dont run twice at the sam time.
						return false;
					}

					var b = ayoola.div.getParameterOptions( a );

					var c = a.getElementsByClassName( \'pc_page_object_inner_preview_area\' )[0];
					var d = a.getAttribute( "data-class_name" );

					var f = "";
					var g = false;
				//	if( ! c )
					{
						//	rebuild whole box
						var f = "&rebuild_widget_box=1";
						c = a;
						g = true;

					}
					var ajax = ayoola.xmlHttp.fetchLink( { url: \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Object_Preview/?rebuild_widget=1&class_name=\' + d + f, data: b.content, container: c, replaceContent: g } );
					a.setAttribute( "data-pc-object-refreshing", "true" );
					var v = function()
					{
						if( ayoola.xmlHttp.isReady( ajax ) )
						{	
							a.removeAttribute( "data-pc-object-refreshing" );
							pc_makeInnerSettingsAutoRefresh();

					//		if( target.getAttribute( "data-pc-return-focus-to" ) )
							{

						//		if( ed && ed.focus )
								{

								}
							}

						}		
					}	

					ayoola.events.add
					( 
						ajax, 
						"readystatechange",
						v,
						true
					);			
				}
				var z = document.getElementsByClassName( \'pc_page_object_inner_settings_area\' );
				for( var y = 0; y < z.length; y++ )
				{
					var x = z[y].getElementsByTagName( \'select\' );
					for( var w = 0; w < x.length; w++ )
					{
						ayoola.events.add( x[w], "change", loadInner, true );					
					}
				}
				var z = document.getElementsByClassName( \'pc_page_object_inner_settings_area2\' );
				for( var y = 0; y < z.length; y++ )
				{
					var x = z[y].getElementsByTagName( \'select\' );
					for( var w = 0; w < x.length; w++ )
					{
						ayoola.events.add( x[w], "change", loadInner, true );					
					}
				}
				var z = document.getElementsByClassName( \'DragBox\' );
				for( var y = 0; y < z.length; y++ )
				{
					var x = z[y].getElementsByTagName( \'form\' );
					for( var w = 0; w < x.length; w++ )
					{
						if( x[w].getAttribute( "data-parameter_name" ) != "advanced_parameters" )
						{
							continue;
						}			
						var r = x[w].elements;	

						for( var q = 0; q < r.length; q++ )
						{
							ayoola.events.add( r[q], "change", loadInner, true );	
						}				
					}
				}
			}

		ayoola.events.add
		(
			window,
			"load",
			function()
			{
				//	everything is not clickable by default
				 document.body.style.cursor = "not-allowed";
				 document.body.title = "This area is not editable here!";

				CreateDragContainer( ' . $portion . ' );
				CreateDragContainer( "viewable_objects" );
				pc_makeInnerSettingsAutoRefresh();

			}
		);
			window.onbeforeunload = function()
			{

			}
		var topBarForButtons = document.createElement( "span" );
		topBarForButtons.style.cssText = "overflow:auto;bottom:0;left:0;background-color:#333;color:#fff;position:fixed;padding:0.5em;cursor:move;z-index:200000;";
		topBarForButtons.className = "drag";
		topBarForButtons.title = "You can drag this box to anywhere you want on the screen.";
		document.body.appendChild( topBarForButtons );		

		//	Produce a random id for use
		var getRandomId = function()
		{  
			return "' . md5( rand( 1, 100 ) ) . '";
		}

		//	Display viewable objects
		var displayViewableObjects = document.createElement( "a" );
		displayViewableObjects.style.cssText = "display:none;";
		displayViewableObjects.href = "javascript:";
		displayViewableObjects.innerHTML = "<button type=\"button\">+</button>";
		displayViewableObjects.title = "Show Viewable Widgets";
		topBarForButtons.appendChild( displayViewableObjects );

		var showViewableObjects = function( e )
		{  
			hideViewableObjects();
			var randomId = getRandomId();
			var viewableObjects = document.getElementById( randomId );
			if( ! viewableObjects )
			{
				//	Viewable Object list
				var viewableObjects = document.createElement( "span" );
				viewableObjects.style.cssText = "display:none;";
				viewableObjects.className = "drag";
				viewableObjects.id = randomId;		
				topBarForButtons.appendChild( viewableObjects );
				viewableObjects.style.cssText = "";
			}
			CreateDragContainer( "viewable_objects" );
			viewableObjects.style.display = "";
		}
		ayoola.events.add( displayViewableObjects, "click", showViewableObjects );

		//	Hide viewable objects
		var hideViewableObject = document.createElement( "a" );
		hideViewableObject.style.cssText = "display:none;";
		hideViewableObject.href = "javascript:";
		hideViewableObject.innerHTML = "<button type=\"button\"> x </button>";
		hideViewableObject.title = "Hide Viewable Objects";
		topBarForButtons.appendChild( hideViewableObject );

		var hideViewableObjects = function()
		{  
			var randomId = getRandomId();
			var viewableObjects = document.getElementById( randomId );

			//	Delete previous, if available
			if( viewableObjects )
			{
				viewableObjects.style.display = "none";

			}
		}
		ayoola.events.add( hideViewableObject, "click", hideViewableObjects );  

		//	lets view the object so the sectional inserters can work.
		showViewableObjects();

		//	Hide again
		hideViewableObjects();

		//	Loops through layout sections
		var sectionList = "' . $sections . '";
		var sections = sectionList.split( "," );
		var addANewItemToContainer = function( e )
		{

			var target = ayoola.events.getTarget( e, "addItemButton" );

			var select = document.createElement( "select" );
			select.className = target.className;

			select.innerHTML = "<option>Select Widget</option>' . $this->_viewableSelect . '";
			ayoola.events.add
			( 
				select, 
				"change", 
				function()
				{ 
					var a = document.getElementById( select.value );
					var b = select.parentNode;

					//	Clone the node to replenish the main viewable objects.
					if( a && b && b.parentNode )
					{
						c = a.cloneNode( true );
						c.id = "";
						b.parentNode.appendChild( c );     
					}

					ayoola.events.add( target, "click", addANewItemToContainer ); 
					select.parentNode.appendChild( target );
					select.parentNode.removeChild( select );
				} 
			); 

			target.parentNode.appendChild( select );
			ayoola.events.remove( target, "click", addANewItemToContainer ); 
			target.parentNode.removeChild( target );
        }
		ayoola.events.add
		(
			window,
			"load",
			function()
			{
                for( var a = 0; a < sections.length; a++ )
                {  
                    var sectionName = sections[a];
                    var section = document.getElementById( sectionName ); // e.g. header
                    if( ! section ){ continue; }

                    //	ADDING A LINK TO ADD A NEW OBECT TO THE SECTION
                    //
                    var addItemContainer = document.createElement( "div" );
                    addItemContainer.style.cssText = "text-align:center;";  
                    addItemContainer.className = "pc_page_object_specific_item pc_page_object_insert_button_area";
                    addItemContainer.name = "add_a_new_item_to_parent_section";

                    var addItemButton = document.createElement( "span" );
                    addItemButton.className = "pc_add_widget_button greynews boxednews centerednews pc-btn";
                    addItemButton.innerHTML = "Add Widget Here";
                    ayoola.events.add( addItemButton, "click", addANewItemToContainer ); 

                    var select = document.createElement( "select" );
                    select.innerHTML = "<option value=\"\">' . self::__( 'Insert widget here' ) . '</option>' . $this->_viewableSelect . '";
                    select.className = "pc_add_widget_button greynews boxednews centerednews pc-btn";
                    select.title = "' . self::__( 'Select widget to insert below' ) . '";
                    ayoola.events.add
                    ( 
                        select, 
                        "change", 
                        function( e )  
                        { 
                            var target = ayoola.events.getTarget( e );
                            var a = document.getElementById( target.value );

                            var b = target.parentNode;

                            //	Clone the node to replenish the main viewable objects.
                            if( a && b && b.parentNode )
                            {
                                c = a.cloneNode( true );
                                c.id = "";

                                b.parentNode.appendChild( c ); 
                                c.scrollIntoView( {block: "end",  behaviour: "smooth"} );    
                                pc_makeInnerSettingsAutoRefresh();
                            }
                            target.value = "";
                        } 
                    ); 
                    addItemContainer.appendChild( select );  
                    section.appendChild( addItemContainer );

                }
            } 
        );

		//	button to save the layout
		var saveButton = document.createElement( "a" );
		saveButton.style.cssText = "";
		saveButton.href = "javascript:";
		saveButton.title = "' . self::__( 'Save the layout template' ) . '";
		saveButton.className = "pc-btn pc-bg-color";  
		saveButton.innerHTML = "' . self::__( 'Save' ) . '";  
		topBarForButtons.appendChild( saveButton );

		var functionToSaveTemplate = function()
		{  
			//	Autoclose HTML editor  
			if ( ayoola.div.wysiwygEditor )
			{

			}
		//	for( name in CKEDITOR.instances )
			{

			} 
			var url = location.href;
			var postContent = "";
			var sectionListForPreservation = "";
			for( var a = 0; a < sections.length; a++ )
			{  
				var z = 0; // Use this for real concurrent numbering.
				var sectionName = sections[a];
				var section = document.getElementById( sectionName ); // e.g. header
				if( ! section ){ continue; }

				//	Construct the URL for POSTing
				//	Loops through objects
				var sectionHasContent = false;
				for( var b = 0; b < section.childNodes.length; b++ )
				{

					var object = section.childNodes[b];

					if( ! object || object.nodeName == "#text" ){ continue; }
					if( ! object.dataset || ! object.dataset.object_name ){ continue; }
					var objectName = object.dataset.object_name;

					var numberedSectionName = sectionName + String( z ); // e.g. header2
					z++; //	Count the real concurrent numbering system.
					postContent = postContent ? ( postContent + "&" ) : "";

					postContent +=  numberedSectionName + "=" + objectName;

					// Add View parameters and options
					//	Loops through parameters
					var parameterList = Array();

					var h = ayoola.div.getParameterOptions( object, numberedSectionName );

					//	Add for interior Separately because of wrappers blocking it.

					var k = ayoola.div.getParameterOptions( object.getElementsByClassName( "object_interior" )[0], numberedSectionName );

					if( k.content ) 
					{
						h.content += k.content;
					}
					if( k.list ) 
					{
						h.list = h.list.concat( k.list );
					}

					if( h.content ) 
					{

						postContent += h.content;
					}
					if( h.list ) 
					{

						parameterList = parameterList.concat( h.list );
					}

					parameterList = encodeURIComponent( parameterList.join( "," ) );
					postContent += "&" + numberedSectionName + "_parameters=" + parameterList;
					sectionHasContent = true;
				}
				sectionListForPreservation += sectionHasContent ? ( sectionName + "," ) : "";

			}
			postContent = postContent ? postContent : "a=b";

			//	need to save section list to preserve contents
			postContent = postContent + "&section_list=" + sectionListForPreservation;

			var uniqueNameForAjax = "' . __CLASS__ . rand( 0, 500 ) . '";

			//	debug
			url = url + "";
			ayoola.xmlHttp.fetchLink( url, uniqueNameForAjax, postContent );

			//	Set a splash screen to indicate that we are loading.
			var splash = ayoola.spotLight.splashScreen();

			var ajax = ayoola.xmlHttp.objects[uniqueNameForAjax];

			var ajaxCallback = function()
			{

				if( ayoola.xmlHttp.isReady( ajax ) )
				{ 

					// Close splash screen
					splash.close();

				} 
			}
			ayoola.events.add( ajax, "readystatechange", ajaxCallback );
		}

		ayoola.events.add( saveButton, "click", functionToSaveTemplate );

		' 
		. 
		( $isNotLayoutPage ? 
		'
		//	button to preview page
		var a = document.createElement( "a" );
		a.style.cssText = "";
		a.title = "Click here to preview the LIVE version on this page.";
		a.href = "javascript:";

		a.className = "pc-hide-children-children pc-btn";  
		a.innerHTML = "' . self::__( 'Preview' ) . '";  
		topBarForButtons.appendChild( a );		
		ayoola.events.add( a, "click", function(){ ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '' . $page['url'] . '' . ( $this->getPageEditorLayoutName() ? ( '?pc_page_layout_name=' . $page['layout_name'] ) : null ) . '\' ); } );
		'
		.
				( 
					! $this->getPageEditorLayoutName() 

					? 

					null 

					: 

		'			
				//	button to load layout defaults HTML
				var a = document.createElement( "a" );
				a.style.cssText = "";
				a.href = "javascript:window.location.search = window.location.search + \'&pc_load_theme_defaults=1\'";
				a.onclick = "";
				a.title = "Load default HTML Content to this theme";
				a.className = "pc-hide-children-children pc-btn";  
				a.innerHTML = "' . self::__( 'Load Default' ) . '";  
				topBarForButtons.appendChild( a );
		'			
				)
		.

		'

				//	button to edit template
				var a = document.createElement( "a" );
				a.style.cssText = "";
				a.href = "javascript:";
				a.className = "pc-hide-children-children pc-btn";  
				a.innerHTML = "' . self::__( 'Theme' ) . '";  
				topBarForButtons.appendChild( a );
				ayoola.events.add( a, "click", function(){ ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Editor_Layout/?url=/layout/' . strtolower( $page['layout_name'] ) . '/template\' ); } );

				'  
				:

				'
				//	button to preview page
				var a = document.createElement( "a" );
				a.style.cssText = "";
				a.title = "Click here to preview the LIVE version on this theme.";
				a.href = "javascript:";

				a.className = "pc-hide-children-children pc-btn";  
				a.innerHTML = "' . self::__( 'Preview' ) . '";  
				topBarForButtons.appendChild( a );		
				ayoola.events.add( a, "click", function(){ ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/widgets/Ayoola_Page_Layout_Preview/?layout_name=' . $page['layout_name'] . '\' ); } );

				//	button to load layout defaults HTML
				var loadButton = document.createElement( "a" );
				loadButton.style.cssText = "";
				loadButton.href = "javascript:window.location.search = window.location.search + \'&pc_load_theme_defaults=1\'";
				loadButton.onclick = "";
				loadButton.title = "Load default HTML Content to this theme";
				loadButton.className = "pc-hide-children-children pc-btn";  
				loadButton.innerHTML = "' . self::__( 'Load Default' ) . '";  
				topBarForButtons.appendChild( loadButton );

				'		

				)

				.

				'		

				//	Display Widgets Editor
				var optionbar = document.createElement( "span" );
				optionbar.innerHTML = \'' . self::__( 'Widget Options' ) . '\';
				optionbar.className = " pc-btn";
				optionbar.title = "Show or hide widget options";
				optionbar.onclick = function()
				{
					var a = document.body;
					if( ayoola.style.hasClass( a, "pc_page_widgetmode" ) )  
					{
						ayoola.style.removeClass( a, "pc_page_widgetmode" );
						this.innerHTML = \'' . self::__( 'Show Widget Options' ) . '\';      
					}
					else
					{
						this.innerHTML = \'' . self::__( 'Hide Widget Options' ) . '\';
						ayoola.style.addClass( a, "pc_page_widgetmode" ); 
					}
				};
				topBarForButtons.appendChild( optionbar );

				//	Display options
				var optionbar = document.createElement( "span" );
				optionbar.innerHTML = \'...\';
				optionbar.className = " pc-btn pc-hide-children-children";
				optionbar.title = "Pop-up widget options";
				optionbar.onclick = function()
				{
					var a = document.body;
					if( ayoola.style.hasClass( a, "pc_page_widgetmode_popup" ) )  
					{
						ayoola.style.removeClass( a, "pc_page_widgetmode_popup" );
					}
					else
					{
						ayoola.style.addClass( a, "pc_page_widgetmode_popup" ); 
					}

				};
				topBarForButtons.appendChild( optionbar );

				//	Add options bar
				var optionbar = document.createElement( "span" );
				optionbar.innerHTML = \' ' . $pageVersionHTML . '\';
				optionbar.className = "pc-hide-children-children";
				optionbar.title = "Select page content version";
				topBarForButtons.appendChild( optionbar );

				//	Add show more options button
				var optionbar = document.createElement( "span" );
				optionbar.innerHTML = \'&raquo;&raquo;\';
				optionbar.className = " pc-btn";
				optionbar.title = "Show or hide more options";
				optionbar.onclick = function()
				{
					var a = this.parentNode.getElementsByClassName( "pc-hide-children-children" );
					for( var b = 0; b < a.length; b++ )  
					{
						switch( a[b].style.display )
						{
							case "inline-block":
								a[b].style.display = "";
								this.innerHTML = \'&raquo;&raquo;\';      
							break;
							default:
								a[b].style.display = "inline-block";
								this.innerHTML = \'&laquo;&laquo;\';
							break;
						}
					}
				};
				topBarForButtons.appendChild( optionbar );
		';
		;

		//	if we don't have values, prep the screen for editing'

		if( $this->noExistingContent )
		{
			$js .= '
			ayoola.events.add
			(
				window,
				"load",
				function()
				{
					//	everything is not clickable by default
					ayoola.style.addClass( document.body, "pc_page_widgetmode" );

				}
			);';
		}

		return $js;
	} 

    /**
     * Produce the mark-up for each draggable object
     *
     * @param string | array viewableObject Information
     * @return string Mark-Up to Display Viewable Objects
     */
    protected function isSaveMode()
    {
		if( $_POST || $this->isAutoSaveMode() ) //	create template for POSTed data
		{
			return true;
		}
		return false;

	}

    /**
     * Produce the mark-up for each draggable object
     *
     * @param string | array viewableObject Information
     * @return string Mark-Up to Display Viewable Objects
     */
    protected function isAutoSaveMode()
    {
		if( $this->_updateLayoutOnEveryLoad || $this->updateLayoutOnEveryLoad ) //	create template for POSTed data
		{
			return true;
		}
		return false;

	}

	/**
     * Produce the mark-up for each draggable object
     *
     * @param string | array viewableObject Information
     * @return string Mark-Up to Display Viewable Objects
     */
    protected function getViewableObject( $object )
    {
		// We can accept object name too
		$object =  is_string( $object ) ? $this->getObjectInfo( $object ) : $object;

		$html = null;

		//	We may send whatever info we want to be overwritten in array.
		$defaultObjectInfo = $this->getObjectInfo( $object['object_name'] );

		$object = array_merge( $defaultObjectInfo, $object );

		//	Retrieving object "interior" from the object class
		$getHTMLForLayoutEditor = 'getViewableObjectRepresentation';
		if( method_exists( $object['class_name'], $getHTMLForLayoutEditor ) )
		{
			$html .= $object['class_name']::$getHTMLForLayoutEditor( $object ); 
		}

		return $html;
    }

    /**
     * returns viewable object property
     *
     * @param void
     * @return string Mark-Up to Display Viewable Objects List
     */
    protected function getViewableObjects()
    {	
		if( null === $this->_viewableObjects )
		{
			$this->setViewableObjects();
		}

		return $this->_viewableObjects;
    }

    /**
     * Builds viewable object property
     *
     * @param void
     * @return string Mark-Up to Display Viewable Objects List
     */
    protected function setViewableObjects()
    {	
		// Bring the objects from db
		$table = Ayoola_Object_Table_ViewableObject::getInstance();
		if( ! $objects = (array) $table->select() )
		{

			return false;
		}
		$html = "<div id='viewable_objects'>";

		$this->_viewableSelect = null;

		$objects = self::sortMultiDimensionalArray( $objects, 'view_parameters' );

		foreach( $objects as $object )
		{
            $object['object_unique_id'] = 'object_unique_id_' . md5( $object['object_name'] );
			$html .= $this->getViewableObject( $object );
			$this->_viewableSelect .= "<option style='text-align:initial;' value='{$object['object_unique_id']}'>" . htmlspecialchars( self::__( $object['view_parameters'] ) ) . "</option>";	
		}

		unset( $objects ); // Free memory
		$html .= "</div>";
		return $this->_viewableObjects = $html;
    }

    /**
     * Merges widget Advanced Parameters with the main parameters
     *
     * @param array Parameters
     * @return 
     */
    public static function prepareParameters( $parameters )
    {
		//	Calculate advanced parameters at this level so that access levels might work
		if( ! empty( $parameters['advanced_parameters'] ) )
		{ 
			parse_str( $parameters['advanced_parameters'], $advanceParameters );
			@$injectedValues = array_combine( $advanceParameters['advanced_parameter_name'], @$advanceParameters['advanced_parameter_value'] ) ? : array();
			unset( $advanceParameters['advanced_parameter_name'] );

			$parameters += $advanceParameters ? : array();
			$parameters += $injectedValues;
			unset( $parameters['advanced_parameters'] );
		}
		return $parameters;
	}

    /**
     * This method saves the layout into the page data file
     *
     * @param 
     * @return 
     */
    public function saveXml()
    {
		if( ! $paths = $this->getPagePaths() ){ return false; }
		if( ! $_POST ){ return false; }

		$values['pageLayout'] = json_encode( $this->getValues() ); 

		// Retrieve the previous layout data from the page data file
		require_once 'Ayoola/Xml.php';
		$xml = new Ayoola_Xml();

		require_once 'Ayoola/Page.php';
		$default = Ayoola_Page::getDefaultPageFiles();

		$xml->load( $default['data'] );
		$xml->arrayAsCData( $values );
		$xml->save( $paths['data'] );
	} 
	// END OF CLASS
}