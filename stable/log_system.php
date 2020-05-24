<?php
/*
Copyright 2014, 2015 Dario Fadda.
This file is part of MercUsa.

    MercUsa is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    MercUsa is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with MercUsa.  If not, see <http://www.gnu.org/licenses/>.
*/
 
function write_mysql_log($message, $db)
{
  // Check database connection
  if( ($db instanceof MySQLi) == false) {
    return array(status => false, message => 'MySQL connection is invalid');
  }
 
  // Check message
  if($message == '') {
    return array(status => false, message => 'Message is empty');
  }
 
  // Get IP address
  if( ($remote_addr = $_SERVER['REMOTE_ADDR']) == '') {
    $remote_addr = "REMOTE_ADDR_UNKNOWN";
  }
 
  // Get requested script
  if( ($request_uri = $_SERVER['REQUEST_URI']) == '') {
    $request_uri = "REQUEST_URI_UNKNOWN";
  }
 
  // Escape values
  $message     = $db->escape_string($message);
  $remote_addr = $db->escape_string($remote_addr);
  $request_uri = $db->escape_string($request_uri);
 
  // Construct query
  $sql = "INSERT INTO mercusa_log (remote_addr, request_uri, message) VALUES('$remote_addr', '$request_uri','$message')";
 
  // Execute query and save data
  $result = $db->query($sql);
 
  if($result) {
    return array(status => true);  
  }
  else {
    return array(status => false, message => 'Unable to write to the database');
  }
}
?>
