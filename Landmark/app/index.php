<?php
// header('Content-type: text/plain');

  $json = file_get_contents('php://input');
  $data = json_decode($json); 
  $img_base64 = $data->image;
  $img_file = fopen("/app/image/image.jpg", "w+");
  fwrite($img_file, base64_decode($img_base64));
  fclose($img_file);


  function findLandmarks(){
    $command = '
    cd /app/image;
    /app/bin/FaceLandmarkImg -f /app/image/image.jpg -2Dfp;   
    ';
    return shell_exec($command);
  }
  $result = findLandmarks();
  
  $image = imagecreatefromstring(file_get_contents("/app/image/image.jpg"));
  $csv = fopen("/app/image/processed/image.csv", "r");

  // if ($csv !== FALSE) {
    $line1 = fgetcsv($csv);
    $line2 = fgetcsv($csv); //coordenadas de landmark
    $length = count($line2); //cantidad de elementos
    fclose($csv);
  // }
  $red = imagecolorallocate($image, 255, 0, 0);
  $blue = imagecolorallocate($image, 0,0,255);
  for($c = 2; $c <= $length/2; $c++){
    imagesetpixel($image, $line2[$c], $line2[$c+68], $blue);
    imagesetpixel($image, $line2[$c]+1, $line2[$c+68], $blue);
    imagesetpixel($image, $line2[$c]-1, $line2[$c+68], $blue);
    imagesetpixel($image, $line2[$c], $line2[$c+68]+1, $blue);
    imagesetpixel($image, $line2[$c], $line2[$c+68]-1, $blue);
  }
  // header('Content-type: image/png');
  //   imagepng($image);
  // for ($i = 2; $i < $length; $i++){
  //     echo "$line2[$i] ";
  //   }

  imagepng($image, '/app/image/image2.jpg');
  $base64 = base64_encode(file_get_contents('/app/image/image2.jpg'));
  echo json_encode($base64);
?>