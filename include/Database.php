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

	public function shouldFeedUpdate($urlHash, $updateInterval) {
		$stmt = $this->pdo->prepare(
			'select * from feeds ' .
			'where url_hash = ? and ' .
			'(unix_timestamp(current_timestamp) - unix_timestamp(last_updated)) > ?;'
		);

		$stmt->execute(array($urlHash, $updateInterval));

		return $stmt->rowCount() > 0;
	}
}