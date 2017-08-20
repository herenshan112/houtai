<?php
namespace PublicClass;

class PublicSub{
	//一维数组
	static public function LinearArray($cate, $delimiter = '|———', $parent_id = 0, $level = 10){
		$arr=array();
		foreach ($cate as $val) {
			if($val['fatherid'] == $parent_id){
				//$val['level'] = $level + 1;
				$val['level'] = $level + 5;
                $val['delimiter']=$delimiter;
                $arr[] = $val;
                $arr = array_merge($arr, self::LinearArray($cate, $delimiter, $val['id'], $val['level']));
			}
		}
		return $arr;
	}

	static public function toLevend($cate, $delimiter = '|———', $parent_id = 0, $level = 0){
		$arr=array();
		foreach ($cate as $val) {
			if($val['fatherid'] == $parent_id){
				$val['level'] = $level + 1;
				//$val['level'] = $level + 5;
                $val['delimiter'] = str_repeat($delimiter, $level);
                //$val['delimiter']=$delimiter;
                $arr[] = $val;
                $arr = array_merge($arr, self::toLevend($cate, $delimiter, $val['id'], $val['level']));
			}
		}
		return $arr;
	}
}
?>