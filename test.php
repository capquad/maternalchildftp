<?php
require('./lib/db/db.php');
require('./lib/db/database.php');

$db = new Database(DBHOST, DBUSER, DBPASS);
// $db->connect(DBNAME);
$password = sha1("08162102300");
$db->insert("staff", ["phone" => "08162102300", "fname" => "Anonymous", "lname" => "Better", "mname" => "Boyfirend", "passwd" => $password]);