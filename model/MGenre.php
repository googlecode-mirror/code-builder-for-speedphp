<?php
class MGenre extends spModel{
	public $table = 'genre';
	public $pk = 'id';
		
	public function getAttributes(){
		return array(
			'id'=>'id',
			'name'=>'中文名',
			'name_en'=>'name_en',
			);
	}
}