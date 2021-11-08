<?php
// header('Content-type: text/plain');

  $json = file_get_contents('php://input');
  $data = json_decode($json); 
  $img_base64 = $data->image;
  $img_file = fopen("/app/image/image.jpg", "w+");
  fwrite($img_file, base64_decode($img_base64));
  fclose($img_file);

  $image = '/app/image/image.jpg';

  function findLandmarks(){
    $command = '
    cd /app/image;
    /app/bin/FaceLandmarkImg -f /app/image/image.jpg;   
    ';
    return shell_exec($command);
  }
    
  $result = findLandmarks();
  // $response = array();
  // $response[image] = array();
  // $response[image] = base64_encode($data_img);
  $data_img = file_get_contents('/app/image/processed/image.jpg');
  $response = base64_encode($data_img);
  echo json_encode($response);
  // echo str_replace('\\', '', $response);



?>