<?php

//connect to mysql server, server name: main, database username: root
$link_ID=mysql_connect("localhost","root","altis0725");
mysql_select_db("test"); //abc is the database name
mysql_set_charset('utf8');
$str="select * from chat ORDER BY chtime DESC;" ;
$result=mysql_query($str, $link_ID);
$rows=mysql_num_rows($result);

@mysql_data_seek($result,$rows);

list($chtime, $nick, $words)=mysql_fetch_row($result);
$massage = array(
    "chtime"=>$chtime,
    "nick"=>$nick,
    "words"=>$words
); 

mysql_close($link_ID);

$jsontext = json_encode($massage);
echo $jsontext;

?>
