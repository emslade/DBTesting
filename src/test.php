<?php

require 'User.php';
require 'UserMapper.php';

$db = new PDO('sqlite:' . __DIR__ . '/../example.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$mapper = new \UserMapper($db);

$id = 1;

$user = $mapper->findById($id);

var_dump($user);

$user = new \User;
$user->setUsername('ade');
$mapper->insert($user);

$u = $mapper->findById(2);

var_dump($u);
