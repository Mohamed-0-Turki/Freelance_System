<?php
	session_start();
	$pageTitle = 'Home';
	require('initialize.php');
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$name 	 = htmlspecialchars($_POST['name']);
		$email 	 = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		$subject = htmlspecialchars($_POST['subject']);
		$message = htmlspecialchars($_POST['message']);
		$errors  = [];
		if (empty($message)) {
			$errors[] = 'You must enter a message.';
		}
		else {
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$errors[] = 'You must enter valid email.';
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
			$stmt = $CONDB->prepare("INSERT INTO `feedback` (`Name`, `Email`, `Subject`, `Message`, `FeedbackDate`) VALUES (?, ?, ?, ?, now())");
			$stmt->execute([$name, $email, $subject, $message]);
			$count = $stmt->rowCount();
			if ($count = 1) {
				echo '<div class="messages-in-back">';
					messages('Success', 'We have received your message. We will contact you soon via email.');
				echo '</div>';
			}
			else {
				echo '<div class="messages-in-back">';
					messages('Denger', 'Please resend the message again.');
				echo '</div>';
			}
		}
	}
	else {
		$name 	 = '';
		$email 	 = '';
		$subject = '';
		$message = '';
	}
?>
<div class="body">
	<?php require $temp . ('navbar.php');?>
	<section class="container">
		<div id="home" class="all-sections">
			<section class="welcome-section">
				<div class="welcome-text">
					<h1>Welcome,</h1>
					<p>If you landed on this page, chances are you 
						are considering taking the leap into freelancing. 
						Welcome to the world of the entrepreneur! Whether 
						you're motivated by a dream to work for yourself 
						or a desire for more flexibility, it's worth 
						remembering that a freelance life comes with great 
						perks and some challenges.</p>
				</div>
			</section>
			<section id="features" class="features-section">
				<h2>features</h2>
				<div class="container">
					<div class="feature">
						<span class="icon">
							<i class="fa-solid fa-user-tie"></i>
						</span>
						<h3>Client</h3>
						<p>Freelancing is a type of self-employment. Instead 
							of being employed by a company, freelancers tend 
							to work as self-employed, delivering their 
							services on a contract or project basis.</p>
					</div>
					<div class="feature">
						<span class="icon">
							<i class="fa-solid fa-users"></i>
						</span>
						<h3>Freelancer</h3>
						<p>Companies of all types and sizes can hire freelancers 
							to complete a project or a task, but freelancers 
							are responsible for paying their own taxes, 
							health insurance, pension and other personal 
							contributions.</p>
					</div>
				</div>
			</section>
			<section id="about" class="aboutUs-section">
				<h2>About Us</h2>
				<div class="container">
					<div class="ask">
						<h4>Looking for a job?</h4>
						<p>If you are searching for a new career opportunity,
							you can search open vacancies and jobs.You can also
							<a href="registration.php">sign up</a> 
							here to be alerted of new jobs by email.</p>
					</div>
					<div class="ask">
						<h4>Are you a recruiter or employer?</h4>
						<p>If you are searching for a new career opportunity,
							you can search open vacancies and jobs.You can also
							<a href="registration.php">sign up</a> 
							here to be alerted of new jobs by email.</p>
					</div>
					<div class="ask">
						<h4>Other question?</h4>
						<p>If you have any another question, please contact us.</p>
					</div>
				</div>
			</section>
			<section id="Contact" class="contactUs-section">
				<div class="form-pages">
					<h2>Contact Us</h2>
					<form action="<?php $_SERVER['PHP_SELF']?>" method="POST">
						<div class="form-content">
							<div class="input-field">
								<label for="name">Name</label>
								<input type="text" id="name" name="name" value="<?=htmlspecialchars($name)?>" placeholder="Enter Your Name">
							</div>
							<div class="input-field">
								<label for="email">Email</label>
								<input type="email" id="email" name="email" value="<?=htmlspecialchars($email)?>" placeholder="example@example.com">
							</div>
							<div class="input-field">
								<label for="subject">Subject</label>
								<input type="text" id="subject" value="<?=htmlspecialchars($subject)?>" name="subject">
							</div>
							<div class="input-field">
								<label for="message">Message</label>
								<textarea name="message" id="message" value="<?=htmlspecialchars($message)?>" rows="5" required></textarea>
							</div>
						</div>
						<div class="buttons">
							<input type="submit" value="Send">
							<input type="reset" value="Reset">
						</div>
					</form>
				</div>
			</section>
		</div>
	</section>
	<?php require $temp . ('footer.php');?>
</div>
<?php require $temp . ('end.body.php');?>