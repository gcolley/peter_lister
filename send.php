<?php
require 'include/class.phpmailer.php';

if (isset($_POST['submit']) && $_POST['submit'] == 'send')
{
	$name = $_POST['name'] ? $_POST['name'] : null;
	$email = $_POST['email'] ? $_POST['email'] : null;
	$message = $_POST['message'] ? $_POST['message'] : null;

	$json_return = array();
	$errors = array();

	if (!isset($name) or strlen($name) < 3 or !preg_match("/^[A-Z ]+$/i", $name)) 
	{
		$errors[] = "Your name must contain at least 3 characters and not contain any numbers, or special characters.";
	}

	if (!isset($email) or strlen($email) == 0 or !filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		$errors[] = "You must enter a valid email address, please try a different one";
	}

	if (!isset($message) or strlen($message) < 20)
	{
		$errors[] = "Your message must contain at least 20 characters.";
	}

	if (count($errors)) 
	{
		$json_return['code'] = 203;
		$json_return['errors'] = $errors;
	}
	else
	{
		$Mail = new PHPMailer;

		$Mail->From = $email;
		$Mail->FromName = $name;
		$Mail->AddAddress('grant.p.colley@gmail.com', 'Grant Colley');
		$Mail->AddReplyTo($email, $name);

		$Mail->IsHTML(true);

		$Mail->Subject = 'Booking Enquiry';
		$Mail->Body = nl2br(strip_tags($message));
		$Mail->AltBody = $message;

		try {
			$Mail->Send();
			$json_return['code'] = 201;
		}
		catch (Exception $e)
		{
			$json_return['code'] = 203;
			$json_return['errors'] = array('Your message could not be sent, please try again.');
		}
	}

	echo json_encode($json_return);
}

?>