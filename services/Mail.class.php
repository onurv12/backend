<?php

	require 'vendor/swiftmailer/swiftmailer/lib/swift_required.php';

	abstract class MailService {

		public static function send ($subject, $fromAddress, $fromName, $toAddress, $toName, $htmlContent, $textContent) {
			// Creating the transporter (using the built-in PHP mail function)
			$transporter = Swift_MailTransport::newInstance();
			// Creating the mailer using the transporter
			$mailer = Swift_Mailer::newInstance($transporter);
			// Composing the message
			$message = Swift_Message::newInstance($transporter)
				// Setting the subject
				->setSubject($subject)
				// Setting the sender (by creating an array)
				->setFrom(array($fromAddress => $fromName))
				// Setting the recipient (by creating an array)
				->setTo(array($toAddress => $toName))
				// Setting the message's non-HTML content
				->setBody($textContent)
				// Setting the message's (alternative) HTML content
				->addPart($htmlContent, 'text/html');
			// Sending the mail, eventually
			$mailer->send($message);
		}

		// 'sendTemplate' uses 'send' to send a templated mail, however, it first compiles a HTML template and a text template.
		public static function sendTemplate ($subject, $fromAddress, $fromName, $toAddress, $toName, $htmlTemplatePath, $textTemplatePath, $templateParameters) {
			$compiledHtmlTemplate = self::getCompiledTemplate($htmlTemplatePath, $templateParameters);
			$compiledTextTemplate = self::getCompiledTemplate($textTemplatePath, $templateParameters);
			self::send($subject, $fromAddress, $fromName, $toAddress, $toName, $compiledHtmlTemplate, $compiledTextTemplate);
		}

		// 'getCompiledTemplate' loads a template and replace all occurences of the specified parameters.
		private static function getCompiledTemplate ($templatePath, $templateParameters) {
			$content = file_get_contents($templatePath);
			foreach ($templateParameters as $key => $value) {
				$content = str_replace("%" . $key . "%", $value, $content);
			}
			return $content;
		}
	}

?>