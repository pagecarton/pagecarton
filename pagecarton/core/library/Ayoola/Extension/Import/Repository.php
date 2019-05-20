<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Extension_Import_Repository
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Repository.php Monday 14th of May 2018 07:32PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Ayoola_Extension_Import_Repository extends Application_Article_ShowAll
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 98, 99 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Browse Plugins'; 
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_site = 'plugins.pagecarton.org'; 
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_pluginType = 'plugin'; 
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_pluginClass = 'Ayoola_Extension_Import'; 

	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
        set_time_limit(0); // unlimited max execution time

		if( ! empty( $_GET['install'] ) )
		{
       //     var_export( array( 'article_url' =>  @$_GET['install'] ) );
         //   var_export( Ayoola_Page_PageLayout::getInstance()->select( null, array( 'article_url' =>  @$_GET['install'] ) ) );

            $layout = Ayoola_Page_PageLayout::getInstance()->selectOne( null, array( 'article_url' =>  @$_GET['install'] ) );
            $url = 'https://' . static::$_site . '/tools/classplayer/get/name/Application_Article_View?article_url=' . $_GET['install'] . '&pc_widget_output_method=JSON';
            $feed = self::fetchLink( $url, array( 'time_out' => 288000, 'connect_time_out' => 288000, ) );
            $allFeed = json_decode( $feed, true );

        //    var_export( $allFeed );
            $install = 'Install';
            if( ! empty( $_GET['update'] ) )
            {
                $install = 'Update';
            }
            
            

            if( $layout['article_url'] === @$_GET['install'] && empty( $_GET['update'] ) )
            {
                $this->setViewContent( '<h1 class="pc-heading">' . @$_GET['title'] . ' Installed</h1>' );
                $this->setViewContent( '<a class="pc-btn" href="#" onclick="location.search+=\'&update=' . $_GET['install'] . '\'">Update</a></p>' );
            }
            else
            {
                $this->createConfirmationForm( $install . ' ' . static::$_pluginType . '', 'Download and install latest ' . static::$_pluginType . ' files and its components' );
                $this->setViewContent( '<h1 class="pc-heading">' . @$_GET['title'] . '</h1>' );
                $this->setViewContent( $this->getForm()->view() );
            }
            $photoUrl = 'https://' . static::$_site . '/tools/classplayer/get/object_name/Application_Article_PhotoViewer/?article_url=' . $_GET['install'] . '';
			$this->setViewContent( '<img style="width:100%;" src="' . $photoUrl . '&width=1500&height=600" alt="">' );
			$this->setViewContent( self::getMenu() );
            if( ! $values = $this->getForm()->getValues() ){ return false; }
            

            //   delete first if this is upgrade
       //     var_export( $layout['article_url'] );
        //    var_export( @$_GET['update'] );
        
            if( $layout['article_url'] === @$_GET['update'] )
            {
                $layout = Ayoola_Page_PageLayout::getInstance()->delete( array( 'article_url' =>  @$_GET['update'] ) );
          //      var_export( $layout['article_url'] );
           //     var_export( @$_GET['update'] );
            }

            $link = 'https://' . static::$_site . '/tools/classplayer/get/object_name/Application_Article_Type_Download/?article_url=' . $_GET['install'] . '&auto_download=1';
        //    var_export(  $link );

            $content = self::fetchLink( $link, array( 'time_out' => 28800, 'connect_time_out' => 28800, 'raw_response_header' => true, 'return_as_array' => true, ) );
            $filename = tempnam( CACHE_DIR, __CLASS__ ) . '';

            if(preg_match('/Content-Disposition: .*filename=([^0-9A-Za-z_-.]+)/', $content['options']['raw_response_header'], $matches)) {
                $filename .= $matches[1];
            //    var_export( $matches );
            }
            else
            {
                $filename .= '.tar.gz';
            }
                
        

       //     var_export( $filename );   
        //    var_export( $content['options'] );
        //    exit();
     //       var_export( strlen( $content ) );  
         //   file_put_contents( $filename, fopen( $link, 'r' ) );
            file_put_contents( $filename, $content['response'] );
            $values = static::getOtherInstallOptions( $filename );

        //    copy( $filename, $filename . '.copy.tar.gz' );

            // add screenshot
            $repository = 'Ayoola_Phar_Data';

            $repository = new $repository( $filename );
            //   var_export( $allFeed['modified_time'] );
            $repository->startBuffering(); 
            $repository['screenshot.jpg'] = file_get_contents( $photoUrl );
         //   var_export( $repository['layout_information'] );
        //    $previousData = json_decode( file_get_contents( $repository['layout_information'] ), true );
            if( $previousData = json_decode( file_get_contents( $repository['layout_information'] ), true ) OR $previousData = unserialize( file_get_contents( $repository['layout_information'] ) ) )
            {

            }
        //    var_export( $previousData );

            //  set current time to be able to calculate updates
            $previousData['modified_time'] = time();
            $previousData['creation_time'] = time();
        //    var_export( $previousData );

            $repository['layout_information'] = json_encode( $previousData );

            $repository->stopBuffering();
        //    $repository->compress( Ayoola_Phar::GZ ); 
        //    var_export( Ayoola_Doc::getFiles( dirname( $filename ) ) );
        //    exit();

            try
            {
                $class = new static::$_pluginClass( array( 'xno_init' => true, 'fake_values' => $values, 'path' => $filename, ) ); 
            //    $class->fakeValues = $values;
            //    $class->init();
            }
            catch( Exception $e )
            {
            //    echo $e->getMessage();
            //    $this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>' ); 
            }
        //    var_export( $class->getForm()->getValues() );
        //    var_export( $class->getForm()->getBadnews() );
        //	$this->setViewContent( '<h1 class="pc-heading">' . @$_GET['title'] . '</h1>' );
            if( ! $class->getForm()->getBadnews() )
            {
                
                $this->setViewContent( $class->view(), true );
             //   $this->setViewContent( '<p class="badnews">' . array_pop( $class->getForm()->getBadnews() ) . '</p>' );
            }
            else
            {
            	$this->setViewContent( '<p class="badnews">' . array_pop( $class->getForm()->getBadnews() ) . '</p>', true );
            }
 //           unlink( $filename );
	//		if( $this->deleteDb( false ) )
			{ 

            }
        }
        else
        {
		//	$this->setViewContent( self::getMenu(), true );
            $this->_parameter['pagination'] = true;
            $this->_parameter['no_of_post_to_show'] = 20;
            $this->_parameter['template_name'] = 'ItemsList';
            $this->_parameter['button_value'] = 'Install';
            $this->_parameter['markup_template_prepend'] = self::getMenu();
            $options = self::getMenuOptions();
            $category = ( @$_GET['category'] ? : $this->getDefaultCategory() );
        //    var_export( $options );

            if( $this->getDbData() )
            {
                //var_export( $this->getDbData() );
            }
            else
            {
                $this->noContentDefault();
                return false;
            }
        //    var_export( $category );
        //    var_export( $options[$category]['option_name'] );
            if( ! empty( $options[$category] ) )
            {
            	$this->_parameter['markup_template_prepend'] .= '<h3 class="pc_give_space_top_bottom">' . $options[$category]['option_name'] . ' Themes</h3>';
            }

            parent::init();
        }
    }		
    
    /**
     * 
     * 
     */
	public function noContentDefault()
    {
        if( $this->getDbData() )
        {
            //var_export( $this->getDbData() );
        }
        else
        {
        //    $this->setViewContent( '<h2 class="">Upload theme manually</h2>', true );
            $this->setViewContent( '<p class="pc-notify-info">Connect to the internet to download plugins directly from <a target="_blank" href="https://plugins.pagecarton.org">PageCarton Plugins </a> or download manually and upload here. <a target="_blank" href="https://plugins.pagecarton.org"><i class="fa fa-external-link pc_give_space"></i> PageCarton plugins </a></p>' );
            $this->setViewContent( Ayoola_Extension_Import::viewInLine() );
            return false;
        }
    }
    
    /**
     * Overides the parent class
     * 
     */
	public static function getOtherInstallOptions( $filename )
    {
        $values = array( 
                            'path' => $filename,
                            'article_url' => $_GET['install'],
        );
        return $values;
    }
    
    /**
     * Overides the parent class
     * 
     */
	public static function getMenuOptions()
    {
		$storage = self::getObjectStorage( array( 'id' => 'mdenu=ddffd', 'device' => 'File', 'time_out' => 446000, ) );
		if( ! $data = $storage->retrieve() )
        {
            $url = 'https://' . static::$_site . '/tools/classplayer/get/name/Application_Category_ShowAll?pc_widget_output_method=JSON';
            $feed = self::fetchLink( $url, array( 'time_out' => 288000, 'connect_time_out' => 288000, ) );
            $allFeed = json_decode( $feed, true );
           //    var_export( $url );
            //   var_export( $allFeed );
            $data = array();
            foreach( $allFeed as $each )
            {
          //     var_export( $each );
                $data[$each['category_name']] = array(
                    'url' => '?category=' . $each['category_name'] . '&' . http_build_query( $_GET ) . '',
                    'option_name' => $each['article_title'],
                    'title' => $each['article_title'],
                   
                    'append_previous_url' => 0, 'enabled' => 1, 'auth_level' => array( 99, 98 ), 'menu_id' => '1', 'option_id' => 0, 'link_options' => array( 'logged_in','logged_out' ),  
                ) + $each;
            //    $data += $each;
            }
          //     var_export( $data );

            $storage->store( $data );
        }
     //   var_export( $data );
        return $data;
    }
    
    /**
     * Overides the parent class
     * 
     */
	public static function getMenu()
    {
        $data = static::getMenuOptions();
        $data[] = array(
            'url' => '?category=DEFAULT',
            'option_name' => 'All Themes',
            'title' => 'All Categories',
            
            'append_previous_url' => 0, 'enabled' => 1, 'auth_level' => array( 99, 98 ), 'menu_id' => '1', 'option_id' => 0, 'link_options' => array( 'logged_in','logged_out' ),
        );

            $menu = Ayoola_Menu::viewInLine( array(
                                    'raw-options' => $data,
                                     'template_name' => 'HorizontalGrayish',
                               //     'raw-options' => $data,
            ) );            
//       var_export( $data );
        
		return $menu;
      //  self::v( $allFeed['channel']->item[0] );
     //   self::v( $allFeed['channel']->item[1] );

	}
    
    /**
     * 
     * 
     */
	public function getDefaultCategory()
    {
        ;
    }
    
    /**
     * Overides the parent class
     * 
     */
	public function setDbData( array $data = null )
    {
		if( is_array( $data ) )
		{
			$this->_dbData = $data;
			return true;
        }
        $options = array_keys( self::getMenuOptions() );
    //    self::v( $options );
        $category = ( @$_GET['category'] ? : $this->getDefaultCategory() );
        if( ! in_array( $category, $options ) )
        {
            $category = null;
        }
        $featured = null;
        if( ! empty( $category ) )
        {
            $featured = '&post_switch=featured';
        }
		$storage = self::getObjectStorage( array( 'id' => 'cssdcf-fw' . $category . $featured, 'device' => 'File', 'time_out' => $this->getParameter( 'cache_timeout' ) ? : 446000, ) );
		if( ! $data = $storage->retrieve() )
        {
        //    &category=' . 
            $url = 'https://' . static::$_site . '/widgets/Application_Article_RSS?category=' . $category . $featured;
            $feed = self::fetchLink( $url, array( 'time_out' => 28800, 'connect_time_out' => 28800, ) );
            $allFeed = (array) simplexml_load_string( $feed );
       //   self::v($feed_to_array);
        //   self::v($url);
    //     $allFeed['channel'] = (array) $allFeed['channel'];
            $data = array();
            foreach( $allFeed['channel']->item as  $each )
            {
                $each = (array) $each;
            //    self::v( $each );  
            //    if( empty( $each['featured'] ) )
                {
                //    continue; 
                }

                $data[] = array(
                    'article_url' => '?title=' . $each['title'] . '&layout_type=upload&install=' . $each['guid'] . '&' . http_build_query( $_GET ),
                //   'article_url' => $each['link'], 
                    'guid' => $each['guid'],
                    'article_title' => $each['title'],
                    'article_description' => $each['description'],
                    'article_creation_date' => strtotime( $each['pubDate'] ),
                    'article_modified_date' => strtotime( $each['pubDate'] ),
                );
        //       var_export( $each );
            }
            $storage->store( $data );
        }
//       var_export( $data );
        
		$this->_dbData = $data;
      //  self::v( $allFeed['channel']->item[0] );
     //   self::v( $allFeed['channel']->item[1] );

	}
			
    /**
     * 
     */
	public static function sanitizeData( &$data )
    {
	//	var_export( $data );
		$data['not_real_post'] = true; 
		$data['document_url'] = 'https://' . static::$_site . '/tools/classplayer/get/object_name/Application_Article_PhotoViewer/?max_width=850&max_height=540&article_url=' . @$data['guid'];
		$data['publish'] = '1'; 
		$data['auth_level'] = '0';   
	//	$data['allow_raw_data'] = true;    
	}
	// END OF CLASS
}
