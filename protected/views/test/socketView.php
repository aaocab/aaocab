<?php
//   /  Yii::app()->clientScript->registerScriptFile("http://localhost:3000/socket.io/socket.io.js");
Yii::app()->clientScript->registerScriptFile("/js/gozo/worker.js", null, ["type" => "module"]);
$elephant = new \ElephantIO\Client(new Version4X('http://localhost:3000', []));
?>

<script>
 
</script>