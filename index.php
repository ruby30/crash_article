<?php

require_once 'server.php';

$server = new Server();

$server->serveRequest($_GET['action'], $_SERVER['REQUEST_METHOD']);

