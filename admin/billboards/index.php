<?php include('../includes/header.php')?>
<?php include('../includes/navbar.php')?>
<!doctype html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, intial-scale=1.0">
        <title>Billboards</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide|Sofia|Trirong">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
     

      <style>
        .container1{
            display:flex;
            background:white;
            position:absolute;
            width:100%;
            margin: 10px auto;
            background: white;
            border-radius: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            padding: 20px;
           }
           h1:hover{
            font-size:50px;
            color:red;
           }
        
        .button{
            background:cornflowerblue;
            padding:10px 15px;
            color:black;
            font-weight:bolder;
            font-size:15px;
            border-radius:30px;
            text-decoration:none;
            margin:10px 10px 10px auto;
        }
        .button:hover{
            background:black;
            color:white;
        }

        .popup{
            background:white;
            width:100%;
            height:100%;
            margin-top:200px;
            margin-bottom:200px;
            position: absolute;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            top:0;
            display:none;

          }
         
        input {
            width: 50%;
            padding: 10px;
            margin:10px 50px 4px 500px;
            border: 1px solid #ccc;
            border-radius: 40px;
        }
        .save{
            background:cornflowerblue;
            color:black;
            font-weight:bolder;
            font-size:15px;
            border-radius:30px;
            text-decoration:none;
            margin:10px 10px 10px 626px;
            width: 100px;
            height:50px;
        }
        .cancel{
            background:cornflowerblue;
            color:black;
            font-weight:bolder;
            font-size:15px;
            border-radius:30px;
            text-decoration:none;
            width: 100px;
            height:50px;
        }

       


      </style>
</head>

<body>
    <div class="container1">
        <h1>Billboards</h1>
        <a href="#" class="button" id="button">Create New</a>
    </div>

    <div class="popup">

    <form class="form"> 

  <input type="text" placeholder="new billboard name"><br>
  <input type="file"><br><br>
  <button class="save" type="submit">Save</button>
  <button class="cancel" type="submit">Cancel</button>

      </form> 

    </div>

</body>
       

<script>
    document.getElementById("button").addEventListener("click", function(){
        document.querySelector(".popup").style.display = "flex";  
    })
</script>
    


</html>    
    
<?php include('../includes/footer.php')?>