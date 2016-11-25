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
  <h4 id="exerciseName"></h4>
  <h1 class="w3-xxxlarge w3-animate-bottom" id="textoEjercicio"></h1>
  </div>
</header>


<div id="id01" class="w3-modal">
    <div class="w3-modal-content w3-card-8 w3-animate-top">
      <header class="w3-container w3-theme-l1"> 
        <span onclick="document.getElementById('id01').style.display='none'; window.location.href = 'index.php';" class="w3-closebtn">X</span>
        <h4>&iexcl;Bien hecho!</h4>
        <h5>Estos fueron tus resultados: </h5>
      </header>
      <div class="w3-padding w3-responsive w3-card-4" id="listaResultados">


      </div>
      <footer class="w3-container w3-theme-l1 w3-center">
        <p>&iexcl;Sigue as&iacute;!</p>
      </footer>
    </div>
</div>

<div class = "w3-container">
	<div class="w3-row-padding w3-border s1">
		
		<div class="w3-col w3-container w3-black w3-center w3-btn s4">				
			<button onclick="checkAnswer(this.id)" class="w3-btn w3-teal" id="btn0" ></button>	
		</div> 


		<div class="w3-col w3-container w3-black w3-center w3-btn s4">				
			<button onclick="checkAnswer(this.id)" class="w3-btn w3-teal" id="btn1" ></button>	
		</div> 


		<div class="w3-col w3-container w3-black w3-center w3-btn s4">				
			<button onclick="checkAnswer(this.id);" class="w3-btn w3-teal" id="btn2" ></button>	
		</div>
		
	</div>
</div>
<hr>
<div class="w3-container">
	<div class="w3-progress-container" style="height:30px;">
		<div id="myBar" class="w3-progressbar w3-green" style="width:0%"></div>
	</div><br>
	<p class="w3-right" style="color:white;" id="demoprgr">0%</p>
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
			console.log(lessonInfo.nombre);
			$('#exerciseName').text(lessonInfo.nombre);
			lessonInfo.ejercicios[exercise_id]['errores'] = 0
			var texto1 = lessonInfo['ejercicios'][exercise_id]['textoP1'];
			var texto2 = lessonInfo['ejercicios'][exercise_id]['textoP2'];
			$("#textoEjercicio").text(texto1 + " _____ " + texto2);
			for(i = 0; i < lessonInfo.ejercicios.length; i++){
				$("#btn"+i).html(lessonInfo['ejercicios'][exercise_id]['opciones'][i]);
			}

		}else{
			//Crear una interfaz de finalizacion de ejercicio			
			var html = '<table class="w3-table w3-striped w3-bordered><thead><tr class="w3-theme w3-center"><th>Ejercicios</th><th>Puntuacion</th></tr></thead><tbody>';
			console.log(lessonInfo.ejercicios.length);
			for (var i = 0, len = lessonInfo.ejercicios.length; i < len; ++i) {
			    html += '<tr class="w3-white w3-center">';
			    
			    html += '<td>' + 'Ejercicio ' + lessonInfo.ejercicios[i].idEjercicio +  '</td>';
			    html += '<td>' + (10 - parseInt(lessonInfo.ejercicios[i].errores)) +  '</td>';
			    
			    html += "</tr>";
			}
			html += '</tbody></table>';
			$(html).appendTo('#listaResultados');
			document.getElementById('id01').style.display='block'
		}
		
	}

	function codeAddress() {  
		setExerciseInfo(0);
		//document.getElementById('id01').style.display='block'
	}
	window.onload = codeAddress;

	function incrementaError(){
		lessonInfo.ejercicios[globalExcercise]['errores'] = parseInt(lessonInfo.ejercicios[globalExcercise]['errores']) + 1;
	}

	function checkAnswer(clicked_id){
		console.log("globalExcercise: "+globalExcercise);
		console.log("opcionCorrecta: "+lessonInfo['ejercicios'][globalExcercise]['opcionCorrecta']);
		console.log("idBtn: "+clicked_id.charAt(clicked_id.length-1));
	  if(clicked_id.charAt(clicked_id.length-1) == lessonInfo['ejercicios'][globalExcercise]['opcionCorrecta']){
	  	//Mostrar Texto Completo, si es posible, colorear respuesta
	  	$("#textoEjercicio").text(lessonInfo['ejercicios'][globalExcercise]['textoCompleto']);
	  	//Reproducir sonido
	  	move();
	  	correctSnd.play();	
	  	//Aumentar el id de ejercicio
	  	globalExcercise++;	  	
	  	//Cambiar de ejercicio en X segundos
	  	setTimeout(updateExercise, 1000);
	  }else{
	  	incrementaError();
	  	errorSnd.play();
	  }
	}

	function updateExercise(){		
		setExerciseInfo(globalExcercise);
		window.scrollTo(0, 0);
	}

	// Progress Bars
	function move() {
		var salto = parseInt(100 / parseInt(lessonInfo.ejercicios.length));
		var complemento = 100 % parseInt(lessonInfo.ejercicios.length);
		var elem = document.getElementById("myBar");   
		var width = (globalExcercise * salto) + complemento;
		var id = setInterval(frame, 10);
		function frame() {
			if (width == (salto * globalExcercise) + complemento) {
		    	clearInterval(id);
		    } else {
		    	width++; 
		    	elem.style.width = width + '%'; 
		    	document.getElementById("demoprgr").innerHTML = width * 1  + '%';
		    }
		}
		$('#myBar').style
	}

</script>

</body>
</html>

