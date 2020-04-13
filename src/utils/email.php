<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Email extends PHPMailer {
	public function __construct(){
    parent::__construct();

		$this->IsSMTP();
		$this->Host = 'smtp.gmail.com';
		$this->Port = 587;
		$this->SMTPAuth = true;
		$this->SMTPSecure = parent::ENCRYPTION_STARTTLS;
		$this->Username = 'general.teste.email@gmail.com';
		$this->Password = 'test4632';
		$this->setFrom('no-reply@example.com', 'User Exemple');
		$this->addReplyTo('replyto@example.com', 'User Exemple');
  }

	public function setTemplate($template, $variables) {
		$htmlContent = file_get_contents(__DIR__ . '/template/' . $template . '.htm');

		$variablesAplies = array();

		foreach ($variables as $name => $value) {
			$nameRef = "%$name%";

			$variablesAplies[$nameRef] = $value;
		}

		$htmlContent = strtr(utf8_decode($htmlContent), $variablesAplies);

		preg_match('/<title>(.*)<\/title>/iU', $htmlContent, $titleMatches);
		$title = $titleMatches[1];

		$this->Subject = $title;
		$this->msgHTML($htmlContent, __DIR__);
	}
}
?>
