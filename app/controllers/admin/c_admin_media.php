<?php

class controller_admin_media extends admin_controller
{ 
  public function __construct($registry)
  {
    parent::__construct($registry);
    $this->select_menu('media');
  }

  function index()
  {
     $this->redirect("admin_media", "manage");
  }

  function manage()
  {

  }

  function upload()
  {
    $sub_directory = '';
    $max_width = 200;
    $max_height = 200;

    $success = false;
    $this->rendered = true;

    if (isset($_FILES) && isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name']))
    {
      $name = ''; if (isset($_POST['name'])) $name = $_POST['name'];
      $name = strtolower(trim($name));
      $name = preg_replace('/[^a-z0-9-]/', '_', $name);
      $name = preg_replace('/_+/', "_", $name);

      if (isset($_POST['max_width'])) 
        $max_width = (int)$_POST['max_width'];

      if ($max_width < 16)
        $max_width = 16;
      if ($max_width > 4000)
        $max_width = 4000;

      if (isset($_POST['max_height'])) 
        $max_height = (int)$_POST['max_height'];

      if ($max_height < 16)
        $max_height = 16;
      if ($max_height > 4000)
        $max_height = 4000;

      if (isset($_POST['sub_directory'])) 
        $sub_directory = $_POST['sub_directory'];

      if ($sub_directory && !preg_match('/^[_a-z0-9\/]+$/', $sub_directory))
      {
        echo json_encode(array("success"=>false, "error"=>"Invalid sub_directory"));
        exit();
      }

      if (!$name)
        $name = time();

      $img = @$this->image_helper->imagecreatefromfile($_FILES['image']['tmp_name']);

      if (!$img)
      {
        echo json_encode(array("success"=>false, "error"=>"Invalid image"));
        exit();
      }


      $path = SITE_PATH."/app/public/uploads/images/".$sub_directory; 
      $images_path = SITE_PATH."/app/public/uploads/images/";

      if (!is_dir($images_path))
        @mkdir($images_path);

      if (!is_dir($path))
      {
        if (!@mkdir($path))
        {
          echo json_encode(array("success"=>false, "error"=>"Can not create more than one directory deeper from existing one"));
          exit();
        }
      } 

      $fname = $name.".jpg"; $i=1;
      while (is_file($path."/".$fname))
        $fname = $name."_".($i++).".jpg";

      if ($img)
      {
        $img = $this->image_helper->image_to_max_height($img, $max_height);
        $img = $this->image_helper->image_to_max_width($img, $max_width);

        imagejpeg($img, $path."/".$fname);
        echo json_encode(array("success"=>true, "filename"=>$sub_directory."/".$fname));
        exit();
      }
    }

    echo json_encode(array("success"=>false));
  }

}