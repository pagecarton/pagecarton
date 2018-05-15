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
		if( ! empty( $_GET['install'] ) )
		{
			$this->createConfirmationForm( 'Download theme', 'Download and Install Theme and its Components' );
			$this->setViewContent( $this->getForm()->view(), true);
			if( ! $values = $this->getForm()->getValues() ){ return false; }
            $link = 'https://themes.pagecarton.org/tools/classplayer/get/object_name/Application_Article_Type_Download/?article_url=' . $_GET['install'] . '&auto_download=1';

            set_time_limit(0); // unlimited max execution time
            $content = self::fetchLink( $link, array( 'time_out' => 28800, 'connect_time_out' => 28800, ) );
            $filename = tempnam( sys_get_temp_dir(), __CLASS__ );
     //       var_export( $link );
     //       var_export( strlen( $content ) );
         //   file_put_contents( $filename, fopen( $link, 'r' ) );
            file_put_contents( $filename, $content );
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
   //   else
        {
            $this->_parameter['pagination'] = true;
            $this->_parameter['no_of_post_to_show'] = 20;
            $this->_parameter['template_name'] = 'ItemsList';
            $this->_parameter['button_value'] = 'Install';
            parent::init();
        }
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
        $feed = 'https://themes.pagecarton.org/widgets/Application_Article_RSS';
        $feed = self::fetchLink( $feed );
        $allFeed = (array) simplexml_load_string($feed);
      //  self::v($feed_to_array);
        $items = $allFeed['channel']['item'];
   //     $allFeed['channel'] = (array) $allFeed['channel'];
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
