<?php
/* https://gist.github.com/philBrown/880506
 */
 
class ImageManipulator
{
    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var resource
     */
    protected $image;

    /**
     * Image manipulator constructor
     * 
     * @param string $file OPTIONAL Path to image file or image data as string
     * @return void
     */
    public function __construct($file = null)
    {
        if (null !== $file) {
            if (is_file($file)) {
                $this->setImageFile($file);
            } else {
                $this->setImageString($file);
            }
        }
    }

    /**
     * Set image resource from file
     * 
     * @param string $file Path to image file
     * @return ImageManipulator for a fluent interface
     * @throws InvalidArgumentException
     */
    public function setImageFile($file)
    {
        if (!(is_readable($file) && is_file($file))) {
            throw new InvalidArgumentException("Image file $file is not readable");
        }

        if (is_resource($this->image)) {
            imagedestroy($this->image);
        }

        list ($this->width, $this->height, $type) = getimagesize($file);

        switch ($type) {
            case IMAGETYPE_GIF  :
                $this->image = imagecreatefromgif($file);
                break;
            case IMAGETYPE_JPEG :
                $this->image = imagecreatefromjpeg($file);
                break;
            case IMAGETYPE_PNG  :
                $this->image = imagecreatefrompng($file);
                break;
            default             :
                throw new InvalidArgumentException("Image type $type not supported");
        }

        return $this;
    }
    
    /**
     * Set image resource from string data
     * 
     * @param string $data
     * @return ImageManipulator for a fluent interface
     * @throws RuntimeException
     */
    public function setImageString($data)
    {
        if (is_resource($this->image)) {
            imagedestroy($this->image);
        }

        if (!$this->image = imagecreatefromstring($data)) {
            throw new RuntimeException('Cannot create image from data string');
        }
        $this->width = imagesx($this->image);
        $this->height = imagesy($this->image);
        return $this;
    }

    /**
     * Resamples the current image
     *
     * @param int  $width                New width
     * @param int  $height               New height
     * @param bool $constrainProportions Constrain current image proportions when resizing
     * @return ImageManipulator for a fluent interface
     * @throws RuntimeException
     */
    public function resample($width, $height, $constrainProportions = true)
    {
        if (!is_resource($this->image)) {
            throw new RuntimeException('No image set');
        }
        if ($constrainProportions) {
            if ($this->height >= $this->width) {
                $width  = round($height / $this->height * $this->width);
            } else {
                $height = round($width / $this->width * $this->height);
            }
        }
        $temp = imagecreatetruecolor($width, $height);
        imagecopyresampled($temp, $this->image, 0, 0, 0, 0, $width, $height, $this->width, $this->height);
        return $this->_replace($temp);
    }
    
    /**
     * Enlarge canvas
     * 
     * @param int   $width  Canvas width
     * @param int   $height Canvas height
     * @param array $rgb    RGB colour values
     * @param int   $xpos   X-Position of image in new canvas, null for centre
     * @param int   $ypos   Y-Position of image in new canvas, null for centre
     * @return ImageManipulator for a fluent interface
     * @throws RuntimeException
     */
    public function enlargeCanvas($width, $height, array $rgb = array(), $xpos = null, $ypos = null)
    {
        if (!is_resource($this->image)) {
            throw new RuntimeException('No image set');
        }
        
        $width = max($width, $this->width);
        $height = max($height, $this->height);
        
        $temp = imagecreatetruecolor($width, $height);
        if (count($rgb) == 3) {
            $bg = imagecolorallocate($temp, $rgb[0], $rgb[1], $rgb[2]);
            imagefill($temp, 0, 0, $bg);
        }
        
        if (null === $xpos) {
            $xpos = round(($width - $this->width) / 2);
        }
        if (null === $ypos) {
            $ypos = round(($height - $this->height) / 2);
        }
        
        imagecopy($temp, $this->image, (int) $xpos, (int) $ypos, 0, 0, $this->width, $this->height);
        return $this->_replace($temp);
    }
    
    /**
     * Crop image
     * 
     * @param int|array $x1 Top left x-coordinate of crop box or array of coordinates
     * @param int       $y1 Top left y-coordinate of crop box
     * @param int       $x2 Bottom right x-coordinate of crop box
     * @param int       $y2 Bottom right y-coordinate of crop box
     * @return ImageManipulator for a fluent interface
     * @throws RuntimeException
     */
    public function crop($x1, $y1 = 0, $x2 = 0, $y2 = 0)
    {
        if (!is_resource($this->image)) {
            throw new RuntimeException('No image set');
        }
        if (is_array($x1) && 4 == count($x1)) {
            list($x1, $y1, $x2, $y2) = $x1;
        }
        
        $x1 = max($x1, 0);
        $y1 = max($y1, 0);
        
        $x2 = min($x2, $this->width);
        $y2 = min($y2, $this->height);
        
        $width = $x2 - $x1;
        $height = $y2 - $y1;
      //  var_export( $height );
        $temp = imagecreatetruecolor($width, $height);
        imagecopy($temp, $this->image, 0, 0, $x1, $y1, $width, $height);
        
        return $this->_replace($temp);
    }
    
    /**
     * Replace current image resource with a new one
     * 
     * @param resource $res New image resource
     * @return ImageManipulator for a fluent interface
     * @throws UnexpectedValueException
     */
    protected function _replace($res)
    {
        if (!is_resource($res)) {
            throw new UnexpectedValueException('Invalid resource');
        }
        if (is_resource($this->image)) {
            imagedestroy($this->image);
        }
        $this->image = $res;
        $this->width = imagesx($res);
        $this->height = imagesy($res);
        return $this;
    }

    public static function makeThumbnail($thumb_target = '', $max_width = 60,$max_height = 60,$SetFileName = false, $quality = 80)
    {

        $imgsize = getimagesize($thumb_target);
        $width = $imgsize[0];
        $height = $imgsize[1];
        $mime = $imgsize['mime'];
    
        switch($mime){
            case 'image/gif':
                $image_create = "imagecreatefromgif";
                $image = "imagegif";
                break;
    
            case 'image/png':
                $image_create = "imagecreatefrompng";
                $image = "imagepng";
                $quality = 7;
                break;
    
            case 'image/jpeg':
                $image_create = "imagecreatefromjpeg";
                $image = "imagejpeg";
                $quality = 80;
                break;
    
            default:
                return false;
                break;
        }
        
        $dst_img = imagecreatetruecolor($max_width, $max_height);
        $src_img = $image_create($thumb_target);
        
        $width_new = $height * $max_width / $max_height;
        $height_new = $width * $max_height / $max_width;
        //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
        // I think this is where you are mainly going wrong
        $dst_img = imagecreatetruecolor($max_width,$max_height);
        imagealphablending( $dst_img, false );
        imagesavealpha( $dst_img, true );
        if($width_new > $width){
            //cut point by height
            $h_point = (($height - $height_new) / 2);
            //copy image
            @imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
        }else{
            //cut point by width
            $w_point = (($width - $width_new) / 2);
            @imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
        }

        if( 
            ( 
                $docOptions = Ayoola_Doc_Settings::retrieve( 'options' ) 
                AND is_array( $docOptions ) 
                AND in_array( 'link_doc_to_web_root', $docOptions ) 
            )            
        )
        {
            $xTpath = CACHE_DIR . DS .  __CLASS__ . DS . $max_width . 'x' . $max_height . DS . md5( $thumb_target );
            if( ! is_file( $xTpath ) )
            {
                Ayoola_Doc::createDirectory( dirname( $xTpath ) );
                $image( $dst_img, $xTpath, $quality );
            }
            $_GET['width'] = $max_width;
            $_GET['height'] = $max_height;
            Ayoola_Doc_Adapter_Abstract::linkToWebRoot( $xTpath, Ayoola_Application::getRequestedUri() );


        }
        if( $SetFileName == false ) 
        {
            header('Content-Type: ' . $mime );     
            $image($dst_img);
            
            if($dst_img)imagedestroy($dst_img);
            if($src_img)imagedestroy($src_img);
            exit();
        }
        else
        {
            $image( $dst_img, $SetFileName, $quality );
            Ayoola_Doc_Adapter_Abstract::linkToWebRoot( $SetFileName, Ayoola_Application::getRequestedUri() );
        }
       
        if($dst_img)imagedestroy($dst_img);
        if($src_img)imagedestroy($src_img);

    }  

    /**
     * Save current image to file
     * 
     * @param string $fileName
     * @return void
     * @throws RuntimeException
     */
    public function save($fileName, $type = IMAGETYPE_JPEG)
    {
        $dir = dirname($fileName);
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true)) {
                throw new RuntimeException('Error creating directory ' . $dir);
            }
        }
        
        try {
            switch ($type) {
                case IMAGETYPE_GIF  :
                    if (!imagegif($this->image, $fileName)) {
                        throw new RuntimeException;
                    }
                    break;
                case IMAGETYPE_PNG  :
                    if (!imagepng($this->image, $fileName)) {
                        throw new RuntimeException;
                    }
                    break;
                case IMAGETYPE_JPEG :
                default             :
                    if (!imagejpeg($this->image, $fileName, 95)) {
                        throw new RuntimeException;
                    }
            }
        } catch (Exception $ex) {
            throw new RuntimeException('Error saving image file to ' . $fileName);
        }
    }

    /**
     * Returns the GD image resource
     *
     * @return resource
     */
    public function getResource()
    {
        return $this->image;
    }

    /**
     * Get current image resource width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Get current image height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }
}