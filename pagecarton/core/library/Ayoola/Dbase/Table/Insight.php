<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Dbase_Table_Insight
 * @copyright  Copyright (c) 2019 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Insight.php Tuesday 26th of November 2019 09:07AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Ayoola_Dbase_Table_Insight extends PageCarton_Widget
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
    protected static $_objectTitle = 'Get database table analytics'; 
    
    protected static $_timeTable = array(
        'minute' => 60,
        'hour' => 3600,
        'day' => 86400,
        'week' => 604800,
        'month' => 2592000,
        'year' => 31536000,
    );

    protected static $_chartTypes = array( 'line', 'pie', 'bar', 'radar', 'bubble', 'doughnut', 'polarArea', );


    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...

            set_time_limit( 0 );
        // ( $_POST );
        //    $this->setParameter( $_POST );
            $class = $this->getParameter( 'table_class' ) ;
            $class = Ayoola_Loader::loadClass( $class ) ? $class : @$_POST['table_class'];
            $class = Ayoola_Loader::loadClass( $class ) ? $class : 'Application_Log_View_Access_Log';
            if( ! Ayoola_Loader::loadClass( $class ) || ! method_exists( $class, 'select' ))
            {
                $class = 'Application_Log_View_Access_Log';
            //    $this->setViewContent( '<p class="badnews">' . sprintf( self::__( 'Table data insights cannot load because "%s" is not a valid database table class' ), $class ) . '</p>' ); 
            //    return false;
            }

            $label = $this->getParameter( 'table_label' ) ? : explode( '_', str_ireplace( '_Table', '', $class ) ); 
            while( count( $label ) > 2 )
            {
                array_shift( $label );
            }
            $label = implode( ' ', $label );

        //    $timeVariation = $this->getParameter( 'time_variation' ) ? : 'minute';
            $timeVariation = $this->getParameter( 'time_variation' );
            $timeVariation = @$_POST['time_variation'] ? : $timeVariation;
            $timeVariation = $timeVariation ? : 'minute';
            $rowKey = $this->getParameter( 'row_key' );
            $timeTable = self::$_timeTable;
            $timeVariationSec = $timeTable[$timeVariation] ? : 60;
        //    $noOfDatasets = $this->getParameter( 'no_of_datasets' ) ? : 10;
            $noOfDatasets = $this->getParameter( 'no_of_datasets' ) ;
            $noOfDatasets = @$_POST['no_of_datasets'] ? : $noOfDatasets;
            $noOfDatasets = $noOfDatasets ? : 5;
            $storage = self::getObjectStorage( array( 'id' => 'start_time', 'device' => 'File', 'time_out' => 300 ) );
            if( ! $currentTime = $storage->retrieve() )
            {
                $currentTime = $this->getParameter( 'start_time' ) ? : time();
                $storage->store( $currentTime );
            }

            

            $currentDataTime = $currentTime;
            $data = array();
            $labels = array();
            $color = array();
            $borderColor = array();
            $filter = new Ayoola_Filter_Time();
            $values = array();
            $rowKeys = array();
            $records = array();
            $totalRecords = 0;
            $totalPrevRecords = 0;
            if( $fieldsToExhibit = $this->getParameter( 'fields_to_exhibit' ) )
            {
                $fieldsToExhibit = is_array( $fieldsToExhibit ) ? $fieldsToExhibit : array_map( 'trim', explode( ',', $fieldsToExhibit ) );
            }
            else
            {
                $fieldsToExhibit = @$_POST['fields_to_exhibit'] ? : $fieldsToExhibit;
                $fieldsToExhibit = is_array( $fieldsToExhibit ) ? $fieldsToExhibit : array();
            }
            for( $i = 0; $i < ( $noOfDatasets * 2 ); $i++ )
            {
                $from = $currentDataTime;
                $currentDataTime = $currentDataTime - $timeVariationSec;
                $to = $currentDataTime;
                $result = $class::getInstance()->select( null, array( 'creation_time' => array( $from, $to ) ), array( 'creation_time_operator' => 'range' ) );
                if( $i >= $noOfDatasets )
                {  
                    $totalPrevRecords += count( $result );;
                    continue;
                }

                if( 
                    ! $fieldsToExhibit 
                    && ! empty( $result[0] )
                )
                {
                    $fieldsToExhibit = array_keys( $result[0] ); 
                }
            //    var_export( $fieldsToExhibit );
                if( ! empty( $fieldsToExhibit ) )
                {
                    foreach( $fieldsToExhibit as $field )
                    {
                    //    var_export( $field );
                        foreach( $result as $each )
                        {
                            $value = trim( $each[$field] ) ? : 'Undefined';
                            $valueArray = (array) $value;
                        //    var_export( $valueArray );
                            if( ! is_scalar( $field ) || stripos( $field, '_id' ) || stripos( $field, '_ip' ) || stripos( $field, '_time' ) )
                            {
                                continue;
                            }
                            foreach( $valueArray as $eachKey => $eachValue )
                            {
                                $fieldToUse = $field;
                                if( ! is_numeric( $eachKey ) )
                                {
                                    //  no assoc array
                                //    break;
                                    $fieldToUse = $eachKey;
                                    $fieldsToExhibit[$eachKey] = $eachKey;
                                //    var_export( $eachKey );
                                }
                                if( ! is_scalar( $eachValue ) )
                                {
                                    continue;
                                }
                                $records[$fieldToUse] = @$records[$fieldToUse] ? : array();
                                $records[$fieldToUse][$eachValue] = @$records[$fieldToUse][$eachValue] ? : 0;
                                $records[$fieldToUse][$eachValue]++;
                            }
                            

                            if( is_numeric( $value ) )
                            {
                            //    var_export( $value );
                            //    var_export( $each['url'] );
                                $values[$field][] = intval( $value );
                                $keyToUse = $each[$rowKey];
                                $keyToUse = $keyToUse ? : $each['username'];
                                $keyToUse = $keyToUse ? : $each['profile_url'];
                                $keyToUse = $keyToUse ? : $each['url'];
                                $keyToUse = $keyToUse ? : $each['uri'];
                                $keyToUse = $keyToUse ? : $each['article_url'];
                            //    $keyToUse = $keyToUse ? : $each[$class::getInstance()->getTableName() . '_id'];
                                $rowKeys[$field][] = $keyToUse;
                            }
                            


                        }
                    }
                }

            //    $result = $class::getInstance()->select(  );
            //    var_export( $values );
             //   var_export( $rowKeys );
                
           //     $labels[] = $filter->filter( $to );
                $recordCount = count( $result );
                $totalRecords += $recordCount;
            //    $data[] = $recordCount;
                array_unshift( $data, $recordCount );
                array_unshift( $labels, $filter->filter( $to ) );
                $c1 = rand( 0, 255 );
                $c2 = rand( 0, 255 );
                $c3 = rand( 0, 255 );
                $bgColor[] = $this->getParameter( 'background_color_' . $i ) ? : 'rgba( ' . $c1 . ', ' . $c2 . ', ' . $c3 . ', 0.2 )';
                $borderColor[] = $this->getParameter( 'border_color_' . $i ) ? : 'rgba( ' . $c1 . ', ' . $c2 . ', ' . $c3 . ', 1 )';
            }
            //    var_export( $values );
            $this->_objectData['no_of_datasets'] = $noOfDatasets;
            $this->_objectData['time_variation'] = $timeVariation;
            $this->_objectData['start_time'] = date( 'd M Y H:i ', $currentTime );
            $this->_objectData['end_time'] = date( 'd M Y H:i ', $currentDataTime );
            $this->_objectData['total'] = $totalRecords;
        //    var_export( $this->_objectData );
            $this->_objectData['average'] = intval( array_sum( $data ) / ( count( $data ) ? : 1 ) );
            $this->_objectData['max'] = intval( max( $data ) );
            $this->_objectData['min'] = intval( min( $data ) );
            $this->_objectData['step_size'] = intval(  $this->_objectData['max'] / 5 );
            $this->_objectData['previous_total'] = $totalPrevRecords;
            $this->_objectData['changes'] = $totalRecords - $totalPrevRecords;
            $this->_objectData['percentage_changes'] = intval( ( $this->_objectData['changes'] / ( $totalPrevRecords ? : 1 ) ) * 100 );
            if( $this->_objectData['changes'] >= 0 )
            {
                $this->_objectData['percentage_changes_increase'] = $this->_objectData['percentage_changes'];
                $this->_objectData['percentage_changes_decrease'] = '';
            }
            else
            {
                $this->_objectData['percentage_changes_decrease'] = $this->_objectData['percentage_changes'];
                $this->_objectData['percentage_changes_increase'] = '';
            }
            $this->_objectTemplateValues = $this->_objectData;

        //  var_export( $this->_objectData );
        //    $chartType = $this->getParameter( 'chart_type' );
            $chartType = $this->getParameter( 'chart_type' ) ;
            $chartType = @$_POST['chart_type'] ? : $chartType;
            $chartType = $chartType ? : 'line';

            //  Output demo content to screen
            
            $sampleData = "{
                type: '" . ( $chartType ) . "',
                data: {
                    labels: " . json_encode( $labels ) . ",
                    datasets: [{
                        label: '" . $label . "',
                        data: " . json_encode( $data ) . ",
                        backgroundColor: " . json_encode( $bgColor ) . ",
                        borderColor: " . json_encode( $borderColor ) . ",
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                //    responsive: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: " . $this->_objectData['step_size'] . ",
                                max: " . ( $this->_objectData['max'] + $this->_objectData['average'] ) . "
                            }
                        }]
                    }
                }
            }";
            $data = $sampleData;
            Application_Javascript::addFile( 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js' );
            Application_Javascript::addCode 
            (  
                '
                var ctx = document.getElementById( "myChart" );
                var myChart = new Chart( ctx, ' . $data . ' );
                ' 
            );

        //    var_export($values );
            //    var_export( $values );
             //   var_export( $rowKeys );

            //  top field values
            $topHtml = null;
            $maxTopFields = ( $this->getParameter( 'max_top_fields' ) ? : 6 );
            foreach( $fieldsToExhibit as $field )
            {
                
                if( empty( $records[$field] ) )
                {
                    continue;
                }

                while( count( $records[$field] ) > $maxTopFields )
                {
                    $xV = $records[$field];
                    asort( $xV );
                    array_shift( $xV );
                    $records[$field] = array_intersect_assoc( $xV, $records[$field] );
                }

            //    var_export( $records[$field] );
                if( count( ( $records[$field] ) ) > 1 )
                {
                    $chartName = 'myChart' . $field . __CLASS__;
                    $chatData = "{
                        type: '" . ( 'doughnut' ) . "',
                        data: {
                            labels: " . json_encode( array_keys( $records[$field] ) ) . ",
                            datasets: [{
                                label: 'Top " . $field . "',
                                data: " . json_encode( array_values( $records[$field] ) ) . ",
                                backgroundColor: " . json_encode( $bgColor ) . ",
                                borderColor: " . json_encode( $borderColor ) . ",
                                borderWidth: 1
                            }]
                        },
                        options: {
                        //    maintainAspectRatio: false,
                        //    responsive: false,
                            scales: {
                                    
                            },
                            title: {
                                display:true,
                                text: 'Top " . $field . "'
                            }
                        }
                    }";
                    Application_Javascript::addCode 
                    (  
                        '
                        var ctx = document.getElementById( "' . $chartName . '" );
                        var ' . $chartName . ' = new Chart( ctx, ' . $chatData . ' );
                        ' 
                    );
                    $topHtml .= '<div class="col-md-3"><br><br><canvas id="' . $chartName . '" width="400" height="400"></canvas></div>'; 
                }


                if( ! empty( $values[$field] ) )
                {
                    asort( $values[$field] );
                    $vhX = $values[$field];
                    foreach( $vhX as $id => $v )
                    {
                        if( count( $values[$field] ) <= 3 )
                        {
                            break;
                        }
                        unset( $values[$field][$id] );
                        unset( $rowKeys[$field][$id] );
                    }
    
                //    var_export( $values[$field] );
                //    var_export( $rowKeys[$field] );

                    $chartName = 'myChart_high' . $field . __CLASS__;
                    $chatData = "{
                        type: '" . ( 'bar' ) . "',
                        data: {
                            labels: " . json_encode( array_values( $rowKeys[$field] ) ) . ",
                            datasets: [{
                                label: 'High " . $field . "',
                                data: " . json_encode( array_values( $values[$field] ) ) . ",
                            }]
                        },
                        options: {
                        }
                    }";
                    Application_Javascript::addCode 
                    (  
                        '
                        var ctx = document.getElementById( "' . $chartName . '" );
                        var ' . $chartName . ' = new Chart( ctx, ' . $chatData . ' );
                        ' 
                    );
                    $topHtml .= '<div class="col-md-3"><br><br><canvas id="' . $chartName . '" width="400" height="400"></canvas></div>'; 
                }
            }
            $formHTML = null;
        //    var_export( $this->getParameter( 'table_class' )  );
            if( ! $this->getParameter( 'table_class' ) || ! Ayoola_Loader::loadClass( $this->getParameter( 'table_class' ) )  )
            {
                $form = new Ayoola_Form( array( 'method' => 'POST' ) );
                $element = new Ayoola_Form_Element();
                $element->hashElementName = false;
                $form->hashFormElementName = false;
                $options = Ayoola_Object_Dbase::getInstance()->select();
                $filter = new Ayoola_Filter_SelectListArray( 'class_name', 'class_name');
                $options = $filter->filter( $options );  
                $element->addElement( array( 'name' => 'table_class', 'label' => '', 'type' => 'Select', 'value' => $class,  'onchange'=> "this.form.submit();" ), array( '' => 'Database' ) + $options );
                $options = array_combine( array_keys( self::$_timeTable ), array_keys( self::$_timeTable ) );
                $element->addElement( array( 'name' => 'time_variation', 'label' => '', 'type' => 'Select', 'value' => $timeVariation,  'onchange'=> "this.form.submit();" ), array( '' => 'Time Variation' ) + $options );
                $options = array_combine( range( 2, 20 ), range( 2, 20 ) );
                $element->addElement( array( 'name' => 'no_of_datasets', 'label' => '', 'type' => 'Select', 'value' => $noOfDatasets,  'onchange'=> "this.form.submit();" ), array( '' => 'No of Datasets' ) + $options );

                $options = self::$_chartTypes;
                $options = array_combine( $options, $options );
                $element->addElement( array( 'name' => 'chart_type', 'label' => '', 'type' => 'Select', 'value' => $chartType,  'onchange'=> "this.form.submit();" ), array( '' => 'Chart Type' ) + $options );

                if( Ayoola_Loader::loadClass( $class ) )
                {
                    $options = array_keys( $class::getInstance()->getDataTypes() );
                    $options = array_combine( $options, $options );
                    $element->addElement( array( 'name' => 'fields_to_exhibit', 'label' => 'Fields to Exhibit', 'type' => 'Checkbox', 'value' => $fieldsToExhibit,  'onchange'=> "this.form.submit();" ), $options );
                }
            
                $form->addFieldset( $element );
                $formHTML = $form->view();
            }
            $html = '
                                    <div class="row">
                                    <div class="col-md-4" >
                                        <div style="text-align:center;">
                                        <div style="font-size:2em; background-color:#333; color:#fff;padding:1em;">' . ( $this->getParameter( 'title' ) ? : self::__( 'Total' ) ) . '</div>
                                        <span style="font-size:5em;">' . $this->_objectData['total'] . '</span>
                                        <span style="font-size:1em;color:green; display:inline-block;"><i class="fa fa-arrow-up"></i> ' . $this->_objectData['percentage_changes_increase'] . '%</span>
                                        <span style="font-size:1em;color:red; display:inline-block;"><i class="fa fa-arrow-down"></i> ' . $this->_objectData['percentage_changes_decrease'] . '%</span>
                                        <div style="font-size:small; background-color:#333; color:#fff;padding:1em;">' . sprintf( self::__( '%d %s to %s' ), $noOfDatasets, self::__( $timeVariation ), $this->_objectData['start_time'] ) . '</div>
                                        </div>

                                        <br>
                                        ' . $formHTML . '
                                        <br>
                                    </div>
                                    <div class="col-md-8" style="text-align:center;">
                                    
                                        
                                        <canvas id="myChart" width="400" height="400"></canvas>
                                      
                                        
                                    </div>
                                    <div class="col-md-12" style="text-align:center;">
                                    
                                        
                                    <div class="row">' . $topHtml . '</div>
                                      
                                        
                                    </div>


                                    </div>
                                        ';
            $this->_parameter['content_to_clear_internal'] .= ' <span style="font-size:1em;color:green; display:inline-block;"><i class="fa fa-arrow-up"></i> %</span>' . "\r\n";
            $this->_parameter['content_to_clear_internal'] .= '<span style="font-size:1em;color:red; display:inline-block;"><i class="fa fa-arrow-down"></i> %</span>' . "\r\n";
            $this->setViewContent( $html ); 


    
             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
        //    $this->setViewContent( self::__( '<p class="badnews">' . $e->getMessage() . '</p>' ) ); 
            $this->setViewContent( self::__( '<p class="badnews">Theres an error in the code</p>' ) ); 
            return false; 
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
        if( empty( $object['table_class'] ) )  
        {
            $object['table_class'] = 'Application_Log_View_Access_Log'; 
        }
        $html .= '<label>Table Class: </label> <br>
        <select data-parameter_name="table_class" onchange="if( this.value == \'__custom\' ){  var a = prompt( \'Custom Parameter Name\', \'\' ); if( ! a ){ this.value = \'\'; return false; } var option = document.createElement( \'option\' ); option.text = a; option.value = a; this.add( option ); this.value = a;  }">';
        $html .= '<option value="-">' . self::__( 'Dynamic' ) . '</option> '; 
        $options = Ayoola_Object_Dbase::getInstance()->select();
        $filter = new Ayoola_Filter_SelectListArray( 'class_name', 'class_name');
        $options = $filter->filter( $options );  
        foreach( $options as $key => $value )
        { 
            $html .=  '<option value="' . $key . '"';   
            if( @$object['table_class'] == $key )
            {
                $present = true;
                $html .= ' selected = selected '; 
            }
            $html .=  '>' . $value . '</option>';   
        }
//		var_export( $object );
        if( empty( $present ) )
        {
            $html .= '<option value="' . $object['table_class'] . '" selected = selected>' . $object['table_class'] . '</option> '; 
        }
        $html .= '<option value="__custom">' . self::__( 'Custom Table Class' ) . '</option> '; 
        $html .= '</select>';

        // time variation

        if( empty( $object['time_variation'] ) )  
        {
            $object['time_variation'] = 'hour'; 
        }
        $html .= '<br><label>Time Variation: </label> <br>
        <select data-parameter_name="time_variation" onchange="if( this.value == \'__custom\' ){  var a = prompt( \'Custom Parameter Name\', \'\' ); if( ! a ){ this.value = \'\'; return false; } var option = document.createElement( \'option\' ); option.text = a; option.value = a; this.add( option ); this.value = a;  }">';
        $options = array_combine( array_keys( self::$_timeTable ), array_keys( self::$_timeTable ) );
        foreach( $options as $key => $value )
        { 
            $html .=  '<option value="' . $key . '"';   
            if( @$object['time_variation'] == $key )
            {
                $present = true;
                $html .= ' selected = selected '; 
            }
            $html .=  '>' . $value . '</option>';   
        }
//		var_export( $object );
        if( empty( $present ) )
        {
            $html .= '<option value="' . $object['time_variation'] . '" selected = selected>' . $object['time_variation'] . '</option> '; 
        }
        $html .= '<option value="__custom">' . self::__( 'Custom' ) . '</option> '; 
        $html .= '</select>';

        // Datasets
        if ( @$object['time_variation'] )
        {
            if( empty( $object['no_of_datasets'] ) )  
            {
                $object['no_of_datasets'] = '10'; 
            }
            $html .= '<br><label>Number of  ' . @$object['time_variation'] . ' to colate: </label> <br>
            <select data-parameter_name="no_of_datasets" onchange="if( this.value == \'__custom\' ){  var a = prompt( \'Custom Parameter Name\', \'\' ); if( ! a ){ this.value = \'\'; return false; } var option = document.createElement( \'option\' ); option.text = a; option.value = a; this.add( option ); this.value = a;  }">';
            $options = array_combine( range( 2, 20 ), range( 2, 20 ) );
            foreach( $options as $key => $value )
            { 
                $html .=  '<option value="' . $key . '"';   
                if( @$object['no_of_datasets'] == $key )
                {
                    $present = true;
                    $html .= ' selected = selected '; 
                }
                $html .=  '>' . $value . '</option>';   
            }
    //		var_export( $object );
            if( empty( $present ) )
            {
                $html .= '<option value="' . $object['no_of_datasets'] . '" selected = selected>' . $object['no_of_datasets'] . '</option> '; 
            }
            $html .= '<option value="__custom">' . self::__( 'Custom Value' ) . '</option> '; 
            $html .= '</select>';
        }
        $database = @$object['table_class'];
        if( Ayoola_Loader::loadClass( $database ) )
        {
            $options = array_keys( $database::getInstance()->getDataTypes() );
            $html .= '<br><label>Fields to Exhibit: </label> <br>
            <input data-parameter_name="fields_to_exhibit" placeholder="e.g. ' . implode( ', ', $options ) . '" value="' . $object['fields_to_exhibit'] . '">';
        }

        // chart type
        {
            if( empty( $object['chart_type'] ) )  
            {
                $object['chart_type'] = 'line'; 
            }
            $html .= '<br><label>Chart Type</label> <br>
            <select data-parameter_name="chart_type" onchange="if( this.value == \'__custom\' ){  var a = prompt( \'Custom Parameter Name\', \'\' ); if( ! a ){ this.value = \'\'; return false; } var option = document.createElement( \'option\' ); option.text = a; option.value = a; this.add( option ); this.value = a;  }">';
            $options = self::$_chartTypes;
            $options = array_combine( $options, $options );
            foreach( $options as $key => $value )
            { 
                $html .=  '<option value="' . $key . '"';   
                if( @$object['chart_type'] == $key )
                {
                    $present = true;
                    $html .= ' selected = selected '; 
                }
                $html .=  '>' . $value . '</option>';   
            }
    //		var_export( $object );
            if( empty( $present ) )
            {
                $html .= '<option value="' . $object['chart_type'] . '" selected = selected>' . $object['chart_type'] . '</option> '; 
            }
            $html .= '<option value="__custom">' . self::__( 'Custom Value' ) . '</option> '; 
            $html .= '</select>';
        }
		return $html;
	}
	// END OF CLASS
}
