<?php if (isset($_GET['p']) && strlen($_GET['p']) > 0) $p = ($_GET['p']); else
$p = 1;
$directory = "pages/";
if (glob("$directory*.php") != false)
{
 $limit = count(glob("$directory*.php"));
 echo $filecount;
}
else
{
 $limit = 0;
}


$back = $p -1;$next = $p +1;
include('header.php');
if (isset($_GET['p'])) { include('pages/page'.$p.'.php'); }
 else  {include('1.php'); };
include('footer.php');