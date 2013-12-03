<html>
    <head>
        <title>Notifications</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <script src="assets/jquery-2.0.3.min.js"></script>
        <script>
            //save what glom returns in data
            var data = new Array();
            //names stores data[0], the names array
            var names = new Array();
            //times stores data[1], the times array
            var times = new Array();
            //calls glom.php and gets the stuff in the current database
            function getDataz(){
                var thebox = "";
                $.ajax({
                    type:"POST",
                    url:"glom.php",
                    success: function(data){;}
                }).done(function(msg){
                    //parse the JSON object into an array
                    data = jQuery.parseJSON(msg);
                    names = data[0];
                    times = data[1];
                    //debugging!
                    console.log(msg);
                });
                //put the data into a string to be stuck to a select list
                for(x = 0; x < names.length; x++){
                    thebox+="<option value=\"" + names[x] + "\">" + names[x] + "</option>";
                }
                //jQuery! grab the selectlist
                $("#selectlist")
                    //find options
                    .find('option')
                    //remove 'em
                    .remove()
                    .end()
                    //re-append the select list
                    .append(thebox)
                ;
            }
            function sendReservation(){
                theList = document.getElementById("selectlist");
                var selected = theList.selectedIndex;
                var list = theList.options;
                $.ajax({
                   type:"POST",
                   url:"final.php",
                   data: {userno: list[selected].value}
                }).done(function(msg){
                    console.log("Data saved: " + msg);
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