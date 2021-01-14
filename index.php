<?php
session_start();
require('./lib/server/authorize.php');

authorizeLogin(); // check if user is logged in

require('./lib/db/db.php');
require('./lib/db/database.php');
require('./lib/config/pagesetup.php');

require('./lib/util/header.php');
