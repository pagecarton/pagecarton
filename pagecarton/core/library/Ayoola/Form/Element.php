<?php

/**
*	Class of Form elements and its validation, 
*	it complements Ayoola_Forms
*
*
*/ 

class Ayoola_Form_Element extends Ayoola_Form  
{  
	
    /**  
     * A unique ID that identifies a fieldset. Introducing this to curb fieldset appearing twice
     * 
     * @var string
     */
	public $id;  
	
	
    /**
     * Count fields
     * 
     * @var array
     */
	protected static $_fieldCount = array();     
	
    /**
     * Whether or not to hash the element name (AntiBOT).
     * 
     * @var boolean
     */
	public $hashElementName = true;
	
    /**
     * Whether to show only placeholders.
     * 
     * @var boolean
     */
	public $placeholderInPlaceOfLabel = false;  
	
    /**
     * Whether to wrap each element in a div tag.
     * 
     * @var boolean
     */
	public $useDivTagForElement = false;
	
    /**
     * Allow duplication of fieldset
     * 
     * @var boolean 
     */					
	public $allowDuplication = false;
	
    /**
     * Whether to append or prepend new element to the fieldset.
     * 
     * @var boolean
     */
	public $appendElement = true;
	
	protected $_html = null;

	protected $_legend;
	
	protected $_values =array();
	
	protected $_names =array();
	
	protected $_description;
	
	protected $_elements = array();
	
	protected $_requirements = array();
	
	protected $_filters = array();
	
	protected $_customBadnews = array();
	
	
	protected static $_elementCounter = 0;


    /**
     * Constructor
     *
     * @param array Element to be added
     * 
     */
    public function __construct( Array $element = null )
    {
	
		self::$_elementCounter++;
		$element ? $this->addElement( $element ) : null;
    }

    /**
     * Returns the html of the attribute of an element
     *
     * @param array Element Attributes
     * 
     */
    public function getAttributesHtml( Array $element )
    {
		$inner = null;
	//	self::v( $element );
		unset( $element['label'] );
		foreach( $element as $key => $each )
		{
			if( is_scalar( $each ) )
			{
				$inner .= ' ' . $key . ' = "' . str_ireplace( '"', "'", $each ) . '" ';
			}
		}
		return $inner;
    }

    public function addElement( $element, $values = array() )
    {
		if( ! is_array( $element ) )
		{
			$element = _Array( $element );
		}
        if( empty( $element['name'] ) )
		{
		//	var_export( $element );
			trigger_error(  'You must enter a name for the Form Element' );
		}
		if( @$element['type'] )
		{
		}
		//	Set Element ID and Label to default if undeclared
    //    $element['label'] = isset( $element['label'] ) ? $element['label'] : ucwords( str_replace( '_', ' ', $element['name'] ) );	
			
		$element['real_name'] = $element['name'];
		@$element['title'] = trim( $element['title'] ? : str_replace( array( '_', '-' ), ' ', htmlentities( $element['label'] . ': ' . $element['placeholder'] ) ) , ':' );
		@$element['placeholder'] = ( $element['placeholder'] ? : $element['label'] ) ? : ucwords( str_replace( '_', ' ', $element['name'] ) );
		$element['hashed_name'] = self::hashElementName( $element['name'] );
		$element['title'] = self::__( $element['title'] );
		$element['placeholder'] = self::__( $element['placeholder'] );
		if( $this->hashElementName )
		{
			$element['name'] = $element['hashed_name'];
		}
		@$element['id'] = $element['id'] ? : $element['name'];
	//	var_export( $element['real_name'] );
	//		var_export( __LINE__ );
							
		//	set value to GET or POST equivalent if available
		if( $element['type'] != 'submit' )
		{
			@$element['value'] = ! is_null( $element['value'] ) ? $element['value'] : Ayoola_Form::getDefaultValues( $element['real_name'] );
		}
	//	self::v( $element['value'] );
		
		if( @$element['autocomplete'] !== 'off' )
		{
			$element['value'] = self::getGlobalValue( $element['real_name'], $element['value'], true ) ? : $element['value'];
		}

			//	self::v( $element['value'] );

		if( is_scalar( @$element['value'] ) )
		{
	//		$element['value'] = htmlentities( $element['value'], ENT_QUOTES, "UTF-8", false );	
			
			//	ENT_SUBSTITUTE allows non-utf encoding to go past this point
			if ( ! defined( 'ENT_SUBSTITUTE') ) define( 'ENT_SUBSTITUTE', ENT_QUOTES );
			$element['value'] = htmlentities( $element['value'], ENT_SUBSTITUTE, "UTF-8", true );						
		}
	//	self::v( $element['value'] );    
		
	//	$element['name'] = md5( $element['name'] );
		$name = $element['name'];
		$realName = $element['real_name'];
		if( $this->hashElementName )
		{
			$name = $element['name'] = $element['hashed_name'];
		}
		//	Record element in name list
		$this->_names[$element['name']] = $element;
		
		//	Record values
		$this->_values[$name] = @$element['value'];
		
		// 	Covert to html object and add description 
		$footnote = @$element["footnote"];
//		$description = @$element["description"];
		$markup = null;
//		self::v( $element['real_name'] );
//		self::v( $element['type'] );
		if( $element['type'] )
		{
			$method = 'add' . @$element['type'];
			if( ! method_exists( __CLASS__, $method ) )
			{
			//	$adderEx
				$method = 'addInputText';
			//	$element .= $description ? "<span> {$description} </span>" : null;		
			//	$element = "<span>{$element}</span>";		
				//	exit();
			}
			else
			{
				unset( $element['type'] );
			}
			$element['name'] = @$element['multiple'] ? ( $element['name'] . '[]' ) : $element['name']; 
            unset( $element['multiple'], $element['description'], $element['real_name'], $element['hashed_name'], $element['event'] );
            if( ! empty( $values ) && empty( $values['html'] ) )
            {
                $values = self::__( $values );
            }
			$markup .= $this->$method( $element, $values );
		}
		$markup .= $footnote ? "<br>{$footnote}<br>\n" : null;	
//		var_export( $method );	
		if( ! empty( $_GET['pc_inspect_widget_form'] ) && stripos( $realName, 'submit-' ) === false && stripos( $realName, 'SUBMIT_DETECTOR' ) === false )
		{
			$markup .= ' <div style="font-size:smaller; padding-top:1em; padding-bottom:1em;"><br> The name attribute of the element above is "' . $realName . '"<br></div>';   
		}
	//	self::v( $element );
		switch( strtolower( $method ) )
		{
			case 'addinputtext':
			case 'addtextarea':
			case 'addselect':
			//	self::v( $method );
				//	Set Element ID and Label to default if undeclared
                $element['label'] = isset( $element['label'] ) ? $element['label'] : ucwords( str_replace( '_', ' ', $realName ) );
			break;
			case 'addhidden':
				$element['label'] = null;	
			break;
		}
		if( $this->placeholderInPlaceOfLabel || ! trim( @$element['label'] ) )
		{
		
		}
		else
		{
            $element['label'] = self::__( $element['label'] );
			@$markup = "<label style=\"{$element['label_style']}\" for=\"{$element['name']}\">{$element['label']}</label>{$markup}";
		}
		if( strtolower( $method ) == 'adddocument' )
		{
			$markup = "<div>{$markup}</div>";
		}
		$this->setHtml( $markup );
		if( $this->appendElement )
		{
			$this->_elements[$name] = $markup;
		}
		else
		{
			$this->_elements = array( $name => $markup ) + $this->_elements;
		}
		
		// Register Html object to fieldlist
	//	var_export( $div );
		return $markup;
		
    }
	
    public function addElements( array $elements, $values = array() )
    {
        foreach( $elements as $each )
		{
			$this-addElement( $element[$each], $values );
		}
		return $this;
    }

    public function addRequirement( $name, $requirement, $message = '' )
    {
		if( $this->hashElementName )
		{
			$name = self::hashElementName( $name );
		}
	//	var_export( $name );
	//	var_export( $requirement );
		if( array_key_exists( $name, $this->_values ) )
		{	
			$this->_requirements[$name] = empty( $this->_requirements[$name] ) ? 
											_Array( $requirement ) :
											array_merge( $this->_requirements[$name], _Array( $requirement ) );
			$this->_customBadnews[$name] = (string) $message;
		}
		else
		{
		//	throw new Ayoola_Form_Exception( $name . ' does not exist in fieldlist' );
		}
		
    }
	
    public function addRequirements( $requirement, $message = '' )
    {
		foreach( $this->_names as $details )
		{	
			$this->addRequirement( $details['real_name'], $requirement, $message );
		}
		
    }
	
    public function addFilter( $name, $filters )
    {
		if( $this->hashElementName )
		{
			$name = self::hashElementName( $name );
		}
//		var_export( $name );
	//	var_export( $filters );
		if( array_key_exists( $name, $this->_values ) )
		{	
			$this->_filters[$name] = empty( $this->_filters[$name] ) ? 
											_Array( $filters ) :
											array_merge( $this->_filters[$name], _Array( $filters ) );
		}
		else
		{
		//	throw new Ayoola_Form_Exception( $name . ' does not exist in fieldlist' );
		}
		
    }
	
    public function addFilters( $filters )
    {
		foreach( $this->_names as $details )
		{	
			
			$this->addFilter( $details['real_name'], $filters );
		}
		
    }
	
    public function addDescription( $element )
    {
		if( ! empty( $element['description'] ) )
		{	
			$element = $element .  "\n<p>{$element["description"]}</p>";
		}		
    }

    public function getElements()
    {
        return $this->_elements;
    }
	// @ return the array of names to value of elements
    public function getValues()
    {
        return $this->_values;
    }
	
	// @ return the array of Label to details of elements
    public function getNames()
    {
        return $this->_names;
    }
	
    public function getFilters()
    {
        return $this->_filters;
    }

    public function getRequirements()
    {
        return $this->_requirements;
    }

	
    /**
     * Sets and Updates the _html property
     *
     * @param string The Mark-up For Displaying Elements in a Browers
     * @return void
     */
    public function setHtml( $html )
    {
		if( $this->appendElement )
		{
			$this->_html .= (string) $html;
		}
		else
		{
			$this->_html = $html . $this->_html;
		}
    } 	
	
    /**
     * Sets and Updates the _html property
     *
     * @return string The Mark-up For Displaying Elements in a Browers
     */
    public function getPreHtml()
    {
		$html = null;
		$html .= @$this->container ? "<{$this->container}>\n" : null;
		$fieldsetTag = @$this->tag ? : "fieldset";

	//	$html .= ! @$this->noFieldset && ! $this->getParameter( 'no_fieldset' ) ? "<{$fieldsetTag} class='pc-form-fieldset-{$this->counter}'>\n" : null;
		$html .= $this->getLegend() ? "\n<legend>{$this->getLegend()}</legend>\n" : null;
		return $html;
    } 	

	
    /**
     * Sets and Updates the _html property
     *
     * @return string The Mark-up For Displaying Elements in a Browers 
     */
    public function getPostHtml()
    {
		$html = null;
		$html .= $this->allowDuplication ? "<div><a class='pc-btn pc-btn-small' href='javascript:' title='" . ( @$this->duplicationData['add'] ? : "Duplicate this fieldset" ) . "' onClick='try{ ayoola.xmlHttp.callAfterStateChangeCallbacks(); }catch( e ){}var fieldset = this.parentNode.parentNode.cloneNode( true ); var fieldtags= [ \"input\", \"textarea\", \"select\"]; for ( var tagi= fieldtags.length; tagi-->0; ) { var fields = fieldset.getElementsByTagName( fieldtags[tagi] ); for( var i = fields.length; i-->0; ){ fields[i].value= \"\"; } } this.parentNode.parentNode.parentNode.insertBefore( fieldset, this.parentNode.parentNode.nextSibling ); ayoola.xmlHttp.callAfterStateChangeCallbacks(); this.name=\"\"; ayoola.div.refreshVisibleCounter( \"" . @$this->duplicationData['counter'] . "\", ayoola.div.getParentWithTagName( this, \"form\" ) );'>" . ( @$this->duplicationData['add'] ? : " + " ) . "</a>\n" : null; 
		$html .= $this->allowDuplication ? "<a class='pc-btn pc-btn-small' href='javascript:' title='" . ( @$this->duplicationData['add'] ? : "Remove this fieldset" ) . "' onClick='confirm( \"Delete all the elements in these fieldset?\") ? this.parentNode.parentNode.parentNode.removeChild( this.parentNode.parentNode ) : null; ayoola.div.refreshVisibleCounter(\"" . @$this->duplicationData['counter'] . "\", ayoola.div.getParentWithTagName( this, \"form\" ) );'>" . ( @$this->duplicationData['remove'] ? : " - " ) . "</a></div>\n" : null; 
//		$html .= ! @$this->noFieldset && ! $this->getParameter( 'no_fieldset' ) ? "</{$fieldsetTag}>\n" : null;
		$html .= @$this->container ? "</{$this->container}>\n" : null;
		return $html;
    } 	

	
    /**
     * Sets and Updates the _html property
     *
     * @return string The Mark-up For Displaying Elements in a Browers
     */
    public function getHtml()
    {
		$this->_html = Ayoola_Object_Wrapper_Abstract::wrapContent( $this->_html, @$this->wrapper  );
		return $this->getPreHtml() . $this->_html . $this->getPostHtml();
    } 	

    /**
     * Because the generic elements does not cater for specific situation
     * E.g. if we want to embed the form in some ready made html e.g. table
     * @param string
     * @param string
     * @return string
     */
    public function addHtml( $element, $values )
    {
		$fields = array_map( 'trim', explode( ',', @$values['fields'] ) );
		foreach( $fields as $field )
		{
			if( ! $field ){ continue; }
		//	var_export( $field );
		
			$this->addElement( array( 'name' => $field ) + ( @$values['parameters'] ? : array() ) );
		}
	//	var_export( $this->_names );
		return @$values['html'];
		
    }

    public function addCaptcha(array $element )
    {
		//$html = file_get_contents('http://default/tools/captcha/');
	//	return $html;
    }
	
    public function addInputText(array $element, $values = array() )
    {
	//	var_export( $element );
		
	//	@$element['required'] = $element['required'] ? "required='{$element['required']}'" : null;
    	$html = null;
    //	$html = $this->useDivTagForElement ? "<div id='{$element['id']}_container'>\n" : null;
		if( $this->placeholderInPlaceOfLabel || ! trim( $element['label'] ) )
		{
		
		}
		else
		{
	//		$html .= "<label for='{$element['name']}'>{$element['label']}</label>\n";
		}
		if( is_array( $element['value'] ) )
		{
			$element['value'] = null;
/* 			$a = md5( $element['name'] );
			if( ! is_numeric( @static::$_fieldCount[$a] ) || ( static::$_fieldCount[$a] ) <= 0 )
			{
			//	static::$_fieldCount = array();
				static::$_fieldCount[$a] = count( $element['value'] );
			}
			var_export( static::$_fieldCount[$a] );
			$element['value'] = $element['value'][static::$_fieldCount[$a]];
			static::$_fieldCount[$a]--;
 */		}
		elseif( is_scalar( $element['value'] ) )
		{
			
		}
		$html .= self::$_placeholders['badnews'];
	//	var_export( $element );
		//	
//$element['value'] = htmlspecialchars( $element['value'] );
		unset( $element['label'] );
		if( empty( $element['type'] ) )
		{
			$element['type'] = 'text';
		}
		$html .= "<input " . self::getAttributesHtml( $element ) . " />\n";
	//	$html .= $this->useDivTagForElement ? "</div>\n" : null;

		 return $html;
    }
	
    public function addDocument(array $element, $values = array() )
    {
	//	var_export( $element );
		$uniqueIDForElement = $element['name'] . '_' . self::$_elementCounter;
	//	@$element['required'] = $element['required'] ? "required='{$element['required']}'" : null;
    	$html = null;
    //	$html = $this->useDivTagForElement ? "<div id='{$element['id']}_container'>\n" : null;
    	$html .= "<div>";
		if( $this->placeholderInPlaceOfLabel || ! $element['label'] )
		{
			@$element['placeholder'] = $element['placeholder'] ? : $element['label'];
		}
		else
		{
		//	$html .= "<label for='{$element['name']}'>{$element['label']}</label>\n";
		}
		$link = '' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Doc_Browser/?field_name=' . $element['name'];
		if( ! @$element['data-multiple'] )
		{
			$link .= '&unique_id=' . $uniqueIDForElement;
		}
		$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
		
		//	Need to be up so as to serve the JS
		$uploader = Ayoola_Doc_Upload_Link::viewInLine( array( 'image_preview' => ( @$element['value'] ? : Ayoola_Form::getGlobalValue( $element['name'] ) ), 'field_name' => $element['name'], 'width' => @$articleSettings['cover_photo_width'] ? : '900', 'height' => @$articleSettings['cover_photo_height'] ? : '300', 'crop' => true, 'field_name_value' => @$element['data-field_name_value'] ? : 'url', 'preview_text' => 'Cover Photo', 'file_types_to_accept' => 'image/*', 'call_to_action' => 'Change ' . @$element['label'] ) );
	//	var_export( $element['real_name'] );
	//	var_export( $this->_names[$element['name']]['real_name'] );
		switch( $this->_names[trim( $element['name'], '[]' )]['real_name'] )
		{
	//		case 'document_url_base64':
/*			case 'document_url':
			case self::hashElementName( 'document_url' ):
			case 'cover_photo':
			case self::hashElementName( 'cover_photo' ):
				
				
				//	Give me cover photo
			//	$html .= Ayoola_Doc_Upload_Link::viewInLine( array( 'image_preview' => ( @$element['value'] ? : Ayoola_Form::getGlobalValue( $element['name'] ) ), 'field_name' => $element['name'], 'width' => @$element['data-image_width'], 'height' => @$element['data-image_height'], 'crop' => true, 'field_name_value' => @$element['data-field_name_value'] ? : 'url', 'preview_text' => 'Cover Photo', 'call_to_action' => @$element['label'] ) );
				$html .= $uploader;    
			break;
*/			case 'display_picture':
			case 'display_picture_base64':
			case self::hashElementName( 'display_picture' ):
				$width = '300';
				$height = '300';
				$element['data-document_type'] = 'image';
		//		var_export( $width );
			case 'document_url':
			case self::hashElementName( 'document_url' ):
			case 'cover_photo':
			case 'site_logo':
			case self::hashElementName( 'cover_photo' ):
				$element['data-document_type'] = 'image';
				
			default:
				switch( $this->_names[trim( $element['name'], '[]' )]['real_name'] )
				{
					case 'document_url_base64':
						$width = @$articleSettings['cover_photo_width'];
						$height = @$articleSettings['cover_photo_height'];
						$element['data-document_type'] = 'image';
					break;
				}	
				@$width = $width ? : $element['data-pc-upload-image-width'];
				@$height = $height ? : $element['data-pc-upload-image-height'];
				$docSettings = Ayoola_Doc_Settings::getSettings( 'Documents' );
			//	var_export( $docSettings );
				$defaultPix = '' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_IconViewer/?url=' . ( ( $element['data-document_type'] == 'image' ) ? '/img/placeholder-image.jpg' : '/open-iconic/png/document-8x.png' ) . '&crop=1&max_width=64&max_height=64';
				$valuesForPreview = (array) $element['value'];
				$html .= '<div></div>';  
			//	var_export( $valuesForPreview );
			//	var_export( $element['value'] );
				do
				{
					@$each = array_shift( $valuesForPreview );
					$valuePreview = '' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Application_IconViewer/?url=' . @$each . '&crop=1&max_width=64&max_height=64';

			//		var_export( $element['value'] );
					
/*					$html .= '
					<span class="" style="max-height:50px;display:inline-block;vertical-align:middle;" onclick="var a = this.parentNode.getElementsByTagName( \'input\' ); for( var b = 0; b < a.length; b++ ){var c = a[b].type; if( c == \'text\' ){ a[b].type = \'hidden\' }else{ a[b].type = \'text\'; }  a[b].style.display=\'\';  a[b].focus();  a[b].click(); }" title="The preview thumbnail image for the uploaded file will show here...">    
						<img onerror="this.src=\'' . $defaultPix . '\';this.onerror=\'/\';" alt="" style="max-height:50px" name="' . $uniqueIDForElement . '_preview_zone_image' . '" src="' . ( @$element['data-previous-url'] ? : ( ( @$each && is_scalar( $each ) ? $valuePreview : Ayoola_Form::getGlobalValue( $element['name'] ) ) ? : '' . $defaultPix . '' ) ) . '"  class="" onClick=""  > 
					</span>
					';
*/				}
				while( $valuesForPreview );
				if( @$element['data-allow_base64'] )
				{
					$uploadJsText = 'ayoola.image.upLoadOnSelect = false; ayoola.image.fieldNameValue = \'base\';';
				}
			//	if( ! Ayoola_Abstract_Table::hasPriviledge( @$docSettings['allowed_uploaders'] )  )
			//	var_export( @$uniqueIDForElement );  
 				$html .= '<div style="">';
				if( @$element['data-allow_base64']  )
				{ 
/*				//	if( @$element['data-allow_base64'] )
					{
						$html .= '
						<span title="Click to upload new photo" style="cursor: pointer;max-height:100px;vertical-align:middle;display:inline-block;" class="" onClick="ayoola.image.formElement = this; ayoola.image.maxWidth = ' . ( @$width ? : 0 ) . '; ayoola.image.maxHeight = ' . ( @$height ? : 0 ) . '; ayoola.image.imageId = \'' . ( @$uniqueIDForElement ) . '\'; ayoola.image.fieldNameValue = \'url\'; ayoola.image.formElement = this.parentNode.getElementsByTagName( \'input\' ).item(0); ' . @$uploadJsText . ' ayoola.image.clickBrowseButton( { accept: \'' . @$element['data-document_type'] . '/*\', multiple: \'' . @$element['data-multiple'] . '\' } );">  
							<img  style="max-height:32px;" alt="Upload" src="' . Ayoola_Application::getUrlPrefix() . '/open-iconic/png/arrow-circle-top-8x.png" >
						</span>
						'; 
					}
				//	else
					{  
					//	return ;
					}
*/				}
				elseif( Ayoola_Abstract_Table::hasPriviledge( @$docSettings['allowed_uploaders'] ? : 98 )  )
				{
					$html .= '
					<a  title="Upload new file" style="padding: 2px;font-size: small;cursor: pointer;vertical-align:middle;display:inline-block;" class="pc-btn" onClick="ayoola.image.formElement = this;  ayoola.image.maxWidth = ' . ( @$width ? : 0 ) . '; ayoola.image.maxHeight = ' . ( @$height ? : 0 ) . '; ayoola.image.imageId = \'' . ( @$uniqueIDForElement ) . '\';  ayoola.image.fieldNameValue = \'url\';  ayoola.image.formElement = this.parentNode.parentNode.getElementsByTagName( \'input\' ).item(0);  ' . @$uploadJsText . ' ayoola.image.clickBrowseButton( { accept: \'' . @$element['data-document_type'] . '/*\', multiple: \'' . @$element['data-multiple'] . '\' } );">  
						<i class="fa fa-arrow-up pc_give_space"></i>	Upload <i class="fa fa-chevron-right pc_give_space"></i>
					</a>
					'; 
				}
				@$docSettings['allowed_viewers'] = @$docSettings['allowed_viewers'] ? : array();
				@$docSettings['allowed_viewers'][] = 98;	// allow us to user domain owners
				
 				if( Ayoola_Abstract_Table::hasPriviledge( @$docSettings['allowed_viewers'] ? : 98 ) && ! @$element['data-allow_base64'] )
				{ 
					$html .= '
					<a title="Browse Existing files on site" style="padding: 2px;font-size: small;cursor: pointer;vertical-align:middle;display:inline-block;" class="pc-btn" onClick="ayoola.spotLight.showLinkInIFrame( \'' . $link . '\' ); return true;"> 
					<i class="fa fa-eye pc_give_space"></i>	Browse <i class="fa fa-chevron-right pc_give_space"></i>
					</a>
					
					'; 
				}
 				$html .= '</div>';
 				$html .= '<div style="clear:both;"></div>';
			break;
		}
		$html .= self::$_placeholders['badnews'];
	//	self::v( $element );
		//	
//$element['value'] = htmlspecialchars( $element['value'] );
		unset( $element['label'] ); 
		unset( $element['id'] );
		$element['id'] = $uniqueIDForElement;
	//	$element['value'] = $element['id'];
//		var_export( $element['value'] );  
	//	@$element['value'] = (array) $element['value'];
		if( ! is_array( @$element['value'] ) )
		{
			$element['value'] = array( @$element['value'] );
		}  
		foreach( $element['value'] as $each )
		{
			if( trim( $each ) )
			{
				$valuesJs = array( 
									'dedicated_url' => Ayoola_Doc::uriToDedicatedUrl( $each ),
									'url' => $each,
									);
				$valuesJs = json_encode( $valuesJs );
				Application_Javascript::addCode  
				( 
					"
					ayoola.events.add
					(
						window, 'load', function()
						{ 
							ayoola.image.setExistingDocument( '" . $element['name'] .  "', " . $valuesJs .  " ); 
						} 
					);" 
				);			
			}
			$html .= "<input type=\"hidden\" value=\"" . $each . "\" " . self::getAttributesHtml( $element ) . " />\n";
		}
	//	$html .= $this->useDivTagForElement ? "</div>\n" : null;
    	$html .= "</div>";

		 return $html;
    }

    public function addInputSearch(array $element )
    {
		@$element['required'] = $element['required'] ? "required='{$element['required']}'" : null;
        $html = "";
		$html .= "<input type='search' {$element['required']} placeholder='{$element['placeholder']}' id='{$element['id']}' name='{$element['name']}' value='{$element['value']}' />\n";

		 return $html;
    }
    public function addHoneyPot(array $element )
    {
        @$html = "<div class='hidden'>\n";
		@$html .= "<input type='text' id='{$element['id']}' name='{$element['name']}' value='{$element['value']}' /></div>";

		 return $html;
    }
    public function addHidden(array $element )
    {
	//	self::v( $element );
    	$html = null;
		$html .= self::$_placeholders['badnews'];
		@$html .= "<input type='hidden' " . self::getAttributesHtml( $element ) . " />\n";
		 return $html;
    }
    public function addInputPassword(array $element )
    {
    	$html = null;
    //	$html = $this->useDivTagForElement ? "<div id='{$element['id']}_container'>\n" : null;
		if( $this->placeholderInPlaceOfLabel || ! $element['label'] )
		{
			$element['placeholder'] = $element['placeholder'] ? : $element['label'];
		}
		else
		{
		//	$html .= "<label for='{$element['name']}'>{$element['label']}</label>\n";
		}
		$html .= self::$_placeholders['badnews'];
		@$html .= "<input type='password' placeholder='{$element['placeholder']}' id='{$element['id']}' name='{$element['name']}' />\n";
	//	$html .= $this->useDivTagForElement ? "</div>\n" : null;
		 return $html;
    }
   
	public function addTextArea( array $element, $values = array() )
    {
		@$element['name'] = $values ? ( $element['name'] . '[]' ) : $element['name'];
	//	@$element['required'] = $element['required'] ? "required='{$element['required']}'" : null;
        $html = "\n";
		if( $this->placeholderInPlaceOfLabel || ! $element['label'] )
		{
			@$element['placeholder'] = $element['placeholder'] ? : $element['label'];
		}
		else
		{
		//	$html .= "<label for='{$element['name']}'>{$element['label']}</label>\n";
		}
		$html .= self::$_placeholders['badnews'];
		$value = is_scalar( $element['value'] ) ? $element['value'] : null;
		unset( $element['value'] );
		@$html .= "<textarea ondblclick='ayoola.div.autoExpand( this );'  " . self::getAttributesHtml( $element ) . " >{$value}</textarea>\n";
		 return $html;
    }
   
	public function addFile( array $element )
    {
    	$html = $this->useDivTagForElement ? "<div id='{$element['id']}_container'>\n" : null;
   //     $html .= "<label for='{$element['name']}'>{$element['label']}</label>\n";
		$html .= self::$_placeholders['badnews'];
		$html .= "<input id='{$element['id']}' name='{$element['name']}' type='file' >\n";
		$html .= $this->useDivTagForElement ? "</div>\n" : null;
		 return $html;
    }
	
    public function addSubmit(array $element)
    {
	//	var_export( $element );
    	$html = null;
  //  	$html = $this->useDivTagForElement ? "<span id='{$element['id']}_container'>\n" : null;
		$html .= "<input " . self::getAttributesHtml( $element ) . " type='submit' >\n";
//		$html .= $this->useDivTagForElement ? "</span>\n" : null;
		return $html;
    }
	
    public function addSubmitButton(array $element)
    {
	//	var_export( $element );
    	$html = null;
  //  	$html = $this->useDivTagForElement ? "<span id='{$element['id']}_container'>\n" : null;
		$value = html_entity_decode( $element['value'] );
		unset( $element['value'] );
//		var_export( htmlentities( $value ) );
		$html .= ( "<button " . self::getAttributesHtml( $element ) . " type='submit'>" . $value . "</button>" );
//		$html .= $this->useDivTagForElement ? "</span>\n" : null;
// var_export( htmlentities( $html ) );

		return $html;
    }
	
    public function addReset(array $element)
    {
        $html = "<input value='{$element['label']}' type='reset' />\n";
		return $html;
    }
	
    public function addButton(array $element)
    {
        $html = "<input " . self::getAttributesHtml( $element ) . " type='button' />\n"; 
		return $html;
    }
	
    public function addCheckbox( array $element, $values = array() )
    {
		//	Setting the [] from the class level causes some trouble.
		$element['name'] = trim( $element['name'], '[]' ) . '[]';
       $html = null;
 //      @$html = "<label style='{$element['label_style']}' for='{$element['name']}'>{$element['label']}\n";
 //      $html .= "</label>\n";
		$html .= self::$_placeholders['badnews'];
		$counter = 0;
		//		var_export( $element['value'] );
       	$html .= '<div style="display:inline-block;">';
		foreach( $values as $value => $label )
		{ 
		//	var_export( $label );
			$label = $label !== '' ? $label : $value;
		//	var_export( $value );
			$counter++;
			$checked = null; 
			//	@var_export( $value );
			if( isset( $element['value'] ) && is_array( $element['value'] ) )
			{ 
				if( in_array( $value, $element['value'] ) )
				{
				//	var_export( $value ); 
				//	var_export( $label ); 
				//	var_export( $element['value'] ); 
					$checked = "checked='true'";
				}
			} 
			@$element['class'] .= ' pc_give_space';
			@$html .= "<label style='{$element['label_style']}' for='{$element['id']}{$counter}' class='clearTransformation'> 
							<input type='checkbox' id='{$element['id']}$counter' value='{$value}' {$checked} " . self::getAttributesHtml( $element ) . " > {$label} 
						</label>\n";  
		}
       	$html .= '</div>';
		unset( $values );
		 
		return $html;
    }
	
    public function addMultipleInputText( array $element, $values = array() )
    {
		//		var_export( $values );
		//	Setting the [] from the class level causes some trouble.
    	$html = null;
    //	$html = $this->useDivTagForElement ? "<div id='{$element['id']}_container'>\n" : null;
		$html .= "<span>\n";
	//	var_export( $values );
	//	var_export( $element['value'] );
		$values = array_unique( ( $values ? : array() ) + ( $element['value'] ? : array() ) ); 
	//	var_export( $values );
		if( $this->placeholderInPlaceOfLabel || ! trim( $element['label'] ) )
		{
		//	@$element['placeholder'] = $element['placeholder'] ? : $element['label'];
		}
		elseif( ! $values )
		{
			$values = array( '' => '' );
	//		$html .= "<label for='{$element['name']}'>{$element['label']}</label>\n";
		}
		else
		{
		//	$html .= "<label for='{$element['name']}'>{$element['label']}</label>\n";
		}
		$element['name'] = trim( $element['name'], '[]' ) . '[]';
		$html .= self::$_placeholders['badnews'];
		$counter = 0;
		foreach( @$values as $value => $label )
		{ 
			$tempName = $element['name'];
			if( $label === '' )
			{
				//	Avoid empty answers
				$tempName = 'temporary_name_to_disable_option';
			}
			$counter++;
			@$html .= "
						<div style='xdisplay:none;' name='{$element['name']}container'>
							<input onchange='this.name = this.name != \"{$element['name']}\" ? \"{$element['name']}\" : \"{$element['name']}\"; ' type='text' style='{$element['style']}' id='{$element['id']}$counter' value='{$label}' name='{$tempName}' " . self::getAttributesHtml( $element ) . " />
							<span class='pc-btn' onClick='this.parentNode.parentNode.insertBefore( this.parentNode.cloneNode( true ), this.parentNode );' title='Add new {$element['label']}'>+</span>
							<span class='pc-btn' onClick='confirm( \"Delete this option?\" ) ? this.parentNode.parentNode.removeChild( this.parentNode ) : null;' title='Delete this option for {$element['label']}'>-</span>
						</div>\n";
		}
		unset( $values );
		$html .= "</span>\n";
		 
		return $html;
    }
	
    public function addMultipleTextArea( array $element, $values = array() )
    {
		//	Setting the [] from the class level causes some trouble.
		$element['name'] = trim( $element['name'], '[]' ) . '[]';
    	$html = null;
    //	$html = $this->useDivTagForElement ? "<div id='{$element['id']}_container'>\n" : null;
		if( $this->placeholderInPlaceOfLabel || ! $element['label'] )
		{
			@$element['placeholder'] = $element['placeholder'] ? : $element['label'];
		}
		else
		{
	//		$html .= "<label for='{$element['name']}'>{$element['label']}</label>\n";
		}
		$html .= self::$_placeholders['badnews'];
		$counter = 0;
		//		var_export( $element['value'] );
		foreach( $values as $value => $label )
		{ 
			$counter++;
			$checked = null; 
			//	@var_export( $value );
			if( isset( $element['value'] ) && is_array( $element['value'] ) )
			{ 
				if( in_array( $value, $element['value'] ) )
				{
					$checked = "checked='true'";
				}
			}
/* 			@$html .= "<span style='display:inline-block;'>
							<label style='display:inline;font-weight:normal;' for='{$element['id']}{$counter}'>{$label} </label>
							<input type='text' style='{$element['style']}' id='{$element['id']}$counter' value='{$value}' name='{$element['name']}' {$checked} />
						</span>\n";
 */			@$html .= "<span style='display:inline-block;'>
							<label style='display:inline;font-weight:normal;' for='{$element['id']}{$counter}'>{$element['label']} {$counter} </label>
							<textarea style='{$element['style']}' id='{$element['id']}$counter' name='{$element['name']}'>{$label}</textarea>
						</span>\n";
		}
		unset( $values );
		 
		return $html;
    }
	
    public function addRadio(array $element,Array $values = array())
    {
	//	self::v( $element );
		//	debug making "/tools/classplayer/get/object_name/Ayoola_Page_Editor/?url=/" to display nonsense title
	//	unset( $element['title'] );
  //      $html = "<label for='{$element['name']}'>{$element['label']}\n";
  //     $html .= "</label>\n";
		$html .= self::$_placeholders['badnews'];
		$i = 0;
		unset( $element['label'] );
       	$html .= '<div style="display:inline-block;">';
		foreach( $values as $value => $label )
		{ 
			$label !== '' ? $label : $value;
			//	Doing this because of CHeckout logo
			$label = htmlspecialchars_decode( $label );
			$i++;
			$checked = null; 
			if( isset( $_POST[$element["name"]] ) && $value == $_POST[$element["name"]] ) 
			{ 
				$checked = 'checked="true"'; 
			}
			elseif( isset( $element["value"] ) &&  $value == $element["value"]  )
			{
				$checked = 'checked="true"';   
			}
			$element['class'] .= ' pc_give_space';
			@$html .= "<label class='clearTransformation' for='{$element['id']}{$i}'>
							<input type='radio' id='{$element['id']}{$i}' value='{$value}' {$checked}  " . self::getAttributesHtml( $element ) . " >{$label}
						</label>";
		}
       	$html .= '</div>';
		unset( $values );
		$html .= "\n";
		 
		return $html;
    }
	
    public function addSelectMultiple( array $element, $values = array() )     
    {
		//	Setting the [] from the class level causes some trouble.
		$element['name'] = trim( $element['name'], '[]' ) . '[]';
	//	var_export( $_POST[$element["name"]] );
        $html = "";
		if( $this->placeholderInPlaceOfLabel || ! trim( $element['label'] ) )
		{
	//		$element['placeholder'] = $element['placeholder'] ? : $element['label'];
		}
		else
		{
	//		$html .= "<label for='{$element['name']}'>{$element['label']}</label>\n";
		}
      ///  $html = "<div id='{$element['id']}_container'><label for='{$element['name']}'>{$element['label']}</label>\n";
		$html .= self::$_placeholders['badnews'];
        $html .= "<select id='{$element['id']}' name='{$element['name']}' multiple='multiple' > \n";
		if( isset( $_POST[$element["name"]] ) ) 
		{ 
			$element["value"] = (array) $_POST[$element["name"]]; 
		}
		foreach( $values as $subgroupLabel => $subgroup )
		{
			$hasSubgroup = is_array( $subgroup );
			if( $hasSubgroup )
			{
				$html.= "<optgroup label='{$subgroupLabel}'>";
			}
			else
			{
				$subgroup = array( $subgroupLabel => $subgroup );   
			}
			
			foreach( $subgroup as $value => $title )
			{
				$title = $title !== '' ? $title : $value;
				$html.= "<option value='{$value}'";
		//		var_export( $value );
		//		var_export( $element["value"] );
				if( is_array( $element["value"] ) && in_array( $value, $element["value"] ) ) 
				{ 
					$html.= 'selected="selected"'; 
				}
				elseif( is_scalar( $element["value"] ) && $value === $element["value"] )
				{ 
					$html.= 'selected="selected"'; 
				}
				$html.= "> \n";
				$html.= $title;
				$html.= "</option> \n";
			}
			if( $hasSubgroup )
			{
				$html.= "</optgroup>";
			}
			
		}
		$html.= "</select> \n";
		 
		return $html;
    }
	
    public function addSelect(array $element, $values = array() )
    {
    	$html = $this->useDivTagForElement ? "<div id='{$element['id']}_container'>\n" : null . ' ';
		if( $this->placeholderInPlaceOfLabel || ! trim( $element['label'] ) )
		{
	//		$element['placeholder'] = $element['placeholder'] ? : $element['label'];
		}
		else
		{
	//		$html .= "<label for='{$element['name']}'>{$element['label']}</label>\n";
		}
		$html .= self::$_placeholders['badnews'];
        $html .= "<select " . self::getAttributesHtml( $element ) . "> \n";
	//	var_export( $values );
		foreach( $values as $value => $title )
		{
			$title = $title !== '' ? $title : $value;
			$html.= "<option value='{$value}'";
			if( isset( $_POST[$element["name"]] ) && !( strcmp( $value, $_POST[$element["name"]] ) ) )
			{ 
				$html.= ' selected="selected "'; 
			}
			elseif( isset( $element["value"] ) && is_scalar( $element["value"] ) && !( strcmp( $value, $element["value"] ) ) )
				$html.= ' selected="selected" '; 
/* 			if( is_array( $element["value"] ) ) 
			{
		//		var_export( $element["name"] );
		//		var_export( $element["value"] );
			}
 */			$html.= "> \n";
			$html.= $title;
			$html.= "</option> \n";
			//		 var_export( $title );
			//		 var_export( $value );

		}
		$html.= "</select> \n";
		$html .= $this->useDivTagForElement ? "</div>\n" : null;
		return $html;
    }
	
    public function addLegend( $legend )
    {
		$this->_legend = '' . self::__( $legend ) . '';
    }
    public function getLegend(  )
    {
		return $this->_legend;
    }
	
    /**
     * This method provides the mark-up to display a the form element
     *
     * @param void
     * @return string
     */
    public function view()
    {
		return $this->getHtml();
    } 	
}
