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

	public function update($db, $force = false) {
		if (!$force) {
			if (!$this->db->shouldFeedUpdate($this->urlHash, $this->updateInterval)) {
				return false;
			}
		}
	}
}