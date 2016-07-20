<?php
function include_all_php($folder){
    foreach (glob("{$folder}/*.php") as $filename)
    {
        require_once $filename;
    }
}

include_all_php("library/VMware/VCloud/");
include_all_php("library/VMware/VCloud/Http");
include_all_php("library/VMware/VCloud/Query");
class AdminController
{
   function __construct($s,$u,$p)
   {
      $this->server=$s;
      $this->user=$u;
      $this->pswd=$p;
      $this->service=NULL;
      $this->methodMaps=array("getRoles" => "get_name"); // Need to add sub methods if objects are printed for any method 
   }
   function validateMethod($m)
   {
      $validMethods=array("getRoles","getRights","getSystemOrg");
      return in_array($m,$validMethods);
   }
   function setServiceObj()
   {
     if($this->service !== NULL)
        $this->service= VMware_VCloud_SDK_Service::getService();
   }
   function serviceLogin()
   {
     $this->service->login($this->server, array('username'=>$this->user, 'password'=>$this->pswd), $httpConfig, "5.5");
   }
   function serviceLogout()
   {
     $this->service->logout();
   }
   function __destruct()
   {
     $this->serviceLogout();
   }
}
$adminController=new AdminController($_REQUEST["server"], $_REQUEST["username"], $_REQUEST["password"]);
$method=$_REQUEST["method"];
$task=$_REQUEST["task"];
if(! $adminController->validateMethod($method))
{
 header("HTTP/1.0 404 Not Found");
 exit(0);
}
if($task=="Admin")
{
   $SDK_AdminObj=new VMware_VCloud_SDK_Admin();
   $adminController->serviceLogin();
   $result=$SDK_AdminObj->$method();
   if(is_array($result))
   {
      if(array_key_exists($method, $adminController->methodMaps)){
        $temp_arr=array();
        foreach($SDK_AdminObj->$method() as $obj)
        {
           $subMethod=$adminController->methodMaps[$method];
           array_push($temp_arr,$obj->$subMethod()); // for methods like VMware_VCloud_SDK_Admin::getRoles
        }
        print json_encode($temp_arr);
      }
      else{
        print json_encode($result); // for method like VMware_VCloud_SDK_Admin::getRights()
      }
   }
   else {
    if(array_key_exists("sub_method", $_REQUEST) && $_REQUEST["sub_method"]!="")
    {
      $sub_method=$_REQUEST["sub_method"];
      print $result->$sub_method()->export(); // for methods like VMware_VCloud_SDK_Admin::getSystemOrg 
    }
    else
      print $result->get_name();
   }
}


?>