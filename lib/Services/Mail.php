<?php

declare(strict_types=1);

namespace Lib\Services;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
	private PHPMailer $mailer;
	public string $error = "";
	public function __construct()
	{
		$this->mailer = new PHPMailer();
		$this->mailer->isSMTP();
		$this->mailer->Host = $_ENV['MAILER_HOST'];
		$this->mailer->SMTPAuth = true;
		$this->mailer->Username = $_ENV['MAILER_USERNAME'];
		$this->mailer->Password = $_ENV['MAILER_PASSWORD'];
		$this->mailer->SMTPSecure = 'ssl';
		$this->mailer->Port = $_ENV['MAILER_PORT'];
		$this->mailer->isHTML(true);
		$this->mailer->CharSet = 'UTF-8';
		$this->mailer->addAddress($_ENV['MAILER_RECIPIENT']);
		$this->mailer->setFrom($_ENV['MAILER_RECIPIENT']);
		$this->mailer->Subject = "Message provenant du blog";
	}

	public function sendMail(string $email, string $firstname, string $lastname, string $message): bool
	{
		$this->mailer->setFrom($email);
		$this->mailer->Body = "De : " . $firstname . " " . $lastname . " (" . $email . ") " . "<br><br> Message : <br> $message";
		return $this->mailer->send();

	}
}