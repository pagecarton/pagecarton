	<?php
	/**
	* PageCarton
	*
	* LICENSE
	*
	* @category   PageCarton
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
	* @category   PageCarton
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

			}
		}
	
		public static function getThemeIncludeFile( $themeName, $options, $type = 'include' )
		{

			$fileC = array();
			$fPaths = Ayoola_Page_Layout_Pages::getPagePaths( $themeName, '/default-layout' );
			$optionsForDefault = $options;
			//$optionsForDefault['path_blacklist'] .= ',/core/application';
			//var_export( $options );
			if( ! $path = Ayoola_Loader::getFullPath( $fPaths[$type], $optionsForDefault ) )
			{
				$fPaths[$type] = 'documents/layout/' . $themeName . '/theme/variant/auto/default-layout/' . $type;
				if( $path = Ayoola_Loader::getFullPath( $fPaths[$type], $options ) )
				{

				}
			}
			$fileC[] = $path;

			//var_export( $fileC );
			//var_export( $fPaths );

			//	use default designed layout if available
            $themeDir = 'documents/layout/' . $themeName . '/theme/' . $type . '';
            $themeDirV = 'documents/layout/' . $themeName . '/theme/variant/auto/' . $type . '';

			$optionsForNow = $options;

			if( empty( $options['always_blacklist'] ) )
			{
				unset( $optionsForNow['path_blacklist'] );
			}

			if( $paths = Ayoola_Loader::getValidIncludePaths( $themeDir, $optionsForNow ) )
			{

                if( $type === 'template' )
                {
                    $path = Ayoola_Loader::getFullPath( $themeDir, $optionsForNow );
                    $fileC[] = $path;
                }
                else
                {
				    $fileC = array_merge( $fileC, $paths );
                }


            }
			elseif( $path = Ayoola_Loader::getFullPath( $themeDir, $optionsForNow ) )
			{
                //  don't
				//	ayoola- but why?
			    //	$fileC[] = $path;

            }
            elseif( $paths = Ayoola_Loader::getValidIncludePaths( $themeDirV, $optionsForNow ) )
            {
				if( $type === 'template' )
                {
                    $path = Ayoola_Loader::getFullPath( $themeDir, $optionsForNow );
                    $fileC[] = $path;
                }
                else
                {
				    $fileC = array_merge( $fileC, $paths );
                }

            }
            $themeName = strtolower( $themeName );
			
			$pagePaths = Ayoola_Page::getPagePaths( '/layout/' . strtolower( $themeName ) . '/template' );

			
			if( $path = Ayoola_Loader::getFullPath( $pagePaths[$type], $options ) )
			{
				$fileC[] = $path;
            }

			//var_export( $fileC );
			//var_export( $optionsForNow  );   
			//var_export( $options  );   


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
			return false;
		}

		public function filter( $value )
		{
			$table = Ayoola_Page_PageLayout::getInstance();
			
			//	Integer value means a layout ID is sent, otherwise Layout Name
			if( is_numeric( $value ) )
			{

				$value = $table->selectOne( null, array( 'pagelayout_id' => $value ) );
				@$value = $value['layout_name'];

			}
			else
			{

				$isLayoutPage = stripos( $this->_pageInfo['url'], '/layout/' ) === 0;
				if( $isLayoutPage )
				{
					
					list(  ,$value ) = explode( '/', trim( $this->_pageInfo['url'], '/' ) );

				}

			
				$dir = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS;

				$layoutData = $table->selectOne( null, array( 'layout_name' => $value ) );    
				
				//	Allow a template editor that is similar to a page editor by allowing a page "/layout/$layoutName/template
				$url = '/layout/' . strtolower( @$layoutData['layout_name'] ? : $value ) . '/template';
				list(  , $themeName ) = explode( '/', trim( $url, '/' ) );
				
				$pagePaths = Ayoola_Page::getPagePaths( $url );

				//	use default designed layout if available
				$fPaths = Ayoola_Page_Layout_Pages::getPagePaths( $themeName, '/default-layout' );
				$mPaths = Ayoola_Page_Layout_Pages::getPageFile( $themeName, $this->_pageInfo['url'] );


				if( 
                    stripos( $this->_pageInfo['url'], '/default-layout' ) === 0 
                    OR ! $PAGE_INCLUDE_FILE = Ayoola_Loader::getFullPath( $fPaths['include'], array( 'prioritize_my_copy' => true ) ) 
                    OR Ayoola_Loader::getFullPath( $mPaths, array( 'prioritize_my_copy' => true ) )  
                )
				{
					$PAGE_INCLUDE_FILE = 
					( Ayoola_Loader::getFullPath( 'documents/layout/' . $themeName . '/theme/include', array( 'prioritize_my_copy' => true ) )  
						? : Ayoola_Loader::getFullPath( $pagePaths['include'], array( 'prioritize_my_copy' => true ) ) );
                            
                    //  autogenerated theme file
					$PAGE_INCLUDE_FILE = $PAGE_INCLUDE_FILE ?
						: Ayoola_Loader::getFullPath( 'documents/layout/' . $themeName . '/theme/variant/auto/include', array( 'prioritize_my_copy' => true ) );
				}
				if( 
                    stripos( $this->_pageInfo['url'], '/default-layout' ) === 0 
                    OR ! $PAGE_TEMPLATE_FILE = Ayoola_Loader::getFullPath( $fPaths['template'], array( 'prioritize_my_copy' => true ), '' ) 
                    OR Ayoola_Loader::getFullPath( $mPaths, array( 'prioritize_my_copy' => true ) )   )
				{
					$PAGE_TEMPLATE_FILE = 
					( Ayoola_Loader::getFullPath( 'documents/layout/' . $themeName . '/theme/template', array( 'prioritize_my_copy' => true ) )
					? : Ayoola_Loader::getFullPath( $pagePaths['template'], array( 'prioritize_my_copy' => true ) ) ); 
                    
                    //  autogenerated theme file
					$PAGE_TEMPLATE_FILE = $PAGE_TEMPLATE_FILE ? : Ayoola_Loader::getFullPath( 'documents/layout/' . $themeName . '/theme/variant/auto/template', array( 'prioritize_my_copy' => true ) ); 

				}

				$time = time();
				$myClassName = __CLASS__;           
				$myPath = array_pop( explode( PC_BASE, Ayoola_Application::getDomainSettings( APPLICATION_PATH ) ) );

				//var_export( $this->_pageInfo );
				//var_export( $PAGE_TEMPLATE_FILE );


				if( $PAGE_INCLUDE_FILE && $PAGE_TEMPLATE_FILE && $this->_pageInfo && ! $isLayoutPage )
				{
					//	use global temp folder because of progenies

					$temIncludeFile = tempnam( CACHE_DIR, __CLASS__ );           
					
                    //	Must always refresh because we are using file_get_contents on file that could change
                    //	Must include the include file because there is no closing php tag
                    //	Ayoola_Loader::getFullPath( $pagePaths['include'] ) because $PAGE_INCLUDE_FILE is too static. We need to have something that won't break if site is exported to another server  
                    Ayoola_File::putContents( $temIncludeFile, "
                    <?php 

                            //	ADDED TO CHECK WITH CACHE_DIR TOO BECAUSE OF EDITING THEME PAGES
                            //	THEY WON'T HAVE CORRECT APPDIR
                        if
                        ( 

                            \$x_{$time} = {$myClassName}::getThemeIncludeFile( '{$themeName}', stripos( __FILE__, Ayoola_Application::getDomainSettings( APPLICATION_DIR ) ) !== false || stripos( __FILE__,CACHE_DIR ) !== false ? array( 'prioritize_my_copy' => true, 'multiple' => true,  ) : array( 'path_blacklist' => @array_pop( explode( PC_BASE, Ayoola_Application::getDomainSettings( APPLICATION_PATH ) ) ), 'multiple' => true ) ) 
                        )
                        {

                            if( \$x_{$time} )
                            foreach( \$x_{$time} as \$each )
                            {	
                                include_once \$each;
                            }
                        }

                    ?>
                    " . ' ' . file_get_contents( $PAGE_TEMPLATE_FILE ) );   

					
					$value = $temIncludeFile;     
					return $value;
				}
				elseif( $isLayoutPage )
				{
					//	if we have parent layout files, lets use it instead		
                    //	we have a parent template. lets make sure that's what is being used.
                    //	strip APPLICATION_PATH so that we don't store full url'   

                    $templateFile = self::getThemeIncludeFile( $themeName, array( 'path_blacklist' => $myPath, 'always_blacklist' => true, 'multiple' => true ), 'template' );  
                                    
					//var_export( $templateFile );
					//exit();

    
                    //	use global temp folder because of progenies
                    $temIncludeFile = tempnam( CACHE_DIR, __CLASS__ );           
                    if( is_file( $templateFile ) )
                    {
                        //	Must always refresh because we are using file_get_contents on file that could change
                        //	Must include the include file because there is no closing php tag
                        //	Ayoola_Loader::checkFile( $pagePaths['include'] ) because $PAGE_INCLUDE_FILE is too static. We need to have something that won't break if site is exported to another server  
                        Ayoola_File::putContents( $temIncludeFile, "
                        <?php 
                            if
                            ( 
                                \$x_{$time} = {$myClassName}
                                ::getThemeIncludeFile( '{$themeName}', array( 'path_blacklist' => '{$myPath}', 'always_blacklist' => true ) ) 
                            )
                            {
                                //    PageCarton_Widget::v( '{$myPath}' );
                                //    PageCarton_Widget::v( \$x_{$time} );
                                include_once \$x_{$time};
                            }
                        ?>
                        " . ' ' . file_get_contents( $templateFile ) );      
        
                        $value = $temIncludeFile;     
                        return $value;
                    }    
					

				
				}
				if( ! is_file( $dir . @$layoutData['pagelayout_filename'] ) )
				{
                //    var_export( $dir );
                //    var_export( $layoutData );

                    //	Use the parent layout file
					if( ! $value ){ return false; }

					$class = new Ayoola_Page_Layout_Creator();
					$class->setFilename( array( 'layout_name' => $value ) );
					$value = $class->getFilename();
					
				}
				else
				{
					//	compatibility
					$value = $layoutData['pagelayout_filename'];
				}

			}
			return $value;
		}
	
	}
