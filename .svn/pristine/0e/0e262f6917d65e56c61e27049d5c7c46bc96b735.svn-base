<?php
/**
 * Created by PhpStorm.
 * User: lijunhua
 * Date: 2019/2/16
 * Time: 11:10
 */

namespace app\common;


class Common
{
    public static function saveFile($files,$path){

        $filename = md5($files['tmp_name']);
        $extname = strtolower(substr($files['name'], strrpos($files['name'], ".")));

        $md5NamePath = self::md5NamePath($filename);

        $dir        = '.'.$path.$md5NamePath;
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $filename = time().rand(1000,9999);

        $file = $dir . $filename . $extname;

//        if (in_array($extname,['.jpeg','.jpg','.png','.gif','.bmp','.wbmp'])){
//            $imgWidth = getimagesize($files['tmp_name'])[0];
//            if ($imgWidth > 750){
//                $org = $dir .$filename.'.org'.$extname;
//                move_uploaded_file($files['tmp_name'],$org );
//
//                $image = new Imgcompress($org,1);
//                $image->compressImg($file);
//            }else{
//                move_uploaded_file($files['tmp_name'],$file );
//            }
//        }else{
            move_uploaded_file($files['tmp_name'],$file );
//        }

        $name = $filename . $extname;
        $url  = $path. $md5NamePath . $name;
        return ['code'=>0, 'msg'=>'上传成功','src'=>$url,'name'=>$name];
    }

    public static function md5NamePath($name)
    {
        $first    = substr($name, 0, 1);
        $second   = substr($name, 1, 1);

        return "/".$first."/".$second."/";
    }

    /**
     * @param $start  从第几位开始隐藏
     * @param $length 被隐藏字符串的长度
     * @return $string
     */
    public static function  encryptPhone($string,$start = 3,$length = 4,$encrypt = '****') {
        return $string ? substr_replace($string, $encrypt, $start, $length) : "";
    }

    /**
     * 判断日期是否在范围
     * @return boolean
     */
    public static function rangeDate($date,$startDate,$endDate){
        //开始时间
        $start = strtotime($startDate);
        $end = strtotime($endDate);
        //当前时间
        $now = strtotime($date);
        if($now >= $start && $now <= $end){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 根据日期范围获取日期数组
     * @return array
     */
    public static function getDateArrByRange($startDate,$endDate){
        if (empty($startDate)||empty($endDate)){
            return array();
        }
        $arr = [];
        do{
            $arr[] = $startDate;
            $startDate = date( 'Y-m-d', strtotime($startDate.'+1 day'));
        }while(strtotime($startDate) <= strtotime($endDate));

        return $arr;
    }
}