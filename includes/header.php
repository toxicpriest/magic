<head>
<script language="JavaScript" src="/src/js/jquery.min.js"></script>
    <script>
        function addData(){
          $.ajax({url:"insertData.php",success:function(result){
            $("#renderTable").html(result);
          }});
        }
    </script>

</head>