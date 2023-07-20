<?php
	session_start();
	$pageTitle = 'Applying for the job';
	require('initialize.php');
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userID       = filter_var($_GET['userID'], FILTER_SANITIZE_NUMBER_INT);
    $categoryID   = filter_var($_GET['categoryID'], FILTER_SANITIZE_NUMBER_INT);
    $jobID        = filter_var($_GET['jobID'], FILTER_SANITIZE_NUMBER_INT);
    $JobPublisher = filter_var($_GET['JobPublisher'], FILTER_SANITIZE_NUMBER_INT);
    $userSkills   = htmlspecialchars($_POST['userSkills']);
    if (empty($userSkills)) {
      echo '<div class="messages-in-back">';
        messages('Warning', 'Please Enter Your Skills.');
      echo '</div>';
    }
    else {
      $stmt = $CONDB->prepare("INSERT INTO `messages` (`JobPublisher`, `FreelancerID`, `CategoryID`, `JobID`, `FreelancerSkills`, `Date`) VALUES (?, ?, ?, ?, ?, now())");
      $stmt->execute([$JobPublisher, $userID, $categoryID, $jobID, $userSkills]);
      $count = $stmt->rowCount();
      if ($count == 1) {
        echo '<div class="messages-in-back">';
          messages('Success', 'You will be contacted upon acceptance of the position.');
        echo '</div>';
      }
      else {
        echo '<div class="messages-in-back">';
          messages('Denger', 'This Message Can\'t Send.');
        echo '</div>';
      }
    }
  }
?>
<div class="body">
  <?php require $temp . ('navbar.php');?>
  <section class="container">
    <div class="form-pages">
      <h1 class="form-name">Applying for the job</h1>
      <form action="<?php $_SERVER['PHP_SELF']?>" method="POST">
        <div class="form-content">
          <div class="input-field userSkills">
            <label for="userSkills">Enter your skills</label>
            <textarea class="addSkills" name="userSkills" id="userSkills" rows="5" placeholder="Skill - Skill - Skill." required></textarea>
            <p class="input-notes">! Please put hyphen (-) between each skill.</p>
          </div>
        </div>
        <div class="buttons">
          <input type="submit" value="Send">
          <input type="reset" value="Reset">
        </div>
      </form>
    </div>
  </section>
  <?php require $temp . ('footer.php');?>
</div>
<?php require $temp . ('end.body.php');?>