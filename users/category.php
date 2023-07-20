<?php
	session_start();
	$pageTitle = 'Categories';
	require ('initialize.php');
	?>
	<div class="body">
		<?php require $temp . ('navbar.php') ?>
		<section class="container">
			<?php
				if (isset($_SESSION['NAME'])) {
					$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
					//! Start Manage Page.
					if ($do == 'Manage') {
						// An array containing the types of Sort.
						$sortArray = ['ASC', 'DESC'];
						// in_array() -> Checks if a value exists in an array
						if (isset($_GET['sort']) && in_array($_GET['sort'], $sortArray)) {
							$sort = $_GET['sort'];
							// SQL query [SELECT].
							$stmt = $CONDB->prepare("SELECT * FROM `categories` ORDER BY `CategoryDate` $sort");
							// execute query.
							$stmt->execute();
							// fetchAll() -> Fetches the remaining rows from a result set
							$rows = $stmt->fetchAll();
						}
						else {
							if (isset($_GET['submit'])) {
								$category = $_GET['searchCat'];
								$stmt = $CONDB->prepare("SELECT * FROM `categories` WHERE `CategoryName` = ?");
								$stmt->execute([$category]);
								$count = $stmt->rowCount();
								if ($count == 1) {
									$rows = $stmt->fetchAll();
								}
								else {
									messages('Warning', 'There is no category with this name.');
									$stmt = $CONDB->prepare("SELECT * FROM `categories`");
									$stmt->execute();
									$rows = $stmt->fetchAll();
								}
							}
							else {
								$stmt = $CONDB->prepare("SELECT * FROM `categories`");
								$stmt->execute();
								$rows = $stmt->fetchAll();
							}
						}
						?>
						<div class="name-of-page">
							<h1>Categories</h1>
						</div>
						<!--Box All categories -->
						<div class="details">
							<div class="box">
								<a href="category.php">
									<p class="num"><?php counter('categories', "");?></p>
									<p class="box-name">All categories</p>
								</a>
							</div>
							<div class="box">
								<a href="category.php?do=Manage&sort=ASC">
									<span class="icon"><i class="fa-solid fa-sort"></i></span>
									<p class="box-name">Sort by oldest</p>
								</a>
							</div>
							<div class="box">
								<a href="category.php?do=Manage&sort=DESC">
									<span class="icon"><i class="fa-solid fa-sort"></i></span>
									<p class="box-name">Sort by latest</p>
								</a>
							</div>
							<!--Box Add New category -->
							<div class="box">
								<a href="category.php?do=Add">
									<span class="icon"><i class="fa-solid fa-clipboard"></i></span>
									<p class="box-name">Add New category</p>
								</a>
							</div>
						</div>
						<!-- Search Bar -->
						<div class="search-bar">
							<form action="<?php $_SERVER['PHP_SELF']?>" method="GET">
								<i class="search-icon fa-solid fa-magnifying-glass"></i>
								<input type="search" name="searchCat" placeholder="Enter name of category...">
								<input type="submit" name="submit" value="Search">
							</form>
						</div>
						<!-- The table contains categories data. -->
						<div class="table-content">
							<table>
								<thead>
									<tr>
										<th>ID</th>
										<th>Image</th>
										<th>Name</th>
										<th>Date</th>
										<th>Controle</th>
									</tr>
								</thead>
								<tbody>
								<?php
									foreach ($rows as $row) {
										echo '<tr>';
											echo '<td>' . $row['CategoryID'] . '</td>';
											echo '<td><a href="../allJobs.php?categoryName='.$row['CategoryName'].'&categoryID='.$row['CategoryID'].'"><img src="admin/data/upload/images/categoryImages/'.$row['CategoryPhoto'].'"alt=""/></a></td>';
											echo '<td>' . $row['CategoryName'] . '</td>';
											echo '<td>' . $row['CategoryDate'] . '</td>';
											echo '<td> 
															<div class="table-btn">
																<a href="category.php?do=Edit&catID='.$row['CategoryID'].'" class="edit-btn">Edit</a>
																<a href="category.php?do=Delete&catID='.$row['CategoryID'].'" class="del-btn">Delete</a>
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
					//! Start Add Data.
					elseif ($do == 'Add') {
						?>
						<div class="form-pages">
							<h1 class="form-name">Add New Category</h1>
							<!-- The data encoding type, enctype, MUST be specified as below. -->
							<form action="?do=Insert" method="POST" enctype="multipart/form-data">
								<div class="form-content">
									<!-- Upload Image -->
									<div class="input-field">
										<label for="name">Category Image</label>
										<input type="file" name="catImages" required>
										<p class="input-notes">! The extension should be the image ['jpeg', 'jpg', 'png'].</p>
										<p class="input-notes">! Maximum size is 20MB.</p>
									</div>
									<!-- Name -->
									<div class="input-field">
										<label for="name">Category Name</label>
										<input type="text" name="catName" placeholder="Enter category name" required>
									</div>
									<!-- Title -->
									<div class="input-field">
										<label for="title">category Title</label>
										<input type="text" name="catTitle" placeholder="Enter category title" required>
									</div>
									<!-- Description -->
									<div class="input-field">
										<label for="categoryDescription">Category Description</label>
										<textarea name="catDescription" id="categoryDescription" rows="5" placeholder="Enter Description Of Category" required></textarea>
									</div>
								</div>
								<!-- Buttons -->
								<div class="buttons">
									<input type="submit" value="Add Category">
									<input type="reset" value="Reset">
								</div>
							</form>
						</div>
						<?php
					}
					//! End Add Data.
					//! Start Insert Data.
					elseif ($do == 'Insert') {
						if ($_SERVER['REQUEST_METHOD'] == 'POST') {
							// The global $_FILES[] will contain all the uploaded file information.
							$image				= $_FILES['catImages'];
							$imageName    = $_FILES['catImages']['name'];
							$imageSize    = $_FILES['catImages']['size'];
							$imageType    = $_FILES['catImages']['type'];
							$imageTmpName = $_FILES['catImages']['tmp_name'];
							// List Of Allowed File Type Upload.
							$imageAllowedExtensions = ['jpeg', 'jpg', 'png'];
							// Get Values From Inputs.
							$catName 				= htmlspecialchars($_POST['catName']);
							$catTitle 			= htmlspecialchars($_POST['catTitle']);
							$catDescription = htmlspecialchars($_POST['catDescription']);
							// end() -> Set the internal pointer of an array to its last element.
							// strtolower() -> Make a string lowercase.
							$imageNameArray = explode('.', $imageName);
							$imageExtension = strtolower(end($imageNameArray));
							// Empty Array Will Use To Give It The Errors.
							$errors = [];
							// explode() -> Split a string by a string.
							if (empty($catName) && empty($catTitle) && empty($catDescription)) {
								$errors[] = 'All fields are required.';
							}
							else {
								if (empty($catName)) {
									$errors[] = 'Please enter the name of the category.';
								}
								else {
									if (empty($catTitle)) {
										$errors[] = 'Please enter the title of the category.';
									}
									else {
										if (empty($catDescription)) {
											$errors[] = 'Please enter the description of the category.';
										}
									}
								}
							}
							if (empty($imageName)) {
								$errors[] = 'You must upload an image of the category type.';
							}
							else {
								if (!in_array($imageExtension, $imageAllowedExtensions)) {
									$errors[] = 'The types of files allowed to be uploaded are: (jpeg, jpg, png).';
								}
								else {
									if ($imageSize > 20971520) {
										$errors[] = 'Image can\'t be larger than <strong>20MB</strong>';
									}
									else {
										// rand($min, $max) -> Generate a random integer.
										// move_uploaded_file($from, $to) -> Moves an uploaded file to a new location.
										// is_file() -> Tells whether the filename is a regular file.
										$image = rand(0, 10000) . '_' . $imageName;
										move_uploaded_file($imageTmpName, 'admin\data\upload\images\categoryImages\\'.$image);
										if (!is_file('admin\data\upload\images\categoryImages\\'.$image)) {
											$errors[] = 'The image could not be uploaded, please try again.';
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
								header('refresh:5, category.php?do=Add');
								exit();
							}
							else {
								$stmt = $CONDB->prepare("SELECT `CategoryName` FROM `categories` WHERE `CategoryName` = ?");
								$stmt->execute([$catName]);
								$count = $stmt->rowCount();
								if ($count == 1) {
									echo '<div class="messages-in-back">';
										messages('Warning', 'The name of category is already exist.');
									echo '</div>';
									include $pages . ('loader.html');
									header('refresh:5, category.php?do=Add');
									exit();
								}
								else {
									$stmt = $CONDB->prepare("INSERT INTO
																											`categories` (`CategoryName`,
																																		`CategoryTitle`,
																																		`CategoryDescription`,
																																		`CategoryPhoto`,
																																		`CategoryDate`)
																								VALUES
																											(?, ?, ?, ?, now())");
									$stmt->execute([$catName, $catTitle, $catDescription, $image]);
									$count = $stmt->rowCount();
									if ($count == 1) {
										echo '<div class="messages-in-back">';
											messages('Success', 'The category has been created. You will be directed to the categories page.');
										echo '</div>';
										include $pages . ('loader.html');
										header('refresh:5;url=category.php');
										exit();
									}
									else {
										echo '<div class="messages-in-back">';
											messages('Denger', 'An error occurred, try again.');
										echo '</div>';
										include $pages . ('loader.html');
										header('refresh:5, category.php?do=Add');
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
							header('refresh:5, category.php?do=Add');
						}
					}
					//! End Insert Data.
					//! Start Edit Data.
					elseif ($do == 'Edit') {
						// isset() 			=> Determines if a variable is declared other than NULL.
						// is_numeric() => Finds whether a variable is a number or a numeric string
						// intval() 		=> The numerical value is converted to an integer.
						$catID = isset($_GET['catID']) && is_numeric($_GET['catID']) ? intval($_GET['catID']) : 0;
						// SQL query [SELECT].
						$stmt = $CONDB->prepare("SELECT * FROM `categories` WHERE `CategoryID` = ?");
						// execute query.
						$stmt->execute([$catID]);
						// Fetch Data From Database.
						$row = $stmt->fetch();
						// Returns the number of rows affected by the last SQL statement.
						$count = $stmt->rowCount();
						// Check that there is data for this ID or not.
						if ($count == 1) {
							?>
							<div class="form-pages">
								<h1 class="form-name">Update Category</h1>
								<!-- The data encoding type, enctype, MUST be specified as below. -->
								<form action="?do=Update" method="POST" enctype="multipart/form-data">
									<!--ID -->
									<input hidden type="text" name="catID" value="<?= $row['CategoryID']?>">
									<img class="img" src="admin/data/upload/images/categoryImages/<?= $row['CategoryPhoto']?>"alt=""/>
									<!-- Upload Image -->
									<div class="form-content">
										<div class="input-field">
											<label for="name">Category Image</label>
											<input hidden type="text" name="catOldName" value="<?= $row['CategoryPhoto']?>">
											<input type="file" name="catImages">
											<p class="input-notes">! The extension should be the image ['jpeg', 'jpg', 'png'].</p>
											<p class="input-notes">! Maximum size is 20MB.</p>
										</div>
										<!-- Name -->
										<div class="input-field">
											<label for="name">Category Name</label>
											<input type="text" name="catName" value="<?= $row['CategoryName']?>" placeholder="Enter category name">
										</div>
										<!-- Title -->
										<div class="input-field">
											<label for="title">category Title</label>
											<input type="text" name="catTitle" value="<?= $row['CategoryTitle']?>" placeholder="Enter category title">
										</div>
										<!-- Description -->
										<div class="input-field">
											<label for="categoryDescription">Category Description</label>
											<textarea name="catDescription" id="categoryDescription" rows="5" placeholder="Enter Description Of Category"><?= $row['CategoryDescription']?></textarea>
										</div>
									</div>
									<!-- Buttons -->
									<div class="buttons">
										<input type="submit" value="Update Category">
										<input type="reset" value="Reset">
									</div>
								</form>
							</div>
							<?php
						}
						else {
							echo '<div class="messages-in-back">';
								messages('Warning', 'There is no data for this category.');
							echo '</div>';
							include $pages . ('loader.html');
							header('refresh:0.00000005,category.php?do=Manage');
							exit();
						}
					}
					//! End Edit Data.
					//! Start Update Data.
					elseif ($do == 'Update') {
						if ($_SERVER['REQUEST_METHOD'] == 'POST') {
							// The global $_FILES[] will contain all the uploaded file information.
							$image				= $_FILES['catImages'];
							$imageName    = $_FILES['catImages']['name'];
							$imageSize    = $_FILES['catImages']['size'];
							$imageType    = $_FILES['catImages']['type'];
							$imageTmpName = $_FILES['catImages']['tmp_name'];
							// List Of Allowed File Type Upload.
							$imageAllowedExtensions = ['jpeg', 'jpg', 'png'];
							// Get Values From Inputs.
							$catID 					= $_POST['catID'];
							$catName 				= htmlspecialchars($_POST['catName']);
							$catTitle 			= htmlspecialchars($_POST['catTitle']);
							$catDescription = htmlspecialchars($_POST['catDescription']);
							// end() -> Set the internal pointer of an array to its last element.
							// strtolower() -> Make a string lowercase.
							$imageNameArray = explode('.', $imageName);
							$imageExtension = strtolower(end($imageNameArray));
							// Empty Array Will Use To Give It The Errors.
							$errors = [];
							// explode() -> Split a string by a string.
							if (empty($catName) && empty($catTitle) && empty($catDescription)) {
								$errors[] = 'All fields are required.';
							}
							else {
								if (empty($catName)) {
									$errors[] = 'Please enter the name of the category.';
								}
								else {
									if (empty($catTitle)) {
										$errors[] = 'Please enter the title of the category.';
									}
									else {
										if (empty($catDescription)) {
											$errors[] = 'Please enter the description of the category.';
										}
									}
								}
							}
							if (!empty($imageName)) {
								if (!in_array($imageExtension, $imageAllowedExtensions)) {
									$errors[] = 'The types of files allowed to be uploaded are: (jpeg, jpg, png).';
								}
								else {
									if ($imageSize > 20971520) {
										$errors[] = 'Image can\'t be larger than <strong>20MB</strong>';
									}
									else {
										// rand($min, $max) -> Generate a random integer.
										// move_uploaded_file($from, $to) -> Moves an uploaded file to a new location.
										// is_file() -> Tells whether the filename is a regular file.
										$image = rand(0, 10000) . '_' . $imageName;
										move_uploaded_file($imageTmpName, 'admin\data\upload\images\categoryImages\\'.$image);
										if (!is_file('admin\data\upload\images\categoryImages\\'.$image)) {
											$errors[] = 'The image could not be uploaded, please try again.';
										}
										else {
											// unlink() -> Deletes a file
											if (!unlink('admin/data/upload/images/categoryImages/'.$_POST['catOldName'])) {
												echo '<div class="messages-in-back">';
													messages('Denger', 'This category image cannot be deleted.');
												echo '</div>';
												include $pages . ('loader.html');
											}
										}
									}
								}
							}
							else {
								$image = $_POST['catOldName'];
							}
							if (!empty($errors)) {
								echo '<div class="messages-in-back">';
								foreach ((array)$errors as $error) {
									messages('Warning', $error);
								}
								echo '</div>';
								include $pages . ('loader.html');
								header("refresh:5, category.php?do=Edit&catID=$catID");
								exit();
							}
							else {
								$stmt = $CONDB->prepare("SELECT * FROM `categories` WHERE `CategoryName` = ? AND `CategoryID` != ?");
								$stmt->execute([$catName, $catID]);
								$count = $stmt->rowCount();
								if ($count == 1) {
									echo '<div class="messages-in-back">';
										messages('Warning', 'The name of category is already exist.');
									echo '</div>';
									include $pages . ('loader.html');
									header("refresh:5, category.php?do=Edit&catID=$catID");
									exit();
								}
								else {
									$stmt = $CONDB->prepare("UPDATE
																									`categories`
																							SET
																								`CategoryName` = ?,
																								`CategoryTitle` = ?,
																								`CategoryDescription` = ?,
																								`CategoryPhoto` = ?
																						WHERE
																								`CategoryID` = ?");
									$stmt->execute([$catName, $catTitle, $catDescription, $image, $catID]);
									$count = $stmt->rowCount();
									if ($count == 1) {
										echo '<div class="messages-in-back">';
											messages('Success', 'The category has been updated. You will be directed to the categories page.');
										echo '</div>';
										include $pages . ('loader.html');
										header("refresh:5, category.php?do=Edit&catID=$catID");
										exit();
									}
									else {
										echo '<div class="messages-in-back">';
											messages('Denger', 'An error occurred, try again.');
										echo '</div>';
										include $pages . ('loader.html');
										header("refresh:5, category.php?do=Edit&catID=$catID");
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
							header('refresh:5, category.php?do=Manage');
							exit();
						}
					}
					//! End Update Data.
					//! Start Delete Data.
					elseif ($do == 'Delete') {
						// isset() 			=> Determines if a variable is declared other than NULL.
						// is_numeric() => Finds whether a variable is a number or a numeric string.
						// intval() 		=> The numerical value is converted to an integer.
						$catID = isset($_GET['catID']) && is_numeric($_GET['catID']) ? intval($_GET['catID']) : 0;
						// SQL query [SELECT].
						$stmt = $CONDB->prepare("SELECT * FROM `categories` WHERE `CategoryID` = ?");
						// execute query.
						$stmt->execute([$catID]);
						// Fetch Data From Database.
						$row = $stmt->fetch();
						// Returns the number of rows affected by the last SQL query.
						$count = $stmt->rowCount();
						// Check The ID Is Exist Or Not.
						if ($count == 1) {
							// unlink() -> Deletes a file
							if (!unlink('admin/data/upload/images/categoryImages/'.$row['CategoryPhoto'])) {
								echo '<div class="messages-in-back">';
									messages('Denger', 'This category image cannot be deleted.');
								echo '</div>';
									include $pages . ('loader.html');
							}
							else {
								// SQL query [DELETE].
								$stmt = $CONDB->prepare("DELETE FROM `categories` WHERE `CategoryID` = ?");
								// execute query.
								$stmt->execute([$catID]);
								// Returns the number of rows affected by the last SQL statement.
								$count = $stmt->rowCount();
								// Check Whether The Data Has Been Deleted Or Not.
								if ($count == 1) {
									echo '<div class="messages-in-back">';
										messages('Success', 'You have now deleted the category.');
									echo '</div>';
									include $pages . ('loader.html');
									header('refresh:0.00000005,category.php?do=Manage');
									exit();
								}
								else {
									echo '<div class="messages-in-back">';
										messages('Denger', 'This category cannot be deleted.');
									echo '</div>';
									include $pages . ('loader.html');
									header('refresh:5, category.php?do=Manage');
									exit();
								}
							}
						}
						else {
							echo '<div class="messages-in-back">';
								messages('Denger', 'THIS ID IS NOT EXIST.');
							echo '</div>';
							include $pages . ('loader.html');
							header('refresh:5, category.php?do=Manage');
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