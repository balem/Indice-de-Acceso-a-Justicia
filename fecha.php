<?php

print '<h2>string date ( string format [, int timestamp])</h2>';
print 'Devuelve una cadena formateada de acuerdo con la cadena de formato dada, utilizando el valor de timestamp dado o la hora local actual si no hay parámetro.<br />Ej.:print (date("l, j de F de Y")) ==> ';
print (date("l, j \d\e F \d\e Y"));
print '<br /><br />Ej.: print(date("d-m-y")) ==> ';
print (date("d-m-y - h:i:s a"));
print '<br /><br />Ej.: print (date("d-m-y")) ==> ';
print (date("D, j, M. Y - H:i \h\s."));

print '<h2>array getdate ( int timestamp)</h2>';
print 'Devuelve un array asociativo que contiene la información de fecha del valor timestamp.<br />';
$a=getdate();
$hoyes=$a['mday']."-".$a['mon']."-".$a['year'];
print "hoy es: $hoyes.<br>dias desde el 01-01-1970:<br>";
print time();
?>