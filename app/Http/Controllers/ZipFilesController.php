<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Chumper\Zipper\Zipper;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
//use Maatwebsite\Excel\Facades\Excel;

class ZipFilesController extends Controller
{
   /**
    * index 解压文件
    * @param Zipper $zippper
    */
   public function index(Zipper $zippper)
   {	
   		$files = $this->my_dir(storage_path().'/zip');
   		if(is_array($files) && !empty($files)){
   			foreach ($files as $file){
   				$path = storage_path('/app/unzip/'.explode('.', $file)[0]);
   				try {
   					$zippper->make(storage_path().'/zip/'.$file)->extractTo($path);
   				}catch (\Exception $e){
   					return '解压失败';
   				}
   			}
 			return "解压成功";
   		}
   		return "解压失败";
   }
   
	/**
	 * readFile 读取文件
	 * @param \Maatwebsite\Excel\Excel $excel
	 */
   public function readFile(\Maatwebsite\Excel\Excel $excel)
   {
	   	$excel_file_path = storage_path('zip/weather_forecast/weather_forecast/Changzhi_2019030508.csv');
	   	$res = [];
	   	$excel->load($excel_file_path, function($reader) use( &$res ) {
	   		$reader = $reader->getSheet(0);
	   		$res = $reader->toArray();
	   	},'UTF-8');
	   	for($i = 1; $i<count($res); $i++){
	   		echo '<pre>';
	   		var_dump($res[$i]);
	   		echo '</pre>';
	   	}
   }
   
   /**
    * my_dir 遍历文件
    * @param unknown $dir
    * @return multitype:string multitype:NULL string
    */
   public function my_dir($dir)
   {
	   	$files = array();
	   	if(@$handle = opendir($dir)) { //注意这里要加一个@，不然会有warning错误提示：）
	   		while(($file = readdir($handle)) !== false) {
	   			if($file != ".." && $file != ".") { //排除根目录；
	   				if(is_dir($dir."/".$file)) { //如果是子文件夹，就进行递归
	   					$files[$file] = $this->my_dir($dir."/".$file);
	   				} else { //不然就将文件的名字存入数组；
	   					$files[] = $file;
	   				}
	   			}
	   		}
	   		closedir($handle);
	   		return $files;
	   	}
   }
}
