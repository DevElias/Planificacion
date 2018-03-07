<?php 

$route[] = ['/', 'HomeController@index'];

/** Anos **/
$route[] = ['/anos', 'AnosController@index'];

/** Temporalidades **/
$route[] = ['/temporalidades', 'TemporalidadesController@index'];

/** Tipos **/
$route[] = ['/tipos', 'TiposController@index'];

/** Pilares **/
$route[] = ['/pilares', 'PilaresController@index'];

return $route;