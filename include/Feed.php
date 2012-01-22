<?
class Feed {
	protected $url;
	protected $urlHash;
	protected $updateInterval;

	public function __construct($args) {
		$this->url = $args['url'];
		$this->urlHash = md5($this->url);
		$this->updateInterval = $args['updateInterval'];
	}

	protected function loadFeed() {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$data = curl_exec($curl);

		if (curl_errno($curl)) {
			return null;
		}

		return FeedParser::parse($data);
	}

	public function update($db, $force = false) {
		if (!$force) {
			if (!$this->db->shouldFeedUpdate($this->urlHash, $this->updateInterval)) {
				return false;
			}
		}

		return $this->loadFeed();
	}
}