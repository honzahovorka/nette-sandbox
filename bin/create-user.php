#!/usr/bin/env php
<?php

if (!isset($_SERVER['argv'][2])) {
	echo 'Add new user to database.

Usage:
  create-user.php <username> <password>

';
	exit(1);
}

list(, $user, $password) = $_SERVER['argv'];

$container = require __DIR__ . '/../app/bootstrap.php';
$container->getByType('App\UserModel')->add($user, $password);

echo "User $user was added.\n";
