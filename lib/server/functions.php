<?php

function logEvent($message, $level = 'info')
{
	return true;
}

function deliverJsonOutput($data)
{
	echo json_encode($data, 512);
	exit();
}