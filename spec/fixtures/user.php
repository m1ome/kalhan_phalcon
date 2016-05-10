<?php
use \Api\Models\Users;

$usersData = [
	[1, 'Pavel', 29, 'admin'],
	[2, 'Max', 30, 'admin'],
	[3, 'Igor', 35, 'moderator'],
	[4, 'Mark', 22, 'user']
];

foreach($usersData as $userData) {
	$user = new Users();
	$user->id = $userData[0];
	$user->name = $userData[1];
	$user->age = $userData[2];
	$user->type = $userData[3];
	$user->create();
}