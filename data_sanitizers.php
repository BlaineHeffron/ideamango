<?php
function clean($elem) //This function cleans user input for the get variables.
{
  if(!is_array($elem))
    $elem = htmlentities($elem,ENT_QUOTES,"UTF-8");
  else
    foreach ($elem as $key => $value)
      $elem[$key] = $this->clean($value);
  return $elem;
}
function explode_filtered_empty($var) {
  if ($var == "")
    return(false);
  return(true);
}

function explode_filtered($delimiter, $str) {
  $parts = explode($delimiter, $str);
  return(array_filter($parts, "explode_filtered_empty"));
}?>
