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
			//'columnName'=>array('type'=>'dropDownList', 'options'=>array('false'=>'选项一','true'=>'选择二'), 'htmlOptions'=>array()),
			'name'=>array('type'=>'textField', 'htmlOptions'=>array('readonly'=>'true')),
			//'columnName'=>array('type'=>'password', 'htmlOptions'=>array()),
			//'columnName'=>array('type'=>'textArea', 'htmlOptions'=>array('rows'=>'6')),
			//'columnName'=>array('type'=>'checkBox', 'checkBoxText'=>'钓鱼岛是中国的吗？', 'checkBoxValue'=>'yes', 'htmlOptions'=>array()),
			//'columnName'=>array('type'=>'radio', 'options'=>array('A'=>'钓鱼岛是中国的','B'=>'苍老师是世界的')),
			//'columnName'=>array('type'=>'file', 'htmlOptions'=>array()),
		);
	}
}