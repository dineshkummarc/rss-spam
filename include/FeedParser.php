<?
class FeedParser {
	protected static function diffbot($link, $token)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'http://www.diffbot.com/api/article?token='.$token.'&url='.urlencode($link));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($curl);
		$data = json_decode($data);

		if(isset($data->text)) {
			return $data->text;
		}

		return null;
	}

	protected static function getItems($node) {
		$items = $node->entry; // Atom
		if (!count($items)) {
			$items = $node->item; // RSS
		}

		return $items;
	}

	protected static function getId($node) {
		$id = (string)$node->id; // Atom

		if (!strlen($id)) {
			$id = (string)$node->guid; // RSS
		}

		if (!strlen($id)) {
			$id = (string)$node->link; // RSS
		}

		if (!strlen($id)) {
			return null;
		}

		return $id;
	}

	protected static function getLink($node) {
		$link = (string)$node->link; // RSS

		if (!strlen($link)) {
			$attr = $node->link->attributes();
			$link = (string)$attr->href; // Atom
		}

		return $link;
	}

	protected static function getContent($node) {
		$content = (string)$node->content; // Atom

		if (!strlen($content)) {
			$content = (string)$node->description; // RSS
		}

		return $content;
	}

	public static function parse($data, $parser = null, $parserToken = null) {
		$root = simplexml_load_string($data);

		$title = (string)$root->title; // Assume Atom
		if (!strlen($title)) {
			$root = $root->channel; // RSS fallback
			$title = (string)$root->title;
		}

		$items = self::getItems($root);

		$feed = array(
			'title' => $title,
			'items' => array()
		);

		$count = count($items);
		for ($j = 0; $j < $count; $j += 1) {
			$id = self::getID($items[$j]);

			if (is_null($id)) {
				/* Can't find a unique identifier .. skip! */
				continue;
			}

			$item = array(
				'id' => $id,
				'title' => (string)$items[$j]->title,
				'link' => self::getLink($items[$j]),
				'content' => self::getContent($items[$j])
			);

			if(null !== $parser && in_array($parser, get_class_methods(get_called_class()))) {
				$content = self::$parser($item['link'], $parserToken);
				if(null !== $content) {
					$item['content'] = $content;
				}
			}

			array_push($feed['items'], $item);
		}

		return $feed;
	}
}