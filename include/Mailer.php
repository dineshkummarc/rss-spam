<?
class Mailer {
	protected $to;
	protected $from;

	public function __construct($args) {
		$this->to = $args['to'];
		$this->from = $args['from'];
	}

	public function mail($subject, $message) {
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
		$headers .= 'From: ' . $this->from . "\r\n";

		mail(
			$this->to,
			$subject,
			$message,
			$headers
		);
	}
}
