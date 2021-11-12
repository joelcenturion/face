<?php
// header('Content-type: text/plain');

  $json = file_get_contents('php://input');
  $data = json_decode($json); 
  $img_base64 = $data->image;
  $img_path = '/app/image/image.jpg';
  $img_file = fopen($img_path, "w+");
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
  
  $image = imagecreatefromstring(file_get_contents($img_path));
  $csv = fopen("/app/image/processed/image.csv", "r");

  // if ($csv !== FALSE) {
    $line1 = fgetcsv($csv);
    $line2 = fgetcsv($csv); //coordenadas de landmark
    $length = count($line2); //cantidad de elementos
    fclose($csv);
  // }

  list($width, $height) = getimagesize($img_path);

  // echo "width: $width <br/> height: $height";
  // $white= imagecolorallocate($image, 242, 242, 242);
  // $white= imagecolorallocate($image, 222, 220, 220);
  $white= imagecolorallocate($image, 255, 255, 255);
  for ($x = 15; $x < $width; $x+=15){
    for($y = 0; $y < $height; $y+=2){
        imagesetpixel($image, $x, $y, $white);
    } 
  }
  for ($y = 15; $y < $height; $y+=15){
    for($x = 0; $x < $width; $x+=2){
        imagesetpixel($image, $x, $y, $white);
    } 
  } 

  $red = imagecolorallocate($image, 255, 0, 0);
  $blue = imagecolorallocate($image, 0,0,255);
  $purple = imagecolorallocate($image, 128, 0, 128);
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
   
  $new_img_path = '/app/image/image2.jpg';
  imagepng($image, $new_img_path);
  $base64 = base64_encode(file_get_contents($new_img_path));
  echo json_encode($base64);
?>