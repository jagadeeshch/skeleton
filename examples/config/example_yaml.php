<?php
include 'config.php';
include '../../A/autoload.php';

$config = new A_Config_Yaml('example1.yaml');
$data = $config->loadFile();

dump($data);