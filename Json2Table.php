<?php

/*----------------------------------------------------------------------------
// Name            : Json2Table.class.php  (JSON-to-HTML-Table)
//
// Author          : Justin G. Francis
// Author's Version: https://github.com/comwt/php-tools-json2table
// Created         : December 13, 2017
// License         : GNU GPL 3
//                   This program is free software and comes with absolutely
//                   no warranty.  Use at your own risk.
//                   You may copy, modify and distribute this as you like
//                   without consent by Justin Francis, as long as copies
//                   retain this header.
//
// Purpose         : Builds and outputs an HTML table representation of
//                   normalized JSON data at any (nested) depth.
//
// Usage           : require_once('php-tools/json2table.php');
//                   new Json2Table( "<json_data>" [,array(<comma_separated_property_list>)] );
//
// Example         : new Json2Table( $myJsonData, array('TITLE'=>'My Data','DEBUG_TF'=>1,'DEBUG_COLOR'=>'blue') );
//
// Property List   : 'TITLE'=>'<Label for your JSON data>'
//                   'DEBUG_TF'=>{0|1}
//                   'DEBUGLVL'=>{1-##}
//                   'DEBUG_COLOR'=>'<any HTML color indicator>'
//--------------------------------------------------------------------------*/

class Json2Table {

  //############################################################################
  function __construct( $data, $property=null ) {

    $property_REF =& $property;
    if ( isset($property['DEBUG_TF']) && $property['DEBUG_TF'] != 0 ) {
        $trace = debug_backtrace();
        $caller = array_shift( $trace );
        $this->json2TableDebug(2, '<font face="courier new">' . sprintf("%'04d",$caller['line']) . '</font>) In Json2Table()', $property_REF);
    }

    if ( isset($property['DEBUG_TF']) && $property['DEBUG_TF'] &&
         isset($property['DEBUGLVL']) && $property['DEBUGLVL'] >= 3 ) {
        $this->json2TableDebug(5, "<--property dump" . var_dump($property), $property_REF) ;
    }
    $line = 0;
    $depth = 1;
    $level = 1;
    list($depth,$lines) = $this->getJsonDataStats( $data, $depth, $line, $level, $property_REF );
    $colnum = $depth + 1;
    $this->json2TableDebug(3, "depth: $depth", $property_REF);
    $this->json2TableDebug(3, "colnum: $colnum", $property_REF);
    $this->json2TableDebug(3, "lines: $lines", $property_REF);
    $title = ( isset($property['TITLE']) )
           ? '<tr style="background-color: #2c3e50; color: white;">' . "\n" . '<td colspan="' . ($colnum + 1) .
             '"><font size=+2><em><strong>' . $property['TITLE'] . '</strong></em></font></td></tr>' . "\n"
           : '';
    $this->json2TableDebug(3, "title: {$title}", $property_REF);
    echo '<table border=1 cellspacing=5 cellpadding=2>' . "\n" . $title;
    $depth = 1;
    $this->json2TableRow( $data, $colnum, $depth, $line, $property_REF );
    echo "</table>\n";

  } //##########-> END function - Json2Table::__construct() <-------##########


  //----------------------------------------------------------------------------
  //- Internal Functions
  //----------------------------------------------------------------------------

  //############################################################################
  private function json2TableDebug( $p_dbglvl, $p_msg, $property ) {

    if ( ! isset($property['DEBUG_TF']) || $property['DEBUG_TF'] == 0 ) { return; }
    if ( isset($property['DEBUGLVL']) && $property['DEBUGLVL'] > $p_dbglvl  ) {
        return; //only show deeper debug output if specifically requested
    } else if ( $p_dbglvl > 1 ) {
        return; //by default, only show level one debug output
    }
    $property['DEBUG_COLOR'] = ( isset($property['DEBUG_COLOR']) ) ? $property['DEBUG_COLOR'] : 'grey';
    echo '<font style="color: ' . $property['DEBUG_COLOR'] . ';">[j2T-DEBUG]: ' . $p_msg . '</font><br />' . "\n";

  } //##########-> END private function - json2TableDebug() <---------##########

  //############################################################################
  private function getJsonDataStats( $p_data, $p_depth, $p_line, $p_level, $property ) {

    foreach ( $p_data as $key=>$value ) {
        $p_line += 1;
        $this->json2TableDebug(2, "$p_line. (D:$p_depth)(L:$p_level) key: $key &nbsp; &nbsp; value: $value", $property);
        if ( is_array( $value ) ) {
            list($p_depth,$p_line) = $this->getJsonDataStats( $value, $p_depth, $p_line, $p_level + 2, $property );
        }
        $p_depth = ( $p_level > $p_depth ) ? $p_level : $p_depth;
    }
    return array($p_depth,$p_line);

  } //##########-> END private function - getJsonDataStats() <--------##########

  //############################################################################
  private function json2TableRow( $p_data, $p_colnum, $p_depth, $p_line, $property ) {

    foreach ( $p_data as $key=>$value ) {
        $p_line += 1;
        $this->json2TableDebug(2, "$p_line. (D:$p_depth) key: $key &nbsp; &nbsp; value: $value", $property);

        $bgcolor = ( ($p_line % 2) == 0 ) ? '#ffffff' : '#cfcfcf';
        $row = '<tr style="background-color: ' . $bgcolor . ';"><td align="right">Line ' . $p_line . '&nbsp; | &nbsp;</td>';
        if ( $value == '' || is_array($value) ) {
            $displayed_value = '&nbsp;';
        } else {
            $displayed_value = ' = ' . $value;
        }

        if ( $p_depth == 1 ) {
            $row .= '<td>' . $key . '</td>'
                  . '<td colspan=' . ($p_colnum - 1) . '>' . $displayed_value . '</td></tr>' . "\n";
        } else {
            for ($i=1; $i<=($p_depth - 1); $i++) {
                $row .= '<td>&nbsp;</td>';
            }
            $row .= '<td>' . $key . '</td>'
                  . '<td colspan=' . ($p_colnum - $p_depth) . '>' . $displayed_value . '</td></tr>' . "\n";
        }
        echo $row . "\n";
        if ( is_array( $value ) ) {
            $p_line = $this->json2TableRow( $value, $p_colnum, $p_depth + 1, $p_line, $property );
        }
    }
    return $p_line;

  } //##########-> END private function - json2TableRow() <-----------##########

  //----------------------------------------------------------------------------
  //- END: Internal Functions
  //----------------------------------------------------------------------------

} //END: class Json2Table

?>
