<?php    
   return array(
    'appenders' => array(
    'default' => array(
    'class' => 'LoggerAppenderDailyFile',
    'layout' => array(
    'class' => 'LoggerLayoutPattern',
	  'params' => array(
			'conversionPattern' => '%date %logger %-5level %msg%n'
		)
    ),
    'params' => array(
    'datePattern' => 'Y-m-d',
    'file' => LOGGER_PATH.'/log-%s.log',
    ),
    ),
    ),
    'rootLogger' => array(
    'appenders' => array('default'),
    ),
    );
?>
