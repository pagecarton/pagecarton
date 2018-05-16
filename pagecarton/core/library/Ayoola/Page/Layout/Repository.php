<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Layout_Repository
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Repository.php Monday 14th of May 2018 07:32PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Ayoola_Page_Layout_Repository extends Application_Article_ShowAll
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 0 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Browse Themes'; 

	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
        set_time_limit(0); // unlimited max execution time

		if( ! empty( $_GET['install'] ) )
		{
			$this->createConfirmationForm( 'Install theme', 'Download and Install Theme and its Components' );
			$this->setViewContent( $this->getForm()->view(), true);
			if( ! $values = $this->getForm()->getValues() ){ return false; }
            $link = 'https://themes.pagecarton.org/tools/classplayer/get/object_name/Application_Article_Type_Download/?article_url=' . $_GET['install'] . '&auto_download=1';

            $content = self::fetchLink( $link, array( 'time_out' => 28800, 'connect_time_out' => 28800, 'raw_response_header' => true, 'return_as_array' => true, ) );
            $filename = tempnam( sys_get_temp_dir(), __CLASS__ ) . '';

            if(preg_match('/Content-Disposition: .*filename=([^ ]+)/', $content['options']['raw_response_header'], $matches)) {
                $filename .= $matches[1];
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
		    $options =  array( 
							'auto_section',  
							'auto_menu', 
							); 
            $values = array( 
                                'path' => $filename,
                                'layout_label' => $_GET['layout_label'],
                             //   'theme_url' => 'cccccc',
                                'layout_options' => $options,
                                'layout_type' => 'upload',
            );
            $class = new Ayoola_Page_Layout_Creator( array( 'no_init' => true, 'fake_values' => $values, 'path' => $filename ) );
            $class->fakeValues = $values;
            $class->init();
        //    var_export( $class->getForm()->getBadnews() );
			$this->setViewContent( $class->view(), true );
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
            parent::init();
        }
    }		
    
    /**
     * Overides the parent class
     * 
     */
	public static function getMenu()
    {
		$storage = self::getObjectStorage( array( 'id' => 'menu=ffd', 'device' => 'File', 'time_out' => 446000, ) );
		if( ! $data = $storage->retrieve() )
        {
            $feed = 'https://themes.pagecarton.org/tools/classplayer/get/name/Application_Category_ShowAll?pc_widget_output_method=JSON';
            $feed = self::fetchLink( $feed, array( 'time_out' => 28800, 'connect_time_out' => 28800, ) );
            $allFeed = json_decode( $feed, true );
          //     var_export( $allFeed );
            $data = array();
            $data[] = array(
                'url' => '?',
                'option_name' => 'All Categories',
                'title' => 'All Categories',
                
                'append_previous_url' => 0, 'enabled' => 1, 'auth_level' => array( 99, 98 ), 'menu_id' => '1', 'option_id' => 0, 'link_options' => array( 'logged_in','logged_out' ),
            );
            foreach( $allFeed as $each )
            {
          //     var_export( $each );
                $data[] = array(
                    'url' => '?category=' . $each['category_name'] . '',
                    'option_name' => $each['article_title'],
                    'title' => $each['article_title'],
                   
                    'append_previous_url' => 0, 'enabled' => 1, 'auth_level' => array( 99, 98 ), 'menu_id' => '1', 'option_id' => 0, 'link_options' => array( 'logged_in','logged_out' ),
                );
            }
          //     var_export( $data );

            $storage->store( $data );
        }
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
		$storage = self::getObjectStorage( array( 'id' => 'cdcffw' . @$_GET['category'], 'device' => 'File', 'time_out' => $this->getParameter( 'cache_timeout' ) ? : 446000, ) );
		if( ! $data = $storage->retrieve() )
        {
            $feed = 'https://themes.pagecarton.org/widgets/Application_Article_RSS?category=' . @$_GET['category'];
            $feed = self::fetchLink( $feed, array( 'time_out' => 28800, 'connect_time_out' => 28800, ) );
            $allFeed = (array) simplexml_load_string($feed);
        //  self::v($feed_to_array);
    //     $allFeed['channel'] = (array) $allFeed['channel'];
            $data = array();
            foreach( $allFeed['channel']->item as  $each )
            {
                $each = (array) $each;
                $data[] = array(
                    'article_url' => '?layout_label=' . $each['title'] . '&layout_type=upload&install=' . $each['guid'],
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
		$data['document_url'] = 'http://themes.pagecarton.org/tools/classplayer/get/object_name/Application_Article_PhotoViewer/?max_width=850&max_height=540&article_url=' . @$data['guid'];
		$data['publish'] = '1'; 
		$data['auth_level'] = '0';   
	//	$data['allow_raw_data'] = true; 
	}
	// END OF CLASS
}
