<?
class Mailer {
	public function __construct($args) {
		$this->to = $args['to'];
		$this->from = $args['from'];
	}

	public function mail($subject, $message) {
		mail(
			$this->to,
			$subject,
			$message,
			'From: ' . $this->from
		);
	}
}
