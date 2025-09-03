<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen"/>
    
    <title>Server dos Sócios</title>
</head>
<body>
<div class="cards">
   <?php $scan = scandir('./');
   foreach($scan as $file) {
      if (is_dir("$file") && $file!='.' && $file!='..') {?>
      <div class="card text-center" style="width: 25rem;">
      <div class="card-body">
         <h5 class="card-title "> <?php echo $file ?></h5>
         <div class="card-buttons">
            <form method="post">
            <input type="submit" class="btn btn-success" name="run" id="run" value="RUN" /><br/>
            <input type="hidden" id="folder" name="folder" value=<?=$file?>>
            </form>

            <form method="post">
            <input type="submit" class="btn btn-danger" name="stop" id="stop" value="STOP" /><br/>
            <input type="hidden" id="folder" name="folder" value=<?=$file?>>
            </form>

            <?php if($file!="Elanicraft")
            {?>
               <form method="post">
               <input type="submit" class="btn btn-warning" name="delete" id="delete" value="DELETE WORLD" /><br/>
               <input type="hidden" id="folder" name="folder" value=<?=$file?>>
               </form>
            <?php
         }
         ?>
         </div>
      </div>
   </div>
   <?php }
   } ?>
</div>
 


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">World delete confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete the world in <?php echo $_POST["folder"]?> server? 
      </div>
      <div class="modal-footer">
      <form method="post">
        <input type="submit" name="yes" id="yes" value="Yes" class="btn btn-success" />
        <input type="hidden" id="folder" name="folder" value=<?=$_POST["folder"]?>>
      </form>
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>

<?php
function deleteAll($str) {
      
   // Check for files
   if (is_file($str)) {
         
       // If it is file then remove by
       // using unlink function
       return unlink($str);
   }
     
   // If it is a directory.
   elseif (is_dir($str)) {
         
       // Get the list of the files in this
       // directory
       $scan = glob(rtrim($str, '/').'/*');
         
       // Loop through the list of files
       foreach($scan as $index=>$path) {
             
           // Call recursive function
           deleteAll($path);
       }
         
       // Remove the directory itself
       return @rmdir($str);
   }
}

function run()
{
   exec("ps x | grep DmineServer",$o);
   echo "<br>";
   preg_match_all('/(\d*).*DmineServer=(\w*)/',$o[0],$matches);

   if(sizeof($matches[0])!=0)
   {
      $name=$matches[2][0];
      echo "server ".$name." already running!";
      echo sizeof($matches);
   }
   else 
   {
      $pid = pcntl_fork();
      if ($pid == -1) {
      die('could not fork');
      } else if ($pid) {
          echo "Starting server";
          pcntl_wait($status); //Protect against Zombie children
      } else {
          exec('gnome-terminal -- bash '.escapeshellarg($_POST["folder"]).'/ServerStart.sh ');
      }

   }
  
}

function stop()
{
   exec("ps x | grep DmineServer",$o);
   echo "<br>";
   preg_match_all('/ (\d*).*DmineServer=(\w*)/',$o[0],$matches);
   $pid=$matches[1][0];
   $name=$matches[2][0]; 
   echo "<br>";
   if(strcasecmp($name,$_POST["folder"])==0)
   {
    
      if(posix_kill($pid,2))
      echo "server stopped";
      else echo "error stopping server";
   }
   else echo "that server is not running";
   //echo "Your stop function on button click is working for folder ".$_POST["folder"];
}

function delete()
{
?>
<script>$('#exampleModal').modal('toggle');</script>
<?php

}

function yes()
{
   ?> <script>$('#exampleModal').modal('hide');</script> <?php
   
   if(deleteAll($_POST["folder"]."/world"))
   echo "Folder ".$_POST["folder"]."/world"." was removed";
   else echo "Error removing folder ".$_POST["folder"]."/world";
}

if(array_key_exists('run',$_POST)){
  
   run();
}

if(array_key_exists('stop',$_POST)){
   stop();
}

if(array_key_exists('delete',$_POST)){
   delete();
}

if(array_key_exists('yes',$_POST)){ 
   yes();
  }

?>
</body>
</html>
