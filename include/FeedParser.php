<?
class FeedParser {
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

	public static function parse($data) {
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

			array_push($feed['items'], array(
				'id' => $id,
				'title' => (string)$items[$j]->title,
				'link' => self::getLink($items[$j]),
				'content' => self::getContent($items[$j])
			));
		}

		return $feed;
	}
}