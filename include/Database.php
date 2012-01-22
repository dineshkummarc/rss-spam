<?
class Database {
	protected $pdo;

	public function __construct($args) {
		$this->pdo = new PDO(
			'mysql:host=' . $args['host'] . ';' . 'dbname=' . $args['database'],
			$args['username'],
			$args['password']
		);
	}

	public function close() {
		$this->dbo = null;
	}

	public function beginTransaction() {
		return $this->pdo->beginTransaction();
	}

	public function commit() {
		return $this->pdo->commit();
	}

	public function ensureFeedExists($url) {
		$this->pdo->beginTransaction();

		$stmt = $this->pdo->prepare(
			'select id from feeds where url_hash = ?'
		);

		$hash = md5($url);

		$stmt->execute(array($hash));

		if ($stmt->rowCount() === 0) {
			$stmt = $this->pdo->prepare(
				'insert into feeds (url, url_hash, last_updated) ' .
				'values(?, ?, ?)'
			);

			$stmt->execute(array($url, $hash, 0));
		}

		$this->pdo->commit();
	}

	public function shouldFeedUpdate($urlHash, $updateInterval) {
		$stmt = $this->pdo->prepare(
			'select id from feeds ' .
			'where url_hash = ? and ' .
			'(unix_timestamp(current_timestamp) - unix_timestamp(last_updated)) > ?'
		);

		$stmt->execute(array($urlHash, $updateInterval));

		return $stmt->rowCount() > 0;
	}

	public function getFeedId($urlHash) {
		$stmt = $this->pdo->prepare(
			'select id from feeds ' .
			'where url_hash = ?'
		);

		$stmt->execute(array($urlHash));

		$result = $stmt->fetch();
		if (!$result) {
			return false;
		}

		return $result['id'];
	}

	public function doesFeedItemExist($feedId, $itemGuid) {
		$stmt = $this->pdo->prepare(
			'select id from items ' .
			'where feed_id = ? and guid = ?'
		);

		$stmt->execute(array($feedId, $itemGuid));

		return $stmt->rowCount() > 0;
	}
}