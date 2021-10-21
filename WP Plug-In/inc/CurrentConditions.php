<?php

//require_once('weatherSupportingFunctions.php'); // List temps as table.

add_shortcode('WindIOT-CurrentConditions', 'WindIOT_CurrentConditions_shortcode');



function WindIOT_CurrentConditions_shortcode($atts = [], $content = null, $tag = '')
{
   global $wpdb;
   global $temperaturesArray;
   $sql = "SELECT
      Location, SampleTime, Speed, GUST as Gust, Direction, TimeStamp  
  FROM
  " . $wpdb->prefix . "WindIOT_Wind   
 ORDER BY
      SampleTime DESC
  LIMIT 1";

   $wind = $wpdb->get_row($sql);

   $sql = "SELECT
      Location, SampleTime, Air, Water, TimeStamp  
      FROM
      " . $wpdb->prefix . "WindIOT_Temperatures   
      ORDER BY
         SampleTime DESC
      LIMIT 1";
   $temperatures  = $wpdb->get_row($sql);

   $airTemp = number_format(($temperatures->Air * 9 / 5) + 32);
   $waterTemp = number_format(($temperatures->Water * 9 / 5) + 32);

   // ***********************************************************
   // print_r(($batteryArray));

   $date = new DateTime();
   ob_start();
   $output1 = "<h3>PYC Weather Buoy</h3>"; // . $date->getTimestamp() . " " . $wind->SampleTime;


   if (($date->getTimestamp() - $wind->SampleTime) > 7200) {
      $output2 = "<h5>Sorry, but weather data is not available. Please check the Historic Data link. </h5>";
      # code...
   } else {

      $output2 = "<table class='WindTempListing'><thead><tr>
         <td>Date/Time</td>
    
         <td>Speed (mph)</td>
         <td>Gust (mph)</td>
        <td>Direction (flaky)</td>
         <td>Air Temp</td>
         <td>Water Temp</td>
         </tr></thead><tbody>
         <tr><td>" .  wp_date("m/d g:i A", $wind->SampleTime, wp_timezone()) . "</td>
         <td>" . $wind->Speed . "</td>
         <td>" . $wind->Gust . "</td>";
      if ($wind->Speed == 0) {
         $output2 .= "<td> --- </td>";
      } else {
         $output2 .= "<td>" . checkAdjustWindDirection($wind->Direction) . "</td>";
      }

      $output2 .=
         "<td>" .  $airTemp . " </td><td>" . $waterTemp . "</td>" .
         "</tr>";

      $output2 .= "</tbody> </table>";
   }
   $output3 = "<p><a href='/windiot-testing-page/'>Historic Data</a><br/>

   PYC weather station is located on Race 0 mark, about ¾ miles offshore. Weather station is still 
   in development so please use other sources to validate readings.</p>";

   echo $output1 . $output2 . $output3;

   return ob_get_clean();
}
