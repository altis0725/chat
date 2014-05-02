<?php  
    $nick = $_POST["nick"];
    $words = $_POST["words"];
    $filepath = $_POST["filepath"];
    
    $link_ID=mysql_connect("localhost","root","altis0725");
    mysql_select_db("test"); // abc is the database name
    mysql_set_charset('utf8');
    $time=date(y).date(m).date(d).date(H).date(i).date(s); //get current time
    if($filepath!=null){
        $str="INSERT INTO chat(chtime,nick,words,filepath) values ('$time','$nick','$words','$filepath');" ;
    }else{
        $str="INSERT INTO chat(chtime,nick,words) values ('$time','$nick','$words');" ;    
    }
    $result = mysql_query($str,$link_ID); //save message record into database
    mysql_close($link_ID);
    
    $result = json_encode($result);
    echo $result;
?>