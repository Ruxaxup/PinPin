<?php
header("Content-Type: text/html; charset=iso-8859-1");
$archivo = $_POST["lesson"];

$string = file_get_contents("json/{$archivo}");
$json_a = json_decode($string, true);


/*foreach ($json_a as $person_name => $person_a) {
    echo $person_a['opcionCorrecta'];
    echo $person_a['opciones'][0] . "====";
}*/

?>

<html>
<head>
<title>Lesson Selection</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
  <link rel="stylesheet" href="http://www.w3schools.com/lib/w3-theme-black.css">
  <link rel="stylesheet" type="text/css" href="css/w3Styles.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script type="text/javascript">  	
  	var lessonInfo = <?php echo $string; ?>;
	if (typeof(Storage) !== "undefined") {
	    // Code for localStorage/sessionStorage.
	    if(!localStorage[lessonInfo['id']]){
	    	localStorage[lessonInfo['id']] = 0;
	    }else{
	    	//alert(localStorage[[lessonInfo['id']]]);	    	
	    	//alert(lessonInfo['nombre']);
	    }
	} else {
	    // Sorry! No Web Storage support..	    
	}
  </script>


</head>
<body bgcolor="black">

<header class="w3-container w3-theme w3-padding" id="myHeader">
  <i onclick="w3_open()" class="fa fa-bars w3-xlarge w3-opennav"></i> 
  <div class="w3-center">
  <h4></h4>
  <h1 class="w3-xxxlarge w3-animate-bottom" id="textoEjercicio"></h1>
  </div>
</header>

<div class = "w3-container">
	<div class="w3-row-padding w3-border s1">
		
		<div class="w3-col w3-container w3-black w3-center s4">				
			<button onclick="checkAnswer(this.id)" class="w3-btn w3-teal" id="btn0" ></button>	
		</div> 


		<div class="w3-col w3-container w3-black w3-center s4">				
			<button onclick="checkAnswer(this.id)" class="w3-btn w3-teal" id="btn1" ></button>	
		</div> 


		<div class="w3-col w3-container w3-black w3-center s4">				
			<button onclick="checkAnswer(this.id)" class="w3-btn w3-teal" id="btn2" ></button>	
		</div> 
		
	</div>
</div>

<script type="text/javascript">
	var globalExcercise = 0;
	if(!!window.chrome && !!window.chrome.webstore){
		var correctSnd = new Audio("sounds/correct.ogg")
		var errorSnd = new Audio("sounds/error.ogg")	
	}else{
		var correctSnd = new Audio("sounds/correct.wav")
		var errorSnd = new Audio("sounds/error.wav")
	}
	

	function setExerciseInfo(exercise_id){
		if(exercise_id < lessonInfo['ejercicios'].length){
			var texto1 = lessonInfo['ejercicios'][exercise_id]['textoP1'];
			var texto2 = lessonInfo['ejercicios'][exercise_id]['textoP2'];
			$("#textoEjercicio").text(texto1 + " _____ " + texto2);
			$("#btn0").html(lessonInfo['ejercicios'][exercise_id]['opciones'][0]);
			$("#btn1").html(lessonInfo['ejercicios'][exercise_id]['opciones'][1]);
			$("#btn2").html(lessonInfo['ejercicios'][exercise_id]['opciones'][2]);
		}else{
			//Crear una interfaz de finalizacion de ejercicio
			window.location.href = "index.php";
		}
		
	}

	function codeAddress() {  
		setExerciseInfo(0);
	}
	window.onload = codeAddress;

	/*function checkAnswer(clicked_id){		
		$.ajax({
		  type: "POST",
		  url: "phpScripts/checkAnswer.php",
		  data: { file: <?php echo '"'.$archivo.'"'; ?>, option: clicked_id.charAt(clicked_id.length-1), exercise : globalExcercise }
		}).done(function( msg ) {
		  //alert( "Data Saved: " + msg );
		  if(msg === "true"){
		  	//Mostrar Texto Completo, si es posible, colorear respuesta
		  	$("#textoEjercicio").text(lessonInfo['ejercicios'][globalExcercise]['textoCompleto']);
		  	//Reproducir sonido
		  	correctSnd.play();	
		  	//Aumentar el id de ejercicio
		  	globalExcercise++;
		  	//Cambiar de ejercicio en X segundos
		  	setTimeout(updateExercise, 1000);
		  }else{
		  	errorSnd.play();
		  	console.log(msg);
		  }
		});  
	}*/

	function checkAnswer(clicked_id){
		console.log("globalExcercise: "+globalExcercise);
		console.log("opcionCorrecta: "+lessonInfo['ejercicios'][globalExcercise]['opcionCorrecta']);
		console.log("idBtn: "+clicked_id.charAt(clicked_id.length-1));
	  if(clicked_id.charAt(clicked_id.length-1) == lessonInfo['ejercicios'][globalExcercise]['opcionCorrecta']){
	  	//Mostrar Texto Completo, si es posible, colorear respuesta
	  	$("#textoEjercicio").text(lessonInfo['ejercicios'][globalExcercise]['textoCompleto']);
	  	//Reproducir sonido
	  	correctSnd.play();	
	  	//Aumentar el id de ejercicio
	  	globalExcercise++;
	  	//Cambiar de ejercicio en X segundos
	  	setTimeout(updateExercise, 1000);
	  }else{
	  	errorSnd.play();
	  }
	}

	function updateExercise(){
		setExerciseInfo(globalExcercise);
	}
</script>

</body>
</html>

