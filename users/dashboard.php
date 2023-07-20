<?php
session_start();
$pageTitle = 'Dashboard';
require ('initialize.php');
if (isset($_SESSION['NAME'])) {
	if ($_SESSION['ACCESS'] == 'Admin') {
		//! Start Delete Data.
		if (isset($_GET['do']) && $_GET['do'] == 'Delete') {
			$FeedbackID = isset($_GET['FeedbackID']) && is_numeric($_GET['FeedbackID']) ? intval($_GET['FeedbackID']) : 0;
			$stmt = $CONDB->prepare("SELECT * FROM `feedback` WHERE `FeedbackID` = ?");
			$stmt->execute([$FeedbackID]);
			$count = $stmt->rowCount();
			if ($count == 1) {
				$stmt = $CONDB->prepare("DELETE FROM `feedback` WHERE `FeedbackID` = ?");
				$stmt->execute([$FeedbackID]);
				$count = $stmt->rowCount();
				if ($count == 1) {
					echo '<div class="messages-in-back">';
						messages('Success', 'You have now deleted the feedback.');
					echo '</div>';
					header('refresh:0.00000005,dashboard.php#feedback');
					exit();
				}
				else {
					echo '<div class="messages-in-back">';
						messages('Denger', 'This feedback cannot be deleted.');
					echo '</div>';
				}
			}
			else {
				echo '<div class="messages-in-back">';
					messages('Denger', 'THIS feedback IS NOT EXIST.');
				echo '</div>';
			}
		}
		//! End Delete Data.
		?>
			<div class="body">
				<?php require $temp . ('navbar.php');?>
				<section class="container">
					<?php
						$userID = $_SESSION['ID'];
						if (isset($_SESSION['NAME']) || isset($_COOKIE)) {
							if ($_SESSION['ACCESS'] == 'Admin') {
								?>
									<div class="name-of-page">
										<h1>Dashboard</h1>
									</div>
									<div class="details">
										<!--Box All Users -->
										<div class="box">
											<a href="members.php?do=Manage&userID=<?php echo $userID;?>">
												<span class="icon"><i class="fa-solid fa-users"></i></span>
												<p class="num"><?php counter('users', "");?></p>
												<p class="box-name">All Users</p>
											</a>
										</div>
										<!--Box All categories -->
										<div class="box">
											<a href="category.php">
												<p class="num"><?php counter('categories', "");?></p>
												<p class="box-name">All categories</p>
											</a>
										</div>
										<!--Box All Jobs -->
										<div class="box">
											<a href="jobs.php?do=Manage&AccessControl=AllJobs">
												<p class="num"><?php counter('jobs', "");?></p>
												<p class="box-name">All Jobs</p>
											</a>
										</div>
										<!--Box All My Jobs -->
										<div class="box">
											<a href="jobs.php">
												<p class="num"><?php counter('jobs', "WHERE `MemberID` = $userID");?></p>
												<p class="box-name">My Jobs</p>
											</a>
										</div>
										<!--Box Messages -->
										<div class="box">
											<a href="messages.php">
												<span class="icon"><i class="fa-solid fa-message"></i></span>
												<p class="num"><?php counter('messages', "WHERE `JobPublisher` = $userID");?></p>
												<p class="box-name">Messages</p>
											</a>
										</div>
									</div>
									<div class="more-than-one-table">
										<!-- Table Users -->
										<div class="table-content">
											<a href="members.php">
												<div class="name-of-page name-of-table">
													<h1>Latest Users</h1>
												</div>
											</a>
											<table>
												<thead>
													<tr>
														<th>ID</th>
														<th>Name</th>
													</tr>
												</thead>
												<tbody>
													<?php
														$getLatestRows = getLatest('ID, Name, UserDate', 'users', "", 'UserDate', 'DESC', 5);
														foreach ($getLatestRows as $row) {
															echo '<tr>';
																echo '<td>' . $row['ID'] 		   . '</td>';
																echo '<td>' . $row['Name']     . '</td>';
															echo '</tr>';
														}
													?>
												</tbody>
											</table>
										</div>
										<!-- Table Categories -->
										<div class="table-content">
											<a href="category.php">
												<div class="name-of-page name-of-table">
													<h1>Latest Categories</h1>
												</div>
											</a>
											<table>
												<thead>
													<tr>
														<th>ID</th>
														<th>Category Name</th>
													</tr>
												</thead>
												<tbody>
													<?php
														$getLatestRows = getLatest('CategoryID, CategoryName, CategoryDate', 'categories', "", 'CategoryDate','DESC', 5);
														foreach ($getLatestRows as $row) {
															echo '<tr>';
																echo '<td>' . $row['CategoryID'] 	 . '</td>';
																echo '<td>' . $row['CategoryName'] . '</td>';
															echo '</tr>';
														}
													?>
												</tbody>
											</table>
										</div>
										<!-- Table Jobs -->
										<div class="table-content">
											<a href="jobs.php">
												<div class="name-of-page name-of-table">
													<h1>Latest Jobs</h1>
												</div>
											</a>
											<table>
												<thead>
													<tr>
														<th>ID</th>
														<th>Job Title</th>
													</tr>
												</thead>
												<tbody>
													<?php
														$getLatestRows = getLatest('JobID, JobTitle, JobDate', 'jobs', "", 'JobDate','DESC', 5);
														foreach ($getLatestRows as $row) {
															echo '<tr>';
																echo '<td>' . $row['JobID'] 		   . '</td>';
																echo '<td>' . $row['JobTitle']     . '</td>';
															echo '</tr>';
														}
													?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="table-content">
										<a id="feedback">
											<div class="name-of-page">
												<h1>Feedback</h1>
											</div>
										</a>
										<table>
											<thead>
												<tr>
													<th>Feedback ID</th>
													<th>Name</th>
													<th>Email</th>
													<th>Subject</th>
													<th>Message</th>
													<th>Date</th>
													<th>Controle</th>
												</tr>
											</thead>
											<tbody>
												<?php
													$getLatestRows = getLatest('*', 'feedback', "", 'FeedbackDate','DESC', 1000000);
													foreach ($getLatestRows as $row) {
														echo '<tr>';
															echo '<td>' . $row['FeedbackID'] 	 . '</td>';
															echo '<td>' . $row['Name']         . '</td>';
															echo '<td>' . $row['Email']        . '</td>';
															echo '<td>' . $row['Subject']      . '</td>';
															echo '<td>' . $row['Message']      . '</td>';
															echo '<td>' . $row['FeedbackDate'] . '</td>';
															echo '<td> 
																<div class="table-btn">
																	<a href="dashboard.php?do=Delete&FeedbackID='.$row['FeedbackID'].'" class="del-btn">Delete</a>
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
							?>
				</section>
				<?php require $temp . ('footer.php');?>
			</div>
		<?php
		}
		else {
			echo '<div class="messages-in-back">';
				messages('Denger', 'You can\'t access this page.It will be taken back to the login page after 5 seconds.');
			echo '</div>';
			include $pages . ('loader.html');
			header('refresh:5,../logout.php');
			exit();
		}
	}
	else {
		echo '<div class="messages-in-back">';
			messages('Denger', 'There was an access error..It will be taken back to the login page after 5 seconds.');
		echo '</div>';
		include $pages . ('loader.html');
		header('refresh:5,../logout.php');
		exit();
	}
}
else {
	echo '<div class="messages-in-back">';
		messages('Denger', 'There was an access error..It will be taken back to the login page after 5 seconds.');
	echo '</div>';
	include $pages . ('loader.html');
	header('refresh:5,../logout.php');
	exit();
}
require $temp . ('end.body.php');
?>