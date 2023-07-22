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
						$memberID = $_SESSION['ID'];
						if (isset($_GET['AccessControl']) && $_SESSION['ACCESS'] == 'Admin') {
							$stmt = $CONDB->prepare("SELECT
																							`jobs`.*,
																							`users`.`Name`,
																							`categories`.`CategoryName`
																				FROM
																							`jobs`
																			INNER JOIN
																							`categories`
																					ON
																							`categories`.`CategoryID` = `jobs`.`CatID`
																			INNER JOIN
																							`users`
																					ON
																							`users`.`ID` = `jobs`.`MemberID`");
							$stmt->execute();
							$rows = $stmt->fetchAll();
						}
						else {
							$sortArray = ['ASC', 'DESC'];
							if (isset($_GET['sort']) && in_array($_GET['sort'], $sortArray)) {
								$sort = $_GET['sort'];
								$stmt = $CONDB->prepare("SELECT
																								`jobs`.*,
																								`categories`.`CategoryName`
																						FROM
																								`jobs`
																			INNER JOIN
																								`categories`
																							ON
																								`categories`.`CategoryID` = `jobs`.`CatID`
																					WHERE
																								`MemberID` = ?
																				ORDER BY
																								`JobDate` $sort");
								$stmt->execute([$memberID]);
								$rows = $stmt->fetchAll();
							}
							else {
								if (isset($_GET['submit'])) {
									$job = $_GET['searchJob'];
									if ($_SESSION['ACCESS'] === 'Admin') {
										$andStmt = null;
									}
									else {
										$andStmt = 'AND `MemberID` = ?';
									}
									$stmt = $CONDB->prepare("SELECT
																									`jobs`.*,
																									`categories`.`CategoryName`
																							FROM
																									`jobs`
																				INNER JOIN
																									`categories`
																								ON
																									`categories`.`CategoryID` = `jobs`.`CatID`
																							WHERE
																									`JobTitle` LIKE '%' ? '%'
																									$andStmt
																					ORDER BY
																									`JobDate`
																							ASC");
									if ($_SESSION['ACCESS'] === 'Admin') {
										$stmt->execute([$job]);
									}
									else {
										$stmt->execute([$job, $memberID]);
									}
									$count = $stmt->rowCount();
									if ($count === 1) {
										$rows = $stmt->fetchAll();
									}
									else {
										echo '<div class="messages-in-back">';
											messages('Warning', 'There is no job with this title.');
										echo '</div>';
										$stmt = $CONDB->prepare("SELECT
																										`jobs`.*,
																										`categories`.`CategoryName`
																								FROM
																										`jobs`
																					INNER JOIN
																										`categories`
																									ON
																										`categories`.`CategoryID` = `jobs`.`CatID`
																							WHERE
																										`MemberID` = ?
																						ORDER BY
																										`JobDate`
																								ASC");
										$stmt->execute([$memberID]);
										$rows = $stmt->fetchAll();
									}
								}
								else {
									$stmt = $CONDB->prepare("SELECT
																									`jobs`.*,
																									`categories`.`CategoryName`
																							FROM
																									`jobs`
																				INNER JOIN
																									`categories`
																								ON
																									`categories`.`CategoryID` = `jobs`.`CatID`
																							WHERE
																									`MemberID` = ?
																					ORDER BY
																									`JobDate`
																							ASC");
									$stmt->execute([$memberID]);
									$rows = $stmt->fetchAll();
								}
							}
						}
						?>
						<div class="name-of-page">
							<h1>Jobs</h1>
						</div>
						<div class="details">
							<?php
								if ($_SESSION['ACCESS'] === 'Admin') {
									?>
										<div class="box">
											<a href="./jobs.php?do=Manage&AccessControl=AllJobs">
												<p class="num"><?php counter('jobs', "");?></p>
												<p class="box-name">All Jobs</p>
											</a>
										</div>
									<?php
								}
							?>
							<div class="box">
								<a href="./jobs.php">
									<p class="num"><?php counter('jobs', "WHERE MemberID = $memberID");?></p>
									<p class="box-name">My Jobs</p>
								</a>
							</div>
							<div class="box">
								<a href="./jobs.php?do=Manage&sort=ASC">
									<span class="icon"><i class="fa-solid fa-sort"></i></span>
									<p class="box-name">Sort by oldest</p>
								</a>
							</div>
							<div class="box">
								<a href="./jobs.php?do=Manage&sort=DESC">
									<span class="icon"><i class="fa-solid fa-sort"></i></span>
									<p class="box-name">Sort by latest</p>
								</a>
							</div>
							<div class="box">
								<a href="./jobs.php?do=Add">
									<span class="icon"><i class="fa-solid fa-briefcase"></i></span>
									<p class="box-name">Add New job</p>
								</a>
							</div>
						</div>
						<div class="search-bar">
							<form action="<?php $_SERVER['PHP_SELF']?>" method="GET">
								<i class="search-icon fa-solid fa-magnifying-glass"></i>
								<input type="search" name="searchJob" placeholder="Enter title of job...">
								<input type="submit" name="submit" value="Search">
							</form>
						</div>
						<div class="table-content">
							<table>
								<thead>
									<tr>
										<th>ID</th>
										<th>Title</th>
										<th>Salary</th>
										<?php
											if (isset($_GET['AccessControl']) && $_SESSION['ACCESS'] === 'Admin') {
												echo '<th>Client</th>';
											}
										?>
										<th>Category</th>
										<th>Date</th>
										<th>Controle</th>
									</tr>
								</thead>
								<tbody>
									<?php
										foreach ($rows as $row) {
											echo '<tr>';
												echo '<td>' . $row['JobID'] . '</td>';
												echo '<td>' . $row['JobTitle'] . '</td>';
												echo '<td>' . $row['JobSalary'] . '</td>';
												if (isset($_GET['AccessControl']) && $_SESSION['ACCESS'] === 'Admin') {
													echo '<td>' . $row['Name'] . '</td>';
												}
												echo '<td>' . $row['CategoryName'] . '</td>';
												echo '<td>' . $row['JobDate'] . '</td>';
												echo '<td> 
																<div class="table-btn">
																	<a href="./jobs.php?do=Edit&jobID='.$row['JobID'].'" class="edit-btn">Edit</a>
																	<a href="./jobs.php?do=Delete&jobID='.$row['JobID'].'" class="del-btn">Delete</a>
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
								<h1>Add New Job</h1>
								<form action="?do=Insert" method="POST">
									<div class="input-field">
										<span class="icon-input"><i class="fa-solid fa-user-doctor"></i></span>
										<input type="text" name="jobTitle" placeholder="Enter Title Of The Job">
									</div>
									<div class="input-field">
										<span class="icon-input"><i class="fa-solid fa-hand-holding-dollar"></i></span>
										<input id="jobSalary" type="number" name="jobSalary" placeholder="500$">
									</div>
									<div class="input-field textarea-field">
										<span class="icon-input"><i class="fa-solid fa-file-pen"></i></span>
										<textarea name="jobDescription" id="jobDescription" rows="5" placeholder="Enter Description Of Job">Enter Description Of Job</textarea>
									</div>
									<div class="input-field textarea-field">
										<span class="icon-input"><i class="fa-solid fa-wand-magic-sparkles"></i></span>
										<textarea name="jobSkills" id="jobSkills" rows="5" placeholder="Skill - Skill - Skill.">Enter the skills required for the job</textarea>
									</div>
									<div class="notes">
										<p>! Please put hyphen (-) between each skill.</p>
									</div>
									<div class="input-field">
										<span class="icon-input"><i class="fa-solid fa-magnifying-glass-chart"></i></span>
										<select name="jobClassification">
										<?php
											$stmt = $CONDB->prepare("SELECT `CategoryID`, `CategoryName` FROM `categories`");
											$stmt->execute();
											$rows = $stmt->fetchAll();
											foreach ($rows as $row) {
												$x = $row['CategoryName'] == 'Other' ? 'selected' : null ;
												echo '<option value="'.$row['CategoryID'].'" '.$x.'>'.$row['CategoryName'].'</option>';
											}
										?>
										</select>
									</div>
									<input class="button-submit-reset" id="submit-button" type="submit" value="Add Job">
									<input class="button-submit-reset" type="reset" value="Reset">
								</form>
							</div>
						</div>
					<?php
					}
					//! End Add Member Page.

					//! Start Insert Data.
					elseif ($do === 'Insert') {
						if ($_SERVER['REQUEST_METHOD'] == 'POST') {
							$memberID 	 = filter_var($_SESSION['ID'], FILTER_VALIDATE_INT);
							$title 			 = htmlspecialchars($_POST['jobTitle']);
							$salary 		 = filter_var($_POST['jobSalary'], FILTER_SANITIZE_NUMBER_INT);
							$description = htmlspecialchars($_POST['jobDescription']);
							$skill 			 = htmlspecialchars($_POST['jobSkills']);
							$jobCategory = htmlspecialchars($_POST['jobClassification']);
							$errors = [];
							if (empty($title) && empty($salary) && empty($description) && empty($skill)) {
								$errors[] = 'All fields are required.';
							}
							else {
								if (empty($title)) {
									$errors[] = 'Job title is required.';
								}
								else {
									if (empty($description)) {
										$errors[] = 'Job description is required.';
									}
									else {
											if (empty($skill)) {
												$errors[] = 'Job skill is required.';
											}
											else {
												if (empty($salary)) {
													$errors[] = 'Job salary is required.';
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
									header('refresh:5, ./jobs.php?do=Add');
									exit();
							}
							else {
								$stmt = $CONDB->prepare("INSERT INTO
																										`jobs` (
																														`JobTitle`,
																														`JobSalary`,
																														`JobDescription`,
																														`JobSkills`,
																														`CatID`,
																														`MemberID`,
																														`JobDate`)
																							VALUES
																										(?, ?, ?, ?, ?, ?, now())");
								$stmt->execute([$title, $salary, $description, $skill, $jobCategory, $memberID]);
								$count = $stmt->rowCount();
								if ($count == 1) {
									echo '<div class="messages-in-back">';
										messages('Success', 'The job has been added. You will be directed to the jobs page.');
									echo '</div>';
									include $pages . ('loader.html');
									header('refresh:5, ./jobs.php');
									exit();
								}
								else {
									echo '<div class="messages-in-back">';
										messages('Denger', 'An error occurred, try again.');
									echo '</div>';
									include $pages . ('loader.html');
									header('refresh:5, ./jobs.php?do=Add');
									exit();
								}
							}
						}
						else {
							echo '<div class="messages-in-back">';
								messages('Denger', 'You Can\'t Access This Page Directly.');
							echo '</div>';
							include $pages . ('loader.html');
							header('refresh:5, ./jobs.php?do=Manage');
						}
					}
					//! End Insert Data.

					//! Start Edit Data.
					elseif ($do === 'Edit') {
						$jobID = isset($_GET['jobID']) && is_numeric($_GET['jobID']) ? intval($_GET['jobID']) : 0;
						if ($_SESSION['ACCESS'] == 'Admin') {
							$stmt = $CONDB->prepare("SELECT * FROM `jobs` WHERE `JobID` = ?");
							$stmt->execute([$jobID]);
						}
						else {
							$stmt = $CONDB->prepare("SELECT * FROM `jobs` WHERE `JobID` = ? AND `MemberID` = ?");
							$stmt->execute([$jobID, $_SESSION['ID']]);
						}
						$row = $stmt->fetch();
						$count = $stmt->rowCount();
						if ($count === 1) {
							?>
							<div class="container-all-forms">
								<div class="items-container">
									<h1>Edit Job</h1>
									<form action="?do=Update&jobID=<?=$_GET['jobID']?>" method="POST">
										<input hidden type="text" name="memberID" value="<?= $row['MemberID']?>">
										<div class="input-field">
											<span class="icon-input"><i class="fa-solid fa-user-doctor"></i></span>
											<input type="text" name="jobTitle" value="<?= $row['JobTitle']?>" placeholder="Enter Title Of The Job">
										</div>
										<div class="input-field">
											<span class="icon-input"><i class="fa-solid fa-hand-holding-dollar"></i></span>
											<input id="jobSalary" type="number" name="jobSalary" value="<?= $row['JobSalary']?>" placeholder="500$">
										</div>
										<div class="input-field textarea-field">
											<span class="icon-input"><i class="fa-solid fa-file-pen"></i></span>
											<textarea name="jobDescription" id="jobDescription" rows="5" placeholder="Enter Description Of Job"><?=$row['JobDescription'];?></textarea>
										</div>
										<div class="input-field textarea-field">
											<span class="icon-input"><i class="fa-solid fa-wand-magic-sparkles"></i></span>
											<textarea name="jobSkills" id="jobSkills" rows="5" placeholder="Skill - Skill - Skill."><?=$row['JobSkills'];?></textarea>
										</div>
										<div class="notes">
											<p>! Please put hyphen (-) between each skill.</p>
										</div>
										<div class="input-field">
											<span class="icon-input"><i class="fa-solid fa-magnifying-glass-chart"></i></span>
											<select name="jobClassification">
											<?php
													$stmt = $CONDB->prepare("SELECT `CategoryID`, `CategoryName` FROM `categories`");
													$stmt->execute();
													$rows2 = $stmt->fetchAll();
													foreach ($rows2 as $row2) {
														$x = $row2['CategoryID'] === $row['CatID'] ? 'selected' : null ;
														echo '<option value="'.$row2['CategoryID'].'" '.$x.'>'.$row2['CategoryName'].'</option>';
													}
												?>
											</select>
										</div>
										<input class="button-submit-reset" id="submit-button" type="submit" value="Update Job">
										<input class="button-submit-reset" type="reset" value="Reset">
									</form>
								</div>
							</div>
							<?php
						}
						else {
							echo '<div class="messages-in-back">';
								messages('Warning', 'There is no data for this Job.');
							echo '</div>';
							include $pages . ('loader.html');
							header("refresh:5, ./jobs.php?do=Manade.php");
							exit();
						}
					}
					//! End Edit Data.
					//! Start Update Data.
					elseif ($do === 'Update') {
						if ($_SERVER['REQUEST_METHOD'] == 'POST') {
							$jobID			 = filter_var($_GET['jobID'], FILTER_SANITIZE_NUMBER_INT);
							$title 			 = htmlspecialchars($_POST['jobTitle']);
							$salary 		 = filter_var($_POST['jobSalary'], FILTER_SANITIZE_NUMBER_INT);
							$description = htmlspecialchars($_POST['jobDescription']);
							$skill 			 = htmlspecialchars($_POST['jobSkills']);
							$jobCategory = htmlspecialchars($_POST['jobClassification']);
							$errors = [];
							if (empty($title) && empty($salary) && empty($description) && empty($skill)) {
								$errors[] = 'All fields are required.';
							}
							else {
								if (empty($title)) {
									$errors[] = 'Job title is required.';
								}
								else {
									if (empty($description)) {
										$errors[] = 'Job description is required.';
									}
									else {
											if (empty($skill)) {
												$errors[] = 'Job skill is required.';
											}
											else {
												if (empty($salary)) {
													$errors[] = 'Job salary is required.';
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
									header("refresh:5, ./jobs.php?do=Edit&jobID=$jobID");
									exit();
							}
							else {
								$stmt = $CONDB->prepare("UPDATE
																								`jobs`
																						SET
																								`JobTitle` = ?,
																								`JobSalary` = ?,
																								`JobDescription` = ?,
																								`JobSkills` = ?,
																								`CatID` = ?
																					WHERE
																								`JobID` = ?");
								$stmt->execute([$title, $salary, $description, $skill, $jobCategory, $jobID]);
								$count = $stmt->rowCount();
								if ($count == 1) {
									echo '<div class="messages-in-back">';
										messages('Success', 'The job has been updated. You will be directed to the jobs page.');
									echo '</div>';
									include $pages . ('loader.html');
									header("refresh:5, ./jobs.php?do=Edit&jobID=$jobID");
									exit();
								}
								else {
									echo '<div class="messages-in-back">';
										messages('Denger', 'An error occurred, try again.');
									echo '</div>';
									include $pages . ('loader.html');
									header("refresh:5, ./jobs.php?do=Edit&jobID=$jobID");
									exit();
								}
							}
						}
						else {
							echo '<div class="messages-in-back">';
								messages('Denger', 'You Can\'t Access This Page Directly.');
							echo '</div>';
							include $pages . ('loader.html');
							header('refresh:5, ./jobs.php?do=Manage');
							exit();
						}
					}
					//! End Update Data.
					//! Start Delete Data.
					elseif ($do == 'Delete') {
						$jobID = isset($_GET['jobID']) && is_numeric($_GET['jobID']) ? intval($_GET['jobID']) : 0;
						if ($_SESSION['ACCESS'] == 'Admin') {
							$stmt = $CONDB->prepare("SELECT * FROM `jobs` WHERE `JobID` = ?");
							$stmt->execute([$jobID]);
						}
						else {
							$stmt = $CONDB->prepare("SELECT * FROM `jobs` WHERE `JobID` = ? AND `MemberID` = ?");
							$stmt->execute([$jobID, $_SESSION['ID']]);
						}
						$row = $stmt->fetch();
						$count = $stmt->rowCount();
						if ($count == 1) {
							if ($_SESSION['ACCESS'] == 'Admin') {
								$stmt = $CONDB->prepare("DELETE FROM `jobs` WHERE `JobID` = ?");
								$stmt->execute([$jobID]);
							}
							else {
								$stmt = $CONDB->prepare("DELETE FROM `jobs` WHERE `JobID` = ? AND `MemberID` = ?");
								$stmt->execute([$jobID, $_SESSION['ID']]);
							}
							$count = $stmt->rowCount();
							if ($count == 1) {
								echo '<div class="messages-in-back">';
									messages('Success', 'You have now deleted the job.');
								echo '</div>';
								include $pages . ('loader.html');
								header('refresh:0.00000005, ./jobs.php?do=Manage');
								exit();
							}
							else {
								echo '<div class="messages-in-back">';
									messages('Denger', 'This job cannot be deleted.');
								echo '</div>';
								include $pages . ('loader.html');
								header('refresh:5, ./jobs.php?do=Manage');
								exit();
							}
						}
						else {
							echo '<div class="messages-in-back">';
								messages('Denger', 'You Can\'t Delete This Job.');
							echo '</div>';
							include $pages . ('loader.html');
							header('refresh:5, ./jobs.php?do=Manage');
							exit();
						}
					}
					else {
						echo '<div class="messages-in-back">';
							messages('Denger', 'THIS ID IS NOT EXIST.');
						echo '</div>';
						include $pages . ('loader.html');
						header('refresh:5, ./jobs.php?do=Manage');
						exit();
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