<?php
	session_start();
	$pageTitle = 'Login';
	require ('initialize.php');
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$email = '';
		$password = '';
		if (!isset($_POST['email']) || !isset($_POST['password'])) {
			echo '<div class="messages-in-back">';
				messages('Denger', 'There was a problem');
			echo '</div>';
		}
		else {
			$email 			= filter_var(sanitize_data($_POST['email']), FILTER_SANITIZE_EMAIL);
			$password  	= sanitize_data($_POST['password']);
			@$remmberMe = sanitize_data($_POST['remmberMe']);
			if (empty($email) && empty($password)) {
				echo '<div class="messages-in-back">';
					messages('Warning', 'Enter your email and password.');
				echo '</div>';
			}
			else {
				if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
					echo '<div class="messages-in-back">';
						messages('Warning', 'Enter your email.');
					echo '</div>';
				}
				else {
					if (empty($password)) {
						echo '<div class="messages-in-back">';
							messages('Warning', 'Enter your password.');
						echo '</div>';
					}
					else {
						$stmt = $CONDB->prepare("SELECT * FROM
																										`users`
																							WHERE
																										`Email` = ?
																							LIMIT 1");
						$stmt->execute([$email]);
						$row = $stmt->fetch();
						$count = $stmt->rowCount();
						$stmt = null;
						if ($count > 0) {
							if (!password_verify($password, $row['Password'])) {
								echo '<div class="messages-in-back">';
									messages('Warning', 'Check your email and password.');
								echo '</div>';
							}
							else {
								if ($remmberMe == 1) {
									setcookie('ID', $row['ID'], time() + 60*60*24*30*12, '/');
									setcookie('NAME', $row['Name'], time() + 60*60*24*30*12, '/');
									setcookie('ACCESS', $row['UserRole'], time() + 60*60*24*30*12, '/');
									setcookie('EMAIL', $email, time() + 60*60*24*30*12, '/');
									header('Location: ./index.php');
									exit();
								}
								else {
									$_SESSION['ID'] 	 	= $row['ID'];
									$_SESSION['NAME']   = $row['Name'];
									$_SESSION['ACCESS'] = $row['UserRole'];
									$_SESSION['EMAIL']  = $email;
									if ($_SESSION['ACCESS'] == 'Admin') {
										header('Location: ./users/dashboard.php');
										exit();
									}
									else {
										if ($_SESSION['ACCESS'] == 'Client') {
											header('Location: ./main.php');
											exit();
										}
										else {
											header('Location: ./main.php');
											exit();
										}
									}
								}
							}
						}
						else {
							echo '<div class="messages-in-back">';
								messages('Warning', 'Check your email');
							echo '</div>';
						}
					}
				}
			}
		}
	}
	else {
		$email = '';
		$password = '';
	}
?>
<div class="container-form-login-signup">
	<div class="items-container">
		<h1>Login | <a href="main.php">Freelance</a></h1>
		<form action="<?php $_SERVER['PHP_SELF']?>" method="POST">
			<div class="input-field">
				<span class="icon-input"><i class="fa-solid fa-envelope"></i></span>
				<input type="email" name="email" value="<?=sanitize_data($email)?>" placeholder="Enter your email">
			</div>
			<div class="input-field">
				<span class="icon-input"><i class="fa-solid fa-lock"></i></span>
				<input id="password" type="password" name="password" value="<?=sanitize_data($password)?>" placeholder="Enter password">
				<span class="icon-show-password"><i class="fa-solid fa-eye"></i></span>
			</div>
			<div class="check">
				<input id="remmberMe" type="checkbox" name="remmberMe" value="1">
				<label for="remmberMe">Remmber Me</label>
			</div>
			<input class="button-form-login-signup-reset" id="submit-button" type="submit" value=" Login">
		</form>
		<div class="links-form">
			Create an account?
			<a href="./registration.php">Sign up</a>
		</div>
	</div>
</div>
<?php require $temp . ('end.body.php');?>
<script>loginForm();</script>
