<head>
<script language="JavaScript" src="/src/js/jquery.min.js"></script>
    <script>
        function addData(cards){
          $.ajax({url:"insertData.php",type:"POST",data:{ id : "menuId" },success:function(result){
            $("#renderTable").html(result);
          }});
        }
    </script>

</head>