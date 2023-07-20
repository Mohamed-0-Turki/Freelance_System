<?php
	$pageTitle = 'Sign Up';
	require('initialize.php');
	// Condition to check if request method is POST or GET.
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
			// Get Values From Inputs.
			$name 			 = sanitize_data($_POST['name']);
			$email 			 = filter_var(sanitize_data($_POST['email']), FILTER_SANITIZE_EMAIL);
			$password 	 = sanitize_data($_POST['password']);
			$rePassword  = sanitize_data($_POST['rePassword']);
			$userRole  	 = sanitize_data($_POST['userRole']);
			$phoneNumber = filter_var(sanitize_data($_POST['phoneNumber']), FILTER_SANITIZE_NUMBER_INT);
			// str_split() -> Convert a string to an array
			// All Upper Case Characters In Array.
			$upperCase 	 = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
			// Empty Array Will Use To Give It The Errors.
			$errors 		 = [];
			// TODO: Start Validation The Form.
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
						if ($userRole == 'Freelancer' && empty($phoneNumber) && !filter_var($phoneNumber, FILTER_VALIDATE_INT)) {
							$errors[] = 'The freelancer must enter their phone number.';
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
				// in_array() -> Checks if a value exists in an array
				if (!in_array($password[0], $upperCase)) {
					$errors[] = 'First letter of the password should be capital.';
				}
				else {
					// strlen() -> Get string length.
					for ($i=0; $i<strlen($password); $i++) {
						// strpos() -> Find the position of the first occurrence of a substring in a string.
						// If Password Not Have Special Character The Variable $count Will Be value 0 else The Value Will Be 1.
						if (!strpos('~!@#$%&*+=|?', $password[$i]) !== false) {
							$count = 0;
						}
						else {
							$count = 1;
							break;
						}
					}
					if ($count == 0) {
						$errors[] = 'Password must contain a special character (~, !, @, #, $, %, &, *, +, =, |, ?).';
					}
					else {
						// strlen() -> Get string length.
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
			// TODO: End Validation The Form.
			// Verify That There Are No Errors In The Array.
			if (!empty($errors)) {
				echo '<div class="messages-in-back">';
					foreach ((array)$errors as $error) {
						messages('Warning', $error);
					}
				echo '</div>';
			}
			else {
				// SQL query [SELECT].
				$stmt = $CONDB->prepare("SELECT
																				`Email`
																	FROM
																				`users`
																	WHERE
																				`Email` = ?
																	LIMIT 1");
				// execute query.
				$stmt->execute([$email]);
				// Returns the number of rows affected by the last SQL query.
				$count = $stmt->rowCount();
				// Check The Email Entered By The User If It Exists Or Not.
				if ($count == 1) {
					echo '<div class="messages-in-back">';
						messages('Warning', 'You can\'t use this email because it already exist.');
					echo '</div>';
				}
				else {
					// password_hash — Creates a password hash
					$hashedPassword	= password_hash($password, PASSWORD_DEFAULT);
					// SQL query [INSERT].
					$stmt = $CONDB->prepare("INSERT INTO
																							`users`(`ID`, `Name`, `Email`, `Password`, `UserRole`, `PhoneNumber`, `UserDate`)
																				VALUES
																							(?, ?, ?, ?, ?, ?, now())");
					// execute query.
					$stmt->execute([unique_id(), $name, $email, $hashedPassword, $userRole, $phoneNumber]);
					// Returns the number of rows affected by the last SQL query.
					$count = $stmt->rowCount();
					// Check Whether The Data Has Been Recorded In The Database.
					if ($count == 1) {
						echo '<div class="messages-in-back">';
							messages('Success', 'The account has been created. You will be directed to the members page.');
						echo '</div>';
						include $pages . ('loader.html');
						header('refresh:2;login.php');
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
		<h1>Sign up | <a href="main.php">Freelance</a></h1>
		<form action="<?php $_SERVER['PHP_SELF']?>" method="POST" id="signup-form">
			<div class="input-field" id="field-name">
				<span class="icon-input"><i class="fa fa-solid fa-user"></i></span>
				<input type="text" name="name" id="name" placeholder="Enter your name" value="<?=sanitize_data($name)?>">
			</div>
			<div class="input-field" id="field-email">
				<span class="icon-input"><i class="fa fa-solid fa-envelope"></i></span>
				<input type="email" id="email" name="email" placeholder="Enter your email" value="<?=sanitize_data($email)?>">
			</div>
			<div class="input-field" id="field-password">
				<span class="icon-input"><i class="fa fa-solid fa-lock"></i></span>
				<input id="password" type="password" name="password" placeholder="Enter password" value="<?=sanitize_data($password)?>">
				<span class="icon-show-password"><i class="fa-solid fa-eye"></i></span>
			</div>
			<div class="notes">
				<p class="validation">! first capital letter.</p>
				<p class="validation">! special character(~!@#$%&*+=|?).</p>
				<p class="validation">! 8 characters At Least.</p>
			</div>
			<div class="input-field" id="field-rePassword">
				<span class="icon-input"><i class="fa fa-solid fa-lock"></i></span>
				<input id="rePassword" type="password" name="rePassword" placeholder="Confirm password" value="<?=sanitize_data($rePassword)?>">
			</div>
			<div class="check">
					<label class="radio" for="client">
						<input type="radio" class="radio-value" id="client" name="userRole" value="Client" checked>
						Client
					</label>
					<label class="radio" for="freelancer">
						<input type="radio" class="radio-value" id="freelancer" name="userRole" value="Freelancer">
						Freelancer
					</label>
			</div>
			<div class="input-field" id="field-phoneNumber">
				<span class="icon-input"><i class="fa fa-solid fa-phone"></i></span>
				<input type="tel" id="phoneNumber" name="phoneNumber" placeholder="+20 Phone Number" value="<?=sanitize_data($phoneNumber)?>">
			</div>
			<input class="button-form-login-signup-reset" id="submit-button" type="submit" value=" SignUp">
			<input class="button-form-login-signup-reset" type="reset" value="Reset">
		</form>
		<div class="links-form">
			Alrady have an account?
			<a href="login.php">login</a>
		</div>
	</div>
</div>
<?php require $temp . ('end.body.php'); ?>
<script>signupForm();</script>