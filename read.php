<?php

//connect to mysql server, server name: main, database username: root
$link_ID=mysql_connect("localhost","root","altis0725");
mysql_select_db("test"); //abc is the database name
mysql_set_charset('utf8');
$str="select * from chat ORDER BY chtime;" ;
$result=mysql_query($str, $link_ID);
$rows=mysql_num_rows($result);
//get the latest 15 messages
@mysql_data_seek($result,$rows-30);
//if the number of messages<30, get all of the messages
if ($rows<30) $l=$rows;
else $l=30;
for ($i=1;$i<=$l; $i++) {
    list($chtime, $nick, $words, $filepath)=mysql_fetch_row($result);
    //$massage .= "<div>".nl2br(htmlentities($chtime." ".$nick.":".$words,ENT_QUOTES,"UTF-8"))."</div>";
    $massage[] = array(
        "chtime"=>$chtime,
        "nick"=>$nick,
        "words"=>$words,
        "filepath"=>$filepath
    );
} 
//delete the old messages(only keep the newest 100 only)
@mysql_data_seek($result,$rows-105);
list($limtime)=mysql_fetch_row($result);
$str="DELETE FROM chat WHERE chtime<'$limtime' ;" ;
$result=mysql_query($str,$link_ID);
mysql_close($link_ID);

$jsontext = json_encode($massage);
echo $jsontext;

?>
