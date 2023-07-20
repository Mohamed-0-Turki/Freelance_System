<?php
	//! Start Function Page Title
	function pageTitle() {
		global $pageTitle;
		if (isset($pageTitle)) {
			echo $pageTitle;
		} else {
			echo 'Page';
		}
	}
	//! End Function Page Title

	//! Start Function Get Latest
	function getLatest($select, $table, $where = null, $orderBy , $order = 'DESC' , $limit = 5) {
		global $CONDB;
		$getLatest = $CONDB->prepare("SELECT $select FROM $table $where ORDER BY $orderBy $order LIMIT $limit");
		$getLatest->execute();
		$getLatestRows = $getLatest->fetchAll();
		return $getLatestRows;
	}
	//! End Function Get Latest

	//! Start Function messages.
	function messages($type, $content){
		if ($type == 'Info') {
			?>
			<div class="items-message m1" id="choose-display">
					<div class="icon-mass"><i class="fa-solid fa-bell"></i></div>
					<p class="message">Info: <?php echo $content;?></p>
					<div class="icon-mess-2"><i class="fa-solid fa-xmark"></i></div>
			</div>
			<?php
		}
		if ($type == 'Success') {
			?>
			<div class="items-message m2" id="choose-display">
					<div class="icon-mass"><i class="fa-solid fa-check"></i></div>
					<p class="message">Success: <?php echo $content;?></p>
					<div class="icon-mess-2"><i class="fa-solid fa-xmark"></i></div>
			</div>
			<?php
		}
		if ($type == 'Warning') {
			?>
			<div class="items-message m3" id="choose-display">
					<div class="icon-mass"><i class="fa-solid fa-triangle-exclamation"></i></div>
					<p class="message">Warning: <?php echo $content;?></p>
					<div class="icon-mess-2"><i class="fa-solid fa-xmark"></i></div>
			</div>
			<?php
		}
		if ($type == 'Denger') {
			?>
			<div class="items-message m4" id="choose-display">
					<div class="icon-mass"><i class="fa-solid fa-xmark"></i></div>
					<p class="message">Danger: <?php echo $content;?></p>
					<div class="icon-mess-2"><i class="fa fa-solid fa-xmark"></i></div>
			</div>
			<?php
		}
	}
	//! End Function messages.

	//! Start Function Counter.
	function counter($table, $where) {
		global $CONDB;
		$c = $CONDB->prepare("SELECT * FROM `$table` $where");
		$c->execute();
		echo $c->rowCount();
	}
	//! End Function Counter.

	//! Start Function sanitize_data.
	function sanitize_data($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = strip_tags($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	//! End Function sanitize_data.

	//! Start Function unique_id.
	function unique_id() {
		$id = rand(1,918576324) . time();;
		return $id;
	}
	//! End Function unique_id.
?>