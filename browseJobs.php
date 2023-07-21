<?php
	session_start();
	$pageTitle = 'Jobs by Categories';
	require('initialize.php');
?>
<div class="body">
  <?php require $temp . ('navbar.php');?>
  <section class="container">
    <div class="name-of-page">
      <h1>Categories</h1>
    </div>
    <div class="cards">
      <?php
        $stmt = $CONDB->prepare("SELECT * FROM `categories`");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $count = $stmt->rowCount();
        if ($count > 0) {
          foreach ($rows as $row) {
            ?>
              <a href="allJobs.php?categoryName=<?= $row['CategoryName']?>&categoryID=<?= $row['CategoryID']?>" class="link-card">
                <div class="card">
                  <div class="card-img">
                    <img class="img" src="users/admin/data/upload/images/categoryImages/<?= $row['CategoryPhoto']?>"alt=""/>
                  </div>
                  <div class="card-name">
                    <p class="name"><?= $row['CategoryName']?></p>
                  </div>  
                </div>
              </a>
            <?php
          }
        }
        else {
          include $pages . ('coming.soon.html');
        }
      ?>
    </div>
  </section>
  <?php require $temp . ('footer.php');?>
</div>
<?php require $temp . ('end.body.php');?>