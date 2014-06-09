<?php

function redirect($page)
{
  echo "<script type='text/javascript'>
   window.location = '$page';
   </script>";	
}