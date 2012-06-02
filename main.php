<?
include('include/Database.php');
include('include/Mailer.php');
include('include/FeedParser.php');
include('include/Feed.php');
include('include/config.php');

$db = new Database($config['db']);
$mailer = new Mailer($config['mail']);

$parser = null;
$parserToken = null;
if(isset($config['parser']) && !empty($config['parser'])) {
    $parser = array_keys($config['parser']);
    $parser = $parser[0];
    $parserToken = $config['parser'][$parser];
}

foreach ($config['feeds'] as $feed) {
	print 'Processing ' . $feed['url'] . ' ...';
	$db->ensureFeedExists($feed['url']);
	$feed = new Feed($feed, $parser, $parserToken);
	$feed->update($db, $mailer);

	print "\n";
}