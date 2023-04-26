<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Abstract_Playable
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com) 
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Playable.php 4.26.2012 10.08am ayoola $
 */

/**
 * @see Ayoola_Exception 
 * @see Ayoola_Object_Interface_Viewable 
 * @see Ayoola_Abstract_Viewable 
 */
 
require_once 'Ayoola/Exception.php';
require_once 'Ayoola/Object/Interface/Playable.php';
require_once 'Ayoola/Abstract/Viewable.php';

/**
 * @category   PageCarton
 * @package    Ayoola_Abstract_Playable
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Abstract_Playable extends Ayoola_Abstract_Viewable implements Ayoola_Object_Interface_Playable
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = false;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 99;
	
    /**
     * Singleton instance
     *
     * @var self
     */
	protected static $_properties;
	
    /**
     * Array of data response to send as JSON or PHP Serial or other standard form Response
     *
     * @var array
     */
	protected $_objectData = array();
	
    /**
     * Response mode 
     *
     * @var array
     */
	protected $_playMode = self::PLAY_MODE_DEFAULT;
		
    /**
     * 
     *
     */
	const PLAY_MODE_DEFAULT = self::PLAY_MODE_HTML;
		
    /**
     * 
     *
     */
	const PLAY_MODE_HTML = 'HTML';
		
    /**
     * 
     *
     */
	const PLAY_MODE_MUTE = 'MUTE';
		
    /**
     * 
     *
     */
	const PLAY_MODE_JSON = 'JSON';
		
    /**
     * 
     *
     */
	const PLAY_MODE_JSONP = 'JSONP';
		
    /**
     * 
     *
     */
	const PLAY_MODE_PHP = 'PHP';
	
    /** 
     * Filter for xss
     * 
     */
	public static function filterReplacement( & $replacement, $key = null, array $filters = null )
    {
		$first = $replacement;
		if( ! is_scalar( $replacement )  )
		{
			return $replacement;
        }
        if( $key === 'article_content' )
        {
            $replacement = self::cleanHTML( $replacement );

        }
        elseif( stripos( $key, '_html' ) )
        {

            $replacement = self::cleanHTML( $replacement );
        }
        else
        {
            $replacement = strip_tags( $replacement );
            $replacement = htmlentities( $replacement, null, null, false );
        }
		if( $first != $replacement )
		{

		}
        $replacement = self::filterTemplateData( $key, $replacement, $filters );
	}
	
    /** 
     * Extract the post theme from string for replacing placeholders
     * 
     * @param string $template
     * @param int $key
     * @return string 
     */
	public static function getPostTheme( $template, $key = 0, $namespace = null )
    {
        
        if( ! is_numeric( $key ) && empty( $namespace ) && $key !== null )
        {
            $namespace = $key;
        }
        if( $namespace )
        {
            $namespace .= '-'; 
        }
        $startText = '<!--{{{' . $key . '}}}';
        $endText = '{{{' . $key . '}}}-->';
        if( strpos( $template, $startText ) === false )
        {
            $startText = '<data-' . $key . '>';
            $endText = '</data-' . $key . '>';
            if( strpos( $template, $startText ) === false )
            {
                $startText = '<' . $namespace . 'repeat>';
                $endText = '</' . $namespace . 'repeat>';
                if( strpos( $template, $startText ) === false && $key !== 0 )
                {
                    $startText = '<!--{{{' . $namespace . '0}}}';
                    $endText = '{{{' . $namespace . '0}}}-->';
                }    
            }    
        }
        $start = strpos( $template, $startText ) + strlen( $startText );
        $length = strpos( $template, $endText ) - $start;
        $postTheme = substr( $template, $start, $length );
        return array( 'theme' => $postTheme, 'start' => $startText, 'end' => $endText );
    }
	
    /** 
     * 
     * 
     */
	public static function filterTemplateData( &$dataKey, &$dataValue, array &$filters = null )
    {
        // $values['markup_template_data_filters']
        if( ! empty( $filters[$dataKey] ) AND is_array( $filters[$dataKey] ) )
        {
            foreach( $filters[$dataKey] as $eachFilter => $arguments )
            {
                if( is_numeric( $eachFilter ) && is_string( $arguments ) )
                {
                    $eachFilter = $arguments;
                }
                if( ! is_callable( $eachFilter ) )
                {
                    continue;
                }
                array_unshift( $arguments, $dataValue );
                //echo ENT_QUOTES;
                $dataValue = call_user_func_array( $eachFilter, $arguments );
            }  
        }
        return $dataValue;
    }
    
    /** 
     * Replace placeholders in notification Info
     * 
     */
	public static function replacePlaceholders( $template, array $values )
    {
        if( is_array( $template ) )
        {
            foreach( $template as $eachTemplateKey => $eachTemplate )
            {
                $template[$eachTemplateKey] = self::replacePlaceholders( $eachTemplate, $values );
            }
        }

		$search = array();
		$replace = array();
		$defaultSearch = array();
		$defaultSearch['pc_domain'] = $defaultSearch['pc_domain'] = Ayoola_Page::getDefaultDomain();
		$defaultSearch['pc_url_prefix'] = Ayoola_Application::getUrlPrefix();
		$defaultSearch['placeholder_prefix'] = @$values['placeholder_prefix'] ? : '@@@';
		$defaultSearch['placeholder_suffix'] = @$values['placeholder_suffix'] ? : '@@@';
        $defaultSearch['pc_background_color'] = Application_Settings_Abstract::getSettings( 'Page', 'background_color' ) ? : '#333333';
        if( stripos( $template, "pc_background_color_rgb" ) )
        {
            list($r, $g, $b) = sscanf( $defaultSearch['pc_background_color'], "#%02x%02x%02x" );
            $defaultSearch['pc_background_color_rgb'] = $r . ',' . $g . ',' . $b;
        }
        $defaultSearch['pc_font_color'] = Application_Settings_Abstract::getSettings( 'Page', 'font_color' ) ? : '#cccccc';
        //  comment some content till real output
        $defaultSearch['<!--//'] = '';
        $defaultSearch['//-->'] = '';
        $defaultSearch['<repeat>'] = '';
        $defaultSearch['</repeat>'] = '';
		$values = $values + $defaultSearch;
        $replaceInternally = false;
        $iTemplate = null;
        $postTheme = null;
		foreach( $values as $key => $value )
		{
			if( ! is_array( $value ) && stripos( $template, $values['placeholder_prefix'] . $key . $values['placeholder_suffix'] ) )
			{
				$search[] = $values['placeholder_prefix'] . $key . $values['placeholder_suffix'];
				@$values['pc_no_data_filter'] ? : self::filterReplacement( $value, $key, $values['markup_template_data_filters'] );
                $replace[] = $value;	

			}
			elseif( is_array( $value ) )
			{
				if( empty( $postTheme ) && is_numeric( $key ) )
				{
                    $postKey = null;
                    if( ! is_numeric( $key ) )
                    {
                        $postKey = $key;
                    }
                    $postThemeInfo = self::getPostTheme( $template, $postKey, $values['pc_replace_namespace'] );
                    if( stripos( $template, $postThemeInfo['start'] ) !== false )
                    {
                        $postTheme = $postThemeInfo['theme'];
                        $taggedPostTheme = $postThemeInfo['start'] . $postTheme . $postThemeInfo['end'];
                        $otherPostsPlaceholder = $values['placeholder_prefix'] . 'pc_other_posts_goes_here' . $values['placeholder_suffix'];

                        $search[] = $postThemeInfo['start'];
                        $replace[] = '';          
                        $search[] = $postThemeInfo['end'];
                        $replace[] = '';          
                        if( ! empty( $postKey ) && count(array_filter(array_keys($value), 'is_string')) > 0 )
                        {
                           $func = __METHOD__;
                            $taggedPostThemeY = $func( $postTheme, $value + array( 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', ) ); 
                            $template = str_replace( $taggedPostTheme, $taggedPostThemeY, $template );
                            continue;
                        }
                        if( stripos( $template, $otherPostsPlaceholder ) !== false || stripos( $template, $values['placeholder_prefix'] . 'pc_post_item_' ) !== false )
                        {
                            $replaceInternally = true;
                            $template = @str_ireplace( $taggedPostTheme, '', $template );  
                        }
                        elseif( stripos( $template, '<!--{{{1}}}' ) === false && stripos( $template, '<data-1>' ) === false )
                        {
                            //	if we are not listing one by one, then autofix {{{pc_other_posts_goes_here}}}
                            $template = str_replace( $taggedPostTheme, $taggedPostTheme . $otherPostsPlaceholder, $template );
                            $replaceInternally = true;
                            $template = @str_replace( $taggedPostTheme, '', $template );  
                        }
                    }

                }

                $postThemeInfo = self::getPostTheme( $template, $key, $values['pc_replace_namespace'] );
                $func = __METHOD__;

                if( stripos( $template, $postThemeInfo['start'] ) !== false )
                {
                    $taggedPostThemeXxx = $postThemeInfo['start'] . $postThemeInfo['theme'] . $postThemeInfo['end'];
                    if( ! is_numeric( $key ) && ! count(array_filter(array_keys($value), 'is_string')) > 0 && ! count(array_filter(array_values($value), 'is_array')) > 0 )
                    {
                        foreach( $value as $eKey => $eValue )
                        {
                            $value[$eKey] = array( $key => $eValue );
                        } 
                        $value['pc_replace_namespace'] = $key;
                    }
                    elseif( is_numeric( $key ) && count(array_filter(array_keys($value), 'is_string')) > 0 )
                    {

                    }
                    elseif( ! is_numeric( $key ) )
                    {
                        $value['pc_replace_namespace'] = $key;
                    }
                    $taggedPostThemeYyy = $func( $postThemeInfo['theme'], $value + $defaultSearch );   
                    $template = str_replace( $taggedPostThemeXxx, $taggedPostThemeYyy, $template );
                    continue;
                }
                elseif( ! is_numeric( $key ) )
                {
                   continue;
                }
  
				//	CLEAR HTML comments like <!--{{{0}}} {{{0}}}-->
				$search[] = '<!--' . $values['placeholder_prefix'] . $key . $values['placeholder_suffix'] . '';
				$replace[] = '';  
				$search[] = '' . $values['placeholder_prefix'] . $key . $values['placeholder_suffix'] . '-->';
				$replace[] = ''; 
				$search[] = '<data-' . $key . '>';
				$replace[] = '';  
				$search[] = '</data-' . $key . '>';
				$replace[] = ''; 

				$iSearch = array();
				$iReplace = array();
				$jSearch = array();
				$jReplace = array();
                $taggedPostThemeYy = null;
                $numberedPostTheme = null;
                foreach( $value as $eachKey => $eachValue )
				{
					@$values['pc_no_data_filter'] ? : self::filterReplacement( $eachValue, $eachKey, $values['markup_template_data_filters'] );
					if( is_array( $eachValue ) )
					{
                        $postThemeInfo = self::getPostTheme( $template, $eachKey, $values['pc_replace_namespace'] );
                        $templateToUse = $template;
                        if( stripos( $template, $postThemeInfo['start'] ) === false  )
                        {
                            $postThemeInfo = self::getPostTheme( $postTheme, $eachKey, $values['pc_replace_namespace'] );
                            if( stripos( $postTheme, $postThemeInfo['start'] ) !== false  )
                            {
                                $templateToUse = $postTheme;
                                $numberedPostTheme = $postTheme;

                            }
                        }

                        
                        if( stripos( $template, $postThemeInfo['start'] ) !== false && count(array_filter(array_keys($eachValue), 'is_string')) > 0 )
                        {
                            $func = __METHOD__;
                            $taggedPostThemeXx = $postThemeInfo['start'] . $postThemeInfo['theme'] . $postThemeInfo['end'];
                            $taggedPostThemeYy = $func( $postThemeInfo['theme'], $eachValue + $defaultSearch );   
                            $template = str_replace( $taggedPostThemeXx, $taggedPostThemeYy, $template );
                        }
                        elseif( stripos( $template, $values['placeholder_prefix'] . $eachKey ) !== false )
                        {
                            foreach( $eachValue as $vKey => $eachValueV )
                            {
                                if( ! is_scalar( $eachValueV ) )
                                {
                                    continue; 
                                }
                                if( stripos( $template, $postThemeInfo['start'] ) !== false )
                                {
                                    $taggedPostThemeXx = $postThemeInfo['start'] . $postThemeInfo['theme'] . $postThemeInfo['end'];
                                    $taggedPostThemeYy .= str_replace( $values['placeholder_prefix'] . $eachKey . $values['placeholder_suffix'], $eachValueV , $postThemeInfo['theme'] );   
                                }
                                elseif( $replaceInternally & is_numeric( $key )  )
                                {
                                    $iSearch[] = $values['placeholder_prefix'] . $eachKey . '_' . $vKey . $values['placeholder_suffix'] . $values['placeholder_prefix'] . '0' . $values['placeholder_suffix'];
                                    @$values['pc_no_data_filter'] ? : self::filterReplacement( $eachValueV );
                                    $iReplace[] = $eachValueV; 
                                }
                                else
                                {
                                    $jSearch[] = $values['placeholder_prefix'] . $eachKey . '_' . $vKey . $values['placeholder_suffix'] . $values['placeholder_prefix'] . $key . $values['placeholder_suffix'];
                                    $jReplace[] = $eachValueV;  	
                                }    
                            }
                            if( $taggedPostThemeYy )
                            {
                                $template = str_replace( $taggedPostThemeXx, $taggedPostThemeYy, $template );
                            }

                        }    
                        elseif( stripos( $numberedPostTheme, $values['placeholder_prefix'] . $eachKey ) !== false )
                        {
                            foreach( $eachValue as $vKey => $eachValueV )
                            {
                                if( ! is_scalar( $eachValueV ) )
                                {
                                    continue; 
                                }

                                if( stripos( $numberedPostTheme, $postThemeInfo['start'] ) !== false )
                                {
                                    $taggedPostThemeXx = $postThemeInfo['start'] . $postThemeInfo['theme'] . $postThemeInfo['end'];
                                    $taggedPostThemeYy .= str_replace( $values['placeholder_prefix'] . $eachKey . $values['placeholder_suffix'], $eachValueV , $postThemeInfo['theme'] );   
                                }
                                elseif( $replaceInternally & is_numeric( $key )  )
                                {
                                    $iSearch[] = $values['placeholder_prefix'] . $eachKey . '_' . $vKey . $values['placeholder_suffix'] . $values['placeholder_prefix'] . '0' . $values['placeholder_suffix'];
                                    @$values['pc_no_data_filter'] ? : self::filterReplacement( $eachValueV );
                                    $iReplace[] = $eachValueV; 
                                }
                                else
                                {
                                    $jSearch[] = $values['placeholder_prefix'] . $eachKey . '_' . $vKey . $values['placeholder_suffix'] . $values['placeholder_prefix'] . $key . $values['placeholder_suffix'];
                                    $jReplace[] = $eachValueV;  	
                                }    
                            }
                            if( $taggedPostThemeYy )
                            {
                                $numberedPostTheme = str_replace( $taggedPostThemeXx, $taggedPostThemeYy, $numberedPostTheme );
                            }

                        }    
					}
					else
					{
    
						//	placeholder now {{{key}}}{{{0}}}
					    //	if( $replaceInternally & $key > 0 )
						//	skipping index 0 makes the first on the list be info about current post on the page
						if( $replaceInternally & is_numeric( $key ) && $eachKey && stripos( $postTheme, $values['placeholder_prefix'] . $eachKey . $values['placeholder_suffix'] ) )
						{
                            if( stripos( $postTheme, $values['placeholder_prefix'] . $eachKey . $values['placeholder_suffix'] . $values['placeholder_prefix'] . '0' . $values['placeholder_suffix'] ) !== false )
                            {
                                $iSearch[] = $values['placeholder_prefix'] . $eachKey . $values['placeholder_suffix'] . $values['placeholder_prefix'] . '0' . $values['placeholder_suffix'];
                                $iReplace[] = $eachValue;  
                            }
                            if( stripos( $postTheme, $values['placeholder_prefix'] . $eachKey . $values['placeholder_suffix'] ) !== false )
                            {
                                $iSearch[] = $values['placeholder_prefix'] . $eachKey . $values['placeholder_suffix'];
                                $iReplace[] = $eachValue; 
                            }

						}  
                        elseif( @$replaceInternally & ! is_numeric( $key ) && stripos( $postThemeInfo['theme'], $values['placeholder_prefix'] . $key ) !== false )
                        {
                               $iTemplate .= str_replace( $values['placeholder_prefix'] . $key . $values['placeholder_suffix'], $eachValue , $postThemeInfo['theme'] );   
                        }
						elseif( stripos( $template, $values['placeholder_prefix'] . $eachKey ) !== false )
						{
                           $jSearch[] = $values['placeholder_prefix'] . $eachKey . $values['placeholder_suffix'] . $values['placeholder_prefix'] . $key . $values['placeholder_suffix'];
                            $jReplace[] = $eachValue;  	
                            
                            //  This kind of replacement are not able to use shorthand {{{field}}}
                            //  Because all template is tested as one and 
                            //  this may cause only the first item only to be used in all

                        }
					}
 				}
                if( @$replaceInternally && $iSearch )
				{
					foreach( $defaultSearch as $ckey => $cccc )
					{
						$iSearch[] = $values['placeholder_prefix'] . $ckey . $values['placeholder_suffix'];
						@$values['pc_no_data_filter'] ? : self::filterReplacement( $cccc );
						$iReplace[] = $cccc;
					}
                    $iData = @str_replace( $iSearch, $iReplace, $numberedPostTheme ? : $postTheme );
					$iTemplate .= $iData;  

					//	deal with {{{pc_post_item_1}}}
					$search[] = '' . $values['placeholder_prefix'] . 'pc_post_item_' . $key . $values['placeholder_suffix'] . '';  
					$replace[] = $iData;  
				}
				elseif( $jSearch )
				{
					if( is_array( $jReplace ) && ! is_array( $jSearch ) )
					{
						//	don't cause error 

					}
					else
					{    
						$template = str_replace( $jSearch, $jReplace, $template );    
					}
				}
				
			}
		}
		foreach( $defaultSearch as $ckey => $cccc )
		{
            if( stripos( $template, $values['placeholder_prefix'] . $ckey ) !== false )
            {
                $search[] = $values['placeholder_prefix'] . $ckey . $values['placeholder_suffix'];
                @$values['pc_no_data_filter'] ? : self::filterReplacement( $cccc );
                $replace[] = $cccc;
            }
		}
		$search[] = $values['placeholder_prefix'] . 'pc_other_posts_goes_here' . $values['placeholder_suffix'];


        $replace[] = @$iTemplate;
		$template = @str_replace( $search, $replace, $template );  
		$search = array();
		$search[] = '/' . $values['placeholder_prefix'] . '([\w+]+)' . $values['placeholder_suffix'] . '/';
        $search[] = '/<!--([.]+)-->/';    
        @$template = preg_replace( $search, '', $template );

		return $template;
    } 

    /**
     * Returns $_playable 
     *
     * @return boolean
     */
    public static function isPlayable()
    {
		return static::$_playable;
    } 	

    /**
     * Returns $_accessLevel 
     *
     * @return int
     */
    public static function getAccessLevel()
    {
		return static::$_accessLevel;
    } 	
	
    /**
     * Check if I own a resource (they must be a registered user to own a resource)
     * 
     * param int Owner User ID
     * return boolean
     */
	public static function isOwner( $userId  )
	{
		if( $userId && Ayoola_Application::getUserInfo( 'access_level' ) && ( intval( Ayoola_Application::getUserInfo( 'access_level' ) ) === 99 || Ayoola_Application::getUserInfo( 'user_id') === $userId || strtolower( Ayoola_Application::getUserInfo( 'username' ) ) === strtolower( $userId ) ) )
		{
			return true;
        }

		return false;
	}
	
    /**
     * View for ayoola class player
     *
     * @param string The View Parameter
     * @param string The View Option
     */
    public static function viewInLine( $viewParameter = null, $viewOption = null )
    {
		$parameter = $viewParameter;
		if( ! is_array( $viewParameter ) )
		{
			$parameter = array( 'view' => $viewParameter, 'option' => $viewOption, );
		}

		$view = new static( $parameter );

		$view->initOnce();

		return isset( $viewParameter['return_as_object'] ) ? $view : $view->view();
    } 	
	// END OF CLASS
}
