<?php

/**
 * Undocumented class (needs documentation)
 */
class Ayoola_Application
{
	public static $disableOutput = false;

	public static $playMode;

    /**
     * Whether to log every visit or not
     *
     * @var boolean
     */
	public static $accessLogging = true;

	protected static $_autoloader;

    /**
     * The Info of User of the Application
     *
     * @var array
     */
	protected static $_userInfo;

    /**
     * Use to pass parameter accross application
     *
     * @var array
     */
	public static $GLOBAL = array();

    /**
     * The Settings of the current running
     *
     * @var array
     */
	protected static $_runtimeSetting;

    /**
     * The Settings of the current domain
     *
     * @var array
     */
	protected static $_domainSettings;

    /**
     * The system user account information
     *
     * @var array
     */
	protected static $_userAccountInfo;

    /**
     * 
     *
     * @var array
     */
	protected static $_viewFiles;

    /**
     * The Home and Default page
     *
     * @var string
     */
	public static $_homePage = '/';
	public static $_notFoundPage = '/404';

    /**
     *
     * @var string
     */
	protected static $_requestedUri;

    /**
     *
     * @var string
     */
	protected static $_urlPrefix;

    /**
     *
     * @var string
     */
	protected static $_pathPrefix;

    /**
     *
     * @var string
     */
	protected static $_presentUri;

    /**
     * The requested domain name
     *
     * @var string
     */
	protected static $_domainName;

    /**
     * Site Configuration
     *
     * @var array
     */
	protected static $_conf = array();

    /**
     *
     *
     * @var array
     */
	protected static $_includePaths = array();

    /**
     * Application mode e.g. document/page
     *
     * @var string
     */
	public static $mode;

    /**
     * Ayoola Framework software version
     *
     * @var string
     */
	public static $version = '1.6.1';

    /**
     * Ayoola Framework software installer
     *
     * @var string
     */
	public static $installer = 'pc_installer.php';

    /**
     *
     *
     * @var string
     */
	public static $appNamespace = '';

    /**
     * Returns the current runtime settings
     *
     * @return array
     */
	public static function getRuntimeSettings( $key = null )
    {
		return ! array_key_exists( $key, self::$_runtimeSetting ) ? (array) self::$_runtimeSetting : self::$_runtimeSetting[$key];
    }

    /**
     * Set the runtime settings
     *
     * @return array
     */
	public static function setRuntimeSettings( $key, $value )
    {
		self::$_runtimeSetting[$key] = $value;
    }

    /**
     *
     *
     * @return string
     */
	public static function sanitizeDomainName( $domainName )
    {

	}

    /**
     * Returns _domainName
     *
     * @return string
     */
	public static function getDomainName( array $options = null )
    {
		if( ! is_null( self::$_domainName ) )
		{
			return self::$_domainName;
		}

		$storage = new Ayoola_Storage();
		if( empty( $options['no_cache'] ) )
		{
			require_once 'Ayoola/Storage.php';
			$storage->storageNamespace = __METHOD__;
			$storage->setDevice( 'File' );
			$data = $storage->retrieve();
		}

		if( isset($data) && $data )
		{
			self::$_domainName =  $data;
			return self::$_domainName;
		}
        require_once 'Ayoola/Filter/DomainName.php';
		$filter = new Ayoola_Filter_DomainName();
		$domainName = $filter->filter( $_SERVER['HTTP_HOST'] );

		//	Store to global use
		$storage->store( $domainName );

		//	Store for request use
		self::$_domainName = $domainName;

		return self::$_domainName;
	}

    /**
     * Returns the settings of the current domain
     *
     * @return array
     */
	public static function reset( array $settings = null )
    {
		//	set path
		$domainSettings = array( 'no_redirect' => true );

        {
			self::$_pathPrefix = $settings['path'];

			self::setUrlPrefix( self::$_pathPrefix );
		}
		if( ! empty( $settings['domain'] ) )
		{
			$domainSettings['domain'] = $settings['domain'];
		}

		// set domain
		self::setDomainSettings( $domainSettings );
		Ayoola_Loader::resetValidIncludePaths();

	}

    /**
     *
     *
     * @param string Path
     * @return void
     */
	public static function setIncludePath( $path )
    {
		if( ! in_array( $path, self::$_includePaths ) )
		{
			self::$_includePaths[] = $path;
			set_include_path( $path . PS . get_include_path() );
		}
	}

    /**
     *
     *
     * @return array
     */
	public static function getApplicationNameSpace()
    {
		$appPath = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . self::$appNamespace;
		$name = Ayoola_Application::getPathPrefix() . DS . $appPath;
		return $name;
	}

    /**
     * Returns the settings of the current domain
     *
     * @return array
     */
	public static function getDomainSettings( $key = null )
    {
		if( is_null( self::$_domainSettings ) )
		{
			// This is needed in Application_Domain Table
			self::$_domainSettings[APPLICATION_DIR] = APPLICATION_DIR;
			self::$_domainSettings[APPLICATION_PATH] = APPLICATION_PATH;
			@self::$_domainSettings[EXTENSIONS_PATH] = EXTENSIONS_PATH;
			self::setDomainSettings();

		}

		return $key ? ( isset( self::$_domainSettings[$key] ) ? self::$_domainSettings[$key] : "" ) : self::$_domainSettings;
	}

    /**
     * Returns the settings of the current domain
     *
     * @param boolean Whether to force a reset
     * @return array
     */
	public static function setDomainSettings( $domainSettings = null )
    {

		$forceReset = is_array( $domainSettings ) ? $domainSettings['force_reset'] : $domainSettings;
		require_once 'Ayoola/Storage.php';
		$storage = new Ayoola_Storage();
		$protocol = 'http';
		if( ( $_SERVER['SERVER_PORT'] == 443 && ! empty( $_SERVER['HTTPS'] ) ) || $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' )
		{
			$protocol = 'https';
		}

		@$storage->storageNamespace = 'xiu' . $_SERVER['HTTP_HOST'] . $domainSettings['domain'] . $protocol . Ayoola_Application::getPathPrefix();
		$storage->setDevice( 'File' );
		$data = $storage->retrieve();
		if( $data && ! $forceReset && ( isset($_GET['reset_domain_information']) && !$_GET['reset_domain_information']) )
		{

 			//	Allows the sub-domains to have an include path too.

			if( ! empty( $data['parent_domain_settings'][APPLICATION_PATH] ) && $data['parent_domain_settings'][APPLICATION_PATH] !== $data['domain_settings'][APPLICATION_PATH] )
			{
				self::setIncludePath( $data['parent_domain_settings'][APPLICATION_PATH] );
				self::setIncludePath( $data['parent_domain_settings'][APPLICATION_PATH] . '/modules' );

			}
			else
			{

				self::setIncludePath( SITE_APPLICATION_PATH );
				self::setIncludePath( SITE_APPLICATION_PATH . DS . 'modules' );
			}
			//	Allows the sub-domains to have an include path too.
			self::setIncludePath( $data['domain_settings'][APPLICATION_PATH] );
			self::setIncludePath( $data['domain_settings'][APPLICATION_PATH] . '/modules' );

			self::$_domainSettings =  $data['domain_settings'];
			if( ! empty( $data['domain_settings']['username'] ) )
			{
				self::$GLOBAL['domain'] = $data['domain_settings'];

			}
			return true;
		}

		//	Search the domain name in the domain table
		do
		{
			$data = array();

			$domainName = @$domainSettings['domain'] ? :  self::getDomainName();

			require_once 'Application/Domain.php';
			$domain = Application_Domain::getInstance();

			$where = array( 'domain_name' => $domainName );
			$tempDomainName = $domainName;
            $tempWhere = $where;
            
			while( ! $data['domain_settings'] = $domain->selectOne( null, $tempWhere ) )
			{
                //  check for sub domain

				if( is_numeric( str_replace( '.', '', $domainName ) ) )
				{
                    // ip addresses don't have subdomain
					break;
				}
				$tempDomainName = explode( '.', $tempDomainName );
			    if( count( $tempDomainName ) < 2 ){ break; }
				$subDomain = array_shift( $tempDomainName );	// Fix wildcard domainnames
				$tempDomainName = implode( '.', $tempDomainName );
				$tempWhere = array( 'domain_name' => $tempDomainName );

			}
			if( ! empty( $data['domain_settings']['sub_domain'] ) || @$data['domain_settings']['domain_type'] === 'sub_domain' )
			{
				$subDomain = $tempDomainName;
			}

			if( ! $primaryDomainInfo = $domain->selectOne( null, array( 'domain_type' => 'primary_domain' ) ) )
			{
				if( ! $primaryDomainInfo = $domain->selectOne( null, array( 'domain_type' => '', 'sub_domain' => ''  ) ) )
				{
					if( ! $primaryDomainInfo = $domain->selectOne( null, array( 'sub_domain' => ''  ) ) )
					{
						$primaryDomainInfo = $data['domain_settings'];
					}
				}
			}

			$userDomain = false;
			if( ! $data['domain_settings'] )
			{
				//	look for domain in the users table
				if( $userDomainInfo = Application_Domain_UserDomain::getInstance()->selectOne( null, array( 'domain_name' => array( $where['domain_name'], @$_SERVER['HTTP_HOST'] ) ) ) )
				{
					//	link it to the profile
					$subDomain = $userDomainInfo['profile_url'];
					$userDomain = true;
					$data['domain_settings'] = $userDomainInfo;
					$data['domain_settings']['domain_options'] = array( 'user_subdomains' );
					$data['domain_settings']['main_domain'] = $where['domain_name'];

				}
            }

			if( ! @$subDomain && @strlen( $data['domain_settings']['enforced_destination'] ) > 3 && empty( $domainSettings['no_redirect'] ) )
			{

				$enforcedDestination = $data['domain_settings']['enforced_destination'];
				if( count( explode( '.', $enforcedDestination ) ) >= 2 )
				{
					if( strtolower( $_SERVER['HTTP_HOST'] ) !== strtolower( trim( $enforcedDestination ) ) )
					{
                        $urlY = $protocol . '://' . $enforcedDestination . Ayoola_Application::getUrlPrefix() . Ayoola_Application::getPresentUri();
                        $urlY = self::appendCurrentQueryStrings( $urlY );
                        header( 'Location: ' . $urlY );
                        exit();
					}
				}

			}
			if( $domainName === $_SERVER['SERVER_ADDR'] )
			{
				//	don't use ip domain

				//	make IP work but don't store it
				$subDomain = null;
			}

			if( ! $domain->select() )
			{

				//	insert the first domain only
				if( ( '127.0.0.1' !== $_SERVER['REMOTE_ADDR'] ) && empty( $_SERVER['CONTEXT_PREFIX'] ) )
				{
                    //  pc.com has a bug that is deleting Application_Domain data. 
                    //  need to remove auto-insert until this is fixed
                    //  So arbitrary domains don't get inserted as default domain

				}
				$data['domain_settings'] = $where;
				$subDomain = null;

			}

			if( ! empty( $_SERVER['CONTEXT_PREFIX'] )  )
			{
				$data['domain_settings'] = $where;
			}

			if( ! $data['domain_settings'] && '127.0.0.1' !== $_SERVER['REMOTE_ADDR'] && empty( $domainSettings['no_redirect'] ) && empty( $_SERVER['CONTEXT_PREFIX'] )  )
			{

				if( $primaryDomainInfo['domain_name'] )
				{
                    $urlY = $protocol . '://' . $primaryDomainInfo['domain_name'] . Ayoola_Application::getUrlPrefix() . Ayoola_Application::getPresentUri();
                    $urlY = self::appendCurrentQueryStrings( $urlY );
                    header( 'Location: ' . $urlY );
					exit( 'DOMAIN NOT FOUND' );
				}
				else
				{
					$data['domain_settings'] = $where;
					break;
				}
			}
			else
			{
				//	We have found our domain name
				//	Inventing a personal APPLICATION_PATH also for the "default" Installation
				//	Get settings for the primary domain for inheritance

				$oldDomainDir = Application_Domain_Abstract::getSubDomainDirectory( @$primaryDomainInfo['domain_name'] ? : Ayoola_Page::getDefaultDomain() );

				//	we need to change this to main dir
				$domainDir = Application_Domain_Abstract::getSubDomainDirectory( 'default' );
				$data['domain_settings']['site_configuraton'] = array();
				$configurationFile = $domainDir . DS .  'pagecarton.json';

				if( ! file_exists( $configurationFile ) )
				{
					$configurationFile = 'pagecarton.json';
				}
				if( file_exists( $configurationFile ) )
				{
					if( $conf = file_get_contents( $configurationFile ) )
					{
						if( $conf = json_decode( $conf, true ) )
						{
							$data['domain_settings']['site_configuraton'] = $conf;
						}
					}
				}

				if( is_dir( $oldDomainDir ) )
				{
					if( ! is_dir( $domainDir ) )
					{

					}

				}

				$customDir = null;

				if( ! empty( $conf['application_dir'] ) && is_dir( $conf['application_dir'] ) && is_readable( $conf['application_dir'] ) )
				{
					$customDir = $conf['application_dir'];
					$subDomain = null;
				}
				elseif( @strlen( $data['domain_settings']['application_dir'] ) > 3 )
				{
					$customDir =  Application_Domain_Abstract::getSubDomainDirectory( $data['domain_settings']['application_dir'] );
				}
				elseif( @strlen( $primaryDomainInfo['application_dir'] ) > 3 )
				{
					$customDir =  Application_Domain_Abstract::getSubDomainDirectory( $primaryDomainInfo['application_dir'] );
					$oldCustomDir = APPLICATION_DIR . $primaryDomainInfo['application_dir'];

					//	compatibility.
					if( defined( 'PC_BASE' ) )
					{
						if( is_dir( $oldCustomDir ) )
						{

						}
					}
					else
					{
						$customDir =  $oldCustomDir;
					}
				}
				if( $customDir )
				{
					$data['domain_settings'][APPLICATION_DIR] = $primaryDomainInfo[APPLICATION_DIR] = str_replace( '/', DS, $customDir );
					$data['domain_settings'][APPLICATION_PATH] = $primaryDomainInfo[APPLICATION_PATH] = $primaryDomainInfo[APPLICATION_DIR] . DS . 'application';
					$data['domain_settings'][EXTENSIONS_PATH] = @$primaryDomainInfo[EXTENSIONS_PATH] = $primaryDomainInfo[APPLICATION_DIR] . DS . 'extensions';
				}
				else
				{
					if( ! empty( $data['domain_settings']['site_configuraton']['run_as_core'] ) )
					{
						break;
					}

				    //	For backward compatibility, the directory must be "consciously" set
					$data['domain_settings'][APPLICATION_DIR] = $primaryDomainInfo[APPLICATION_DIR] = str_replace( '/', DS, $domainDir );
					$data['domain_settings'][APPLICATION_PATH] = $primaryDomainInfo[APPLICATION_PATH] = $primaryDomainInfo[APPLICATION_DIR] . DS . 'application';
					@$data['domain_settings'][EXTENSIONS_PATH] = @$primaryDomainInfo[EXTENSIONS_PATH] = $primaryDomainInfo[APPLICATION_DIR] . DS . 'extensions';
				}

				//	How do we allow the user accounts in the primary domain visible here?
 			//	if( @$primaryDomainInfo['domain_name'] !== @$data['domain_settings']['domain_name'] )
				{
					$data['parent_domain_settings'] = $primaryDomainInfo;
					self::setIncludePath( $data['parent_domain_settings'][APPLICATION_PATH] );
					self::setIncludePath( $data['parent_domain_settings'][APPLICATION_PATH] . '/modules' );

				}

			}

			//	check subdomain
			if( isset($subDomain) && $subDomain && empty( $_SERVER['CONTEXT_PREFIX'] ) )
			{
				if( $subDomainInfo = $domain->selectOne( null, array( 'domain_name' => $subDomain ) ) )
				{

					if( @$subDomainInfo['sub_domain'] || @$subDomainInfo['domain_type'] === 'sub_domain' )
					{
						$data['domain_settings'] = $subDomainInfo;
						$data['domain_settings']['main_domain'] = $data['domain_settings']['main_domain'] ? : $primaryDomainInfo['domain_name'];
						$data['domain_settings']['domain_name'] = $subDomain . '.' . $primaryDomainInfo['domain_name'];
						$data['domain_settings']['parent'] = @$data['parent_domain_settings'] ? : $primaryDomainInfo;

						$subDomainDir = $subDomainInfo['application_dir'] ? : $subDomainInfo['domain_name'];
						$subDomainDir = Application_Domain_Abstract::getSubDomainDirectory( $subDomainDir );

						$data['domain_settings'][APPLICATION_DIR] = str_replace( '/', DS, $subDomainDir );
						$data['domain_settings']['dynamic_domain'] = true;
						$data['domain_settings'][APPLICATION_PATH] = $data['domain_settings'][APPLICATION_DIR] . DS . 'application';
						@$data['domain_settings'][EXTENSIONS_PATH] = $data['domain_settings'][APPLICATION_DIR] . DS . 'extensions';
					}
					elseif( $data['domain_settings']['*'] )
					{

                        
					}
					else
					{
						exit( 'INVALID SUB-DOMAIN' );
					}
				}
				else
				{

					//	do we have user domains
					$userInfo = Ayoola_Access::getAccessInformation( $subDomain );
					if( ! $userInfo = Ayoola_Access::getAccessInformation( $subDomain ) )
					{
						$userInfo = Application_Profile_Abstract::getProfileInfo( $subDomain );
                    }
                    if( $userInfo['username'] )
                    {
                        if( $realUserInfo = Application_User_Abstract::getUserInfo( array( 'username' => $userInfo['username'] ) ) )
                        {
                            $userInfo += $realUserInfo;
                        }

                    }
					if( @in_array( 'user_subdomains', @$data['domain_settings']['domain_options'] ) && $userInfo  )
					{
						//	we have a user subdomain
						//	do we have a custom domain?
						//	look for domain in the users table
						if( $userDomainInfo = Application_Domain_UserDomain::getInstance()->selectOne( null, array( 'profile_url' => strtolower( $subDomain ) ) ) )
						{
							//	link it to the profile
							if( empty( $userDomain ) && empty( $_REQUEST['pc_clean_url_check'] ) && PageCarton_Widget::fetchLink( 'http://' . $userDomainInfo['domain_name'] . '/pc_check.txt?pc_clean_url_check=1' ) )
							{
                                $urlY = $protocol . '://' . $userDomainInfo['domain_name'] . Ayoola_Application::getUrlPrefix() . Ayoola_Application::getPresentUri();
                                $urlY = self::appendCurrentQueryStrings( $urlY );
                                header( 'Location: ' . $urlY );
                                exit();
							}
						}

						self::$GLOBAL['user'] = $userInfo;
						$data['domain_settings'] = $data['domain_settings'] ? : array();
						$data['domain_settings'] += $userInfo;

						$data['domain_settings']['main_domain'] = $data['domain_settings']['main_domain'] ? : $tempWhere['domain_name'];
						$data['domain_settings']['domain_name'] = Ayoola_Application::getDomainName();
						$data['domain_settings']['dynamic_domain'] = true;
						$data['domain_settings']['parent'] = @$data['parent_domain_settings'] ? : $primaryDomainInfo;

						$data['domain_settings'][APPLICATION_DIR] = $primaryDomainInfo[APPLICATION_DIR] . DS . AYOOLA_MODULE_FILES .  DS . 'profiles' . DS . strtolower( implode( DS, str_split( $subDomain, 2 ) ) );
						$data['domain_settings'][APPLICATION_PATH] = $data['domain_settings'][APPLICATION_DIR] . DS . 'application';
						@$data['domain_settings'][EXTENSIONS_PATH] = $data['domain_settings'][APPLICATION_DIR] . DS . 'extensions';

					}
					elseif( ! empty( $tempWhere['domain_name'] ) && $tempWhere['domain_name'] != self::getDomainName() && empty( $domainSettings['no_redirect'] )  )
					{

                        $urlY = $protocol . '://' . $tempWhere['domain_name'] . Ayoola_Application::getUrlPrefix() . Ayoola_Application::getPresentUri();
                        $urlY = self::appendCurrentQueryStrings( $urlY );
                        //    header( 'Location: ' . $urlY );
                        //	exit( 'USER DOMAIN NOT ACTIVE' );
                        if(  Ayoola_Page::getInfo( Ayoola_Application::getPresentUri() )  )
                        {
                            self::view( '/domain-not-found' );
                        }
					}
					elseif( empty( $domainSettings['no_redirect'] ) )
					{

                        $urlY = $protocol . '://' . $tempWhere['domain_name'] . Ayoola_Application::getUrlPrefix() . Ayoola_Application::getPresentUri();
                        $urlY = self::appendCurrentQueryStrings( $urlY );
                        header( 'Location: ' . $urlY );
            
                        //  exit( 'DOMAIN NOT IN USE' );
                        if(  Ayoola_Page::getInfo( Ayoola_Application::getPresentUri() )  )
                        {
                            self::view( '/domain-not-found' );
                        }

					}
				}
			}
		}
		while( false );
		@$data['domain_settings'][APPLICATION_DIR] = $data['domain_settings'][APPLICATION_DIR] ? : APPLICATION_DIR;
		@$data['domain_settings'][APPLICATION_PATH] = $data['domain_settings'][APPLICATION_PATH] ? : APPLICATION_PATH;
		@$data['domain_settings'][EXTENSIONS_PATH] = $data['domain_settings'][EXTENSIONS_PATH] ? : EXTENSIONS_PATH;
		$data['domain_settings']['protocol'] = $protocol;

		//	Check if theres a forwarding needed.

		if( @is_array( $data['domain_settings']['domain_options'] ) && in_array( 'redirect', $data['domain_settings']['domain_options'] ) && ! @$_REQUEST['ignore_domain_redirect'] && ! @$_SESSION['ignore_domain_redirect'] && empty( $domainSettings['no_redirect'] ) )
		{
			header( 'HTTP/1.1 ' . $data['domain_settings']['redirect_code'] );
            $urlY = $protocol . '://' . $data['domain_settings']['redirect_destination'] . Ayoola_Application::getUrlPrefix() . Ayoola_Application::getPresentUri();
            $urlY = self::appendCurrentQueryStrings( $urlY );
            header( 'Location: ' . $urlY );
            exit();
        }
		elseif( @$_REQUEST['ignore_domain_redirect'] || @$_SESSION['ignore_domain_redirect'] )
		{
			$_SESSION['ignore_domain_redirect'] = true;
		}
		else
		{

			if( @$primaryDomainInfo['domain_name'] !== @$data['domain_settings']['domain_name'] )
			{
				$data['parent_domain_settings'] = $primaryDomainInfo;
				self::setIncludePath( $data['parent_domain_settings'][APPLICATION_PATH] );
				self::setIncludePath( $data['parent_domain_settings'][APPLICATION_PATH] . '/modules' );

			}
			else
			{
				self::setIncludePath( SITE_APPLICATION_PATH );
				self::setIncludePath( SITE_APPLICATION_PATH . DS . 'modules' );
			}
            $storage->store( $data );  
		}
		//	Allows the sub-domains to have an include path too.
		self::setIncludePath( $data['domain_settings'][APPLICATION_PATH] );
		self::setIncludePath( $data['domain_settings'][APPLICATION_PATH] . '/modules' );
        self::$_domainSettings = $data['domain_settings'];



        //  redirect ssl last 
        //  so it wont be auto issue ssl for domains we dont need for autossl settings

        //  now we need https to be always encouraged

        {
            if( $protocol != 'https' && empty( $domainSettings['no_redirect'] ) && empty( $_REQUEST['pc_clean_url_check'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] !== 'https' )
            {
                if( PageCarton_Widget::fetchLink( 'https://' . $_SERVER['HTTP_HOST'] . Ayoola_Application::getUrlPrefix() . '/pc_check.txt?pc_clean_url_check=1', array( 'verify_ssl' => true ) ) === 'pc' )
                {
                    $urlY = 'https://' . $_SERVER['HTTP_HOST'] . Ayoola_Application::getUrlPrefix() . Ayoola_Application::getPresentUri();
                    $urlY = self::appendCurrentQueryStrings( $urlY );
                    header( 'Location: ' . $urlY );
                    exit();

                }
            }
        }
		return true;
    }

    public static function boot()
    {

		date_default_timezone_set( 'UTC' );
		self::loadPrerequisites();
		require_once 'Ayoola/Loader/Autoloader.php';
		self::$_autoloader = Ayoola_Loader_Autoloader::getInstance();

		//
		Ayoola_Event_NewSession::viewInLine();
		if( empty( $_SESSION['PC_SESSION_START_TIME'] ) )
		{
			$_SESSION['PC_SESSION_START_TIME'] = time();
		}
        if( $locale = PageCarton_Locale_Settings::retrieve( 'default_locale' ) )
        {
            $locale = setlocale( LC_ALL, $locale );
        }

		// Error / Exception handling
		// create_function is deprecated: use closuers instead
		require_once 'Application/Log/View/Error.php';
		set_exception_handler( function($object) {
            $message = "Uncaught Exception " . get_class( $object ) . " with message " . $object->getMessage() . " in  " . $object->getFile() . " on line " . $object->getLine() . " Stack trace: " . $object->getTraceAsString();
        //    var_export( $message );
			Application_Log_View_Error::log( $message );
		});

		//	Handle encryption
		if(isset($_SERVER['HTTP_AYOOLA_PLAY_MODE']) ){
			switch( $_SERVER['HTTP_AYOOLA_PLAY_MODE'] )
			{
				case 'ENCRYPTION':
					$_POST = array();
					$data = file_get_contents( "php://input" );

					if( $decrypted = OpenSSL::decrypt( $data, $_SERVER['HTTP_PAGECARTON_REQUEST_ENCRYPTION'] ) )
					{
						parse_str( $decrypted, $result );
						$_POST = is_array( $result ) ? $result : array();
						if( isset( $_POST['pagecarton_request_timezone'], $_POST['pagecarton_request_time'], $_POST['pagecarton_request_timeout'] ) )
						{

							date_default_timezone_set( $_POST['pagecarton_request_timezone'] );

							if( ( time() - $_POST['pagecarton_request_time'] ) > $_POST['pagecarton_request_timeout'] )
							{
								$_POST = array();
							}

						}

					}

					//	Log early before we exit

				//	if( ! self::hasPriviledge() )
					{

					}
				break;
			}
		}

    }

    public static function run()
    {

        // run application

		$time_start = microtime( true );

		self::$_runtimeSetting['start_time'] = $time_start; //	Record start time

		//	Record IP Address
		self::$_runtimeSetting['user_ip'] = [
				'REMOTE_ADDR'          => $_SERVER['REMOTE_ADDR'],
				'HTTP_CLIENT_IP'       => isset($_SERVER['HTTP_CLIENT_IP'] ) ? $_SERVER['HTTP_CLIENT_IP'] : "",
				'HTTP_X_FORWARDED_FOR' => isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : "",
			];

		self::boot();

		self::$_runtimeSetting['real_url'] = Ayoola_Application::getPresentUri();
		self::$_runtimeSetting['url'] = Ayoola_Application::getPresentUri();

		//	Domain settings

        //	run cron
		self::$_conf = self::getDomainSettings( 'site_configuraton' );

		if( empty( self::$_conf['disable_auto_cron'] ) )
		{
			$result = PageCarton_Cron_Run::viewInLine();
		}
        //var_export( microtime( true ) - $time_start );
        //exit();

		self::display();

		$time_end = microtime( true );

		self::$_runtimeSetting['total_runtime'] = $time_end - $time_start; //	Record total runtime

		//	Ignore localhosts and super users
		self::log();

    }

    /**
     * Converts URI to real URI that is viewable
     *
     * @param uri
     * @return void
     */
    public static function getUriToView( $uri )
    {
		//	Handle Files and Documents differently, as per type of file
		$uri = trim( $uri );
		$explodedUri = explode( '.', $uri );
        $extension = strtolower( array_pop( $explodedUri ) );
        $url = $uri;
		if( count( $explodedUri ) > 0 && strlen( $extension ) <= 15 )
		{
			try
			{
				do
				{
					self::$mode = 'document';
                    $fn = DOCUMENTS_DIR . $uri;

                    if( $fn = Ayoola_Loader::checkFile( $fn ) )
                    {
                        return $uri;
                    }
    
					//	Check if this is an article
					$article = Application_Article_Abstract::getFolder() . $uri;
					if( is_file( $article ) )
					{
						self::$mode = 'post';
						$articleInfo = Application_Article_Abstract::loadPostData( $article );

						//	introducing x_url so that user can determine the url to display a post
						if( @$_REQUEST['x_url'] )
						{
							$moduleInfo = Ayoola_Page::getInfo( $_REQUEST['x_url'] );
							if( ( ! empty( $moduleInfo ) ) )
							{
								$url = $_REQUEST['x_url'];
							}
						}
						else
						{

                            //     var_export( Ayoola_Page::getInfo( '/post-viewer' ) );
							if( ( ! empty( $articleInfo['article_type'] ) ) AND ( $moduleInfo = Ayoola_Page::getInfo( '/post-viewer-'  . $articleInfo['article_type'] ) ) )
							{
								//	allow dedicated url for all post types like /post-viewer-article/
								$url = '/post-viewer-'  . $articleInfo['article_type'];
							}
							elseif( ( ! empty( $articleInfo['true_post_type'] ) ) AND ( $moduleInfo = Ayoola_Page::getInfo( '/post-viewer-'  . $articleInfo['true_post_type'] ) ) )
							{
								//	allow dedicated url for all post types like /post-viewer-article/
								$url = '/post-viewer-'  . $articleInfo['true_post_type'];
							}
							elseif( $moduleInfo = Ayoola_Page::getInfo( '/post-viewer' ) )
							{
								//	allow dedicated url for all post types like /post-viewer-article/
								$url = '/post-viewer';
							}
							elseif( ( ! empty( $articleInfo['article_type'] ) ) AND ( $moduleInfo = Ayoola_Page::getInfo( '/' . $articleInfo['article_type'] . '/post' ) ) AND ( ! empty( $moduleInfo ) && @in_array( 'module', $moduleInfo['page_options'] ) ) )
							{
								//	allow dedicated url for all post types like /download/posts/
								$url = '/' . $articleInfo['article_type'] . '/post';
							}
							elseif( ( ! empty( $articleInfo['true_post_type'] ) ) AND  ( $moduleInfo = Ayoola_Page::getInfo( '/' . $articleInfo['true_post_type'] . '/post' ) ) AND ( ! empty( $moduleInfo ) && @in_array( 'module', $moduleInfo['page_options'] ) ) )
							{
								//	allow dedicated url for all post types like /download/posts/
								$url = '/' . $articleInfo['true_post_type'] . '/post';
							}
							else
							{
								$url = '/post/view';
								$moduleInfo = Ayoola_Page::getInfo( $url );
							}
                        }

                        if( self::getViewFiles( $url ) )
                        {

                           return $url;
                        }

                    }
				}
				while( false );
			}
			catch( Ayoola_Exception $e )
			{

				// 	Possibly File not found
				//	This may not work unless a setting is made with the webserver
			}
			//	Decided not to halt the script here
			//	This is mainly because of PHP Documents
			//	If not, this chunk should stop here, because the file has been executed/

		}

        do
        {

            self::$mode = 'uri';

            if( self::getViewFiles( $url ) )
            {
                break;
            }

            //	Check if this is a module url that carries $_GET parameters e.g. /article/category/business/
            $a = explode( '/', $uri );
            $nameForModule = array_shift( $a );
            $module = '/' . $nameForModule;

            //	If the first level url is a valid page, then its the module, else, its the homepage
            $firstLevelPage = '/' . $a[0];
            if( Ayoola_Page::getInfo( $firstLevelPage ) )
            {
                //	We are not using "/"
                $nameForModule = array_shift( $a );
                $module = '/' . $nameForModule;

            }
            $moduleInfo = Ayoola_Page::getInfo( $module );
            if( ( ! empty( $moduleInfo ) && @in_array( 'module', $moduleInfo['page_options'] ) ) || $module === '/article' )
            {
                $url = $module;
                self::$mode = 'module';
                if( self::getViewFiles( $uri ) )
                {
                    break;
                }
            }
            else
            {
                //	Allow /username
                try
                {
                    if( $nameForModule === '' )
                    {
                        $nameForModule = array_shift( $a );
                    }


                    $userInfo = $nameForModule ? Application_Profile_Abstract::getProfileInfo( $nameForModule ) : null;

                    //	Hide superusers
                    if( $userInfo && $userInfo['access_level'] != 99 )
                    {
                        $domainOptions = self::getDomainSettings( 'domain_options' );
                        if( is_array( $domainOptions ) && in_array( 'user_subdomains', $domainOptions ) )
                        {
                            $parentDomainOptions = self::getDomainSettings( 'parent' );
                            $urlY = 'http://' . $nameForModule . '.' . ( @$parentDomainOptions['domain_name'] ? : self::getDomainSettings( 'domain_name' ) );
                            $urlY = self::appendCurrentQueryStrings( $urlY ); 
                            header( 'Location: ' . $urlY );
                            exit();
                        }
                        $url = rtrim( '/profile' );
                        self::$mode = 'profile';
                        $url = rtrim( '/profile/' . implode( '/', $a ), '/' );
                        if( self::getViewFiles( $url ) )
                        {
                            break;
                        }
                    }
                }
                catch( Exception $e )
                {
                    null;
                }

            }
        }
        while( false );
        return $url;
    }

    /**
     * Display The Page
     *
     * @param void
     * @return void
     */
    public static function prepURIforDisplay( $uri )
    {
        $a = explode( '/', trim( $uri, ' /\\' ) );
        $nameForModule = array_shift( $a );
        $module = '/' . $nameForModule;
        $url = Ayoola_Application::getUriToView( $uri );
        self::$_runtimeSetting['url'] = $uri;
        self::$_runtimeSetting['real_url'] = $url;

        switch( self::$mode )
        {
            case 'document':

            //	Enable Cache for Documents
                // seconds, minutes, hours, days
                $expires = 60 * 60 * 24 * 14; // 14 days
                require_once 'Ayoola/Doc.php';
                $fn = DOCUMENTS_DIR . $uri;
                if( $fn = Ayoola_Loader::checkFile( $fn, array( 'prioritize_my_copy' => true ) ) )
                {
                    $changedFile = DOCUMENTS_DIR . DS . '__' . $uri;
                    if( $changedFile = Ayoola_Loader::checkFile( $changedFile, array( 'prioritize_my_copy' => true ) ) )
                    {
                        $fn = $changedFile;
                    }
                }

                //	cache some files forever to reduce connection rates
                $catchForever = false;
                $firstPart = strtolower( array_shift( explode( '/', trim( $uri, '/' ) ) ) );
                switch( $firstPart )
                {
                    case 'css':
                    case 'js':
                    case 'open-iconic':
                    case 'js':
                    case 'loading.gif':
                    case 'loading2.gif':
                    case 'js':
                        //	files already using document time
                        $catchForever = true;
                    break;
                    case 'layout':
                        switch( $extension )
                        {
                            case 'css':
                            case 'js':
                                //	files already using document time
                                $catchForever = true;
                            break;
                        }
                    break;
                }
                if( $_REQUEST['document_time'] )
                {
                    //	files already using document time
                    $catchForever = true;
                }
                if( $fn )
                {
                    if( $catchForever )
                    {
                        #  https://stackoverflow.com/questions/7324242/headers-for-png-image-output-to-make-sure-it-gets-cached-at-browser
                        header('Pragma: public');
                        header('Cache-Control: max-age=8640000');
                        header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 8640000));
                    }
                    else
                    {
                        header('Pragma: private');
                        header('Cache-Control: private');
                        // Checking if the client is validating his cache and if it is current.
                        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == filemtime($fn))) {
                            // Client's cache IS current, so we just respond '304 Not Modified'.
                            header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($fn)).' GMT', true, 304);
                            exit();
                        } else {
                            // Image not cached or cache outdated, we respond '200 OK' and output the image.
                            header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($fn)).' GMT', true, 200);
                        }
                    }
                    //	This was making site load forever if size sent do not match this

                }
            break;
            case 'uri':

            break;
            case 'module':
                //	Check if this is a module url that carries $_GET parameters e.g. /article/category/business/
                self::setQueryStringsForModule( $uri );
            break;
            case 'profile':

                $userInfo = $nameForModule ? Application_Profile_Abstract::getProfileInfo( $nameForModule ) : null;

                //	Hide superusers
                if( $userInfo && $userInfo['access_level'] != 99 )
                {
                    Ayoola_Page::$title = $userInfo['display_name'];
                    Ayoola_Page::$description = $userInfo['profile_description'];
                    Ayoola_Page::$thumbnail = $userInfo['display_picture'];
                    self::$GLOBAL['profile'] = $userInfo; // store this in the global var
                }
            break;
            case 'article':
            case 'post':
                $articleInfo = Application_Article_Abstract::loadPostData( $uri );
                if( $articleInfo['username'] )
                {
                    try
                    {
                        if( $userInfoForArticle = Ayoola_Access::getAccessInformation( $articleInfo['username'] ) )
                        {
                            $articleInfo += $userInfoForArticle;
                        }
                    }
                    catch( Exception $e )
                    {

                    }
                }
                self::$GLOBAL['post'] = is_array( $articleInfo ) ? $articleInfo : array(); // store this in the global var

            break;
        }
    }

    /**
     * Display The Page
     *
     * @param void
     * @return void
     */
    public static function display()
    {
		$uri = Ayoola_Application::getPresentUri();
        $url = Ayoola_Application::getUriToView( $uri );
		self::$_runtimeSetting['real_url'] = $url;

        $a = explode( '/', trim( $uri, ' /\\' ) );
        $nameForModule = array_shift( $a );
        $module = '/' . $nameForModule;

        self::prepURIforDisplay( $uri );
        
        switch( self::$mode )
        {
            case 'document':
                //	DONT LOGG DOCUMENTS
                self::$accessLogging = false;
                $doc = new Ayoola_Doc( array( 'option' => $uri ) );
                $view = $doc->view();
                return true;

            break;            
        }


        if( self::view( $url ) )
        {
            return true;
        }
		//	Handle Files and Documents differently, as per type of file

        do
        {

            //	we cant find the file. Now lets look at the multisite
            $table = new PageCarton_MultiSite_Table();
            $multiSiteDir = '/' . $nameForModule;

            if( $sites = $table->selectOne( null, array( 'directory' => $multiSiteDir ) ) )
            {
                Ayoola_Application::reset( array( 'path' => $multiSiteDir ) );
                //	change requested url
                $requestedUri = self::getRequestedUri();

                $requestedUri = explode( $multiSiteDir, $requestedUri );

                array_shift( $requestedUri );

                //	this have to be imploded because of the case of
                //	https://www.comeriver.com/music/2019/02/25/music-smartex-iya-niwura.html
                //	two cases of /music
                $requestedUri = implode( $multiSiteDir, $requestedUri );
                if( $sites['redirect_url'] && empty( $_REQUEST['ignore_domain_redirect'] ) )
                {
                    $toGo = rtrim( $sites['redirect_url'], '/' ) . $requestedUri . '?' . http_build_query( $_GET );

                    header( 'Location: ' . $toGo  );

                    exit( 'REDIRECTING TO' );
                }

                self::$_requestedUri = $requestedUri;
                self::$_presentUri = null;

                self::run();	//	try again
                return false;
            }
            else
            {
                self::$mode = '404';
                self::view();	//	404 NOT FOUND
            }
        }
        while( false );

    }

    /**
     * Parses a module url and populates $_GET with values 
     *
     * @param string URI to view
     * @return null
     */
    public static function setQueryStringsForModule( $uri )
    {
        $a = explode( '/', $uri );
        $nameForModule = array_shift( $a );
                
        //	Not carrying valid query string pairs
        $module = '/' . $nameForModule;

        //	If the first level url is a valid page, then its the module, else, its the homepage
        $firstLevelPage = '/' . $a[0];
        if( Ayoola_Page::getInfo( $firstLevelPage ) )
        {
            //	We are not using "/"
            $nameForModule = array_shift( $a );
            $module = '/' . $nameForModule;

        }
        $get = array();
        $get['pc_module_url_values'] = $a;
        if( count( $a ) % 2 === 0 )
        {
            while( $a )
            {
                $get[array_shift( $a )] = array_shift( $a );
            }
        }

        $_GET = array_merge( $_GET, $get ); // Combines our generated params with the original
        $_REQUEST = array_merge( $_REQUEST, $get ); // Combines our generated params with the original
    }

    /**
     * Get the Include required file to view page
     *
     * @param string URI to view
     * @return array array(  )
     */
    public static function getViewFiles( $uri )
    {
        if( ! empty( self::$_viewFiles[$uri] ) )
        {
            return self::$_viewFiles[$uri];
        }

        //	my copy first
        require_once 'Ayoola/Page.php';
		$pagePaths = Ayoola_Page::getPagePaths( $uri );

		$PAGE_INCLUDE_FILE = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS .  $pagePaths['include'];
        $PAGE_TEMPLATE_FILE = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS .  $pagePaths['template'];

        

	    $noRestriction = false;
		$previewTheme = function( array $options = null ) use ( $pagePaths, $uri, &$PAGE_INCLUDE_FILE, &$PAGE_TEMPLATE_FILE )
		{

			//	page may just be present in the theme
			$themeName = @$_REQUEST['pc_page_layout_name'];
            $themeName = $themeName ? : Application_Settings_Abstract::getSettings( 'Page', 'default_layout' );

            //  Check if theme file needs to be autogenerated
            $rPath = 'documents/layout/' . $themeName . '/theme/template';
            //  don't autogenerate if we already have the manual saved copy
            $autoName = 'auto';
            if( ! $rPath = Ayoola_Loader::getFullPath( $rPath ) )
            {

                $rPath = 'documents/layout/' . $themeName . '/theme/variant/' . $autoName . '/template';
                $rPath = Ayoola_Loader::getFullPath( $rPath );
                //    var_export( $rPath );
                $pageFile = 'documents/layout/' . $themeName . '/template.html';
                if( ! $pageFile = Ayoola_Loader::getFullPath( $pageFile, array( 'prioritize_my_copy' => true ) ) )
                {
                    $pageFile = 'documents/layout/' . $themeName . '/templateraw';
                    if( ! $pageFile = Ayoola_Loader::getFullPath( $pageFile, array( 'prioritize_my_copy' => true ) ) )
                    {
                        $pageFile = 'documents/layout/' . $themeName . '/index.html';
                        if( ! $pageFile = Ayoola_Loader::getFullPath( $pageFile, array( 'prioritize_my_copy' => true ) ) )
                        {
                            
                        }
                            
                    }
                }
                if( $pageFile )
                {
                    if( ! $rPath || filemtime( $rPath ) < filemtime( $pageFile ) )
                    {
                        $themeValues = Ayoola_Page_PageLayout::getInstance()->selectOne( null, array( 'layout_name' => $themeName ) );
                        Ayoola_Page_Layout_Abstract::buildThemeFile( $themeName, file_get_contents( $pageFile ) );
                    }
                }


                // default-layout
                $pageFile = 'documents/layout/' . $themeName . '/default-layout.html';
                if( $pageFile = Ayoola_Loader::getFullPath( $pageFile, array( 'prioritize_my_copy' => true ) ) )
                {
                    $vPath = 'documents/layout/' . $themeName . '/theme/default-layout/template';
                    $vPath = Ayoola_Loader::getFullPath( $vPath );
                    if( ! $vPath || filemtime( $vPath ) < filemtime( $pageFile ) )
                    {
                        $page = new Ayoola_Page_Editor_Sanitize( array( 'theme_variant' => '' . $autoName . '' ) );
                        $d = $page->refresh( '/default-layout', $themeName );
                    }
                }

            }


            //  now try to locate individual theme page
			$pageThemeFileUrl = $uri;
			if( $pageThemeFileUrl == '/' )
			{
				$pageThemeFileUrl = '/index';
			}
			$realPageFile = 'documents/layout/' . $themeName . '' . $pageThemeFileUrl . '.html';
            $pageFile = Ayoola_Loader::getFullPath( $realPageFile, array( 'prioritize_my_copy' => true ) );
			if( ! is_file( $pageFile ) )
			{
				return false;
            }
            $myPageFile = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $realPageFile;


            if( empty( $options['auto_init_theme_page'] ) && ! is_file( $myPageFile ) )
            {
                return false;
            }
        //    var_export( is_file( $myPageFile ));
        //    var_export( ( $options ));


			$pagePathsX['include'] = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/include';
			$pagePathsX['template'] = 'documents/layout/' . $themeName . '/theme' . $pageThemeFileUrl . '/template';

			//	theme copy
			$include = Ayoola_Loader::getFullPath( $pagePathsX['include'], array( 'prioritize_my_copy' => true ) );
            $template = Ayoola_Loader::getFullPath( $pagePathsX['template'], array( 'prioritize_my_copy' => true ) );

            if( $include && $template )
            {
                $PAGE_INCLUDE_FILE = $include;
                $PAGE_TEMPLATE_FILE = $template;
                $pagePaths['include'] = $pagePathsX['include'];
                $pagePaths['template'] = $pagePathsX['template'];
            }
			if
			(
				! is_file( $include ) OR ! is_file( $template )
			)
			{
                if( ! empty( $options['auto_init_theme_page'] ) )
                {
                    if( $uri == '/' && is_file( $PAGE_INCLUDE_FILE ) )
                    {
                        $content = file_get_contents( $pageFile );
                        if( stripos( $content, '</widget>' ) === false && stripos( $content, '<include' ) === false && empty( $_REQUEST['pc_page_layout_name'] ) )
                        {
                            $options['auto_init_theme_page'] = false;
                        }
                    }
                }
    
                if( ! empty( $options['auto_init_theme_page'] ) )
                {
                    $variant = filemtime( $pageFile );

                    //	auto-saved file
                    $pagePathsX['include'] = 'documents/layout/' . $themeName . '/theme/variant/' . $autoName . '' . $pageThemeFileUrl . '/include';
                    $pagePathsX['template'] = 'documents/layout/' . $themeName . '/theme/variant/' . $autoName . '' . $pageThemeFileUrl . '/template';

                    $include = Ayoola_Loader::getFullPath( $pagePathsX['include'], array( 'prioritize_my_copy' => true ) );
                    $template = Ayoola_Loader::getFullPath( $pagePathsX['template'], array( 'prioritize_my_copy' => true ) );
                    if( $include && $template )
                    {
                        $PAGE_INCLUDE_FILE = $include;
                        $PAGE_TEMPLATE_FILE = $template;
                        $pagePaths['include'] = $pagePathsX['include'];
                        $pagePaths['template'] = $pagePathsX['template'];
                    }
                    if( empty( $include ) || ! is_file( $include ) || ! is_file( $template ) || filemtime( $include ) < $variant || filemtime( $include ) < filemtime( $rPath ) )
                    {
        
                        //	save first
                        //	once page is created, let's have blank content
                        //  was causing "Editing /" in title
                        //  and blank pages
                        
                        $page = new Ayoola_Page_Editor_Sanitize( array( 'theme_variant' => '' . $autoName . '' ) );
                        $d = $page->refresh( $uri, $themeName );

                        $include = Ayoola_Loader::getFullPath( $pagePathsX['include'], array( 'prioritize_my_copy' => true ) );
                        $template = Ayoola_Loader::getFullPath( $pagePathsX['template'], array( 'prioritize_my_copy' => true ) );
                        if( $include && $template )
                        {
                            $PAGE_INCLUDE_FILE = $include;
                            $PAGE_TEMPLATE_FILE = $template;
                            $pagePaths['include'] = $pagePathsX['include'];
                            $pagePaths['template'] = $pagePathsX['template'];
                        }
    
                        
                    }
                }

                if( empty( $include ) OR ! is_file( $include ) OR ! is_file( $template ) )
                {
                    //	not found
                    return false;
                }
			}
			return true;
		};
		do
		{
            //  leaving this one have a way of allowing home page ignore 
            //  widgets-embedded theme page
            //  does not allow autogeneration of theme page  for index
		//	if( ! empty( $_REQUEST['pc_page_layout_name'] ) )
			{
				if( $previewTheme( array( 'auto_init_theme_page' => true ) ) )
				{
                    $noRestriction = true;
					break;
				}
            }
			if
			(
				! is_file( $PAGE_INCLUDE_FILE ) OR ! is_file( $PAGE_TEMPLATE_FILE )
			)
			{
				//	not found
				//	use content of default theme
                //  finally, we want to be autogenerating theme pages now
                if( $previewTheme( array( 'auto_init_theme_page' => true ) ) )
                {
                    $noRestriction = true;
                    break;
                }

				// intended copy next
				$intendedCopyPaths = Ayoola_Page::getPagePaths( '/' . trim( $uri . '/default', '/' ) );
				$PAGE_INCLUDE_FILE = Ayoola_Loader::getFullPath( $intendedCopyPaths['include'], array( 'prioritize_my_copy' => true ) );
				$PAGE_TEMPLATE_FILE = Ayoola_Loader::getFullPath( $intendedCopyPaths['template'], array( 'prioritize_my_copy' => true ) );

				if
				(
					! $PAGE_INCLUDE_FILE OR ! $PAGE_TEMPLATE_FILE
				)
				{

					//	global copy
					$PAGE_INCLUDE_FILE = Ayoola_Loader::getFullPath( $pagePaths['include'], array( 'prioritize_my_copy' => true ) );
					$PAGE_TEMPLATE_FILE = Ayoola_Loader::getFullPath( $pagePaths['template'], array( 'prioritize_my_copy' => true ) );

					if
					(
						! $PAGE_INCLUDE_FILE OR ! $PAGE_TEMPLATE_FILE
					)
					{
                        return false;
					}
				}
			}
		}
        while( false );
        $pagePaths['include'] = $PAGE_INCLUDE_FILE;
        $pagePaths['template'] = $PAGE_TEMPLATE_FILE;
        $pagePaths['no_restrictions'] = $noRestriction;
        self::$_viewFiles[$uri] = $pagePaths;
        return $pagePaths;
    }

    /**

     * Include required file to view page
     *
     * @param string URI to view
     * @return void
     */
    public static function view( $uriToView = null )
    {

        $uri = $uriToView;
        $pageInfo = Ayoola_Page::getInfo( $uri );

		if( ! $uri || ! $pageInfo )
		{
			$uri =  self::$_notFoundPage;
			self::$_runtimeSetting['real_url'] = self::$_notFoundPage;
			header( "HTTP/1.0 404 Not Found" );
			header( "HTTP/1.1 404 Not Found" );
			header('Status: 404 Not Found');
			function_exists( 'http_response_code' ) ? http_response_code(404) : null;

		}
		//	now because of situation where we have username domains
		//	we should be able to overide page inheritance

        if( ! $pagePaths = self::getViewFiles( $uri ) )
        {
            return false;
        }
		//	Put in Access Restriction
		$pagePaths['no_restrictions'] ? : self::restrictAccess();

		//	check if redirect

		if( @$pageInfo['redirect_url'] && ! @$_REQUEST['pc_redirect_url'] )
		{
			if( self::getUrlPrefix() && $pageInfo['redirect_url'][0] === '/' )
			{
				$pageInfo['redirect_url'] = self::getUrlPrefix() . $pageInfo['redirect_url'];
			}

			header( 'Location: ' . $pageInfo['redirect_url'] . '?pc_redirect_url=' . $uri . '&' . http_build_query( $_GET ) );
			exit();
		}

		//	Client-side	scripting
		Application_Javascript::addFile( '' . self::getUrlPrefix() . '/tools/classplayer/get/name/Application_Javascript/?v=' . PageCarton::$version . '-' . filemtime( __FILE__ ) );
		Application_Style::addFile( Ayoola_Page::getPageCssFile() );
		Application_Style::addFile( '//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.css' );

		//	Pass the artificial query string to the client-side
		Application_Javascript::addCode
		(
			"
			ayoola.pcPathPrefix = '" . self::getUrlPrefix() . "';
			ayoola.events.add
			(
				window, 'load', function(){ ayoola.setArtificialQueryString( '" . Ayoola_Application::getRuntimeSettings( 'real_url' ) . "' ); }
			);"
		);

		//	Set TimeZone
		date_default_timezone_set( Application_Settings_CompanyInfo::getSettings( 'CompanyInformation', 'time_zone' ) ? : 'UTC' );

        //	Send content type to avoid mozilla reloading when theres and error message
        if( ! headers_sent() )
        {
            header("Content-Type: text/html; charset=utf-8");
        }

		include_once $pagePaths['include'];

		include_once $pagePaths['template'];

		return true;
	}

    /**
     * 
     *
     * @param string url
     * @return string url with query strings
     */
    public static function appendCurrentQueryStrings( $url )
    {
        if( empty( $_GET ) )
        {
            return $url;
        }
        return $url . '?' . http_build_query( $_GET );
    }

    /**
     * Restrict Access to Application
     *
     * @param void
     * @return void
     */
    public static function restrictAccess()
    {

		$auth = new Ayoola_Access();
		$userInfo = $auth->getUserInfo();

		//	Show error to logged in admin
		switch( @$_SERVER['HTTP_AYOOLA_PLAY_MODE'] )
		{
			case null:
			case '':
			case 0:
				//	By default, don't display error'
				{
					error_reporting( E_ALL & ~E_STRICT & ~E_NOTICE & ~E_USER_NOTICE );
					ini_set( 'display_errors', "0" );
				}

				//	If the mode is selected, we don't want to see errors.
				if( $userInfo['access_level'] > 98 || '127.0.0.1' == $_SERVER['REMOTE_ADDR'] )
				{
					error_reporting( E_ALL & ~E_STRICT );

				}

				//	We explicitly asked for it. So let's have it.'
				if( ! empty( $_REQUEST['pc_show_error'] ) )
				{
					error_reporting( E_ALL & ~E_STRICT & ~E_NOTICE & ~E_USER_NOTICE );
					ini_set( 'display_errors', "1" );
				}
			break;
		}
		require_once 'Ayoola/Access.php';
		$auth = new Ayoola_Access();

		//	general restriction

		{
			$auth->restrict();
		}
		{

		}
	}

    /**
     * Logs request
     *
     */
    public static function log()
    {
		self::$accessLogging ? Application_Log_View_Access::log() : null; //	Log request
    }

    /**
     * This method load needed files
     *
     * @param void
     * @return null
     */
    public static function loadPrerequisites()
    {
		require_once( 'configs/definitions.php' );
		require_once( 'functions/init.php' );

		// we have done this in index file

    }

    /**
     * This method sets the include path to a value
     *
     * @param void
     * @return null
     */
    public static function setDefaultIncludePath()
    {
		self::setIncludePath( LIBRARY_PATH );
		self::setIncludePath( MODULES_PATH );
		self::setIncludePath( APPLICATION_PATH );

    }

    /**
     * Returns header for a request
     *
     * @param string
     * @return mixed Response Code or False on Error
     */
    public static function getHeaders( $link, array $options = null )
    {
		//	http://stackoverflow.com/questions/244506/how-do-i-check-for-valid-not-dead-links-programatically-using-php
		$ch = curl_init(); // get cURL handle

        // set cURL options
        $opts = array( CURLOPT_RETURNTRANSFER => true, // do not output to browser
                                  CURLOPT_URL => $link,            // set URL
                                  CURLOPT_AUTOREFERER => true,
                                  CURLOPT_USERAGENT => 'Mozilla/5.0 ( compatible; ayoolabot/0.1; +http://ayoo.la/bot/ )',            // set URL
                                  CURLOPT_NOBODY => true	// do a HEAD request only
					);   // set timeout

		@$opts[CURLOPT_TIMEOUT] = $options['timeout'] ? : 10; //	Defaults to 1sec
		@$opts[CURLOPT_FOLLOWLOCATION] = $options['follow_redirect']; // By default we don't follow redirects

        curl_setopt_array($ch, $opts);

        $response = curl_exec($ch); // do it!
        $info = curl_getinfo($ch); // check if HTTP OK

		if( $info['http_code'] != 200  ){ return false; }

        curl_close($ch); // close handle

        return $info;
    }

    /**
     * Flushes the output to the user
     *
     * @param last massage to echo to the user
     */
    public static function outputFlush( $lastOutput )
    {
		if( ! self::$disableOutput )
		{
			echo $lastOutput;
			ob_flush();
			flush();
		}
    }

    /**
     * Return _mode
     *
     * @param void
     * @return string
     */
    public static function getMode()
    {
		return self::$_mode;
    }

    /**
     * Returns the $_userInfo
     *
     * @param string Key to the Info to return
     * @return mixed
     */
    public static function getUserInfo( $key = null )
    {
		if( is_null( self::$_userInfo ) || $key === false )
		{
            $access = new Ayoola_Access();
            //    var_export( $access->getUserInfo() );
            if( self::$_userInfo = $access->getUserInfo() )
            {
                self::$_userInfo['username'] = strtolower( self::$_userInfo['username'] );
                self::$_userInfo['email'] = strtolower( self::$_userInfo['email'] );
            }
			if( ! self::$_userInfo ){ return false; }
		}

		if( ! is_array( self::$_userInfo ) || $key === false )
		{
			return array();
		}
		return $key ? @self::$_userInfo[$key] : self::$_userInfo;
    }

    /**
     * This method basically removes the /get/ seo query from the requested Uri
     *
     * @param void
     * @return null
     */
    public static function getPresentUri( $url = null )
    {
        if( isset( self::$_presentUri[$url] ) && @self::$_presentUri[$url] ) 
        {
			return self::$_presentUri[$url];
        }

        return self::setPresentUri( $url );
    }

    /**
     * This method basically removes the /get/ seo query from the requested Uri
     *
     * @param void
     * @return null
     */
    public static function setPresentUri( $url = null )
    {
		$url = $url ? : self::getRequestedUri();
		require_once 'Ayoola/Filter/Uri.php';
		$filter = new Ayoola_Filter_Uri;
		$result = $filter->filter( $url );
		self::$_presentUri[$url] = $result;
		return self::$_presentUri[$url];
    }

    /**
     * This method returns the requested Uri
     *
     * @param void
     * @return null
     */
    public static function getRequestedUri()
    {
        if( self::$_requestedUri ){ return self::$_requestedUri; }
        self::setRequestedUri();
		return self::$_requestedUri;
    }

    /**
     * This method returns the requested Uri
     *
     * @param void
     * @return null
     */
    public static function setRequestedUri( $requestedUri = null )
    {
        if (empty($requestedUri)) 
        {
            $requestedUri = self::$_homePage;	// Default

            //	because of url prefix that has space in them
            @$requestedUriDecoded = $_SERVER['REQUEST_URI'];

            //	remove query strings
            //array_shift works with array reference, so a variables parameter is required
            $shift = explode('?', $requestedUriDecoded) ;
            $requestedUriDecoded = array_shift($shift);

            @$requestedUriDecoded = rawurldecode($requestedUriDecoded);

            if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/' && $_SERVER['SCRIPT_NAME'] != $requestedUriDecoded) {

                $requestedUri = $requestedUriDecoded;
            }

            if (isset($_SERVER['PATH_INFO'])) {
                $requestedUri = $_SERVER['PATH_INFO'];
            } else {
            }
        }

        //	REMOVE PATH PREFIX
        if( Ayoola_Application::getPathPrefix() && strpos( $requestedUri, Ayoola_Application::getPathPrefix() ) === 0 )
        {
            $requestedUri = explode( Ayoola_Application::getPathPrefix(), $requestedUri );
            array_shift( $requestedUri );
            $requestedUri = implode( Ayoola_Application::getPathPrefix(), $requestedUri ) ? : '/';
        }
        elseif( strpos( $requestedUri, '/index.php/' ) !== false )
        {
            $requestedUri = explode( '/index.php', $requestedUri );
            if( count( $requestedUri ) === 2 )
            {
                #	https://www.comeriver.com/music/index.php/trending
                list( $pathPrefix, $requestedUri ) = $requestedUri; 
                if( ! Ayoola_Application::getPathPrefix() )
                {
                    self::$_pathPrefix = $pathPrefix;
                }
            }
        }
        $requestedUri = parse_url( $requestedUri );
        $requestedUri = $requestedUri['path'];
        if( $requestedUri == '/' )
        {
            $requestedUri = self::$_homePage;
        }

        //	an nginx installation not recognizing url like
        //	https://www.example.com/index.php/url
        $controller = '/index.php/';
        if( stripos( $requestedUri, $controller ) === 0 )
        {
            $requestedUri = '/' . array_pop( explode( $controller, $requestedUri ) );
        }

        //  mitigate XSS attacks via URL
        //  http://localhost:8888/search/foo%22%3E%3Cscript%20src=//dtf.pw/2.js%3E%3C/script%3E%3Cx=%22?
        $requestedUri = strip_tags( $requestedUri );

		//	Fetch the GET parameters from the url
		require_once 'Ayoola/Filter/Get.php';
		$filter = new Ayoola_Filter_Get;
		$get = $filter->filter( $requestedUri );
		$_GET = array_merge( $_GET, $get ); // Combines our generated params with the original
		$_REQUEST = array_merge( $_REQUEST, $get ); // Combines our generated params with the original
		self::$_requestedUri = $requestedUri;
		return self::$_requestedUri;
    }

    /**
     *
     *
     */
	public static function getUrlSuffix()
    {
		$suffix = null;
		if( ! empty( $_REQUEST['pc_page_layout_name'] ) )
		{
			//	we need this so that theme preview could be seemless
			$suffix .= '&pc_page_layout_name=' . $_REQUEST['pc_page_layout_name'];
		}
		return $suffix;
	}

    /**
     *
     *
     */
	public static function getUrlPrefix()
    {

		if( is_null( self::$_urlPrefix ) )
		{
			self::setUrlPrefix();
		}

		return self::$_urlPrefix;
	}

    /**
     *
     *
     */
	public static function setUrlPrefix( $prefix = null )
    {

		self::$_urlPrefix = '';
		if( ! empty( $_REQUEST['pc_clean_url_check'] ) )
		{
			return true;
		}
		if( $prefix )
		{
			self::$_urlPrefix = $prefix;
			return true;
		}
		$storage = new Ayoola_Storage();
		$storage->storageNamespace = __CLASS__  . 'url_prefix-x' . Ayoola_Application::getPathPrefix();
		$storage->setDevice( 'File' );
		$response = $storage->retrieve();
 		if( ! $response  )
		{
 			//	Detect if we have mod-rewrite
            $urlToLocalInstallerFile = ( Ayoola_Application::getDomainSettings( 'protocol' ) ? : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . Ayoola_Application::getPathPrefix() . '/pc_check.txt?pc_clean_url_check=1';

    		$response = PageCarton_Widget::fetchLink( $urlToLocalInstallerFile );

 			$storage->store( $response );
        }

		if( $response === 'pc' )
		{
            //  this isn't suppose to be there
            //  causing index.php to appear
		    self::$_urlPrefix .= Ayoola_Application::getPathPrefix();
		}
		elseif( isset( $_SERVER['PATH_INFO'] ) && $_SERVER['PATH_INFO'] != '/' )
		{
			self::$_urlPrefix .= $_SERVER['SCRIPT_NAME'];
		}
		elseif( @self::getPathPrefix() )
		{
			self::$_urlPrefix .= self::getPathPrefix();
		}

		return self::$_urlPrefix;
	}

    /**
     *
     *
     */
	public static function getPathPrefix()
    {
		if( is_null( self::$_pathPrefix ) )
		{
			self::$_pathPrefix = constant( 'PC_PATH_PREFIX' ) ? : '';
		}

		return self::$_pathPrefix;
	}

    /**
     *
     *
     */
	public static function getRealPathPrefix()
    {
		$path = str_replace( @$_SERVER['CONTEXT_PREFIX'], '', Ayoola_Application::getPathPrefix() );
		return $path;
	}

    /**
     *
     *
     */
	public static function getUrlPrefixController()
    {
		$controller = basename( $_SERVER['SCRIPT_NAME'] );
		if( stripos( self::getUrlPrefix(), $controller ) === false )
		{
			return null;
		}
		return '/' . $controller;
	}

    /**
     *
     *
     */
	public static function getUserAccountInfo( $key = null )
    {
		if( ! self::$_userAccountInfo )
		{
			self::$_userAccountInfo['userid'] =  array();
			$functionName = function_exists( 'posix_getuid' ) ? 'posix_getuid' : 'getmyuid';
			self::$_userAccountInfo['userid'] =  $functionName();
			$processUserInfo =  function_exists( 'posix_getpwuid' ) ? posix_getpwuid( self::$_userAccountInfo['userid'] ) : null;
			self::$_userAccountInfo['username'] =  $processUserInfo['name'] ? : 'UNKWOWN';
		}
		return $key ? self::$_userAccountInfo[$key] : self::$_userAccountInfo;
    }

    /**
     * Returns true if the request was by internal cURL
     *
     * @param void
     * @return boolean
     */
    public static function isCurlRequest()
    {
		if
		(
			( isset( $_SERVER['HTTP_REQUEST_TYPE'] ) && $_SERVER['HTTP_REQUEST_TYPE'] == 'curl' )
		)
		{ return true; }
		return false;
    }

    /**
     * Returns true if the request was by ajax
     *
     * @param void
     * @return boolean
     */
    public static function isXmlHttpRequest()
    {
		$pointer = array_map( 'trim', explode( ',', @$_SERVER['HTTP_REQUEST_TYPE'] ) );

		if
		(
			in_array( 'xmlHttp', $pointer )
		)
		{ return true; }
		return false;
    }

    /**
     * Returns true if the particular class is being 'played'
     *
     * @param void
     * @return boolean
     */
    public static function isClassPlayer()
    {

		if
		(
			( isset( $_SERVER['HTTP_APPLICATION_MODE'] ) && $_SERVER['HTTP_APPLICATION_MODE'] == 'Ayoola_Object_Play' )

		)
		{
			return true;
		}
		return false;
    }

    /**
     * Returns true if we are running on local server
     *
     * @param void
     * @return boolean
     */
    public static function isLocalServer()
    {

		if
		(
			in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ) )
		)
		{ return true; }
		return false;

    }

    /**
     * Returns true if we are running on local server
     *
     * @param void
     * @return boolean
     */
    public static function isFirstAdminUser()
    {
		$response = Application_User_Abstract::getUsers( array( 'access_level' => array( 98, 99 ) ) );
		if( $response  )
		{
			return false;
		}
		return true;
    }

	// END OF CLASS
}
