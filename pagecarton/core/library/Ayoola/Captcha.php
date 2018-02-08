<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Captcha
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: filename.php date time username $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Captcha
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Captcha
{
    /**
     * The captcha code
     *
     * @var string
     */
	protected $_code = null;
	
    /**
     * The captcha image
     *
     * @var string
     */
	protected $_image = null;
	
    /**
     * The captcha image width
     *
     * @var string
     */
	protected $_width = 250;
	
    /**
     * The captcha image height
     *
     * @var string
     */
	protected $_height = 75;
	
    /**
     * Filename for the image
     *
     * @var string
     */
	protected $_filename = null;

	
    /**
     * src link for the image
     *
     * @var string
     */
	protected $_link = null;
	
    /**
     * 
     *
     * @var array
     */
	protected static $_hashList;
	
	
    /**
     * Retrieves a application unique hash
     *
     * @param void
     * @return string
     */
    public static function getHash( array $randomizationOptions = null )
    {
		$rand = md5( json_encode( $randomizationOptions ) );
		if( ! empty( self::$_hashList[$rand] ) )
		{
			return self::$_hashList[$rand];
		}
	//	var_export( $rand );
	//	var_export( "\r\n" );
		$randomnizer = $randomizationOptions['name'];
		
		//	Default is refresh dailt
		if( false !== @$randomizationOptions['daily'] )
		{
			$randomnizer .= date( "M Y d" );		
		}
		
		//	Default is browser based
		if( false !== @$randomizationOptions['browser'] )
		{
			$randomnizer .= $_SERVER['HTTP_USER_AGENT'];		
		}
		
		$randomnizer .= Application_Settings_Abstract::getSettings( 'Security', 'application_salt' );

		$randomnizer = $randomnizer . '386989779HGL!@#,-=12' . $randomnizer . '&qwd1235^@-=@11' . $randomnizer; // Creates a new server salt every day
		static::$_hashList[$rand] = md5( $randomnizer  );
		//	hash
		return static::$_hashList[$rand];
    } 
	
    /**
     * Retrieves the object image
     *
     * @param void
     * @return resource
     */
    public function getImage()
    {
		if( null === $this->_image )
			$this->_createImage();
			
        return $this->_image;
    } 
	
	
    /**
     * This method turns the code to image
     *
     * @param void
     * @return void
     */
    protected function _createImage()
    {
		$code = $this->getCode(); 
		$space_per_char = $this->getWidth() / ( mb_strlen($code) + 1);

		/* Create canvas */
		$img = imagecreatetruecolor( $this->getWidth(), $this->getHeight() );
		/* Allocate colors */
		$background = imagecolorallocate($img, 255, 255, 255);
		$border = imagecolorallocate($img, 255, 255, 255);
		$colors[] = imagecolorallocate($img, 0, 0, 0);
		$colors[] = imagecolorallocate($img, 0, 0, 0);
		$colors[] = imagecolorallocate($img, 0, 0, 0);
		
		/* Fill background */
		imagefilledrectangle($img, 1, 1, $this->getWidth() - 2, $this->getHeight() - 2, $background);
		imagerectangle($img, 0, 0, $this->getWidth() - 1, $this->getHeight() - 1, $border);
		/* Draw text */
		for ($i = 0; $i < strlen($code); $i++)
		{
			$color = $colors[$i % count($colors)];
			imagettftext(
			$img,
			28 + rand(0, 8),
			-20 + rand(0, 40),
			($i + 0.3) * $space_per_char,
			50 + rand(0, 10),
			$color,
			'arial.ttf',
			$code{$i}
			);
		}
		/* Adding some random distortions */
		imageantialias($img, true);
		for ($i = 0; $i < 1000; $i++)
		{
			$x1 = rand(5, $this->getWidth() - 5);
			$y1 = rand(5, $this->getHeight() - 5);
			$x2 = $x1 - 4 + rand(0, 8);
			$y2 = $y1 - 4 + rand(0, 8);
			imageline($img, $x1, $y1, $x2, $y2,
			0
			);
		}
		$folder = APPLICATION_DIR . DS . 'public' . DS . 'img' . DS . __CLASS__ . DS;
		if( ! is_dir( $folder ) )
		mkdir( $folder, true );
		
		$filename = $folder . sha1( $this->getCode() )  . '.png';
		$link = '/img/' . __CLASS__ . '/' . basename( $filename );
		
		$this->setFilename( $filename );
		$this->setLink( $link );
		
		imagepng( $img, $filename );
		$this->setImage( $filename );
		
		$this->_db(); // Insert into database

    } 
	
	
    /**
     * sets the image to value
     *
     * @param resource
     * @return void
     */
    public function setImage( $image )
    {
		return $this->_image = '<img src="' . $this->getLink() .  '" />';
    } 
	
    /**
     * Insert the code in the db
     *
     * @param 
     * @return boolean
     */
    protected function _db()
    {
		require_once 'Ayoola/Filter/Alnum.php';
		$filter = new Ayoola_Filter_Alnum;
		$code = $filter->filter( $this->getCode() );
		
		$sessionId = session_id();
		$values = array( 'filename' => addcslashes( $this->getFilename(), '\\' ), 
							'session_id' => session_id(), 
							'code' => $code, 
							'time' => time()
							);
        $table = Ayoola_Dbase_Table_Captcha::getInstance();
		self::cleanUp( $table ); // Clean up old captcha files and db row
		$table->insert( $values ); 
    } 
	
    /**
     * Insert the code in the db
     *
     * @param 
     * @return boolean
     */
    public static function cleanUp( $table )
    {
		$time = time() - ( 2 * 60 );
		$data = $table->select( '', '', "`time` < $time" ); // Find the old captcha
		foreach( $data as $each )
		{
			if( is_file( $each['filename'] ) )
			{	
				if( unlink( $each['filename'] ) ) // delete the old captcha images
				$table->delete( "`code` = '{$each['code']}'" );  // Delete their row
			}
		}
    } 
	
    /**
     * Creates a random code for captcha
     *
     * @param 
     * @return string
     */
    protected function _createCode()
    {
        $letters = range( 'A', 'z' ); 
		$numbers = range( 0, 9 );
		$alnum = array_merge( $letters, $numbers );
		$alnumCount = count( $alnum ) - 1;
		$word = implode( '', $alnum ); 
		$noOfCha = mt_rand( 7, 10 );
		$code = '';
		for( $i=0; $i < $noOfCha; $i++ )
		{
			$code .= $word[mt_rand( 0, $alnumCount )];
		}
		$this->_setCode( $code );

    } 
	
    /**
     * sets the captcha code to a value
     *
     * @param string
     * @return void 
     */
    private function _setCode( $code )
    {
        $this->_code = $code;
    }
	
    /**
     * Returns the captcha code
     *
     * @param 
     * @return string
     */
    public function getCode()
    {
		if( null === $this->_code)
			$this->_createCode();
			
        return $this->_code;
    } 
	
    /**
     * sets the captcha code to a value
     *
     * @param int
     * @return void 
     */
    public function setHeight( $height )
    {
        $this->_height = $height;
    }
	
    /**
     * Returns the captcha height
     *
     * @param 
     * @return int
     */
    public function getHeight()
    {
        return $this->_height;
    } 
	
    /**
     * Returns the width of captcha
     *
     * @param void
     * @return int 
     */
    public function getWidth()
    {
        return $this->_width;
    }
	
    /**
     * sets the captcha image width to a value
     *
     * @param int
     * @return 
     */
    public function setWidth( $width )
    {
        return $this->_width = $width;
    } 
	
	
    /**
     * Returns the filename of the image
     *
     * @param void
     * @return string 
     */
    public function getFilename()
    {
        return $this->_filename;
    }
	
    /**
     * Assigns a value to filename of the image
     *
     * @param string
     * @return 
     */
    public function setFilename( $filename )
    {
        return $this->_filename = $filename;
    } 
	
	
	
    /**
     * Returns the link of the image
     *
     * @param void
     * @return string 
     */
    public function getLink()
    {
        return $this->_link;
    }
	
    /**
     * Assigns a value to link of the image
     *
     * @param string
     * @return 
     */
    public function setLink( $link )
    {
        return $this->_link = $link;
    } 
	
}
