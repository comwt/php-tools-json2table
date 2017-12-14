# php-tools-json2table

Name            : Json2Table.class.php  (JSON-to-HTML-Table)

Author          : Justin G. Francis
Author's Version: https://github.com/comwt/php-tools-json2table
Created         : December 13, 2017
License         : GNU GPL 3
                  This program is free software and comes with absolutely
                  no warranty.  Use at your own risk.
                  You may copy, modify and distribute this as you like
                  without consent by Justin Francis, as long as copies
                  retain this header.

Purpose         : Builds and outputs an HTML table representation of
                  normalized JSON data at any (nested) depth.

Usage           : require_once('php-tools/Json2Table.class.php');
                  new Json2Table( "<json_data>" [,array(<comma_separated_property_list>)] );

Example         : new Json2Table( $myJsonData, array('TITLE'=>'My Data','DEBUG_TF'=>1,'DEBUG_COLOR'=>'blue') );

Property List   : 'TITLE'=>'<Label for your JSON data>'
                  'DEBUG_TF'=>{0|1}
                  'DEBUGLVL'=>{1-##}
                  'DEBUG_COLOR'=>'<any HTML color indicator>'
