<?php
session_start();    //启动Session

if (empty($_SESSION['a'])) {
	$_SESSION['a'] = "aaa";
} else {
	$_SESSION['a'] = "ccc";
}