<?php
	session_start();
  $_GET['categoryName'] = isset($_GET['categoryName']) ? $_GET['categoryName'] : '';
	$pageTitle = 'Category' . ' ' . $_GET['categoryName'];
	require('initialize.php');
?>
<div class="body">
  <?php require $temp . ('navbar.php');?>
  <section class="container">
    <?php
      if (isset($_GET['categoryID'])) {
        $stmt = $CONDB->prepare("SELECT
                                        `jobs`.*,
                                        `users`.`Name`
                                    FROM
                                        `jobs`
                              INNER JOIN
                                        `users`
                                      ON
                                        `users`.`ID` = `jobs`.`MemberID`
                                    WHERE
                                        `CatID` = ?");
        $stmt->execute([$_GET['categoryID']]);
        $rows = $stmt->fetchAll();
        $count = $stmt->rowCount();
      }
      else {
        if (isset($_GET['submit'])) {
          if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $job = htmlspecialchars($_GET['searchJob']);
            $stmt = $CONDB->prepare("SELECT
                                            `jobs`.*,
                                            `users`.`Name`
                                        FROM
                                            `jobs`
                                  INNER JOIN
                                            `users`
                                          ON
                                            `users`.`ID` = `jobs`.`MemberID`
                                        WHERE
                                            `JobTitle` = ?");
            $stmt->execute([$job]);
            $rows = $stmt->fetchAll();
            $count = $stmt->rowCount();
          }
        }
        else {
          $_GET['categoryName'] = 'All Jobs';
          $stmt = $CONDB->prepare("SELECT
                                          `jobs`.*,
                                          `users`.`Name`
                                      FROM
                                          `jobs`
                                INNER JOIN
                                          `users`
                                        ON
                                          `users`.`ID` = `jobs`.`MemberID`");
          $stmt->execute();
          $rows = $stmt->fetchAll();
          $count = $stmt->rowCount();
        }
      }
      ?>
    <div class="name-of-page">
      <h1><?= $_GET['categoryName']?></h1>
    </div>
    <?php
      if ($count > 0) {
        
        ?>
					<div class="search-bar">
						<form action="<?php $_SERVER['PHP_SELF']?>" method="GET">
							<i class="search-icon fa-solid fa-magnifying-glass"></i>
							<input type="search" name="searchJob" placeholder="Enter job title...">
							<input type="submit" name="submit" value="Search">
						</form>
					</div>
        <?php
        foreach ($rows as $row) {
          ?>
            <div class="section-job">
              <!-- Title - Date -->
              <div class="section-one">
                <div class="section-title">
                  <p class="title"><?= $row['JobTitle']?></p>
                </div>
                <div class="section-date">
                  <p class="date"><span>Date of posting: </span><?= $row['JobDate']?></p>
                </div>
              </div>
              <!-- Salary - Publisher -->
              <div class="section-two">
                <div class="section-salary">
                  <p class="salary"><span>Salary: </span>$<?= $row['JobSalary']?></p>
                </div>
                <div class="section-publisher">
                  <p class="publisher"><span>Publisher: </span><?= $row['Name']?></p>
                </div>
              </div>
              <!-- Description -->
              <div class="section-three">
                <div class="section-description">
                  <p class="description"><span>Description: </span><?= $row['JobDescription']?></p>
                </div>
              </div>
              <!-- Skills -->
              <div class="section-four">
                <div class="section-skills">
                  <span>Skills:</span>
                  <?php
                    $jobSkillsArray = explode('-', $row['JobSkills']);
                    echo '<ol type="1">';
                      foreach ($jobSkillsArray as $skill) {
                        echo '<p class="skill"><li>'.$skill.'</li></p>';
                      }
                    echo '</ol>';
                  ?>
                </div>
              </div>
              <?php
                if (isset($_SESSION['NAME'])) {
                  if ($_SESSION['ACCESS'] == 'Freelancer') {
                    ?>
                      <div class="section-five">
                        <div class="button-apply">
                          <a
                            href="apply.php?
                                            userID=<?= $_SESSION['ID']?>
                                            &JobPublisher=<?= $row['MemberID']?>
                                            &categoryID=<?= $row['CatID']?>
                                            &jobID=<?= $row['JobID']?>"
                                            target="_blank">Apply For Job</a>
                        </div>
                      </div>
                    <?php
                  }
                  else {
                    ?>
                      <div class="section-five">
                        <div class="button-apply disabled">
                          <a>Only a freelancer can apply for the job</a>
                        </div>
                      </div>
                    <?php
                  }
                }
                else {
                  ?>
                    <div class="section-five">
                      <div class="button-apply">
                        <a href="login.php">Login As Freelancer for Apply</a>
                      </div>
                    </div>
                  <?php
                }
              ?>
            </div>
          <?php
        }
      }
      else {
        if (isset($_GET['submit']) && $count == 0) {
          echo '<div class="messages-in-back">';
            messages('Warning', 'There is no job with this title.');
            echo '</div>';
          include $pages . ('loader.html');
          header('refresh:5,allJobs.php');
          exit();
        }
        else {
          include $pages . ('coming.soon.html');
        }
      }
    ?>
  </section>
  <?php require $temp . ('footer.php');?>
</div>
<?php require $temp . ('end.body.php');?>