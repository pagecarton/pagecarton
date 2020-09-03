<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Form_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: filename.php date time username $ 
 */

/**
 * @see Ayoola_Form_Abstract
 */
 
require_once 'Ayoola/Page/Abstract.php';

/**
 * @category   PageCarton
 * @package    Ayoola_Form_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Form_View extends Ayoola_Form_Abstract
{
	
    /**	
     *
     * @var boolean
     */
	public static $editorViewDefaultToPreviewMode = true;
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'View Form'; 
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'form_name' );
		
    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
		try
		{

			if( ! $data = $this->getIdentifierData() ){  }
			if( ! $data  
				|| ! self::hasPriviledge( $data['auth_level'] )
			)
			{

				if( self::hasPriviledge( array( 99, 98 ) ) )
				{
					$formName = @$_REQUEST['form_name'] ? : $this->getParameter( 'form_name' );
					if( ! $this->getParameter( 'new_form' ) )
					{
						$this->setViewContent( self::__( '<p class=" badnews">Form not set up yet.</p>' ) );
					}
					else
					{
						$this->setViewContent( self::__( '<p class=" goodnews">New custom form ready.</p>' ) );
					}
					if( $formName  )
					{
						$editLink = '<a href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_Creator/?form_name=' . $formName . '\' )" class="pc-btn">Set up new form</a>';			$this->setViewContent( $editLink );
					}
				}
				else
				{
					$this->setViewContent(  '' . self::__( '<p class=" badnews">The requested form was not found on the server. Please check the URL and try again. </p>' ) . '', true  );

				}
				return false;

            }

			$previousData = null;
			if( $this->getParameter( 'form_data' ) )
			{
				$previousData = $this->getParameter( 'form_data' );
			}
			elseif( ! empty( $_REQUEST['data_id'] ) )
			{
                $dataResponse = Ayoola_Form_Table_Data::getInstance()->selectOne( null, array( 'data_id' => $_REQUEST['data_id'], 'form_name' => $data['form_name'] ) );

                if( self::hasPriviledge( 98 ) || ( $dataResponse['user_id'] && $dataResponse['user_id'] == (string) Ayoola_Application::getUserInfo( 'user_id' ) ) || md5( json_encode( $dataResponse['form_data'] ) ) === $_REQUEST['data_key'] )
                {
                    $previousData = $dataResponse['form_data'];
                }
			}
			$this->createForm( 'Continue...', null, $previousData );          
			$this->setViewContent( '', true  );
			
			//	We show form information by default
			if( ! $this->getParameter( 'hide_form_information' ) && ! $_POST )
			{
				$this->setViewContent(  '' . self::__( '<h1 class="pc-heading">' . $data['form_title'] . '</h1>' ) . '', true  );
				$this->setViewContent( self::__( '<p style="margin-top:2em;margin-bottom:2em;">' . ( strip_tags( $data['form_description'] ) === $data['form_description'] ? nl2br( $data['form_description'] ) : $data['form_description'] ) . '</p>' )  );
			}

			$this->setViewContent( $this->getForm()->view() );
			
			$pageInfo = array(
				'description' => Ayoola_Page::getCurrentPageInfo( 'description' ) . $data['form_description'] ,
				'title' => trim( $data['form_title'] . ' ' . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' )
			);

			Ayoola_Page::setCurrentPageInfo( $pageInfo );

			if( ! $values = $this->getForm()->getValues() ){ return false; }

			$values['username'] = $previousData['username'] ? : Ayoola_Application::getUserInfo( 'username' );
			$values['email'] = @$values['email'] ? : ( $previousData['email'] ? : Ayoola_Application::getUserInfo( 'email' ) );

		
			//	Clear plain text password for security reasons
			unset( $values['password'], $values['password2'], $values['document_url_base64'], $values['download_url_base64'] );
		
			//	Save to table
	//		if( $data['form_options'] && in_array( 'database', $data['form_options'] ) )
			{
				$table = Ayoola_Form_Table_Data::getInstance();
				$infoToInsert = array( 'form_name' => $data['form_name'], 'user_id' => Ayoola_Application::getUserInfo( 'user_id' ), 'form_data' => $values  );

				if( $previousData && ! in_array( 'disable_updates', $data['form_options'] ) )
				{
                    $newDataToInsert = $values + $previousData;
                    $table->update( array( 'form_data' => $newDataToInsert ), array( 'data_id' => $_REQUEST['data_id'] ) );
                    $dataId = $_REQUEST['data_id'];
				}
				else
				{
					$dataId = $table->insert( $infoToInsert );
					$dataId = $dataId['data_id'];
				}
			}     

			//	Add all fieldsets
			if( ! empty( $data['callbacks'] ) && is_array( $data['callbacks'] ) )
			{
				foreach( @$data['callbacks'] as $class ) 
				{
					if( ! Ayoola_Loader::loadClass( $class ) )
					{
						continue;
					}

					$class = new $class();
					$parameters = array( 'fake_values' => $values );
					$class->setParameter( $parameters );
					$class->fakeValues = $values;
					$class->init();

				}
			}
			
			//	Notify Admin
			$link = '' . Ayoola_Page::getHomePageUrl() . '/widgets/Ayoola_Form_Inspect/?form_name=' . $data['form_name'] . '';
			$mailInfo = array();
			$mailInfo['subject'] = 'Form filled [' . $data['form_title'] . ']';
			$mailInfo['body'] = 'Form titled "' . $data['form_title'] . '" has been filled on your website with the following information: ' . self::arrayToString( $values ) . '
			
			Check all form responses in the database: ' . $link . '
			';
			try
			{
				@self::sendMail( $mailInfo + array( 'to' => $data['email'] ) ); 
			}
            catch( Ayoola_Exception $e ){ null; }
            $updateLink = null;
            if( ! @in_array( 'disable_updates', $data['form_options'] )	)
            {
                $updateLink = '<a class="pc-btn" href="' . Ayoola_Page::getHomePageUrl() . '/widgets/' . __CLASS__ . '?form_name=' . $data['form_name'] . '&data_id=' . $dataId . '&data_key=' . md5( json_encode( $values ) ) . '">' . self::__( 'Update Form Entry' ) . '</a>';
            }		

            $data['form_success_message'] = $data['form_success_message'] ? : sprintf( self::__( 'Thank you! Your entry to form %s has been received.' ), $data['form_title'] );
			$data['form_success_message'] = ( strip_tags( $data['form_success_message'] ) === $data['form_success_message'] ? ( '<p>' . nl2br( $data['form_success_message'] ) . '</p>' ) : $data['form_success_message'] );

			$mailInfo = array();
			$mailInfo['subject'] = $data['form_title'];
			$mailInfo['body'] =  $data['form_success_message'] . '<p>' . $updateLink . '</p>';
			$mailInfo['to'] = Ayoola_Form::getGlobalValue( 'email' ) ? : ( Ayoola_Form::getGlobalValue( 'email_address' ) ? : Ayoola_Application::getUserInfo( 'email' ) );
			try
			{
				@self::sendMail( $mailInfo ); 
			}
			catch( Ayoola_Exception $e ){ null; }

			$this->setViewContent( '<div class="pc_give_space_top_bottom" >' . $data['form_success_message'] . '</div>', true );
            $this->setViewContent( '<p> <a class="pc-btn" href="#" onClick="history.go(-1)">' . self::__( 'Go back' ) . '</a> ' . $updateLink . '</p>' );
		}
		catch( Exception $e )
		{ 
			$this->_parameter['markup_template'] = null;
			$this->setViewContent(  '' . self::__( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>' ) . '', true  );

		}
    } 
	
    /**
	 * Returns text for the "interior" of the Layout Editor
	 * The default is to display view and option parameters.
	 * 		
     * @param array Object Info
     * @return string HTML
     */
    public static function getHTMLForLayoutEditor( & $object )
	{
		$html = null;
		@$object['view'] = $object['view'] ? : $object['view_parameters'];
		@$object['option'] = $object['option'] ? : $object['view_option'];

		
		//	Implementing Object Options
		//	So that each objects can be used for so many purposes.
		//	E.g. One Class will be used for any object

		$options = __CLASS__;

		if( ! $options || ! Ayoola_Loader::loadClass( $options ) )
		{
			return false;
		}
		$options = new $options( array( 'no_init' => true ) );

		$options = $options->getDbData();;
		$filter = new Ayoola_Filter_SelectListArray( 'form_name', 'form_title');
		if( $options = $filter->filter( $options ) )
		{

		}
		else
		{

		}
		$newFormName = 'form_' . time();
		if( empty( $object['form_name'] ) )  
		{
			$object['form_name'] = $newFormName; 
			$object['new_form'] = $newFormName; 
		}

		$html .= '<span style=""> Form to Show:   </span>';
		$html .= '<select data-parameter_name="form_name">';
		$html .= '<option value="' . $newFormName . '">New Form</option>';
		foreach( $options as $key => $value )
		{ 
			$html .=  '<option value="' . $key . '"';  

			if( @$object['form_name'] == $key ){ $html .= ' selected = selected '; }
			$html .=  '>' . $value . '</option>';  
		}
		$html .= '</select>';
		$html .= '<span style="">  </span>';
		return $html;
	}
	
    /**
     * This method
     *
     * @param 
     * @return 
     */
    public static function getStatusBarLinks( $object )
    {
		return '<a title="Manage Forms" class="title_button" href="javascript:" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Form_List/\' );">Manage Forms</a>';
	}
	
    /**
     * creates the form for creating and editing form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )  
    {

		//	Form to create a new form
		$formInfo = $this->getIdentifierData();
		$values = ( $values ? : array() ) + ( $this->getParameter( 'default_values' ) ? : array() );

		if( empty( $formInfo['form_name'] ) )
		{
			return false;
		}

        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'id' => $this->getObjectName() . @$formInfo['form_name'] ) );
		$fieldset = new Ayoola_Form_Element;

    
        if( ! empty( $formInfo['form_options'] ) && in_array( 'disable_updates', $formInfo['form_options'] ) && ! self::hasPriviledge( 98 ) && ! empty( $values ) )
        {

        }
        else
        {

            $form->submitValue = @$formInfo['button_value'] ? : $submitValue ;
        }

		$form->badnewsBeforeElements = true;
		
		$form->setFormRequirements( $formInfo['requirements'] );

        
        $i = 0;
		do
		{
			//	Put the questions in a separate fieldset
			$key = md5( $formInfo['element_group_name'][$i] );
			if( empty( $fieldsets[$key] ) )
			{
                $fieldsets[$key] = new Ayoola_Form_Element; 

			}
            $filters = array();
		
			//	Question
			
			$requirement = null;
			$options = array();
			$type = $formInfo['element_type'][$i]; 
			@$formInfo['element_name'][$i] = $formInfo['element_name'][$i] ? : $formInfo['element_title'][$i];

			if( ! empty( $formInfo['element_access_level'][$i] ) && ! Ayoola_Abstract_Table::hasPriviledge( $formInfo['element_access_level'][$i] ) )
			{ 
				$i++;
				continue;
			}

			$elementName = $formInfo['element_name'][$i];
			if( strpos( $elementName, 'base64' ) )
			{
				$options['data-allow_base64'] = true;
			}
			$defaultValue = @$values[$elementName] ? : @$formInfo['element_default_value'][$i];

			switch( $formInfo['element_type'][$i] )
			{
				case 'html': 
					Application_Article_Abstract::initHTMLEditor();
				case 'textarea': 
					$type = 'TextArea'; 
				break;
				case 'submit': 
					$type = 'submit'; 
				break;
				case 'email': 
					$type = 'email'; 
				break;
				case 'hidden': 
					$type = 'hidden'; 
				break;
				case 'date': 
				case 'datetime': 
					//	event
					//	retrieve
					
					if( @$defaultValue )
					{
						switch( strtolower( $defaultValue ) )
						{
							case 'now':
								$defaultValue = date("Y-m-d H:i:s");
							break;
						}
						$defaultValueDigits = str_replace( array( '-', ' ', ':' ), '', $defaultValue );
						$values[$elementName . '_year'] = $defaultValueDigits[0] . $defaultValueDigits[1] . $defaultValueDigits[2] . $defaultValueDigits[3];
						$values[$elementName . '_month'] = $defaultValueDigits[4] . $defaultValueDigits[5];
						$values[$elementName . '_day'] = $defaultValueDigits[6] . $defaultValueDigits[7];
						$values[$elementName . '_hours'] = $defaultValueDigits[8] . $defaultValueDigits[9];
						$values[$elementName . '_minutes'] = $defaultValueDigits[10] . $defaultValueDigits[11];
					}

					
					//	Month
					$optionsX = array_combine( range( 1, 12 ), array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ) );
					$monthValue = intval( @strlen( $values[$elementName . '_month'] ) === 1 ? ( '0' . @$values[$elementName . '_month'] ) : @$values[$elementName . '_month'] );
					$monthValue = intval( $monthValue ?  : $this->getGlobalValue( $elementName . '_month' ) );

					$fieldsets[$key]->addElement( array( 'name' => $elementName . '_month', 'label' => $formInfo['element_title'][$i], 'style' => 'min-width:0px;width:100px;display:inline-block;;margin-right:0;', 'type' => 'Select', 'value' => $monthValue ), array( 'Month' ) + $optionsX ); 
					$fieldsets[$key]->addRequirement( $elementName . '_month', array( 'InArray' => array_keys( $optionsX ) ) );
					if( strlen( $this->getGlobalValue( $elementName . '_month' ) ) === 1 )
					{
						$fieldsets[$key]->addFilter( $elementName . '_month', array( 'DefiniteValue' => '0' . $this->getGlobalValue( $elementName . '_month' ) ) );
					}
					
					//	Day
					$optionsX = range( 1, 31 );
					$optionsX = array_combine( $optionsX, $optionsX );
					$DayValue = intval( @strlen( $values[$elementName . '_day'] ) === 1 ? ( '0' . @$values[$elementName . '_day'] ) : @$values[$elementName . '_day'] );
					$DayValue = intval( $DayValue ?  : $this->getGlobalValue( $elementName . '_day' ) );
					$fieldsets[$key]->addElement( array( 'name' => $elementName . '_day', 'label' => '', 'style' => 'min-width:0px;width:100px;display:inline-block;;margin-right:0;', 'type' => 'Select', 'value' => $DayValue ), array( 'Day' ) + $optionsX );
					$fieldsets[$key]->addRequirement( $elementName . '_day', array( 'InArray' => array_keys( $optionsX ) ) );
					if( strlen( $this->getGlobalValue( $elementName . '_day' ) ) === 1 )
					{
						$fieldsets[$key]->addFilter( $elementName . '_day', array( 'DefiniteValue' => '0' . $this->getGlobalValue( $elementName . '_day' ) ) );
					}
					
					//	Year
					//	10 years and 10 years after todays date
					$optionsX = range( date( 'Y' ) + 100, date( 'Y' ) - 100 );
					$optionsX = array_combine( $optionsX, $optionsX );
					$fieldsets[$key]->addElement( array( 'name' => $elementName . '_year', 'label' => '', 'style' => 'min-width:0px;width:100px;display:inline-block;margin-right:0;', 'type' => 'Select', 'value' => @$values[$elementName . '_year'] ? : '' ), array( 'Year' ) + $optionsX );
					$fieldsets[$key]->addRequirement( $elementName . '_year', array( 'InArray' => array_keys( $optionsX ) ) );
					$date = $this->getGlobalValue( $elementName . '_year' );
					$date .= '-';
					$date .= strlen( $this->getGlobalValue( $elementName . '_month' ) ) === 1 ? ( '0' . $this->getGlobalValue( $elementName . '_month' ) ) : $this->getGlobalValue( $elementName . '_month' );
					$date .= '-';
					$date .= strlen( $this->getGlobalValue( $elementName . '_day' ) ) === 1 ? ( '0' . $this->getGlobalValue( $elementName . '_day' ) ) : $this->getGlobalValue( $elementName . '_day' );
					$fieldsets[$key]->addElement( array( 'name' => $elementName . '_date', 'label' => 'Timestamp', 'placeholder' => 'YYYY-MM-DD HH:MM', 'type' => 'Hidden', 'value' => @$values[$elementName . '_date'] ) );
					$fieldsets[$key]->addFilter( $elementName . '_date', array( 'DefiniteValue' => $date ) );
					$fieldsets[$key]->addFilter( $elementName, array( 'DefiniteValue' => $date ) );
					if( 'datetime' == $formInfo['element_type'][$i] )
					{
						$optionsX = range( 0, 23 );
						foreach( $optionsX as $eachKey => $each )
						{
							if( strlen( $optionsX[$eachKey] ) < 2 )  
							{
								$optionsX[$eachKey] = '0' . $optionsX[$eachKey];
							}
						}
						$fieldsets[$key]->addElement( array( 'name' => $elementName . '_hours', 'label' => ' ', 'style' => 'min-width:0px;width:100px;', 'type' => 'Select', 'value' => @$values[$elementName . '_hours'] ), array( 'Hour' ) +  array_combine( $optionsX, $optionsX ) );
						$fieldsets[$key]->addRequirement( $elementName . '_hours', array( 'InArray' => array_keys( $optionsX ) ) );
						$optionsX = range( 0, 59 );
						foreach( $optionsX as $eachKey => $each )
						{
							if( strlen( $optionsX[$eachKey] ) < 2 )    
							{
								$optionsX[$eachKey] = '0' . $optionsX[$eachKey];
							}
						}
						$fieldsets[$key]->addElement( array( 'name' => $elementName . '_minutes', 'label' => ' ', 'style' => 'min-width:0px;width:100px;', 'type' => 'Select', 'value' => @$values[$elementName . '_minutes'] ), array( 'Minute' ) + array_combine( $optionsX, $optionsX ) );
						$fieldsets[$key]->addRequirement( $elementName . '_minutes', array( 'InArray' => array_keys( $optionsX ) ) );

						//	datetime combined
						$datetime = $date;
						$datetime .= ' ';
						$datetime .= strlen( $this->getGlobalValue( $elementName . '_hours' ) ) === 1 ? ( '0' . $this->getGlobalValue( $elementName . '_hours' ) ) : $this->getGlobalValue( $elementName . '_hours' );
						$datetime .= ':';
						$datetime .= strlen( $this->getGlobalValue( $elementName . '_minutes' ) ) === 1 ? ( '0' . $this->getGlobalValue( $elementName . '_minutes' ) ) : $this->getGlobalValue( $elementName . '_minutes' );
						$fieldsets[$key]->addElement( array( 'name' => $elementName . '_datetime', 'label' => 'Timestamp', 'placeholder' => 'YYYY-MM-DD HH:MM', 'type' => 'Hidden', 'value' => @$values[$elementName . '_datetime'] ) );
						$fieldsets[$key]->addFilter( $elementName . '_datetime', array( 'DefiniteValue' => $datetime ) );

                        $filters[$elementName] = array( 'DefiniteValue' => $datetime );

					}
					
					$type = 'hidden'; 
				break;
				case 'image-multiple': 
					@$options['data-document_type'] = 'image';
				case 'document-multiple': 
					@$options['data-multiple'] = true;
					@$options['multiple'] = true;     
					$type = 'Document'; 
				break;
				case 'file': 
				case 'audio': 
				case 'video': 
				case 'image': 

				case 'document': 
					$type = 'Document'; 
					$docSettings = Ayoola_Doc_Settings::getSettings( 'Documents' );
					@$docSettings['allowed_uploaders'] = is_array( @$docSettings['allowed_uploaders'] ) ? $docSettings['allowed_uploaders'] : array();

					if( @$options['data-allow_base64'] )
					{

						
					}
					elseif( Ayoola_Abstract_Table::hasPriviledge( $docSettings['allowed_uploaders'] + array( 98 ) ) )
					{ 

					}
					else
					{
						$type = 'InputText'; 
						
					}
				break;
				default: 

				break;
			}

			$elementInfo = array( 'name' => $elementName, 'label' => $formInfo['element_title'][$i], 'data-document_type' => $formInfo['element_type'][$i], 'placeholder' => $formInfo['element_placeholder'][$i], 'type' => $type, 'value' => $defaultValue );
			if( $formInfo['element_importance'][$i] == 'required' )
			{
				$elementInfo['required'] = true;
			}
			$multiOptionsRecord = array();
			if( $formInfo['element_multioptions'][$i] )
			{
			if( $multiOptions = Ayoola_Form_MultiOptions::getInstance()->selectOne( null, array( 'multioptions_name' => $formInfo['element_multioptions'][$i] ) ) )
				{

					$tableDb = $multiOptions['db_table_class'];
					if( Ayoola_Loader::loadClass( $tableDb ) && $tableDb::getInstance() instanceof Ayoola_Dbase_Table_Interface  )
					{

						$scope = $tableDb::SCOPE_PRIVATE === $multiOptions['accessibility'] ? $tableDb::SCOPE_PRIVATE : $tableDb::SCOPE_PROTECTED;
						$tableDb = $tableDb::getInstance( $scope );
						$tableDb->getDatabase()->getAdapter()->setAccessibility( $scope );
						$tableDb->getDatabase()->getAdapter()->setRelationship( $scope );

						$where = null;
						if( ! empty( $multiOptions['db_where'] ) && ! empty( $multiOptions['db_where_value'][0] ) )
						{
							$where = array_combine( $multiOptions['db_where'], $multiOptions['db_where_value'] );

						}
						$multiOptionsRecord = $tableDb->select( null, $where );
						require_once 'Ayoola/Filter/SelectListArray.php';
						$filter = new Ayoola_Filter_SelectListArray( $multiOptions['values_field'], $multiOptions['label_field'] );
						$multiOptionsRecord = $filter->filter( $multiOptionsRecord );  
						asort( $multiOptionsRecord );

						if( self::hasPriviledge( 98 ) )
						{
							$elementInfo['onchange'] = 'ayoola.div.manageOptions( { database: "' . $multiOptions['db_table_class'] . '", values: "' . $multiOptions['values_field'] . '", labels: "' . $multiOptions['label_field'] . '", element: this } );';
							$multiOptionsRecord = $multiOptionsRecord + array( '__manage_options' => '[Manage Multi-Options]' );
						}
					}
				}
			}
            $fieldsets[$key]->addElement( $options + $elementInfo , $multiOptionsRecord );
            foreach( $filters as $eachFilter )
            {
                $fieldsets[$key]->addFilter( $elementName, $eachFilter );
            }
			
			if( $formInfo['element_validators'][$i] )
			{
				if( $validatorInfo = Ayoola_Form_Validator::getInstance()->selectOne( null, array( 'validator_name' => $formInfo['element_validators'][$i] ) ) )
				{
					foreach( $validatorInfo['validators'] as $iKey => $eachValidator )
					{

						if( Ayoola_Loader::loadClass( $eachValidator ) && new $eachValidator instanceof Ayoola_Validator_Interface  )
						{

							$req = array( $eachValidator => $validatorInfo['parameters'][$iKey] );

							$fieldsets[$key]->addRequirement( $elementName, $req );
						}
					}
				}

			}

			$requirement ? $fieldsets[$key]->addRequirement( $elementName, $requirement ) : null;
							
			$i++;

		}
		while( ! empty( $formInfo['element_title'][$i] ) );

		$form->requiredElements = $form->requiredElements + array( 'form_name' => $formInfo['form_name'] );
		
		//	Add all fieldsets
		foreach( $fieldsets as $each )
		{

			$form->addFieldset( $each );
		}
		$this->setForm( $form );
    } 
}
