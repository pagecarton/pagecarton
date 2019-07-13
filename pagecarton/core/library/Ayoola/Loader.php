<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Loader
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Loader.php 1.18.2012 06.24am ayoola $
 */

/**
 * @see Ayoola_
 */

//require_once 'Ayoola/.php';

/**
 * @category   PageCarton
 * @package    Ayoola_Loader
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Loader
{
    protected static $_validIncludePaths = array();

    /**
     * Loads a class
     *
     * @param String Class to be loaded
     * @param String | Array Include Paths
     * @param boolean Whether to include once
     * @return boolean
     */
    public static function loadClass($class, $dir = null, $once = true)
    {
        //    return true;
        if (class_exists($class, false) || interface_exists($class, false)) {
            //    return early
            return true;
        }
        require_once 'Ayoola/Filter/ClassToFilename.php';
        $filter   = new Ayoola_Filter_ClassToFilename();
        $filename = $filter->filter($class);
        if (!is_null($dir)) {
            $dir  = is_array($dir) ? implode(PS, $dir) : $dir;
            $temp = get_include_path();
            $dir  = $dir . PS . get_include_path();
            set_include_path($dir);
        }
        //    var_export( $filename );
        if (!self::loadFile($filename, $once)) {return false;}

        !is_null($dir) ? set_include_path($temp) : null;
        return class_exists($class, false) ? true : false;
    }

    /**
     *
     *
     * @return void
     */
    public static function resetValidIncludePaths()
    {
        return self::$_validIncludePaths = array();
    }

    /**
     * Retrieve the valid include paths for a given relative path
     *
     * @param string Filename
     * @return array Array Containing The Full Paths of Available Files
     */
    public static function getValidIncludePaths($relativePath, array $options = array())
    {
        $pathsId = md5($relativePath . get_include_path() . Ayoola_Application::getPathPrefix() . json_encode($options));
        if (!empty(self::$_validIncludePaths[$pathsId]) && empty($options['refresh_list'])) {
            return self::$_validIncludePaths[$pathsId];
        }
        $availableFullPaths = array();
        if (is_readable($relativePath)) {
            //throw new Ayoola_Loader_Exception( 'Invalid File - ' . $relativePath );
            $availableFullPaths['.'] = $relativePath;
        }

        /* This came fron Zend Framework ( Zend_Loader ) */
        $path = get_include_path();
        if (PATH_SEPARATOR == ':') {
            // On *nix systems, include_paths which include paths with a stream
            // schema cannot be safely explode'd, so we have to be a bit more
            // intelligent in the approach.
            $paths = preg_split('#:(?!//)#', $path);
        } else { $paths = explode(PATH_SEPARATOR, $path);}

        foreach ($paths as $path) {
            //    set_time_limit( 60 );
            if ($path == '.') {
                if (is_readable($relativePath)) {$availableFullPaths['.'] = $relativePath;}
                continue;
            }
            $file = $path . DS . $relativePath;
            //    DEBUG
            /*             if( $relativePath == 'pages/templates/object.phtml' )
            {
            var_export( $file );
            }
             *///      'pages/templates/schrob-page.phtml' ==    $relativePath ? var_export( $file ) : null;
            // '/home/ay1000/www/ayoo.la/application/pages/includes/schrobs-page.php' === $file ? var_export( $file ) : null;
            /*             if( is_link( $file ) )
            {
            'pages/templates/schrob-page.phtml' ==    $relativePath ? var_export( $file ) : null;
            }
             */
            if (is_readable($file) || (isset($options['no_availability_check']) && $options['no_availability_check'])) {
                $availableFullPaths[$path] = $file;
            }
        }
/*             if( $relativePath == 'pages/templates/object.phtml' )
{
var_export( $availableFullPaths );
}
 *///    var_export( $availableFullPaths );
        // End of ZF Code
        self::$_validIncludePaths[$pathsId] = $availableFullPaths;
        return self::$_validIncludePaths[$pathsId];
    }

    /**
     * This method returns the full path of a given path
     *
     * @param string relativePath
     * @return mixed The Full Path to File or False
     */
    public static function getFullPath($relativePath, $options = null)
    {
        $relativePath = str_replace(array('/', '\\'), DS, $relativePath);
        $all          = self::getValidIncludePaths($relativePath, $options ?: array());
        if (@$options['path_blacklist']) {
            //    var_export( $options['path_blacklist'] );
            //    var_export( PC_BASE . $options['path_blacklist'] );
            //$key = array_search( $options['path_blacklist'], $all );
            unset($all[$options['path_blacklist']]);
            unset($all[PC_BASE . $options['path_blacklist']]);
        }
        //    if( @$options['prioritize_my_copy'] && stripos( Ayoola_Application::getDomainSettings( APPLICATION_PATH ), 'music' ) )
        {
//            var_export( $relativePath );
            //            var_export( $all );
        }
        if (@$options['prioritize_my_copy'] && @$all[Ayoola_Application::getDomainSettings(APPLICATION_PATH)]) {
            //$key = array_search( $options['path_blacklist'], $all );
            return $all[Ayoola_Application::getDomainSettings(APPLICATION_PATH)];
        }
        //    won't make app run,
        //    i think because Ayoola_Application::getDomainSettings( APPLICATION_PATH ) isn't available at some point
        //    elseif( $all[Ayoola_Application::getDomainSettings( APPLICATION_PATH )] )
        {
            //        return $all[Ayoola_Application::getDomainSettings( APPLICATION_PATH )];
        }
        //    if we have two, hide PC own
        //    var_export( $all );
        if (count($all) > 1) {
            unset($all[APPLICATION_PATH]);
        }

        return array_shift($all);
    }

    /**
     * Checks if it is a valid file on the filesystem
     *
     * @param string Filename
     * @return string | boolean The Full Filepath or False
     */
    public static function checkFile($filename, $options = null)
    {
        if ($fullPath = self::getFullPath($filename, $options)) {
            if (is_file($fullPath)) {
                return $fullPath;
            } else {
                //    sometimes it was available and it was cached.
                //    Now it is no longer available
                self::resetValidIncludePaths();
                if ($fullPath = self::getFullPath($filename, $options)) {
                    if (is_file($fullPath)) {
                        return $fullPath;
                    }
                }
            }
        }
        //    var_export( $fullPath );
        return false;
    }

    /**
     * Checks if it is a valid directory on the filesystem
     *
     * @param string Directory Name
     * @return string | boolean The Full Filepath or False
     */
    public static function checkDirectory($dir)
    {
        if ($fullPath = self::getFullPath($dir)) {
            if (is_dir($fullPath)) {return $fullPath;}
        }
        return false;
    }

    /**
     * Loads a file into the current script
     *
     * @param string Filename
     * @param boolean Switch to load multiple or once
     * @param boolean Wether to check file or not
     * @return void
     */
    public static function loadFile($filename, $once = true)
    {
        //    var_export( $filename );
        //    Autoload does not need files checked
        if ($filePath = self::checkFile($filename)) {
            if ($once) {include_once $filePath;} else {include $filePath;}
            return true;
        } else {
            //    var_export( $filename ); echo;
            //    if( $once ){ include_once $filename; }else{ include $filename; }
        }
        return false;
    }
    // END OF CLASS
}
