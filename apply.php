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
      $stmt = null;
      if ($count === 1) {
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
    <div class="name-of-page">
      <h1>Enter Your Skills</h1>
    </div>
    <div class="container-form-login-signup apply">
			<div class="items-container apply-items">
        <form action="<?php $_SERVER['PHP_SELF']?>" method="POST">
          <div class="input-field">
            <textarea name="userSkills" id="userSkills" rows="5" placeholder="Skill - Skill - Skill." required></textarea>
          </div>
          <div class="notes">
            <p class="input-notes">! Please put hyphen (-) between each skill.</p>
			    </div>
          <input id="submit-button" class="button-form-login-signup-reset" type="submit" value="Send">
          <input class="button-form-login-signup-reset" type="reset" value="Reset">
        </form>
      </div>
    </div>
  </section>
  <?php require $temp . ('footer.php');?>
</div>
<?php require $temp . ('end.body.php');?>
<script>apply();</script>