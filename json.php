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
  <link rel="stylesheet" type="text/css" href="css/imgFilters.css">
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
<body bgcolor="white">

<header class="w3-container w3-theme-light w3-padding" id="myHeader">
  <i onclick="w3_open()" class="fa fa-bars w3-xlarge w3-opennav"></i> 
  <div class="w3-center">
  <h4 id="exerciseName"></h4>
  <h1 class="w3-xxxlarge w3-animate-bottom" id="textoEjercicio"></h1>
  </div>
</header>

<!-- Dialogo que muestra los resultados -->
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
        <p>&iquest;Cuanto sacar&aacute;s ma&ntilde;ana?</p>
      </footer>
    </div>
</div>

<!-- Botones con respuestas -->
<div class = "w3-container">
	<div class="w3-row-padding w3-border">
		
		<div class="w3-col w3-container w3-orange w3-center w3-btn w3-third">				
			<img id="btn0_error" src="img/error.png" style="width:10%; height:3%">
			<img id="btn0_ok"src="img/ok.png" style="width:10%; height:3%">
			<button onclick="checkAnswer(this.id, actualExercise)" class="w3-btn w3-orange" id="btn0" style="font-size:30px;"></button>
		</div> 


		<div class="w3-col w3-container w3-blue w3-center w3-btn w3-third">				
			<img id="btn1_error" src="img/error.png" style="width:10%; height:3%">
			<img id="btn1_ok"src="img/ok.png" style="width:10%; height:3%">
			<button onclick="checkAnswer(this.id, actualExercise)" class="w3-btn w3-blue" id="btn1" style="font-size:30px;"></button>	
		</div> 


		<div class="w3-col w3-container w3-yellow w3-center w3-btn w3-third">				
			<img id="btn2_error" src="img/error.png" style="width:10%; height:3%">
			<img id="btn2_ok"src="img/ok.png" style="width:10%; height:3%">
			<button onclick="checkAnswer(this.id, actualExercise);" class="w3-btn w3-yellow" id="btn2" style="font-size:30px;"></button>	
		</div>
		
	</div>
</div>

<!-- Botones siguiente escondido -->
<div id="divContinue" class="w3-center">
	<button onclick="updateExercise();" class="w3-btn w3-teal" id="btnContinue" >Continuar</button>
</div>

<hr>

<!-- Barra de progreso -->
<div class="w3-container">
	<div class="w3-progress-container" style="height:30px;">
		<div id="myBar" class="w3-progressbar w3-blue" style="width:0%"></div>
	</div><br>
	<p class="w3-right w3-theme-light" style="color:white;" id="demoprgr">0%</p>
</div>


<!-- JAVASCRIPT -->
<script type="text/javascript">
	var globalExcercise = 0;
	var actualExercise = 0;
	var exercisesToRepeat = [];
	var errores = {};

	if(!!window.chrome && !!window.chrome.webstore){
		var correctSnd = new Audio("sounds/correct.ogg")
		var errorSnd = new Audio("sounds/error.ogg")	
	}else{
		var correctSnd = new Audio("sounds/correct.wav")
		var errorSnd = new Audio("sounds/error.wav")
	}

	function initComponents() {		 
		setExerciseInfo(0);
		//Se muestra el ejercicio a realizar
		console.log(lessonInfo.nombre);
		//Se inicializa diccionario de erroes
		for(i = 0; i < lessonInfo.ejercicios.length; i++){
			errores[i] = 0;
		}
		//document.getElementById('id01').style.display='block'
	}

	window.onload = initComponents;

	
	function setExerciseInfo(exercise_id){
		actualExercise = exercise_id;
		$("#divContinue").hide();		
		//Si no se ha llegado al ultimo ejercicio o hay ejercicios por repetir
		if(exercise_id < lessonInfo['ejercicios'].length){			
			hideErrorAndOKimgs();
			$('#exerciseName').text(lessonInfo.nombre);
			lessonInfo.ejercicios[exercise_id]['errores'] = 0
			var texto1 = lessonInfo['ejercicios'][exercise_id]['textoP1'];
			var texto2 = lessonInfo['ejercicios'][exercise_id]['textoP2'];
			$("#textoEjercicio").text(texto1 + " _____ " + texto2);
			var optionsCount = lessonInfo['ejercicios'][exercise_id]['opciones'].length;
			var indexes = [];
			//Inicializamos el arreglo de los indices segun el numero de respuestas que tenga el ejercicio
			for(i = 0; i < optionsCount; i++){
				indexes.push(i);
			}
			//Mezclamos los  indices para crear aleatoriedad.
			shuffle(indexes);
			//Creamos los botones de las respuestas
			for(i = 0; i < optionsCount; i++){				
				$("#btn"+i).html(lessonInfo['ejercicios'][exercise_id]['opciones'][indexes[i]]);
			}
		}
		
	}

	function updateExercise(){		
		//Aumentar el id de ejercicio		
		//Si ya se hizo el ultimo ejercicio, se proseguira a revisar si hay preguntar por repetir
		if(globalExcercise  + 1 >= lessonInfo.ejercicios.length)
		{
			//Sacamos el primero de la lista, si vuelve a estar incorrecto se agrega nuevamente hasta que termine
			console.log("Exercises to repeat Size: "+exercisesToRepeat.length);
			if(exercisesToRepeat.length > 0)
			{
				var exerciseToRepeat = exercisesToRepeat.shift();
				console.log("Repitiendo: " + exerciseToRepeat);
				setExerciseInfo(exerciseToRepeat);	
				enableButtons();
			}else{
				//Mostrar resultados
				showResults();
			}
			window.scrollTo(0, 0);
		}
		else
		{
			globalExcercise++;		
			increaseProgressBar();	
			setExerciseInfo(globalExcercise);
			enableButtons();
			window.scrollTo(0, 0);
		}		
	}

	function showResults(){
		//Crear una interfaz de finalizacion de ejercicio			
		var html = '<table class="w3-table w3-striped w3-bordered><thead><tr class="w3-theme w3-center"><th>Ejercicios</th><th>Puntuacion</th></tr></thead><tbody>';
		console.log(lessonInfo.ejercicios.length);
		for (var i = 0, len = lessonInfo.ejercicios.length; i < len; ++i) {
			html += '<tr class="w3-white w3-center">';

			html += '<td>' + 'Ejercicio ' + lessonInfo.ejercicios[i].idEjercicio + '</td>';
			html += '<td>' + (10 - parseInt(errores[i])) + '</td>';

			html += "</tr>";
		}
		html += '</tbody></table>';
		$(html).appendTo('#listaResultados');
		document.getElementById('id01').style.display = 'block'
	}	

	function checkAnswer(clicked_id, exercise_id){
		console.log("ActualExcercise: "+exercise_id);
		console.log("opcionCorrecta: "+lessonInfo['ejercicios'][exercise_id]['opcionCorrecta']);
		console.log("Ejercicios Totales:"+lessonInfo.ejercicios.length);

		var opcionSeleccionada = $('#'+clicked_id).text();
		var opcionCorrectaIndex = lessonInfo['ejercicios'][exercise_id]['opcionCorrecta'];
		var opcionCorrecta = lessonInfo['ejercicios'][exercise_id]['opciones'][opcionCorrectaIndex]
		//Respuesta Correcta
		if (opcionSeleccionada === opcionCorrecta) {
			//Mostrar Texto Completo
			$("#textoEjercicio").text(lessonInfo['ejercicios'][exercise_id]['textoCompleto']);			
			//Mostrar imagen de OK
			$("#"+clicked_id+"_ok").show();
			//Reproducir sonido
			correctSnd.play();			
		} else {
			//Respuesta incorrecta
			//Mostrar imagen de error
			$("#"+clicked_id+"_error").show();
			//Agregar ejercicio a la lista de repeticion
			exercisesToRepeat.push(exercise_id);
			incrementaError(exercise_id);
			errorSnd.play();
		}
		//Aumenta progreso
		//increaseProgressBar();
		//Cambiar de ejercicio en X segundos
		//setTimeout(updateExercise, 1000);
		disableButtons();
		//Mostrar boton de continuar
		$("#divContinue").show();
	}

	function incrementaError(exercise_id){
		if(!errores[exercise_id])
		{
			errores[exercise_id]++;
			errores[exercise_id] = errores[exercise_id] + 1;
		}
		//lessonInfo.ejercicios[exercise_id]['errores'] = parseInt(lessonInfo.ejercicios[exercise_id]['errores']) + 1;
		//console.log("Ejercicio: "+exercise_id+", tiene "+lessonInfo.ejercicios[exercise_id]['errores']+" error(es).");
	}

	
	/**
		Oculta las imagenes de error y de Ok de los botones
	*/
	function hideErrorAndOKimgs(){
		for(i = 0; i < lessonInfo['ejercicios'][actualExercise]['opciones'].length; i++){
			$("#btn" + i+ "_error").hide();
			$("#btn" + i+ "_ok").hide();
		}
	}

	/**
		Cuando termina un ejercicio las opciones se deshabilitan para evitar conteo de errores
		o inserciones a la lista de ejercicios a repetir
	*/
	function disableButtons(){
		for(i = 0; i < lessonInfo['ejercicios'][actualExercise]['opciones'].length; i++){
			$("#btn" + i).prop('disabled',true);
		}
	}
	/**
		Cuando se carga un nuevo ejercicio se habilitan los botones de respuesta para que
		el usuario pueda interactuar.
	*/
	function enableButtons(){
		for(i = 0; i < lessonInfo['ejercicios'][actualExercise]['opciones'].length; i++){
			$("#btn" + i).prop('disabled',false);
		}
	}

	// Progress Bars
	function increaseProgressBar() {		
		var salto = parseInt(100 / parseInt(lessonInfo.ejercicios.length));
		var complemento = 100 % parseInt(lessonInfo.ejercicios.length);
		var elem = document.getElementById("myBar");   
		//var width = (globalExcercise * salto) + complemento;
		var width = globalExcercise * salto;
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

	function getRandomInt(min, max) {
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}

	/**
		The de-facto unbiased shuffle algorithm is the Fisher-Yates (aka Knuth) Shuffle.

		See https://github.com/coolaj86/knuth-shuffle
	*/
	function shuffle(array) {
		var currentIndex = array.length,
			temporaryValue, randomIndex;

		// While there remain elements to shuffle...
		while (0 !== currentIndex) {

			// Pick a remaining element...
			randomIndex = Math.floor(Math.random() * currentIndex);
			currentIndex -= 1;

			// And swap it with the current element.
			temporaryValue = array[currentIndex];
			array[currentIndex] = array[randomIndex];
			array[randomIndex] = temporaryValue;
		}

		return array;
	}

</script>

</body>
</html>

