<?php

add_shortcode('WindIOT-Table', 'WindIOT_Table_shortcode');

$temperaturesArray  = array();
$batteryArray  = array();


function TemperatureLookup($sampleTime) {
   global $temperaturesArray;
   $lastReading = 0;
   $airTemp = "";
   $waterTemp = "";
   foreach($temperaturesArray as $row)
   {
      if ( $sampleTime == $row["SampleTime"]) {
      // $lastReading = $row["SampleTime"];
        $airTemp = number_format(($row["Air"]* 9/5) + 32);
        $waterTemp = number_format(($row["Water"]* 9/5) + 32); 
        break;   
     }
     
   }

   
   return  $airTemp . "</td><td>" . $waterTemp ;


}

function BatteryLevelLookup($sampleTime) {
   global $batteryArray;
   $battLevel = "";
   $sampleTimeLow = $sampleTime - 100;
   $sampleTimeHigh = $sampleTime + 100;
   
   foreach($batteryArray as $row)
   {
 
      if ( $row["SampleTime"] >= $sampleTimeLow && $row["SampleTime"] <= $sampleTimeHigh ) {
         $battLevel = $row["StateOfCharge"];
        break;   
     }
     
   }

   
   return $battLevel;


}

function getNumberHours()
{
   // look in querystring for number of hours to pull data from now. limit to 72 hours
   $queryParamater = $_GET['LookBackHours'];

   // echo "paramater:";
   // echo $queryParamater;

   if (empty($queryParamater) || !is_numeric($queryParamater)) {
      $queryParamater = 12;
   }

   if ($queryParamater > 72) {
      $queryParamater = 72;
   }

   return $queryParamater;
}

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

function WindIOT_Table_shortcode($atts = [], $content = null, $tag = '')
{
   global $wpdb;
   global $temperaturesArray;
   global $batteryArray;
   $sql = "SELECT
      Location, SampleTime, Speed, GUST as Gust, Direction, TimeStamp  
  FROM
  " . $wpdb->prefix . "WindIOT_Wind   
 ORDER BY
      SampleTime DESC
  LIMIT 288";



   $posts = $wpdb->get_results($sql);

   $sql = "SELECT
      Location, SampleTime, Air, Water, TimeStamp  
      FROM
      " . $wpdb->prefix . "WindIOT_Temperatures   
      ORDER BY
         SampleTime DESC
      LIMIT 144";
   $temperaturesArray  = $wpdb->get_results($sql, ARRAY_A);

   // ***********************************************************
   $sql = "SELECT
      SampleTime, StateOfCharge FROM " 
      . $wpdb->prefix . "WindIOT_Battery   
      ORDER BY
         SampleTime DESC
      LIMIT 144";
      $batteryArray  = $wpdb->get_results($sql, ARRAY_A);

    // ***********************************************************
 // print_r(($batteryArray));

   ob_start();

   $output = "<table class='WindTempListing'><thead><tr>
         <td>Date/Time</td>
    
         <td>Speed (mph)</td>
         <td>Gust (mph)</td>
        <td>Direction (flaky)</td>
         <td>Air Temp</td>
         <td>Water Temp</td>
         <td>%Battery Level</td>
         </tr></thead><tbody>";
   foreach ($posts as $post) {
      $output .=
  "<tr><td>" .  wp_date("m/d g:i A", $post->SampleTime, wp_timezone()) . "</td>
         <td>" . $post->Speed . "</td>
         <td>" . $post->Gust . "</td>";
         if ($post->Speed == 0) {
            $output .="<td> --- </td>";
         } else {
            $output .= "<td>" . checkAdjustWindDirection($post->Direction) . "</td>";
         }
         
         $output .=
         "<td>" . TemperatureLookup($post->SampleTime) ." </td>
         <td>" . BatteryLevelLookup($post->SampleTime) ." </td>
         </tr>";
   }
   $output .= "</tbody> </table>";

   echo $output;

   return ob_get_clean();
}
