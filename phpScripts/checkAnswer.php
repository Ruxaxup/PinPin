<?php
header("Content-Type: text/html; charset=iso-8859-1");
/*$file = $_POST['file'];
$exercise = $_POST['exercise'];
$option = $_POST['option'];*/

$file = "leccion1.json";
$exercise = 0;
$option = 2;

$string = file_get_contents("../json/{$file}");
$json_a = json_decode($string, true);

//echo $string;

if($option == $json_a['ejercicios'][$exercise]['opcionCorrecta'])
{
    echo 'true';
}else{
    echo 'option: ' . $option;
    echo ' exercise: ' . $exercise;
    echo ' correct: ' . $json_a['ejercicios'][$exercise]['opcionCorrecta'];
    echo ' file: ' . $file;
}

?>