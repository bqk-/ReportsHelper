<?php
  require __DIR__.'/../load.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>WFC PDF ADMIN</title>
    <style>
        td, th {
          width: 4rem;
          height: 2rem;
          border: 1px solid #ccc;
          text-align: center;
        }
        th {
          background: lightblue;
          border-color: white;
        }
    </style>
</head>
<body>

<?php
$authorized=array('clcsblack@gmail.com',
                  'stevecfischer@gmail.com');

if(isset($_SESSION['email']) && in_array($_SESSION['email'], $authorized))
{
    echo '<h1>Admin panel</h1>';
    if(isset($_GET) && !empty($_GET['action']))
    {
        $_GET['action']=preg_replace('#[^0-9a-zA-Z]#i', '', $_GET['action']);
        include $_GET['action'].'.php';
    }
    else
    {
        echo '<p>
                Available options :
                <ul>
                    <li><a href="index.php?action=profiles">Profiles</a></li>
                    <li><a href="index.php?action=pdfs">Send PDF</a></li>
                </ul>
            </p>';
    }

}
else
    exit('Not authorized.');

?>
</body>
    <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
    <script type="text/javascript" src="../js/toastr.min.js"></script>
    <script type="text/javascript" src="admin.js"></script>
</html>