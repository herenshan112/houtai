<?php
return array(
	'TMPL_ACTION_ERROR' => 'Public:error',
    'TMPL_ACTION_SUCCESS' => 'Public:success',
    
    'URL_ROUTER_ON'   => true,
    'URL_ROUTE_RULES'=>array(
        'Orders/send' => array('Orders/index', 'status=2'),
        'Orders/recv' => array('Orders/index', 'status=3'),
        'Orders/pj' => array('Orders/index', 'status=4'),
    ),
);