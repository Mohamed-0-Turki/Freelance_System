<header class="header">
	<a href="../main.php#" class="site-name">freelance</a>
	<input type="checkbox" id="menu-bar">
	<label class="menu-bar" for="menu-bar"><i class="fa-solid fa-bars"></i></label>
	<nav class="navbar">
		<ul>
			<!-- Home -->
			<li><a href="../main.php#">Home</a></li>
			<!-- Account -->
			<li>
				<a>Account <i class="fa-solid fa-caret-down"></i></a>
				<ul>
					<?php
						if (isset($_SESSION['NAME'])) {
							?>
								<li><a href="../profile.php?UserID=<?=$_SESSION['ID']?>">My Profile</a></li>
								<li><a href="../logout.php">Log out</a></li>
							<?php
						}
						else {
							?>
								<li><a href="../login.php">Login</a></li>
								<li><a href="../registration.php">Sign Up</a></li>
							<?php
						}
						?>
				</ul>
			</li>
			<!-- Browse Jobs -->
			<li><a href="../browseJobs.php">Browse Jobs</a></li>
			<!-- Dashboard -->
			<?php
				if (isset($_SESSION['NAME'])) {
					if ($_SESSION['ACCESS'] === 'Admin' || $_SESSION['ACCESS'] === 'Client') {
						$dashboardName = isset($_SESSION['ACCESS']) && $_SESSION['ACCESS'] === 'Admin' ? 'Dashboard' : 'Jobs | Messages';
						?>
							<li>
								<a><?=$dashboardName?> <i class="fa-solid fa-caret-down"></i></a>
								<ul>
									<?php
										if ($_SESSION['ACCESS'] === 'Admin') {
											?>
												<li><a href="./dashboard.php">Dashboard</a></li>
												<li>
													<a>Members <i class="fa-solid fa-caret-down"></i></a>
													<ul>
														<li><a href="./members.php?do=Manage&userID=<?=$_SESSION['ID']?>">All Members</a></li>
														<li><a href="./members.php?do=Add">Add Member</a></li>
													</ul>
												</li>
												<li>
													<a>Categories <i class="fa-solid fa-caret-down"></i></a>
													<ul>
														<li><a href="./category.php?do=Manage">All Categories</a></li>
														<li><a href="./category.php?do=Add">Add Category</a></li>
													</ul>
												</li>
											<?php
										}
									?>
									<li>
										<a>Jobs <i class="fa-solid fa-caret-down"></i></a>
										<ul>
											<?php
											if ($_SESSION['ACCESS'] === 'Admin') {
												?>
													<li><a href="./jobs.php?do=Manage&AccessControl=AllJobs">All Jobs</a></li>
												<?php
											}
											?>
											<li><a href="./jobs.php?do=Manage">My Jobs</a></li>
											<li><a href="./jobs.php?do=Add">Add Job</a></li>
										</ul>
									</li>
									<li><a href="./messages.php?do=Manage">Messages</a></li>
								</ul>
							</li>
						<?php
					}
				}
			?>
			<!-- Features -->
			<li><a href="../main.php#features">Features</a></li>
			<!-- About Us -->
			<li><a href="../main.php#about">About Us</a></li>
			<!-- Contact Us -->
			<li><a href="../main.php#Contact">Contact Us</a></li>
		</ul>
	</nav>
</header>