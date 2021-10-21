
<?php

function checkAdjustWindDirection($dir)
{
   $return = "";

   if (empty($dir) || !is_numeric($dir)) {
      $return = "missing";
   } else { // should have a number

      $newDir = $dir; // took out 7/26 - 60; // difference between observations of real wind direction and reported. 6/27/2021

      if ($newDir < 0) {
         $newDir = 360-$newDir;
      }

      if ($newDir > 360) {
         $newDir = $newDir-360;
      }

      $return = $newDir;
   }

   return $return;
}