<?
$config = array(
	'db' => array(
		'host' => 'localhost',
		'database' => 'rss_spam',
		'username' => 'root',
		'password' => ''
	),

	'mail' => array(
		'to' => 'destination@example.com',
		'from' => 'source@example.com'
	),

	'feeds' => array(
		array(
			'url' => 'http://example.com/index.rss',
			'updateInterval' => 3600,
		)
	),
	'parser'       => array(
		//'diffbot'   => 'TOKEN GOES HERE'
	)
);