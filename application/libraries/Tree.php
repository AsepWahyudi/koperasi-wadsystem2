<?php

Class Tree {
	
	protected function map_tree($field_parentid, $field_id, $result, $arr_sum){
		$data_proses		=	array();
		$i					=	0;		
		$array_keys			=	array_keys($result[0]);
		$array_keys_data	=	array();
		$array_keys_data['_pid']	= '';
		$array_keys_data['_level']	= '';
		$array_keys_data['_header']	= '';
		$array_keys_data['_num']	= '';
		
		foreach($array_keys as $key){
			$array_keys_data[$key] 	= '';
		}
		
		if(count($arr_sum) > 0) {
			foreach($arr_sum as $key) {
				$array_keys_data[$key]			=	'{group:sum}';
			}
			
		}
				
		foreach($result as $key_row){
			$parent_id			=	$key_row[$field_parentid];
			$data_id			=	$key_row[$field_id];
			$k_row['_pid']		=	'_pid_'.$data_id;
			$k_row['_level']	=	0;
			$k_row['_header']	=	0;
			$k_row['_num']		=	0;
			$data_proses['_pid_'.$parent_id][]	=	array_merge($k_row, $key_row);
			
			
		}				
		return array('data'=>$data_proses, 'key'=>$array_keys_data);
		
		
	}
	
	protected function data_tree($data, $key_array, $parentid = '_pid_0', $level = -1){
		$data_ret			=	array();
		$data_row			=	array();
		$data_return		=	array();
		$header				=	array();
		
		if(isset($data[$parentid])){
			
			$length			=	count($data[$parentid]);			
			$level++;
			
			
			for($i=0;$i<$length;$i++){								
				$rowdata					=	$data[$parentid][$i];
				$data_parent				=	$rowdata;
				$child						=	$this->data_tree($data, $key_array, $rowdata['_pid'], $level);
				
				if($child) {													
					foreach($child['formula'] as $key=>$val){
						if($val == 'noformula'){
							$data_row[$i][$key]	= $data_parent[$key];
						} else {
							$data_row[$i][$key]	= $val;				
						}
					}
					$data_row[$i]['_level']	=	$level;	
					$data_row[$i]['_header']=	'1';
					
					$data_ret				=	array_merge($data_ret, $child['data']);
					$data_return[]			=	$data_row[$i]; 
					$data_return			=	array_merge($data_return, $child['return']);
					
				} else {
					$rowdata['_level']		=	$level;
					$rowdata['_header'] 	=	'0';
					$data_row[$i]			=	$rowdata;	
					$data_return[]			=	$data_row[$i];
				}				
			}	
			
			$row_var		=	null;
			$row_formula	=	array();
			foreach($key_array as $key=>$value){
				$cell_var				=	null;				
				switch($value){
					case '{group:sum}'	 :	$row_formula[$key]	=	array_sum(array_column($data_row, $key));
											break;	
					case '{group:count}' :	$row_formula[$key]	=	$i;
											break;
					case '{group:avg}' 	 :	$row_formula[$key]	=	$this->_e->sum_array($data_row, $key)/$i;
											$row_formula[$key]	=	empty($row_formula[$key]) ? '0' : $row_formula[$key];
											break;
					default				 :	$row_formula[$key]	=	'noformula';
											break;
				}
				
			} 
			
			$data_ret[$parentid]	=	$data_row;
			
			return array('data'=>$data_ret, 'formula'=>$row_formula, 'return'=>$data_return, 'header' => $header);		
		} else {
			return false;	
		} 
		
	}
	
	function result_tree($field_parentid, $field_id, $result, $arr_sum = array()){		
		$row				= 	array();		
		if($result == false) return $row;
		
		$data_proses		=	array();
		if(!isset($result[0][$field_parentid]) && !isset($result[0][$field_id])) return $row;
		
		$data_proses		=	$this->map_tree($field_parentid, $field_id, $result, $arr_sum);		
		$data_proses		=	$this->data_tree($data_proses['data'], $data_proses['key']);
		return $data_proses;					
		
	}
	
	function level($level, $header, $data) {
		$result	=	'';
		if($level >= 1) {
			for($i=1; $i<=$level; $i++){
				$result		.=	'&nbsp;&nbsp;&nbsp;';
			}
		}
		return $result . ($header == 0 ? $data : '<strong>'. $data .'</strong>');
	}
	
	function levelOpt($level, $header, $idvalue, $name) {
		$result	=	'';
		if($level >= 1) {
			for($i=1; $i<=$level; $i++){
				$result		.=	'&nbsp;&nbsp;&nbsp;';
			}
		}
		if($header == 1) {
			$return	=	'<option value="" disabled style="color: #6d5e5e; font-weight: 600; background: #eee;">'. $result . $name .'</option>';
		} else {
			$return	=	'<option value="'. $idvalue .'">'. $result . $name .'</option>';
		}
		
		return $return;
	}
}