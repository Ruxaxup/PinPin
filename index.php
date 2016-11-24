<?php
	$fi = new FilesystemIterator('json/', FilesystemIterator::SKIP_DOTS);
	$lessonCount = iterator_count($fi)
?>


<html>
<head>
<title>Lesson Selection</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
  <link rel="stylesheet" href="http://www.w3schools.com/lib/w3-theme-black.css">
  <link rel="stylesheet" type="text/css" href="css/w3Styles.css">
  <script type="text/javascript">
	  if (typeof(Storage) !== "undefined") {
	    // Code for localStorage/sessionStorage.
	    //alert("Code for localStorage/sessionStorage.");
	} else {
	    // Sorry! No Web Storage support..
	    //alert("Sorry! No Web Storage support..");
	}
  </script>
</head>
<body bgcolor="black">

<header class="w3-container w3-theme w3-padding" id="myHeader">
  <i onclick="w3_open()" class="fa fa-bars w3-xlarge w3-opennav"></i> 
  <div class="w3-center">
  <h4></h4>
  <h1 class="w3-xxxlarge w3-animate-bottom">Las Aventuras de Pin Pin</h1>
    <!--<div class="w3-padding-32">
      <button class="w3-btn w3-xlarge w3-dark-grey w3-hover-light-grey" onclick="document.getElementById('id01').style.display='block'" style="font-weight:900;">LEARN W3.CSS</button>
    </div> -->
  </div>
</header>

<div class = "w3-container">
	<div class="w3-row-padding w3-border" <?php echo 'id="myID"'; ?> >
		
		<?php for($i = 0; $i < $lessonCount; $i++){ ?>		
			<div class="w3-third w3-container w3-black w3-center">
				<form action="json.php" method="post">
					<input type="hidden" name="lesson" <?php echo 'value="leccion' . ($i + 1) . '.json"'; ?>>
					<input type="hidden" name="exercise" value=0>
					<button class="w3-btn w3-teal" type="submit" formmethod="post" formaction="json.php" ><?php echo 'Lección '. ($i  + 1); ?></button>
				</form>
      		</div> 

		<?php } ?>
		
	</div>
</div>

</body>
</html>

