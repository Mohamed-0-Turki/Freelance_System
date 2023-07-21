<?php
	session_start();
	$pageTitle = 'My Profile';
	require('initialize.php');
  //! Start Delete Data.
  if (isset($_GET['do']) && $_GET['do'] === 'Delete') {
    $UserID = isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
    $stmt = $CONDB->prepare("SELECT * FROM `users` WHERE `ID` = ?");
    $stmt->execute([$UserID]);
    $count = $stmt->rowCount();
    $stmt = null;
    if ($count === 1) {
      $stmt = $CONDB->prepare("DELETE FROM `users` WHERE `ID` = ?");
      $stmt->execute([$UserID]);
      $count = $stmt->rowCount();
      $stmt = null;
      if ($count == 1) {
        echo '<div class="messages-in-back">';
          messages('Success', 'You have now deleted your account.');
        echo '</div>';
        header('Location: ./logout.php');
        exit();
      }
      else {
        echo '<div class="messages-in-back">';
          messages('Denger', 'This account cannot be deleted.');
        echo '</div>';
      }
    }
    else {
      echo '<div class="messages-in-back">';
        messages('Denger', 'THIS account IS NOT EXIST.');
      echo '</div>';
    }
  }
  //! End Delete Data.
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id 				 = filter_var($_SESSION['ID'], FILTER_SANITIZE_NUMBER_INT);
    $name 			 = htmlspecialchars($_POST['name']);
    $email 			 = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $userRole 	 = htmlspecialchars($_SESSION['ACCESS']);
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
            if ($userRole !== 'Freelancer' && empty($phoneNumber)) {
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
      }
      else {
        $stmt = $CONDB->prepare("UPDATE
                                        `users`
                                    SET
                                        `Name` = ?,
                                        `Email` = ?,
                                        `PhoneNumber` = ?,
                                        `Password` = ?
                                  WHERE
                                        `ID` = ?");
        $stmt->execute([$name, $email, $phoneNumber, $newPassword, $id]);
        $count = $stmt->rowCount();
        $stmt = null;
        if ($count === 1) {
          echo '<div class="messages-in-back">';
            messages('Success', 'The data has been updated successfully.');
          echo '</div>';
        }
        else {
          echo '<div class="messages-in-back">';
            messages('Denger', 'This user cannot be updated.');
          echo '</div>';
        }
      }
    }
  }
?>
<div class="body">
	<?php require $temp . ('navbar.php');?>
	<section class="container">
    <?php
      $UserID = isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
      if ($UserID == $_SESSION['ID']) {
        $stmt = $CONDB->prepare("SELECT * FROM `users` WHERE ID = ?");
        $stmt->execute([$UserID]);
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count === 1) {
          ?>
          <div class="container-form-login-signup profile-container">
            <div class="items-container  profile-items">
              <h1>My Profile</h1>
              <form action="<?php $_SERVER['PHP_SELF']?>" method="POST">
                <div class="input-field">
                  <span class="icon-input"><i class="fa fa-solid fa-user"></i></span>
                  <input type="text" name="name" value="<?= $row['Name']; ?>" placeholder="Enter your name">
                </div>
                <div class="input-field">
                  <span class="icon-input"><i class="fa fa-solid fa-envelope"></i></span>
                  <input type="email" name="email" value="<?= $row['Email']; ?>" placeholder="Enter your email">
                </div>
                <div class="input-field">
				          <span class="icon-input"><i class="fa fa-solid fa-lock"></i></span>
                  <input hidden type="text" value="<?= $row['Password']; ?>" name="oldPassword">
                  <input id="password" type="password" name="newPassword" placeholder="Leave blank if you don't want to change.">
                  <span class="icon-show-password"><i class="fa-solid fa-eye"></i></span>
                </div>
                <div class="input-field">
                  <span class="icon-input"><i class="fa fa-solid fa-lock"></i></span>
                  <input id="rePassword" type="password" name="rePassword" placeholder="Retype the password in this field.">
                </div>
                <div class="input-field" <?php if($_SESSION['ACCESS'] != 'Freelancer'){echo 'style="display:none"';}?> >
                  <span class="icon-input"><i class="fa fa-solid fa-phone"></i></span>
                  <input type="tel" name="phoneNumber" value="0<?= $row['PhoneNumber'] ?>" id="phoneNumber">
                </div>
                <input class="button-form-login-signup-reset" id="submit-button" type="submit" value="Update">
                <input class="button-form-login-signup-reset" type="reset" value="Reset">
                <a class="button-form-login-signup-reset delete-btn" href="profile.php?do=Delete&UserID=<?= $_SESSION['ID']?>">Delete My Account</a>
              </form>
            </div>
          </div>
          <?php
        }
        else {
          echo '<div class="messages-in-back">';
            messages('Warning', 'There is no data for this user.');
          echo '</div>';
          header('Location: ./logout.php');
          exit();
        }
      }
      else {
        echo '<div class="messages-in-back">';
          messages('Warning', 'YOU CAN\'T ACCESS THIS PAGE.');
        echo '</div>';
        header('Location: ./logout.php');
        exit();
      }
    ?>
	</section>
	<?php require $temp . ('footer.php');?>
</div>
<?php require $temp . ('end.body.php');?>
<script>myProfile();</script>