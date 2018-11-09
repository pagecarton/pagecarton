	<?php
	/**
	* PageCarton Content Management System
	*
	* LICENSE
	*
	* @category   PageCarton CMS
	* @package    Ayoola_Filter_LayoutIdToPath
	* @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
	* @license    GNU General Public License version 2 or later; see LICENSE.txt
	* @version    $Id: LayoutIdToPath.php 10-25-2011 3.25pm ayoola $
	*/

	/**
	* @see Ayoola_Filter_Interface
	*/
	
	require_once 'Ayoola/Filter/Interface.php';


	/**
	* @category   PageCarton CMS
	* @package    Ayoola_Filter_LayoutIdToPath
	* @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
	* @license    GNU General Public License version 2 or later; see LICENSE.txt
	*/
	
	class Ayoola_Filter_LayoutIdToPath implements Ayoola_Filter_Interface
	{

		/**
		* 
		*
		* @var array
		*/
		protected $_pageInfo;

		public function __construct( $pageInfo = null)
		{
			$this->_pageInfo = $pageInfo;
		}
		
		//	cant work. must be included in the calling file for var to be available
	    public static function loadFile( $file )
		{
		//	if( is_file( $file ) )
			{
		//	var_export( $file );
			//	include_once $file;
			}
		}
	
		public static function getThemeIncludeFile( $themeName, $options, $type = 'include' )
		{
			$fileC = array();
			$fPaths = Ayoola_Page_Layout_Pages::getPagePaths( $themeName, '/default-layout' );
			if( $path = Ayoola_Loader::getFullPath( $fPaths[$type], $options ) )
			{
				$fileC[] = $path;
			//	return $path;
			}
			//	use default designed layout if available
			if( $path = Ayoola_Loader::getFullPath( 'documents/layout/' . $themeName . '/theme/' . $type . '', $options ) )
			{
			//
				$fileC[] = $path;
			
			//	var_export( $path );  
			//	var_export( file_get_contents( $path ) );
			//	include_once $path;
			//	return $path;
			}
			
			$pagePaths = Ayoola_Page::getPagePaths( '/layout/' . strtolower( $themeName ) . '/template' );
			//	var_export( $pagePaths );  
			
			if( $path = Ayoola_Loader::getFullPath( $pagePaths[$type], $options ) )
			{
				$fileC[] = $path;
			//	var_export( $path );    
			//	include_once $path;
			//	return $path;
			}
			if( $fileC )
			{
				if( $type === 'include' && ! empty( $options['multiple'] ) )
				{
					return $fileC;
				}
				else
				{
					return array_shift( $fileC );
				}
			}
			
		//	var_export( $path );
			return false;
		}

		public function filter( $value )
		{
			$table = Ayoola_Page_PageLayout::getInstance();
			
			//	Integer value means a layout ID is sent, otherwise Layout Name
		//		var_export( $checkInt );
			if( is_numeric( $value ) )
			{
				
			//	var_export( $value );
				$value = $table->selectOne( null, array( 'pagelayout_id' => $value ) );
				@$value = $value['layout_name'];
			//	var_export( $value );
			}
			else
			{
			//	$value = $value ? : 'default';
			//	var_export( $value );
				$isLayoutPage = stripos( $this->_pageInfo['url'], '/layout/' ) === 0;
				if( $isLayoutPage )
				{
					
					list(  ,$value ) = explode( '/', trim( $this->_pageInfo['url'], '/' ) );
					
				//	$files = Ayoola_Loader::getValidIncludePaths();
					
				//	var_export( $value );
				}
			//	$isLayoutPage = ( $isLayoutPage || stripos( $this->_pageInfo['url'], '/default-layout' ) === 0 );
			
				$dir = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS;
			//	var_export( $layoutData );
				$layoutData = $table->selectOne( null, array( 'layout_name' => $value ) );    
			//	var_export( $layoutData );
			//	var_export( $value );
				
				//	Allow a template editor that is similar to a page editor by allowing a page "/layout/$layoutName/template
				$url = '/layout/' . strtolower( @$layoutData['layout_name'] ? : $value ) . '/template';
				list(  , $themeName ) = explode( '/', trim( $url, '/' ) );
				
				$pagePaths = Ayoola_Page::getPagePaths( $url );
			//	var_export( $pagePaths );  
				//	use default designed layout if available
				$fPaths = Ayoola_Page_Layout_Pages::getPagePaths( $themeName, '/default-layout' );
				$mPaths = Ayoola_Page_Layout_Pages::getPageFile( $themeName, $this->_pageInfo['url'] );

				if( stripos( $this->_pageInfo['url'], '/default-layout' ) === 0 OR ! $PAGE_INCLUDE_FILE = Ayoola_Loader::getFullPath( $fPaths['include'], array( 'prioritize_my_copy' => true ) ) OR Ayoola_Loader::getFullPath( $mPaths, array( 'prioritize_my_copy' => true ) )  )
				{
					$PAGE_INCLUDE_FILE = 
					( Ayoola_Loader::getFullPath( 'documents/layout/' . $themeName . '/theme/include', array( 'prioritize_my_copy' => true ) )  
						?
						:
							Ayoola_Loader::getFullPath( $pagePaths['include'], array( 'prioritize_my_copy' => true ) ) );
				}
				if( stripos( $this->_pageInfo['url'], '/default-layout' ) === 0 OR ! $PAGE_TEMPLATE_FILE = Ayoola_Loader::getFullPath( $fPaths['template'], array( 'prioritize_my_copy' => true ), '' ) OR Ayoola_Loader::getFullPath( $mPaths, array( 'prioritize_my_copy' => true ) )   )
				{
					$PAGE_TEMPLATE_FILE = 
					( Ayoola_Loader::getFullPath( 'documents/layout/' . $themeName . '/theme/template', array( 'prioritize_my_copy' => true ) )
					?
					:
						Ayoola_Loader::getFullPath( $pagePaths['template'], array( 'prioritize_my_copy' => true ) ) ); 
				}
		//		$PAGE_TEMPLATE_FILE = Ayoola_Loader::getFullPath( $fPaths['template'], array( 'prioritize_my_copy' => true ) ) ? :
			//	$vpaths = Ayoola_Loader::getValidIncludePaths( $pagePaths['template'], array( 'prioritize_my_copy' => true ) );
			//	var_export( $PAGE_TEMPLATE_FILE );
				$time = time();
				$myClassName = __CLASS__;           
				$myPath = array_pop( explode( PC_BASE, Ayoola_Application::getDomainSettings( APPLICATION_PATH ) ) );
				//		var_export( $PAGE_TEMPLATE_FILE );    
				//		var_export( $isLayoutPage );    
				//		var_export( $this->_pageInfo );    
			//			var_export( $myPath );    
				if( $PAGE_INCLUDE_FILE && $PAGE_TEMPLATE_FILE && $this->_pageInfo && ! $isLayoutPage )
				{
					//	use global temp folder because of progenies
				//	$temIncludeFile = $dir . $pagePaths['include'] . '.tmp';      
					$temIncludeFile = tempnam( CACHE_DIR, __CLASS__ );           
				//	if( ! is_file( $temIncludeFile ) )
					{
						//	Must always refresh because we are using file_get_contents on file that could change
						//	Must include the include file because there is no closing php tag
						//	Ayoola_Loader::getFullPath( $pagePaths['include'] ) because $PAGE_INCLUDE_FILE is too static. We need to have something that won't break if site is exported to another server  
						file_put_contents( $temIncludeFile, "
						<?php 
						//		var_export( array_pop( explode( PC_BASE, Ayoola_Application::getDomainSettings( APPLICATION_PATH ) ) ) );
					//			var_export( __FILE__ );
					//			var_export( CACHE_DIR );
					//			var_export( stripos( __FILE__, Ayoola_Application::getDomainSettings( APPLICATION_DIR ) ) );
								//	ADDED TO CHECK WITH CACHE_DIR TOO BECAUSE OF EDITING THEME PAGES
								//	THEY WON'T HAVE CORRECT APPDIR
//								var_export( stripos( __FILE__,CACHE_DIR ) );
			//					var_export( Ayoola_Application::getDomainSettings( APPLICATION_DIR ) );
			//					var_export( Ayoola_Application::getDomainSettings() );
							if
							( 

								\$x_{$time} = {$myClassName}::getThemeIncludeFile( '{$themeName}', stripos( __FILE__, Ayoola_Application::getDomainSettings( APPLICATION_DIR ) ) !== false || stripos( __FILE__,CACHE_DIR ) !== false ? array( 'prioritize_my_copy' => true, 'multiple' => true,  ) : array( 'path_blacklist' => array_pop( explode( PC_BASE, Ayoola_Application::getDomainSettings( APPLICATION_PATH ) ) ), 'multiple' => true ) ) 
							)
							{
							//	var_export( \$x_{$time} );
							//	echo file_get_contents( \$x_{$time} );
								foreach( \$x_{$time} as \$each )
								{	
									include_once \$each;
								}
							}
		//					var_export( \$x_{$time} );
						?>
						" . ' ' . file_get_contents( $PAGE_TEMPLATE_FILE ) );   
						
					//	copy( $temIncludeFile, $PAGE_TEMPLATE_FILE . '.tmp' );
					//	include_once Ayoola_Loader::getFullPath( '{$pagePaths["include"]}', array( 'prioritize_my_copy' => true ) );
					}  
					$value = $temIncludeFile;     
					return $value;
				}
				elseif( $isLayoutPage )
				{
					//	if we have parent layout files, lets use it instead
				//	$layoutFiles = Ayoola_Loader::getValidIncludePaths( $pagePaths["include"] );
				//	if( count( $layoutFiles ) > 1 )
					{
						//	we have a parent template. lets make sure that's what is being used.
						//	strip APPLICATION_PATH so that we don't store full url'   
				//		$myPath = array_pop( explode( APPLICATION_PATH, Ayoola_Application::getDomainSettings( APPLICATION_PATH ) ) );
					//	self::getThemeIncludeFile( $themeName, array( 'path_blacklist' => $myPath ) );
				//		$includeFile = self::getThemeIncludeFile( $themeName, array( 'path_blacklist' => $myPath ) );
				//		var_export( $myPath );    
						$templateFile = self::getThemeIncludeFile( $themeName, array( 'path_blacklist' => $myPath ), 'template' );
			//		var_export( APPLICATION_PATH );
			//		var_export( APPLICATION_DIR );
				//	var_export( $myPath );
				//	var_export( $templateFile );
						
						//	use global temp folder because of progenies
					//	$temIncludeFile = $dir . $pagePaths['include'] . '.tmp';      
						$temIncludeFile = tempnam( CACHE_DIR, __CLASS__ );           
						if( is_file( $templateFile ) )
						{
							//	Must always refresh because we are using file_get_contents on file that could change
							//	Must include the include file because there is no closing php tag
							//	Ayoola_Loader::checkFile( $pagePaths['include'] ) because $PAGE_INCLUDE_FILE is too static. We need to have something that won't break if site is exported to another server  
							file_put_contents( $temIncludeFile, "
							<?php 
								if
								( 
									\$x_{$time} = {$myClassName}
									::getThemeIncludeFile( '{$themeName}', array( 'path_blacklist' => '{$myPath}' ) ) 
								)
								{
									include_once \$x_{$time};
								}
							?>
							" . ' ' . file_get_contents( $templateFile ) );      
		//		var_export( file_get_contents( $temIncludeFile ) );
							
						//	copy( $temIncludeFile, $PAGE_TEMPLATE_FILE . '.tmp' );
							$value = $temIncludeFile;     
							return $value;
						}    
					}
				//	var_export( $layoutFiles );
				//	var_export( $layoutFiles );
				
				}
				if( ! is_file( $dir . @$layoutData['pagelayout_filename'] ) )
				{
					//	Use the parent layout file
					if( ! $value ){ return false; }
					//	var_export( $value );
					$class = new Ayoola_Page_Layout_Creator();
					$class->setFilename( array( 'layout_name' => $value ) );
					$value = $class->getFilename();
				//	var_export( $value );
					
				}
				else
				{
					//	compatibility
					$value = $layoutData['pagelayout_filename'];
				}
			//	var_export( $this->_pageInfo['url'] );
			//	var_export( $url );
			}
			
		//	var_export( $value );
			return $value;
		}
	
	}
