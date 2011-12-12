<?php

require 'bootstrap.php';

$virtualhost = new \Application\Cli\Virtualhost();
$virtualhost->run();

/* 
Use this if you want an interactive shell
$shell = new Symfony\Component\Console\Shell($virtualhost);
$shell->run();
*/