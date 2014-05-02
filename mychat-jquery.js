var pre = {
    time: null,
    nick: null
}
function send(){
    $("#file").upload("upload.php", function(res){       
        var filepath = res;
        $.ajax({
            url: "action.php",
            type: "POST",
            cache: false,
            dataType: "json",
            data: {
                nick: $("#nick").val(),
                words: $("#words").val(),
                filepath: filepath
            },
            success: function(data){
                if(!data){
                    alert("data not found");
                    
                }else{
                    $("#words").val("");
                    $("#abc").html($("#abc").html());
                    $("#words").focus();
                }         
            },
            error: function(xhr, textStatus, errorThrown){
                alert('Error');
            }
        });
    }, "text");
    
}

function read(){
    $.ajax({
        url: "read.php",
        type: "GET",
        dataType: "json",
        success: function(data, status){
            if(data){
                for(var i=0;i<data.length;i++){
                    htmlwrite(data[i]);
                }
                if(pre.time==null){
                    pre.time = data[data.length-1].chtime;
                    pre.nick = data[data.length-1].nick;
                }
            }else{
                alert('Error');
            }       
        },
        error: function(xhr, textStatus, errorThrown){
            alert('Error');
        }
        
    });
    
    
}



function reset(){
    $("#contents").html("");
}

function htmlwrite(data){
    var html = "<div>" + data.chtime + "&nbsp;" + data.nick + "&nbsp;:&nbsp;" + data.words;
    if(data.filepath!=null){
        html += '<br><a href="' + data.filepath + '.jpg"><img src="' + data.filepath + '_t.jpg"></a>'
    }
    html += '</div>'
    $("#contents").prepend(html);
    var cssObj={
        "width": "700px",
        "color": "#FFFFFF",
        "text-align": "left",
        "background-color": "green",
        "border-width": "4px",
        "border-color": "blue",
        "border-style": "dotted"
    }
    $("#contents div").css(cssObj);
}



function check(){
    $.ajax({
        url: "check.php",
        type: "GET",
        dataType: "json",
        success: function(data, status){
            if(data){
                if(data.chtime!=pre.time || data.nick!=pre.nick){
                    //alert(data.chtime+"&"+pre.time+" "+data.nick+"&"+pre.nick);
                    reset();
                    read();                
                    pre.time = data.chtime;
                    pre.nick = data.nick;
                }
            }else{
                alert('Error');
            }       
        },
        error: function(xhr, textStatus, errorThrown){
            alert('Error');
        }
    });
    
}

var Timer
function startTimer(){
    Timer = setInterval("check()",1000);
}

$(function(){   
    
    startTimer();
    
    $("#submit").click(function(){
        send();
        reset();
        read();
    });
    
    $("#start").click(function(){
        alert("更新再開");
        startTimer();
    });
    $("#clear").click(function(){
        alert("更新停止");
        clearInterval(Timer);
    });
    $("#reset").click(function(){       
        $("#abc").html($("#abc").html());
        alert("リセット完了");
    })
});
        