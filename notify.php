<?php
//This page serves to send out notifications when reservations are ready

?>

<html>
    <head>
        <title>Notifications</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <script src="assets/jquery-2.0.3.min.js"></script>
        <script>
            var data = new Array();
            var names = new Array();
            var times = new Array();
            var thebox = "";
            function getDataz(){
                $.ajax({
                    type:"POST",
                    url:"glom.php",
                    success: function(data){;}
                }).done(function(msg){
                    data = jQuery.parseJSON(msg);
                    names = data[0];
                    times = data[1];
                    console.log(data.length);
                });
                for(x = 0; x < names.length; x++){
                    thebox+="<option value=\"" + names[x] + "\">" + names[x] + "</option>";
                }
                $("#selectlist").append(thebox);
            }
            function sendReservation(){
                var selected = document.getElemenyById("selectlist").selectedIndex;
                var list = document.getElementById("selectlist").options;
                $.ajax({
                   type:"POST",
                   url:"final.php",
                   data: (list[selected])
                });
            }
        </script>
    </head>
    <body>
        <input type="button" onclick="getDataz();" value="Get Data!"/>
        <div id="debug">
            <select size="6" id="selectlist">
                
            </select>
        </div>
        <input type="button" onclick="sendReservation();" value="Send Reservation"/>
    </body>
</html>