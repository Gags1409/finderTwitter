<?php
  require_once('OAuth.php');
  require_once('config.php');
  require_once('twitteroauth.php');
  /*********************************************************************************************************
  * Class : twitter
  * Author : Gagandeep Kaur
  * Description : Fetch and display latest tweets,number of tweets ,following and followers from twitter screen name using twitteroAuth library
  ***********************************************************************************************************/
  
  class twitter{
  /**
  * Twitter screen name
  **/
  private $screen_name;
   /**
  * Private twitter object created from twitteroauth class
  **/
  private $twitterObj;
  
   /**
  * Private format variable
  **/
  public $format = 'json';
  
  /**
  * Constructor
  * Setup the twitter screen name 
  * setup the twitteroauth object
  **/
  function __construct($screen_name){
  	$this->screen_name = $screen_name;

	if (CONSUMER_KEY === '' || CONSUMER_SECRET === '' || CONSUMER_KEY === 'CONSUMER_KEY_HERE' || CONSUMER_SECRET === 'CONSUMER_SECRET_HERE') {
 	echo 'You need a consumer key and secret to test the sample code. Get one from <a href="https://dev.twitter.com/apps">dev.twitter.com/apps</a>';
 	exit;
	}
	$this->twitterObj = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN , ACCESS_SECRET);
	$this->twitterObj->format = $this->format;
  } 
  
  /**
  * Function: fetchData
  * Parameters: url ( method & parameters)
  * Return: Response array or Error Message
  * Description: Fetch response from twitter based on url
  **/
  private function fetchData($url,$params) {
      $params['screen_name']= $this->screen_name;
	  $response = $this->twitterObj->get($url, $params);
	  $http_code = $this->twitterObj->http_code;
	   switch ($http_code) {
		case '200':
		case '304':
		  $err = '';
		  break;
		case '400':
		case '401':
		case '403':
		case '404':
		case '406':
		  $err = "Error $http_code occurred!";
		  break;
		case '500':
		case '502':
		case '503':
		  $err = "Error $http_code occurred!";
		  break;
		default:
		  $err = 'No Response received';
	  }
		 if($err==''){
		 return $response;
		 }else{
		 return $err;
		 }

}
/**
  * Function: getTweets
  * Parameters: number of tweets
  * Return: Response Array or Error
  * Description: fetch tweets 
  **/
  function getTweets($count){
       $params= array("count"=>$count);
	   $tweets= $this->fetchData("statuses/user_timeline", $params);
	   if(!is_array($tweets) && count($tweets) < 1){
	       //error happened
		   echo $tweets;
	   }else{
	      //return $tweets;
		  echo "<b>Latest Tweets..</b></br>";
		  foreach($tweets as $t){
		    echo $t->text."</br>";
		  }
		  echo "<br><br><b>Complete Array</b></br>";
		  echo "<pre>"; print_r($tweets);  echo "</pre>";
	   }
       
  }
  
 /**
  * Function: getTweetCount
  * Parameters: null
  * Return: Integer -total number of tweets OR 0
  * Description: total number of tweets of a user
  **/
  function getTweetCount(){
       
     $perpage="200"; //twitter current per page limit
     $limit="3200"; //twitter current limit
	 $max_id = null;
	 $total=0;
	 //count all tweets from all pages upto 3200 pages
     for ($count = $perpage; $count < $limit; $count += $perpage) {
	   $params= array("count"=>$perpage);
	   //include max id to start from next page
	   if($max_id != null){
	   $params['max_id'] =$max_id;
	   }
       $tweets= $this->fetchData("statuses/user_timeline",$params);
	   $total= $total + count($tweets);
       if(count($tweets) < ($perpage-1)){
		  break;
	   }else{
		  $max_id = $tweets[count($tweets) - 1]->id_str; 
	   }
	 }
	   echo "<b>Total no of Tweets..</b>: $total</br>";
       
  }
 /**
  * Function: getFollowers
  * Parameters: cursor, count
  * Return: Response Array or Error
  * Description: fetch followers 
  **/ 
  function getFollowers($cursor= -1,$count = 5){
       $params= array("cursor"=>$cursor,"count"=>$count);
  	   $followers= $this->fetchData("followers/list",$params);
       if(!is_array($followers) && count($followers) < 1){
	       //error happened
		   echo $followers;
	   }else{
	      //return $tweets;
		  echo "<b>Followers..</b></br>";
		  foreach($followers->users as $f){
		    echo "Name: $f->name......Screen Name: $f->screen_name</br>";
		  }
		  echo "<br><br><b>Complete Array</b></br>";
		  echo "<pre>"; print_r($followers);  echo "</pre>";
	   }
  }
  
   /**
  * Function: getFollowing
  * Parameters: cursor, count,skip_status,include_user_entities
  * Return: Response Array or Error
  * Description: fetch following 
  **/ 
  function getFollowing($count = 20,$cursor= -1,$skip_status = 'false', $include_user_entities = 'false'){
       $params= array("cursor"=>$cursor,"count"=>$count,'skip_status'=>$skip_status,"include_user_entities"=> $include_user_entities);
  	   $following= $this->fetchData("friends/list",$params);
       if(!is_array($following) && count($following) < 1){
	       //error happened
		   echo $following;
	   }else{
	      //return $tweets;
		  echo "<b>Following..</b></br>";
		  foreach($following->users as $f){
		    echo "Name: $f->name...  Screen Name: $f->screen_name</br>";
		  }
		  echo "<br><br><b>Complete Array</b></br>";
		  echo "<pre>"; print_r($following);  echo "</pre>";
	   }
  }
  
 
  
}//ec
