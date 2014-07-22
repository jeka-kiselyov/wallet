<?php

class image_helper extends singleton_base 
{
  function __construct() 
  {
    parent::__construct();
  }

  public function imagecreatefromfile($path, $user_functions = false)
  {
    $info = @getimagesize($path);

    if(!$info)
    {
        return false;
    }

    $functions = array(
        IMAGETYPE_GIF => 'imagecreatefromgif',
        IMAGETYPE_JPEG => 'imagecreatefromjpeg',
        IMAGETYPE_PNG => 'imagecreatefrompng',
        IMAGETYPE_WBMP => 'imagecreatefromwbmp',
        IMAGETYPE_XBM => 'imagecreatefromwxbm',
        );

    if($user_functions)
    {
      $functions[IMAGETYPE_BMP] = 'imagecreatefrombmp';
    }

    if(!$functions[$info[2]])
    {
      return false;
    }

    if(!function_exists($functions[$info[2]]))
    {
      return false;
    }

    return $functions[$info[2]]($path);
  }

  public function image_to_max_height($img, $height)
  {
      $original_width = @imagesx($img);
      $original_height = @imagesy($img);

      if (!$original_width || !$original_height)
       return false;

      if ($original_height <= $height)
       return $img;

      $result_height = $height;
      $k = ($original_width / $original_height);
      $result_width = $height * $k;

      $newimg = imagecreatetruecolor($result_width, $result_height);
      imagesavealpha($newimg, true);
      imagecopyresampled($newimg, $img, 0, 0, 0, 0, $result_width, $result_height, $original_width, $original_height);

      return $newimg;
  }

  public function image_to_max_width($img, $width)
  {
      $original_width = @imagesx($img);
      $original_height = @imagesy($img);

      if (!$original_width || !$original_height)
       return false;

      if ($original_width <= $width)
       return $img;

      $result_width = $width;
      $k = ($original_height / $original_width);
      $result_height = $width * $k;

      $newimg = imagecreatetruecolor($result_width, $result_height);
      imagesavealpha($newimg, true);
      imagecopyresampled($newimg, $img, 0, 0, 0, 0, $result_width, $result_height, $original_width, $original_height);

      return $newimg;
  }

  public function to_fixed($img, $width, $height)
  {
    $img = $this->image_to_max_width($img, $width);
    $img = $this->image_to_max_height($img, $height);

    $pallete = $this->get_most_common_colors($img);
    reset($pallete);
    $bg_color = key($pallete);

    $r = hexdec(substr($bg_color,0,2));
    $g = hexdec(substr($bg_color,2,2));
    $b = hexdec(substr($bg_color,4,2));

    $res = imagecreatetruecolor($width, $height);

    $color = imagecolorallocate($res, $r, $g, $b);
    imagefilledrectangle($res, 0, 0, $width, $height, $color);

    $img_width = imagesx($img);
    $img_height = imagesy($img);

    $dst_x = $width/2 - $img_width/2;
    $dst_y = $height/2 - $img_height/2;

    imagecopyresampled($res, $img, $dst_x, $dst_y, 0, 0, $img_width, $img_height, $img_width, $img_height);
    return $res;
  }

  public function get_most_common_colors($img)
  {
      $PREVIEW_WIDTH    = 150;  //WE HAVE TO RESIZE THE IMAGE, BECAUSE WE ONLY NEED THE MOST SIGNIFICANT COLORS.
      $PREVIEW_HEIGHT   = 150;

      $size = array(0,0);
      $size[0] = imagesx($img);
      $size[1] = imagesy($img);

      $scale=1;
      if ($size[0]>0)
      $scale = min($PREVIEW_WIDTH/$size[0], $PREVIEW_HEIGHT/$size[1]);
      if ($scale < 1)
      {
        $width = floor($scale*$size[0]);
        $height = floor($scale*$size[1]);
      }
      else
      {
        $width = $size[0];
        $height = $size[1];
      }
      $image_resized = imagecreatetruecolor($width, $height);
      $color = imagecolorallocate($image_resized, 255, 255, 255);
      imagefilledrectangle($image_resized, 0, 0, $width, $height, $color);
      imagealphablending($image_resized, false );
      imagesavealpha( $image_resized, true );
      imagesavealpha($img, true );
      imagecopyresampled($image_resized, $img, 0, 0, 0, 0, $width, $height, $size[0], $size[1]); //WE NEED NEAREST NEIGHBOR RESIZING, BECAUSE IT DOESN'T ALTER THE COLORS
      $im = $image_resized;
      $imgWidth = imagesx($im);
      $imgHeight = imagesy($im);
      for ($y=0; $y < $imgHeight; $y++)
      {
        for ($x=0; $x < $imgWidth; $x++)
        {
          $index = imagecolorat($im,$x,$y);
          $Colors = imagecolorsforindex($im,$index);
          $Colors['red']=intval((($Colors['red'])+15)/32)*32;    //ROUND THE COLORS, TO REDUCE THE NUMBER OF COLORS, SO THE WON'T BE ANY NEARLY DUPLICATE COLORS!
          $Colors['green']=intval((($Colors['green'])+15)/32)*32;
          $Colors['blue']=intval((($Colors['blue'])+15)/32)*32;
          if ($Colors['red']>=256)
          $Colors['red']=255;
          if ($Colors['green']>=256)
          $Colors['green']=255;
          if ($Colors['blue']>=256)
          $Colors['blue']=255;
          $hexarray[]=substr("0".dechex($Colors['red']),-2).substr("0".dechex($Colors['green']),-2).substr("0".dechex($Colors['blue']),-2);
        }
      }
      $hexarray=array_count_values($hexarray);
      natsort($hexarray);
      $hexarray=array_reverse($hexarray,true);
      return $hexarray;
  }

}




