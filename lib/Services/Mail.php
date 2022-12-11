<?php

declare(strict_types=1);

namespace Lib\Services;

class Mail
{

	private string $recipient = "elisaklein66@gmail.com";

	public function sendMail(string $email, string $firstname, string $lastname, string $message): bool
	{
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		$headers .= "From: $email \r\n";
		$subject = "Message provenant du blog";
		$content = "Message de : $firstname $lastname ($email) <br><br> Message : <br> $message";
		return mail($this->recipient, $subject, $content, $headers);
	}
}