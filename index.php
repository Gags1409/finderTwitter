<?php
require_once('twitter.php');

/*****create Object - accepts twitter screen name*****/
$obj= new twitter('screen_name'); 

/*****get latest tweets of user ( not >200)- specify the count ****/
$obj->getTweets(5);

echo "------------------------------------------------------------------------------------------------------<br>";
/**** get followers of user ****/
$obj->getFollowers();

echo "------------------------------------------------------------------------------------------------------<br>";
/**** get followings of user ****/
$obj->getFollowing(); 

echo "------------------------------------------------------------------------------------------------------<br>";
/**** get Tweet Count of user ****/
$obj->getTweetCount(); 
