<?php
	session_start();
	$pageTitle = 'My Profile';
	require('initialize.php');
  //! Start Delete Data.
  if (isset($_GET['do']) && $_GET['do'] == 'Delete') {
    $UserID = isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
    $stmt = $CONDB->prepare("SELECT * FROM `users` WHERE `ID` = ?");
    $stmt->execute([$UserID]);
    $count = $stmt->rowCount();
    if ($count == 1) {
      $stmt = $CONDB->prepare("DELETE FROM `users` WHERE `ID` = ?");
      $stmt->execute([$UserID]);
      $count = $stmt->rowCount();
      if ($count == 1) {
        messages('Success', 'You have now deleted your account.');
        include $pages . ('loader.html');
        header('refresh:5, logout.php');
        exit();
      }
      else {
        messages('Denger', 'This account cannot be deleted.');
        include $pages . ('loader.html');
        header("refresh:5, profile.php?UserID=$id");
        exit();
      }
    }
    else {
      messages('Denger', 'THIS account IS NOT EXIST.');
      include $pages . ('loader.html');
      header('refresh:5, logout.php');
      exit();
    }
  }
  //! End Delete Data.
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id 				 = filter_var($_SESSION['ID'], FILTER_SANITIZE_NUMBER_INT);
    $name 			 = htmlspecialchars($_POST['name']);
    $email 			 = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $userRole 	 = htmlspecialchars($_SESSION['ACCESS']);
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
        header("refresh:5, profile.php?UserID=$id");
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
          header("refresh:5, profile.php?UserID=$id");
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
                                        `PhoneNumber` = ?,
                                        `Password` = ?
                                  WHERE
                                        `ID` = ?");
        // execute query.
        $stmt->execute([$name, $email, $phoneNumber, $newPassword, $id]);
        // Returns the number of rows affected by the last SQL query.
        $count = $stmt->rowCount();
        // Check Whether The Data Has Been Updated Or Not.
        if ($count = 1) {
          messages('Success', 'The data has been updated successfully.');
          include $pages . ('loader.html');
          header("refresh:5, profile.php?UserID=$id");
          exit();
        }
        else {
          messages('Denger', 'This user cannot be updated.');
          include $pages . ('loader.html');
          header("refresh:5, profile.php?UserID=$id");
          exit();
        }
      }
    }
  }
?>
<div class="body">
	<?php require $temp . ('navbar.php');?>
	<section class="container">
    <?php
      // isset() 			=> Determines if a variable is declared other than NULL.
      // is_numeric() => Finds whether a variable is a number or a numeric string
      // intval() 		=> The numerical value is converted to an integer.
      $UserID = isset($_GET['UserID']) && is_numeric($_GET['UserID']) ? intval($_GET['UserID']) : 0;
      if ($UserID == $_SESSION['ID']) {
        // SQL query [SELECT].
        $stmt = $CONDB->prepare("SELECT * FROM `users` WHERE ID = ?");
        // execute query.
        $stmt->execute([$UserID]);
        // Fetch Data From Database.
        $row = $stmt->fetch();
        // Returns the number of rows affected by the last SQL statement.
        $count = $stmt->rowCount();
        // Check that there is data for this ID or not.
        if ($count == 1) {
          ?>
          <div class="form-pages">
            <h1 class="form-name">My Profile</h1>
            <form action="<?php $_SERVER['PHP_SELF']?>" method="POST">
              <div class="form-content">
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
                <!-- Phone Number -->
                <div class="input-field">
                  <label <?php if($_SESSION['ACCESS'] != 'Freelancer'){echo 'hidden';}?> for="phoneNumber">Phone number</label>
                  <input <?php if($_SESSION['ACCESS'] != 'Freelancer'){echo 'hidden';}?> type="tel" name="phoneNumber" value="0<?= $row['PhoneNumber'] ?>" id="phoneNumber">
                </div>
              </div>
              <!-- Buttons -->
              <div class="buttons">
                <input type="submit" value="Update">
                <input type="reset" value="Reset">
                <div class="btn">
                  <a class="del-btn" href="profile.php?do=Delete&UserID=<?= $_SESSION['ID']?>">Delete My Account</a>
                </div>
              </div>
            </form>
          </div>
          <?php
        }
        else {
          messages('Warning', 'There is no data for this user.');
          include $pages . ('loader.html');
          header('refresh:5,logout.php');
          exit();
        }
      }
      else {
          messages('Warning', 'YOU CAN\'T ACCESS THIS PAGE.');
          include $pages . ('loader.html');
          header('refresh:5,logout.php');
          exit();
      }
    ?>
	</section>
	<?php require $temp . ('footer.php');?>
</div>
<?php require $temp . ('end.body.php');?>