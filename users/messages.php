<?php
	session_start();
	$pageTitle = 'Messages';
	require ('initialize.php');
	?>
	<div class="body">
		<?php require $temp . ('navbar.php');?>
		<section class="container">
			<?php
				if (isset($_SESSION['NAME'])) {
          $userID = $_SESSION['ID'];
					$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
          if ($do === 'Manage') {
            $sortArray = ['ASC', 'DESC'];
            $sort = 'DESC';
            if (isset($_GET['sort']) && in_array($_GET['sort'], $sortArray)) {
              $sort = $_GET['sort'];
            }
            $stmt = $CONDB->prepare("SELECT
                                          `messages`.*,
                                          `jobs`.`JobTitle`
                                      FROM
                                          `messages`
                                INNER JOIN
                                          `jobs`
                                        ON
                                          `jobs`.`JobID` = `messages`.`JobID`
                                    WHERE
                                          `JobPublisher` = ?
                                    ORDER BY
                                          `Date` $sort");
            $stmt->execute([$userID]);
            $rows = $stmt->fetchAll();
            $stmt = null;
            ?>
              <div class="name-of-page">
                <h1>Messages</h1>
              </div>
              <div class="details">
                <div class="box">
                  <a href="#messages">
                    <p class="num"><?php counter('messages', "WHERE `JobPublisher` = $userID");?></p>
                    <p class="box-name">Messages</p>
                  </a>
                </div>
                <div class="box">
                  <a href="messages.php?do=Manage&sort=ASC#messages">
                    <span class="icon"><i class="fa-solid fa-sort"></i></span>
                    <p class="box-name">Sort by oldest</p>
                  </a>
                </div>
                <div class="box">
                  <a href="messages.php?do=Manage&sort=DESC#messages">
                    <span class="icon"><i class="fa-solid fa-sort"></i></span>
                    <p class="box-name">Sort by latest</p>
                  </a>
                </div>
              </div>
              <div id="messages" class="table-content">
                <table>
                  <thead>
                    <tr>
                      <th>Messages ID</th>
                      <th>Freelancer ID</th>
                      <th>Job Id</th>
                      <th>Job Title</th>
                      <th>Freelancer Skills</th>
                      <th>Date</th>
                      <th>Controle</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      foreach ($rows as $row) {
                        $stmt = $CONDB->prepare("SELECT `PhoneNumber` FROM `users` WHERE `ID` = ?");
                        $stmt->execute([$row['FreelancerID']]);
                        $phoneNumber = $stmt->fetch();
                        echo '<tr>';
                          echo '<td>'. $row['MessageID'] .'</td>';
                          echo '<td>'. $row['FreelancerID'] .'</td>';
                          echo '<td>'. $row['JobID'] .'</td>';
                          echo '<td>'. $row['JobTitle'] .'</td>';
                          echo '<td class="max-width-text">';
                            $freelancerSkillsArray = explode('-', $row['FreelancerSkills']);
                            echo '<ol>';
                            foreach ($freelancerSkillsArray as $skill) {
                              echo '<li>'.$skill.'</li>';
                            }
                            echo '</ol>';
                          echo '</td>';
                          echo '<td>'. $row['Date'] .'</td>';
                          echo '<td> 
                          <div class="table-btn">
                            <a href="https://wa.me/+20'.$phoneNumber['PhoneNumber'].'" class="accept-btn" target="blank">Accept</a>
                            <a href="messages.php?do=Delete&MessageID='.$row['MessageID'].'" class="del-btn">Delete</a>
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
					//! Start Delete Data.
					elseif ($do === 'Delete') {
						$MessageID = isset($_GET['MessageID']) && is_numeric($_GET['MessageID']) ? intval($_GET['MessageID']) : 0;
						$stmt = $CONDB->prepare("SELECT * FROM `messages` WHERE MessageID = ?");
						$stmt->execute([$MessageID]);
						$count = $stmt->rowCount();
						if ($count == 1) {
							$stmt = $CONDB->prepare("DELETE FROM `messages` WHERE MessageID = ?");
							$stmt->execute([$MessageID]);
							$count = $stmt->rowCount();
							if ($count == 1) {
                echo '<div class="messages-in-back">';
								  messages('Success', 'You have now deleted the message.');
                echo '</div>';
                include $pages . ('loader.html');
								header('refresh:0.00000005, ./messages.php?do=Manage');
								exit();
							}
							else {
                echo '<div class="messages-in-back">';
								  messages('Denger', 'This user cannot be deleted.');
                  echo '</div>';
                include $pages . ('loader.html');
								header('refresh:5, ./messages.php?do=Manage');
								exit();
							}
						}
						else {
              echo '<div class="messages-in-back">';
							  messages('Denger', 'THIS ID IS NOT EXIST.');
              echo '</div>';
              include $pages . ('loader.html');
              header('refresh:5, ./messages.php?do=Manage');
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