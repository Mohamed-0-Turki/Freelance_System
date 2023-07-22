<?php
	$pageTitle = 'Sign Up';
	require('initialize.php');
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$name 			 = '';
		$email 			 = '';
		$password 	 = '';
		$rePassword  = '';
		$userRole  	 = '';
		$phoneNumber = '';
		if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['rePassword']) || !isset($_POST['userRole']) || !isset($_POST['phoneNumber'])) {
			echo '<div class="messages-in-back">';
				messages('Denger', 'There was a problem');
			echo '</div>';
		}
		else {
			$name 			 = sanitize_data($_POST['name']);
			$email 			 = filter_var(sanitize_data($_POST['email']), FILTER_SANITIZE_EMAIL);
			$password 	 = sanitize_data($_POST['password']);
			$rePassword  = sanitize_data($_POST['rePassword']);
			$userRole  	 = sanitize_data($_POST['userRole']);
			$phoneNumber = filter_var(sanitize_data($_POST['phoneNumber']), FILTER_SANITIZE_NUMBER_INT);
			$upperCase 	 = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
			$errors 		 = [];
			if (empty($name) && empty($email) && empty($userRole)) {
				$errors[] = 'All fields are required.';
			}
			else {
				if (empty($name)) {
					$errors[] = 'You must enter your name.';
				}
				else {
					if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
						$errors[] = 'You must enter valid email.';
					}
					else {
						if ($userRole === 'Freelancer' && empty($phoneNumber) && !filter_var($phoneNumber, FILTER_VALIDATE_INT)) {
							$errors[] = 'The freelancer must enter their phone number.';
						}
						if (strlen($phoneNumber) > 11) {
							$errors[] = 'Invalid Phone Number';
						}
						else {
							if ($userRole != 'Freelancer' && empty($phoneNumber)) {
								$phoneNumber = null;
							}
						}
					}
				}
			}
			if (empty($password)) {
				$errors[] = 'Password can\'t be empty';
			}
			else {
				if (!in_array($password[0], $upperCase)) {
					$errors[] = 'First letter of the password should be capital.';
				}
				else {
					for ($i=0; $i<strlen($password); $i++) {
						if (!strpos('~!@#$%&*+=|?', $password[$i]) !== false) {
							$count = 0;
						}
						else {
							$count = 1;
							break;
						}
					}
					if ($count === 0) {
						$errors[] = 'Password must contain a special character (~, !, @, #, $, %, &, *, +, =, |, ?).';
					}
					else {
						if (strlen($password) < 8) {
							$errors[] = 'Password length must be greater than 8 characters';
						}
						else {
							if ($rePassword !== $password) {
								$errors[] = 'Incorrect password confirmation';
							}
						}
					}
				}
			}
			if (!empty($errors)) {
				echo '<div class="messages-in-back">';
					foreach ((array)$errors as $error) {
						messages('Warning', $error);
					}
				echo '</div>';
			}
			else {
				$stmt = $CONDB->prepare("SELECT
																				`Email`
																	FROM
																				`users`
																	WHERE
																				`Email` = ?
																	LIMIT 1");
				$stmt->execute([$email]);
				$count = $stmt->rowCount();
				$stmt = null;
				if ($count == 1) {
					echo '<div class="messages-in-back">';
						messages('Warning', 'You can\'t use this email because it already exist.');
					echo '</div>';
				}
				else {
					$hashedPassword	= password_hash($password, PASSWORD_DEFAULT);
					$stmt = $CONDB->prepare("INSERT INTO
																							`users`(`ID`, `Name`, `Email`, `Password`, `UserRole`, `PhoneNumber`, `UserDate`)
																				VALUES
																							(?, ?, ?, ?, ?, ?, now())");
					$stmt->execute([unique_id(), $name, $email, $hashedPassword, $userRole, $phoneNumber]);
					$count = $stmt->rowCount();
					$stmt = null;
					if ($count == 1) {
						echo '<div class="messages-in-back">';
							messages('Success', 'The account has been created. You will be directed to the members page.');
						echo '</div>';
						header('Location: ./login.php');
						exit();
					}
					else {
						echo '<div class="messages-in-back">';
							messages('Denger', 'An error occurred, try again.');
						echo '</div>';
					}
				}
			}
		}
	}
	else {
		$name 			 = '';
		$email 			 = '';
		$password 	 = '';
		$rePassword  = '';
		$userRole  	 = '';
		$phoneNumber = '';
	}
?>
<div class="container-form-login-signup">
	<div class="items-container">
		<h1>Sign up | <a href="./main.php">Freelance</a></h1>
		<form action="<?php $_SERVER['PHP_SELF']?>" method="POST">
			<div class="input-field">
				<span class="icon-input"><i class="fa fa-solid fa-user"></i></span>
				<input type="text" name="name" placeholder="Enter your name" value="<?=sanitize_data($name)?>">
			</div>
			<div class="input-field">
				<span class="icon-input"><i class="fa fa-solid fa-envelope"></i></span>
				<input type="email" name="email" placeholder="Enter your email" value="<?=sanitize_data($email)?>">
			</div>
			<div class="input-field">
				<span class="icon-input"><i class="fa fa-solid fa-lock"></i></span>
				<input id="password" type="password" name="password" placeholder="Enter password" value="<?=sanitize_data($password)?>">
				<span class="icon-show-password"><i class="fa-solid fa-eye"></i></span>
			</div>
			<div class="notes">
				<p>! first capital letter.</p>
				<p>! special character(~!@#$%&*+=|?).</p>
				<p>! 8 characters At Least.</p>
			</div>
			<div class="input-field">
				<span class="icon-input"><i class="fa fa-solid fa-lock"></i></span>
				<input id="rePassword" type="password" name="rePassword" placeholder="Confirm password" value="<?=sanitize_data($rePassword)?>">
			</div>
			<div class="check">
					<label class="radio">
						<input type="radio" class="radio-value" name="userRole" value="Client">
						Client
					</label>
					<label class="radio">
						<input type="radio" class="radio-value" name="userRole" value="Freelancer" checked>
						Freelancer
					</label>
			</div>
			<div class="input-field" id="field-phoneNumber">
				<span class="icon-input"><i class="fa fa-solid fa-phone"></i></span>
				<input type="tel" name="phoneNumber" placeholder="+20 Phone Number" value="<?=sanitize_data($phoneNumber)?>">
			</div>
			<input class="button-form-login-signup-reset" id="submit-button" type="submit" value="SignUp">
			<input class="button-form-login-signup-reset" type="reset" value="Reset">
		</form>
		<div class="links-form">
			Alrady have an account?
			<a href="./login.php">login</a>
		</div>
	</div>
</div>
<?php require $temp . ('end.body.php'); ?>
<script>signupForm();</script>