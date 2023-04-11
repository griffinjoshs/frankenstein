<?php
function showArray($val, $title='')
{
    echo "<pre style='padding: 10px; border: 1px solid'>";
    if($title != '') {
      echo "<div style='font-weight: bold; '>" . $title . "</div>";
   }
    print_r($val);
    echo "</pre>";
}

function systemMessage($type, $title, $message, $duration=5000, $width=500, $returnJs=true, $forceShow=MFS)
{
   if($returnJs == true) {
      return "<script>
      systemMessageSlideIn(" . $type . ", " . $title . ", " . $message . ", " . $duration . ", " . $width . ", " . $forceShow . ");
      </script>";
   } else {
      return "<div class='system-message-container-inline " . strtolower($type) . "'>
      <h3>" . $title . "</h3>
      <span>" . $message . "</span>
      </div>";
   }
}

function redirect($url = null)
{
		if(is_null($url)) $url = $_SERVER['PHP_SELF'];
		if (strstr($url,'http:') || strstr($url,'https:'))
			header("Location: " . $url);
		else
			header("Location: " . PATH . $url);
		exit();
}


function formatDateForSave($dt, $includeTime=true)
{
      if (!strstr($dt, '0000-00-00') && $dt != '')
      {
         $dt = strtotime($dt);
         if ($_SESSION['tzOffset']  != 0)
         {
            $dt = $dt - ($_SESSION['tzOffset'] * 3600);
         }
         if ($includeTime)
            $dt = date('Y-m-d H:i:s', $dt);
         else
            $dt = date('Y-m-d', $dt);
      }
      return $dt;
}

function formatDateForDisplay($dt,$incTime=true)
{
   if ($incTime)
      return formatDate($dt, true, 'n/j/Y g:i A');
   else
      return formatDate($dt, false, 'n/j/Y');
}

function formatDate($dt, $includeTime=true, $format='',$hourAdjust=0,$dayAdjust=0,$monthAdjust=0)
{
   if ($dt == '0000-00-00' || $dt == '0000-00-00 00:00:00' || $dt == '0000-00-00 00:00:00.000000' || $dt == '')
   {
      return false;
   }
   else
   {
      if (!is_numeric($dt))
         $dt = strtotime($dt);

      if (strstr($dayAdjust, 'B'))
      {
         $d = date('N', $dt);
         if ($d==1 || $d==2 || $d==3)
            $dayAdjust = $dayAdjust + 2;
      }

      if ($includeTime && $_SESSION['tzOffset'] != 0)
      {
            $hourAdjust = $hourAdjust + $_SESSION['tzOffset'];
      }

      $dt += ($hourAdjust * 3600);
      $dt += ($dayAdjust * 86400);
      $dt += ($monthAdjust * 86400 * 30);

      if ($format != '')
      {
         return date($format, $dt);
      }
      else
      {
         return false;
      }
      // elseif ($includeTime)
      // {
      //    return date(DATETIME_FORMAT, $dt) . ($_SESSION['tz'] != '' ? ' ' . $_SESSION['tz'] : '');

      // }
      // else
      // {
      //    return date(DATE_FORMAT, $dt);
      // }
   }

}

function isPost($post)
 {
   if(count($post) > 0) {
      return $post;
   } else {
      return false;
   }
 }
?>