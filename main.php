<?
include('include/Database.php');
include('include/Mailer.php');
include('include/FeedParser.php');
include('include/Feed.php');
include('include/config.php');

$db = new Database($config['db']);
$mailer = new Mailer($config['mail']);

foreach ($config['feeds'] as $feed) {
	print 'Processing ' . $feed['url'] . ' ...';
	$db->ensureFeedExists($feed['url']);
	$feed = new Feed($feed);
	$feed->update($db, $mailer);

	print "\n";
}

