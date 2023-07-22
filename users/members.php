<?php
	session_start();
	$pageTitle = 'Memebers';
	require ('initialize.php');
?>
	<div class="body">
		<?php require $temp . ('navbar.php');?>
		<section class="container">
			<?php
				if (isset($_SESSION['NAME'])) {
					$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
					//! Start Manage Page.
					if ($do === 'Manage') {
						$usersArray = ['Admin', 'Client', 'Freelancer'];
						if (isset($_GET['users']) && in_array($_GET['users'], $usersArray)) {
							if ($_GET['users'] === 'Client') {
								$whereStmt = "`UserRole` = 'Client'";
							}
							else {
								if ($_GET['users'] === 'Freelancer') {
									$whereStmt = "`UserRole` = 'Freelancer'";
								}
								else {
									$whereStmt = "`UserRole` = 'Admin'";
								}
							}
							$stmt = $CONDB->prepare("SELECT * FROM `users` WHERE $whereStmt");
							$stmt->execute();
							$rows = $stmt->fetchAll();
						}
						else {
							if (isset($_GET['submit'])) {
								$email = filter_var($_GET['searchUser'], FILTER_SANITIZE_EMAIL);
								$stmt = $CONDB->prepare("SELECT * FROM `users` WHERE `Email` LIKE '%' ? '%'");
								$stmt->execute([$email]);
								$count = $stmt->rowCount();
								if ($count > 0) {
									$rows = $stmt->fetchAll();
								}
								else {
									echo '<div class="messages-in-back">';
										messages('Warning', 'There is no email you entered.');
									echo '</div>';
									$stmt = $CONDB->prepare("SELECT * FROM `users`");
									$stmt->execute();
									$rows = $stmt->fetchAll();
								}
							}
							else {
								$stmt = $CONDB->prepare("SELECT * FROM `users`");
								$stmt->execute();
								$rows = $stmt->fetchAll();
							}
						}
						?>
						<div class="name-of-page">
							<h1>Memebers</h1>
						</div>
						<!--Box All Users -->
						<div class="details">
							<div class="box">
								<a href="./members.php?do=Manage&userID=<?=$_SESSION['ID']?>">
									<span class="icon"><i class="fa-solid fa-users"></i></span>
									<p class="num"><?php counter('users', "");?></p>
									<p class="box-name">All Users</p>
								</a>
							</div>
							<!--Box Admin -->
							<div class="box">
								<a href="?users=Admin">
									<span class="icon"><i class="fa-solid fa-user-secret"></i></span>
									<p class="num"><?php counter('users', "WHERE `userRole` = 'Admin'"); ?></p>
									<p class="box-name">Admin</p>
								</a>
							</div>
							<!--Box Client -->
							<div class="box">
								<a href="?users=Client">
									<span class="icon"><i class="fa-solid fa-user-tie"></i></span>
									<p class="num"><?php counter('users', "WHERE `userRole` = 'Client'"); ?></p>
									<p  class="box-name">Client</p>
								</a>
							</div>
							<!--Box Freelancer -->
							<div class="box">
								<a href="?users=Freelancer">
									<span class="icon"><i class="fa-solid fa-user"></i></span>
									<p class="num"><?php counter('users', "WHERE `userRole` = 'Freelancer'"); ?></p>
									<p class="box-name">Freelancer</p>
								</a>
							</div>
							<!--Box Add New Member -->
							<div class="box">
								<a href="./members.php?do=Add">
									<span class="icon"><i class="fa-solid fa-user-plus"></i></span>
									<p class="box-name">Add New Member</p>
								</a>
							</div>
						</div>
						<!-- Search Bar -->
						<div class="search-bar">
							<form action="<?php $_SERVER['PHP_SELF']?>" method="GET">
								<i class="search-icon fa-solid fa-magnifying-glass"></i>
								<input type="search" name="searchUser" placeholder="Enter email of any member...">
								<input type="submit" name="submit" value="Search">
							</form>
						</div>
						<!-- The table contains member data. -->
						<div class="table-content">
							<table>
								<thead>
									<tr>
										<th>ID</th>
										<th>Name</th>
										<th>Email</th>
										<th>User Role</th>
										<th>Phone Number</th>
										<th>Controle</th>
									</tr>
								</thead>
								<tbody>
									<?php
										foreach ($rows as $row) {
											echo '<tr>';
												echo '<td>' . $row['ID'] 		   . '</td>';
												echo '<td>' . $row['Name']     . '</td>';
												echo '<td>' . $row['Email']    . '</td>';
												echo '<td>' . $row['UserRole'] . '</td>';
												if($row['UserRole'] == 'Freelancer') {
													echo '<td>+20' . $row['PhoneNumber'] . '</td>';
												}
												else {
													echo '<td>NULL</td>';
												}
												echo '<td> 
																<div class="table-btn">
																	<a href="members.php?do=Edit&userID='.$row['ID'].'" class="edit-btn">Edit</a>
																	<a href="members.php?do=Delete&userID='.$row['ID'].'" class="del-btn">Delete</a>
																</div>
															</td>';
											echo '</tr>';
										}
									?>
								</tbody>
							</table>
						</div>
						<?php
					}
					//! End Manage Page.
					//! Start Add Member Page.
					elseif ($do === 'Add') {
						?>
					<div class="container-all-forms">
						<div class="items-container">
						<h1>Add New Member</h1>
							<form action="?do=Insert" method="POST">
									<div class="input-field">
										<span class="icon-input"><i class="fa fa-solid fa-user"></i></span>
										<input type="text" name="name" placeholder="Enter your name">
									</div>
									<div class="input-field">
										<span class="icon-input"><i class="fa fa-solid fa-envelope"></i></span>
										<input type="email" name="email" placeholder="Enter your email">
									</div>
									<div class="input-field">
										<span class="icon-input"><i class="fa fa-solid fa-lock"></i></span>
										<input id="password" type="password" name="password" placeholder="Enter password">
										<span class="icon-show-password"><i class="fa-solid fa-eye"></i></span>
									</div>
									<div class="notes">
										<p>! first capital letter.</p>
										<p>! special character(~!@#$%&*+=|?).</p>
										<p>! 8 characters At Least.</p>
									</div>
									<div class="input-field">
										<span class="icon-input"><i class="fa fa-solid fa-lock"></i></span>
										<input id="rePassword" type="password" name="rePassword" placeholder="Confirm password">
									</div>
									<div class="check">
										<label class="radio">
											<input type="radio" class="radio-value" name="userRole" value="Admin" checked>
											Admin
										</label>
										<label class="radio">
											<input type="radio" class="radio-value" name="userRole" value="Client">
											Client
										</label>
										<label class="radio">
											<input type="radio" class="radio-value" name="userRole" value="Freelancer">
											Freelancer
										</label>
									</div>
									<div class="input-field" id="field-phoneNumber">
										<span class="icon-input"><i class="fa fa-solid fa-phone"></i></span>
										<input type="tel" name="phoneNumber" placeholder="+20 Phone Number">
									</div>
									<input class="button-submit-reset" id="submit-button" type="submit" value="Add Member">
									<input class="button-submit-reset" type="reset" value="Reset">
							</form>
						</div>
						<?php
					}
					//! End Add Member Page.
					//! Start Insert Data.
					elseif ($do == 'Insert') {
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							$name 			 = htmlspecialchars($_POST['name']);
							$email 			 = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
							$password 	 = htmlspecialchars($_POST['password']);
							$rePassword	 = htmlspecialchars($_POST['rePassword']);
							$userRole 	 = htmlspecialchars($_POST['userRole']);
							$phoneNumber = filter_var($_POST['phoneNumber'], FILTER_SANITIZE_NUMBER_INT);
							$upperCase 	 = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
							$errors 		 = [];
							if (empty($name) && empty($email)) {
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
											if ($userRole !== 'Freelancer' || empty($phoneNumber)) {
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
										}else {
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
								include $pages . ('loader.html');
								header('refresh:5, members.php?do=Add');
								exit();
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
								if ($count === 1) {
									echo '<div class="messages-in-back">';
										messages('Warning', 'You can\'t use this email because it already exist.');
									echo '</div>';
									include $pages . ('loader.html');
									header('refresh:5, members.php?do=Add');
									exit();
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
										include $pages . ('loader.html');
										header('refresh:5;url=members.php');
										exit();
									}
									else {
										echo '<div class="messages-in-back">';
											messages('Denger', 'An error occurred, try again. You will be directed to the add members page.');
										echo '</div>';
										include $pages . ('loader.html');
										header('refresh:5, members.php?do=Add');
										exit();
									}
								}
							}
						}
						else {
							echo '<div class="messages-in-back">';
								messages('Denger', 'You Can\'t Access This Page Directly.');
							echo '</div>';
							include $pages . ('loader.html');
							header('refresh:5, members.php?do=Add');
							exit();
						}
					}
					//! End Insert Data.
					//! Start Edit Data.
					elseif ($do === 'Edit') {
						$userID = isset($_GET['userID']) && is_numeric($_GET['userID']) ? intval($_GET['userID']) : 0;
						$stmt = $CONDB->prepare("SELECT * FROM `users` WHERE ID = ?");
						$stmt->execute([$userID]);
						$row = $stmt->fetch();
						$count = $stmt->rowCount();
						$stmt = null;
						if ($count === 1) {
							?>
								<div class="container-all-forms">
									<div class="items-container">
										<h1>Update Member</h1>
										<form action="?do=Update&userID=<?=$_GET['userID']?>" method="POST">
											<div class="input-field">
												<span class="icon-input"><i class="fa fa-solid fa-user"></i></span>
												<input type="text" name="name" value="<?=$row['Name'];?>" placeholder="Enter your name">
											</div>
											<div class="input-field">
												<span class="icon-input"><i class="fa fa-solid fa-envelope"></i></span>
												<input type="email" name="email" value="<?=$row['Email'];?>" placeholder="Enter your email">
											</div>
											<div class="input-field">
												<span class="icon-input"><i class="fa fa-solid fa-lock"></i></span>
												<input hidden type="password" value="<?=$row['Password'];?>" name="oldPassword">
												<input id="password" type="password" name="newPassword" placeholder="Leave blank if you don't want to change.">
												<span class="icon-show-password"><i class="fa-solid fa-eye"></i></span>
											</div>
											<div class="input-field">
												<span class="icon-input"><i class="fa fa-solid fa-lock"></i></span>
												<input id="rePassword" type="password" name="rePassword" placeholder="Confirm password">
											</div>
											<div class="check">
												<label class="radio">
													<input type="radio" class="radio-value" name="userRole" value="Admin" <?php if($row['UserRole'] === 'Admin'){echo 'checked';}?>>
													Admin
												</label>
												<label class="radio">
													<input type="radio" class="radio-value" name="userRole" value="Client" <?php if($row['UserRole'] === 'Client'){echo 'checked';} ?>>
													Client
												</label>
												<label class="radio">
													<input type="radio" class="radio-value" name="userRole" value="Freelancer" <?php if($row['UserRole'] === 'Freelancer'){echo 'checked';} ?>>
													Freelancer
												</label>
											</div>
											<div class="input-field" id="field-phoneNumber">
												<span class="icon-input"><i class="fa fa-solid fa-phone"></i></span>
												<input type="tel" name="phoneNumber" placeholder="+20 Phone Number" value="0<?= $row['PhoneNumber']?>">
											</div>
											<input class="button-submit-reset" id="submit-button" type="submit" value="Update Member">
											<input class="button-submit-reset" type="reset" value="Reset">
										</form>
									</div>
							<?php
						}
						else {
							echo '<div class="messages-in-back">';
								messages('Warning', 'There is no data for this user.');
							echo '</div>';
							include $pages . ('loader.html');
							header('refresh:0.00000005,members.php?do=Manage&userID='.$_SESSION["ID"].'');
							exit();
						}
					}
					//! End Edit Data.
					//! Start Update Data.
					elseif ($do === 'Update') {
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {
							$id 				 = $_GET['userID'];
							$name 			 = htmlspecialchars($_POST['name']);
							$email 			 = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
							$userRole 	 = htmlspecialchars($_POST['userRole']);
							$phoneNumber = filter_var($_POST['phoneNumber'], FILTER_SANITIZE_NUMBER_INT);
							$rePassword  = htmlspecialchars($_POST['rePassword']);
							$newPassword = empty($_POST['newPassword']) ? $_POST['oldPassword'] : $_POST['newPassword'];
							$upperCase 	 = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
							$errors 		 = [];
							if (empty($name) && empty($email)) {
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
											if ($userRole !== 'Freelancer' || empty($phoneNumber)) {
												$phoneNumber = null;
											}
										}
									}
								}
							}
							if (empty($newPassword)) {
								$errors[] = 'Password can\'t be empty';
							}
							else {
								if ($newPassword != $_POST['oldPassword']) {
									if (!in_array($newPassword[0], $upperCase)) {
										$errors[] = 'First letter of the password should be capital.';
									}
									else {
										for ($i=0; $i<strlen($newPassword); $i++) {
											if (!strpos('~!@#$%&*+=|?', $newPassword[$i]) !== false) {
												$count = 0;
											}else {
												$count = 1;
												break;
											}
										}
										if ($count == 0) {
											$errors[] = 'Password must contain a special character (~, !, @, #, $, %, &, *, +, =, |, ?).';
										}
										else {
											if (strlen($newPassword) < 8) {
												$errors[] = 'Password length must be greater than 8 characters';
											}
											else {
												if ($rePassword !== $_POST['newPassword']) {
													$errors[] = 'Incorrect password confirmation';
												}
												else {
													$newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
												}
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
								include $pages . ('loader.html');
								header("refresh:5, ./members.php?do=Edit&userID=$id");
								exit();
							}
							else {
								$stmt = $CONDB->prepare("SELECT * FROM
																											`users`
																									WHERE
																											`Email` = ?
																										AND
																											`ID` != ?");
								$stmt->execute([$email, $id]);
								$count = $stmt->rowCount();
								$stmt = null;
								if ($count === 1) {
									echo '<div class="messages-in-back">';
										messages('Warning', 'You can\'t use this email because it already exist.');
									echo '</div>';
									header("refresh:5, members.php?do=Edit&userID=$id");
									exit();
								}
								else {
									$stmt = $CONDB->prepare("UPDATE
																									`users`
																							SET
																									`Name` = ?,
																									`Email` = ?,
																									`UserRole` = ?,
																									`PhoneNumber` = ?,
																									`Password` = ?
																						WHERE
																									`ID` = ?");
									$stmt->execute([$name, $email, $userRole, $phoneNumber, $newPassword, $id]);
									$count = $stmt->rowCount();
									$stmt = null;
									if ($count == 1) {
										echo '<div class="messages-in-back">';
											messages('Success', 'The data has been updated successfully.');
										echo '</div>';
										include $pages . ('loader.html');
										header("refresh:5, ./members.php?do=Edit&userID=$id");
										exit();
									}
									else {
										$upperCase 	 = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
										echo '<div class="messages-in-back">';
											messages('Denger', 'This user cannot be updated.');
										echo '</div>';
										include $pages . ('loader.html');
										header("refresh:5, ./members.php?do=Edit&userID=$id");
										exit();
									}
								}
							}
						}
						else {
							echo '<div class="messages-in-back">';
								messages('Denger', 'You Can\'t Access This Page Directly.');
							echo '</div>';
							include $pages . ('loader.html');
							header('refresh:3, ./members.php');
							exit();
						}
					}
					//! End Update Data.
					//! Start Delete Data.
					elseif ($do === 'Delete') {
						$userID = isset($_GET['userID']) && is_numeric($_GET['userID']) ? intval($_GET['userID']) : 0;
						$stmt = $CONDB->prepare("SELECT * FROM `users` WHERE ID = ?");
						$stmt->execute([$userID]);
						$count = $stmt->rowCount();
						$stmt = null;
						if ($count === 1) {
							$stmt = $CONDB->prepare("DELETE FROM `users` WHERE ID = ?");
							$stmt->execute([$userID]);
							$count = $stmt->rowCount();
							$stmt = null;
							if ($count === 1) {
								echo '<div class="messages-in-back">';
									messages('Success', 'You have now deleted the user.');
									echo '</div>';
								include $pages . ('loader.html');
								header('refresh:0.00000005,members.php?do=Manage&userID='.$_SESSION["ID"].'');
								exit();
							}
							else {
								echo '<div class="messages-in-back">';
									messages('Denger', 'This user cannot be deleted.');
								echo '</div>';
								include $pages . ('loader.html');
								header('refresh:5,members.php?do=Manage&userID='.$_SESSION["ID"].'');
								exit();
							}
						}
						else {
							echo '<div class="messages-in-back">';
								messages('Denger', 'THIS ID IS NOT EXIST.');
							echo '</div>';
							include $pages . ('loader.html');
							header('refresh:5,members.php?do=Manage&userID='.$_SESSION["ID"].'');
							exit();
						}
					}
					//! End Delete Data.
				}
				else {
					echo '<div class="messages-in-back">';
						messages('Denger', 'You can\'t access this page.It will be taken back to the login page after 5 seconds.');
					echo '</div>';
					include $pages . ('loader.html');
					header('refresh:5,../logout.php');
					exit();
				}
			?>
		</section>
		<?php require $temp . ('footer.php');?>
	</div>
<?php
	require $temp . ('end.body.php');
	if ($do === 'Add') {
		echo '<script>addNewMember();</script>';
	}
	if ($do === 'Edit') {
		echo '<script>editMember();</script>';
	}
?>
