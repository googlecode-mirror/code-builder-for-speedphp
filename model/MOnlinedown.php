<?php
class MOnlinedown extends spModel{
	public $table = 'onlinedown';
	public $pk = 'id';
		
	public function getAttributes(){
		return array(
			'id'=>'id',
			'name'=>'name',
			);
	}
	
	public function getAttributeFormsets(){
		return array(
		);
	}
}