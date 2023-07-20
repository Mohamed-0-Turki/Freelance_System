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
					if ($do == 'Manage') {
						// An array containing the types of users.
						$usersArray = ['Admin', 'Client', 'Freelancer'];
						// in_array() -> Checks if a value exists in an array.
						if (isset($_GET['users']) && in_array($_GET['users'], $usersArray)) {
							if ($_GET['users'] == 'Client') {
								$whereStmt = "`UserRole` = 'Client'";
							}
							else {
								if ($_GET['users'] == 'Freelancer') {
									$whereStmt = "`UserRole` = 'Freelancer'";
								}
								else {
									$whereStmt = "`UserRole` = 'Admin'";
								}
							}
							// SQL query [SELECT].
							$stmt = $CONDB->prepare("SELECT * FROM `users` WHERE $whereStmt");
							// execute query.
							$stmt->execute();
							// fetchAll() -> Fetches the remaining rows from a result set
							$rows = $stmt->fetchAll();
						}
						else {
							if (isset($_GET['submit'])) {
								$email = filter_var($_GET['searchUser'], FILTER_SANITIZE_EMAIL);
								$stmt = $CONDB->prepare("SELECT * FROM `users` WHERE `Email` = ?");
								$stmt->execute([$email]);
								$count = $stmt->rowCount();
								if ($count == 1) {
									$rows = $stmt->fetchAll();
								}
								else {
									messages('Warning', 'There is no email you entered.');
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
								<a href="members.php?do=Manage&userID=<?php echo $_SESSION['ID']?>">
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
								<a href="members.php?do=Add">
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
					elseif ($do == 'Add') {
						?>
						<div class="form-pages">
							<h1 class="form-name">Add New Member</h1>
							<form action="?do=Insert" method="POST">
								<div class="form-content">
									<!-- Name -->
									<div class="input-field">
										<label for="name">Name</label>
										<input type="text" name="name" placeholder="Enter Your Name" required>
									</div>
									<!-- Email -->
									<div class="input-field">
										<label for="email">Email</label>
										<input type="email" name="email" placeholder="example@example.com" required>
									</div>
									<!-- Password -->
									<div class="input-field">
										<label for="password">Password</label>
										<input id="password" type="password" name="password" placeholder="At least 8 characters."required>
										<p class="input-notes">! Password can't be empty</p>
										<p class="input-notes">! First letter of the password should be capital.</p>
										<p class="input-notes">! Password must contain a special character (~, !, @, #, $, %, &, *, +, =, |, ?).</p>
										<p class="input-notes">! Password length must be greater than 8 characters</p>
									</div>
									<!-- Confirm Password -->
									<div class="input-field">
										<label for="repassword">Confirm password</label>
										<input id="repassword" type="password" name="rePassword" placeholder="Retype the password in this field." required>
									</div>
									<!-- User Access -->
									<div class="input-field">
										<label for="userAccess">User Access</label>
										<select name="userRole" id="userAccess">
											<option value="Admin" selected>Admin</option>
											<option value="Client">Client</option>
											<option value="Freelancer">Freelancer</option>
										</select>
									</div>
									<!-- Phone Number -->
									<div class="input-field">
										<label for="phoneNumber">Phone number</label>
										<input type="tel" name="phoneNumber" id="phoneNumber" placeholder="+20">
									</div>
								</div>
								<!-- Buttons -->
								<div class="buttons">
									<input type="submit" value="Add Member">
									<input type="reset" value="Reset">
								</div>
							</form>
						</div>
						<?php
					}
					//! End Add Member Page.
					//! Start Insert Data.
					elseif ($do == 'Insert') {
						// Condition to check if request method is POST or GET.
						if ($_SERVER['REQUEST_METHOD'] == 'POST') {
							// Get Values From Inputs.
							$name 			 = htmlspecialchars($_POST['name']);
							$email 			 = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
							$password 	 = htmlspecialchars($_POST['password']);
							$rePassword	 = htmlspecialchars($_POST['rePassword']);
							$userRole 	 = htmlspecialchars($_POST['userRole']);
							$phoneNumber = filter_var($_POST['phoneNumber'], FILTER_SANITIZE_NUMBER_INT);
							// str_split() -> Convert a string to an array
							// All Upper Case Characters In Array.
							$upperCase 	 = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
							// Empty Array Will Use To Give It The Errors.
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
										if ($userRole == 'Freelancer' && empty($phoneNumber) && filter_var($phoneNumber, FILTER_VALIDATE_INT)) {
											$errors[] = 'The freelancer must enter their phone number.';
										}
										else {
											if ($userRole != 'Freelancer' || empty($phoneNumber)) {
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
										}else {
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
							// Verify That There Are No Errors In The Array.
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
										include $pages . ('loader.html');
										header('refresh:5, members.php?do=Add');
										exit();
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
										header('refresh:5;url=members.php');
										exit();
									}
									else {
										echo '<div class="messages-in-back">';
											messages('Denger', 'An error occurred, try again.');
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
					elseif ($do == 'Edit') {
						// isset() 			=> Determines if a variable is declared other than NULL.
						// is_numeric() => Finds whether a variable is a number or a numeric string
						// intval() 		=> The numerical value is converted to an integer.
						$userID = isset($_GET['userID']) && is_numeric($_GET['userID']) ? intval($_GET['userID']) : 0;
						// SQL query [SELECT].
						$stmt = $CONDB->prepare("SELECT * FROM `users` WHERE ID = ?");
						// execute query.
						$stmt->execute([$userID]);
						// Fetch Data From Database.
						$row = $stmt->fetch();
						// Returns the number of rows affected by the last SQL statement.
						$count = $stmt->rowCount();
						// Check that there is data for this ID or not.
						if ($count == 1) {
							?>
							<div class="form-pages">
								<h1 class="form-name">Update Member</h1>
								<form action="?do=Update" method="POST">
									<div class="form-content">
										<!--ID -->
										<input hidden type="text" name="id" value="<?= $row['ID']?>">
										<!-- Name -->
										<div class="input-field">
											<label for="name">Name</label>
											<input type="text" value="<?= $row['Name']; ?>" name="name" required>
										</div>
										<!-- Email -->
										<div class="input-field">
											<label for="email">Email</label>
											<input type="email" value="<?= $row['Email']; ?>" name="email" required>
										</div>
										<!-- New Password -->
										<div class="input-field">
											<label for="password">New Password</label>
											<input hidden type="password" value="<?= $row['Password']; ?>" name="oldPassword">
											<input id="password" type="password" name="newPassword" placeholder="Leave blank if you don't want to change.">
										</div>
										<!-- Confirm password -->
										<div class="input-field">
											<label for="repassword">Confirm password</label>
											<input id="repassword" type="password" name="rePassword" placeholder="Retype the password in this field.">
										</div>
										<!-- User Access -->
										<div class="input-field">
											<label for="userAccess">User Access</label>
											<select name="userRole" id="userAccess">
												<option value="Admin" <?php if($row['UserRole'] == 'Admin'){echo 'selected';} ?>>Admin</option>
												<option value="Client" <?php if($row['UserRole'] == 'Client'){echo 'selected';} ?>>Client</option>
												<option value="Freelancer" <?php if($row['UserRole'] == 'Freelancer'){echo 'selected';} ?>>Freelancer</option>
											</select>
										</div>
										<!-- Phone Number -->
										<div class="input-field">
											<label for="phoneNumber">Phone number</label>
											<input type="tel" name="phoneNumber" value="0<?= $row['PhoneNumber'] ?>" id="phoneNumber">
										</div>
									</div>
									<!-- Buttons -->
									<div class="buttons">
										<input type="submit" value="Update">
										<input type="reset" value="Reset">
									</div>
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
					elseif ($do == 'Update') {
						// Condition to check if request method is POST or GET.
						if ($_SERVER['REQUEST_METHOD'] == 'POST') {
							// Get Values From Inputs.
							$id 				 = $_POST['id'];
							$name 			 = htmlspecialchars($_POST['name']);
							$email 			 = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
							$userRole 	 = htmlspecialchars($_POST['userRole']);
							$phoneNumber = filter_var($_POST['phoneNumber'], FILTER_SANITIZE_NUMBER_INT);
							$rePassword  = htmlspecialchars($_POST['rePassword']);
							// If the new password field is empty, enter the old password in the variable.
							// If the new password field contains values, put the value of the new password into the variable.
							$newPassword = empty($_POST['newPassword']) ? $_POST['oldPassword'] : $_POST['newPassword'];
							// str_split() -> Convert a string to an array
							// All Upper Case Characters In Array.
							$upperCase 	 = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
							// Empty Array Will Use To Give It The Errors.
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
										if ($userRole == 'Freelancer' && empty($phoneNumber) && filter_var($phoneNumber, FILTER_VALIDATE_INT)) {
											$errors[] = 'The freelancer must enter their phone number.';
										}
										else {
											if ($userRole != 'Freelancer' || empty($phoneNumber)) {
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
									// in_array() -> Checks if a value exists in an array
									if (!in_array($newPassword[0], $upperCase)) {
										$errors[] = 'First letter of the password should be capital.';
									}
									else {
										// strlen() -> Get string length.
										for ($i=0; $i<strlen($newPassword); $i++) {
											// strpos() -> Find the position of the first occurrence of a substring in a string.
											// If Password Not Have Special Character The Variable $count Will Be value 0 else The Value Will Be 1.
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
											// strlen() -> Get string length.
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
							// Verify That There Are No Errors In The Array.
							if (!empty($errors)) {
								echo '<div class="messages-in-back">';
									foreach ((array)$errors as $error) {
										messages('Warning', $error);
									}
									echo '</div>';
									include $pages . ('loader.html');
									header("refresh:5, members.php?do=Edit&userID=$id");
									exit();
							}
							else {
								// SQL query [SELECT].
								$stmt = $CONDB->prepare("SELECT * FROM
																											`users`
																									WHERE
																											`Email` = ?
																										AND
																											`ID` != ?");
								// execute query.
								$stmt->execute([$email, $id]);
								// Returns the number of rows affected by the last SQL query.
								$count = $stmt->rowCount();
								// Check The Email Entered By The User If It Exists Or Not.
								if ($count == 1) {
									echo '<div class="messages-in-back">';
										messages('Warning', 'You can\'t use this email because it already exist.');
										header("refresh:5, members.php?do=Edit&userID=$id");
									echo '</div>';
									exit();
								}
								else {
									// SQL query [UPDATE].
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
									// execute query.
									$stmt->execute([$name, $email, $userRole, $phoneNumber, $newPassword, $id]);
									// Returns the number of rows affected by the last SQL query.
									$count = $stmt->rowCount();
									// Check Whether The Data Has Been Updated Or Not.
									if ($count = 1) {
										echo '<div class="messages-in-back">';
											messages('Success', 'The data has been updated successfully.');
										echo '</div>';
										include $pages . ('loader.html');
										header("refresh:5, members.php?do=Edit&userID=$id");
										exit();
									}
									else {
										echo '<div class="messages-in-back">';
											messages('Denger', 'This user cannot be updated.');
										echo '</div>';
										include $pages . ('loader.html');
										header("refresh:5, members.php?do=Edit&userID=$id");
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
							header('refresh:3, members.php');
							exit();
						}
					}
					//! End Update Data.
					//! Start Delete Data.
					elseif ($do == 'Delete') {
						// isset() 			=> Determines if a variable is declared other than NULL.
						// is_numeric() => Finds whether a variable is a number or a numeric string.
						// intval() 		=> The numerical value is converted to an integer.
						$userID = isset($_GET['userID']) && is_numeric($_GET['userID']) ? intval($_GET['userID']) : 0;
						// SQL query [SELECT].
						$stmt = $CONDB->prepare("SELECT * FROM `users` WHERE ID = ?");
						// execute query.
						$stmt->execute([$userID]);
						// Returns the number of rows affected by the last SQL query.
						$count = $stmt->rowCount();
						// Check The ID Is Exist Or Not.
						if ($count == 1) {
							// SQL query [DELETE].
							$stmt = $CONDB->prepare("DELETE FROM `users` WHERE ID = ?");
							// execute query.
							$stmt->execute([$userID]);
							// Returns the number of rows affected by the last SQL statement.
							$count = $stmt->rowCount();
							// Check Whether The Data Has Been Deleted Or Not.
							if ($count == 1) {
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
?>