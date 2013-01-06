<?php
/**
 * CodeBuilder is the tool which help developer build CURD code easier and faster
 * @author Pony Cui <PonyCui@me.com>
 * @version 1.0
 */
class CodeBuilder extends spController{
        
        private $password = '123456';//The password
        
        public function __construct() {
                global $__action;
                if($__action != 'login'){
                        if(!isset($_SESSION['codeBuilder_admin']) || !$_SESSION['codeBuilder_admin'])
                                $this->jump (spUrl ('CodeBuilder','login'));
                }
                parent::__construct();
        }
        
        public function index(){
                $this -> display('codeBuilder/index.html');
        }
        
	public function genController(){
		$this ->display('codeBuilder/genController.html');
	}
	
	public function genControllerAction(){
		$actionType = $this->spArgs('genAction', 'preview');
		$this -> tpl_controllerName = $controllerName = $this -> spArgs('controllerName','null');
		$genCode = <<<EOT
<?php
class {$controllerName} extends spController{
	public function {$GLOBALS['G_SP']['default_action']}(){
		\$this -> display('{$controllerName}/{$GLOBALS['G_SP']['default_action']}.html');
	}
}
EOT;
		$genTemplate = array();
		$genTemplate[0] = array('path'=>$GLOBALS['G_SP']['view']['config']['template_dir'].'/'.$controllerName.'/'.$GLOBALS['G_SP']['default_action'].'.html');
		$genTemplate[0]['contents']  = <<<EOT
<!DOCTYPE html>
<html>
        <head>
                <title>Hello</title>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        </head>
	<body>
		Hello, World!
	</body>
</html>
EOT;
		if($actionType == 'preview'){
			$this -> tpl_preview = true;
			$this -> tpl_codePath = $GLOBALS['G_SP']['controller_path'].'/'.$controllerName.'.php';
			$this -> tpl_codePreview = $genCode;
			$this ->_checkOverwrite($this -> tpl_codePath, $genTemplate);
			$this -> tpl_genTemplatePreview = $genTemplate;
			$this -> genController();
		}
		if($actionType == 'gen'){
			$this -> tpl_gen = true;
			$this -> tpl_codePath = $GLOBALS['G_SP']['controller_path'].'/'.$controllerName.'.php';
			if(!preg_match('/[0-9a-zA-Z]/', $controllerName))
				$this -> tpl_success = -1;
			else{
				$this -> tpl_success = file_put_contents($GLOBALS['G_SP']['controller_path'].'/'.$controllerName.'.php', $genCode);
				foreach($genTemplate as $template){
					if(!is_file($GLOBALS['G_SP']['view']['config']['template_dir'].'/'.$controllerName.'/')){
						mkdir($GLOBALS['G_SP']['view']['config']['template_dir'].'/'.$controllerName.'/');
					}
					$successTemplate = file_put_contents($template['path'], $template['contents']);
					if(empty($successTemplate)) $this->tpl_success_template = -1;
				}
			}
			$this -> genController();
		}
	}
	
        public function genModel(){
                $model = new spModel();
                $this -> tpl_prefix = $GLOBALS['G_SP']["db"]['prefix'];
                $this -> tpl_tables = $model ->findSql('SHOW TABLES;');
                $this ->display('codeBuilder/genModel.html');
        }
	
	public function genModelAction(){
		$actionType = $this->spArgs('genAction', 'preview');
		$this -> tpl_modelName = $modelName = $this->spArgs('modelName');
		$modelTable = preg_replace('/'.$this->spArgs('modelTablePrefix').'/', '', $this->spArgs('modelTable'), 1);//表名不含前缀
		$this -> tpl_modelTable = $realTable = $this->spArgs('modelTable');//表名含前缀
		$this -> tpl_modelTablePrefix = $this->spArgs('modelTablePrefix');
		$tableColInfo = spClass('spModel') -> findSql('show columns from '.$realTable.';');
		$tablePk = $this -> _findPK($tableColInfo);
		$genCode = <<<EOT
<?php
class M{$modelName} extends spModel{
	public \$table = '{$modelTable}';
	public \$pk = '{$tablePk}';
		
	public function getAttributes(){
		return array(
			{$this->_getAttributes($tableColInfo)});
	}
	
	public function getAttributeFormsets(){
		return array(
			//'columnName'=>array('type'=>'dropDownList', 'options'=>array('false'=>'选项一','true'=>'选择二'), 'htmlOptions'=>array()),
			//'columnName'=>array('type'=>'textField', 'htmlOptions'=>array('readonly'=>'true')),
			//'columnName'=>array('type'=>'password', 'htmlOptions'=>array()),
			//'columnName'=>array('type'=>'textArea', 'htmlOptions'=>array('rows'=>'6')),
			//'columnName'=>array('type'=>'checkBox', 'checkBoxText'=>'钓鱼岛是中国的吗？', 'checkBoxValue'=>'yes', 'htmlOptions'=>array()),
			//'columnName'=>array('type'=>'radio', 'options'=>array('A'=>'钓鱼岛是中国的','B'=>'苍老师是世界的')),
			//'columnName'=>array('type'=>'file', 'htmlOptions'=>array()),
		);
	}
}
EOT;
		if($actionType == 'preview'){
			$this -> tpl_preview = true;
			$this -> tpl_codePath = $GLOBALS['G_SP']['model_path'].'/M'.$modelName.'.php';
			$this -> tpl_codePreview = $genCode;
			$this ->_checkOverwrite($this -> tpl_codePath);
			$this -> genModel();
		}
		if($actionType == 'gen'){
			if(!preg_match('/[0-9a-zA-Z]/', $modelName))
				$this -> tpl_success = -1;
			else
				$this -> tpl_success = file_put_contents($GLOBALS['G_SP']['model_path'].'/M'.$modelName.'.php', $genCode);
			$this -> tpl_gen = true;
			$this -> tpl_codePath = $GLOBALS['G_SP']['model_path'].'/M'.$modelName.'.php';
			$this -> genModel();
		}
	}
	
	public function genCURD(){
		$models = scandir($GLOBALS['G_SP']['model_path']);
		foreach($models as $key => $value){
			if(strlen(basename($value,'.php')) == strlen($value))
				unset($models[$key]);
			else
				$models[$key] = basename($value,'.php');
		}
		$this -> tpl_models = $models;
		$this ->display('codeBuilder/genCURD.html');
	}
	
	public function genCURDAction(){
		$actionType = $this->spArgs('genAction', 'preview');
		$this -> tpl_modelName = $modelName = $this->spArgs('modelName');
		$this -> tpl_controllerName = $controllerName = $this->spArgs('controllerName');
		$genCode = <<<EOT
<?php
class {$controllerName} extends spController{
	public function __construct() {
		parent::__construct();
		\$this -> _controller = '{$controllerName}';
		\$this -> _pk = spClass('{$modelName}') -> pk;
		\$this -> _attributes = spClass('{$modelName}') -> getAttributes();
		\$this -> _attributeFormset = spClass('{$modelName}') -> getAttributeFormsets();
		spAddViewFunction('cbForm',array('LibCodeBuilder','cbFormWidget'));
	}
	
	public function index(){
		\$this -> tpl_page = \$page = \$this -> spArgs('page',1);
		\$this -> tpl_datas = spClass('{$modelName}') -> spPager(\$page, 30) -> findAll();
		\$this -> tpl_pager = spClass('{$modelName}') -> spPager() -> getPager();
		\$this -> display('{$controllerName}/index.html');
	}
	
	public function view(){
		\$condition = array();
		\$condition[\$this->_pk] = \$this -> spArgs('id');
		\$this -> tpl_data = spClass('{$modelName}') -> find(\$condition);
		\$this -> display('{$controllerName}/view.html');
	}
	
	public function create(){
		if(\$_POST){
			\$rows = spClass('spArgs')->get(\$this->_controller, null, 'post');
			if((\$id = spClass('{$modelName}')->create(\$rows)))
				\$this ->success ('新增数据成功',  spUrl (\$this->_controller, 'view', array('id'=>\$id)));
			else
				\$this ->error ('新增数据失败', 'javascript:history.go(-1)');
		}
		\$this ->display('{$controllerName}/create.html');
	}
	
	public function update(){
		\$condition = array();
		\$condition[\$this->_pk] = \$this -> spArgs('id');
		if(\$_POST){
			\$rows = spClass('spArgs')->get(\$this->_controller, null, 'post');
			if(spClass('{$modelName}')->update(\$condition,\$rows))
				\$this ->success ('编辑数据成功',  spUrl (\$this->_controller, 'view', array('id'=>\$this -> spArgs('id'))));
			else
				\$this ->error ('编辑数据失败', 'javascript:history.go(-1)');
		}
		\$this -> tpl_data = spClass('{$modelName}') -> find(\$condition);
		\$this ->display('{$controllerName}/update.html');
	}
	
	public function delete(){
		\$condition = array();
		\$condition[\$this->_pk] = \$this -> spArgs('id');
		\$success = spClass('{$modelName}') -> delete(\$condition);
		if(\$success)
			\$this ->success('删除成功', \$this->spArgs('redirect','javascript:history.go(-1)'));
		else
			\$this ->error('删除失败', \$this->spArgs('redirect','javascript:history.go(-1)'));
	}
	
	public function search(){
		if(\$_POST){
			\$this -> tpl_keyword = \$conditions = spClass('spArgs')->get(\$this->_controller, null, 'post');
			\$this -> tpl_searchType = \$searchType = spClass('spArgs')->get(\$this->_controller.'_searchType', null, 'post');
			\$where = array();
			foreach(\$conditions as \$key => \$value){
				if(empty(\$value)) continue;
				if(empty(\$this->_attributes[\$key])) continue;
				if(\$searchType[\$key] == 1) \$where[] = ' '.\$key.' LIKE '.spClass('{$modelName}')->escape(\$value).' ';
				else \$where[] = ' '.\$key.' LIKE '.spClass('{$modelName}')->escape('%'.\$value.'%').' ';
			}
			\$this -> tpl_page = \$page = \$this -> spArgs('page',1);
			\$this -> tpl_datas = spClass('{$modelName}') -> spPager(\$page, 30) -> findAll(implode('AND',\$where));
			\$this -> tpl_pager = spClass('{$modelName}') -> spPager() -> getPager();
		}
		\$this -> display('{$controllerName}/search.html');
	}
}
EOT;
		$genTemplate = array();
		$genTemplate[0] = array('path'=>$GLOBALS['G_SP']['view']['config']['template_dir'].'/'.$controllerName.'/index.html');
		$genTemplate[0]['contents'] = file_get_contents($GLOBALS['G_SP']['view']['config']['template_dir'].'/codeBuilder/template/index.html');
		$genTemplate[1] = array('path'=>$GLOBALS['G_SP']['view']['config']['template_dir'].'/'.$controllerName.'/create.html');
		$genTemplate[1]['contents'] = file_get_contents($GLOBALS['G_SP']['view']['config']['template_dir'].'/codeBuilder/template/create.html');
		$genTemplate[2] = array('path'=>$GLOBALS['G_SP']['view']['config']['template_dir'].'/'.$controllerName.'/update.html');
		$genTemplate[2]['contents'] = file_get_contents($GLOBALS['G_SP']['view']['config']['template_dir'].'/codeBuilder/template/update.html');
		$genTemplate[3] = array('path'=>$GLOBALS['G_SP']['view']['config']['template_dir'].'/'.$controllerName.'/view.html');
		$genTemplate[3]['contents'] = file_get_contents($GLOBALS['G_SP']['view']['config']['template_dir'].'/codeBuilder/template/view.html');
		$genTemplate[4] = array('path'=>$GLOBALS['G_SP']['view']['config']['template_dir'].'/'.$controllerName.'/search.html');
		$genTemplate[4]['contents'] = file_get_contents($GLOBALS['G_SP']['view']['config']['template_dir'].'/codeBuilder/template/search.html');
		if($actionType == 'preview'){
			$this -> tpl_preview = true;
			$this -> tpl_codePath = $GLOBALS['G_SP']['controller_path'].'/'.$controllerName.'.php';
			$this -> tpl_codePreview = $genCode;
			$this ->_checkOverwrite($this -> tpl_codePath, $genTemplate);
			$this -> tpl_genTemplatePreview = $genTemplate;
			$this -> genCURD();
		}
		if($actionType == 'gen'){
			$this -> tpl_gen = true;
			$this -> tpl_codePath = $GLOBALS['G_SP']['controller_path'].'/'.$controllerName.'.php';
			if(!preg_match('/[0-9a-zA-Z]/', $controllerName))
				$this -> tpl_success = -1;
			else{
				$this -> tpl_success = file_put_contents($GLOBALS['G_SP']['controller_path'].'/'.$controllerName.'.php', $genCode);
				foreach($genTemplate as $template){
					if(!is_file($GLOBALS['G_SP']['view']['config']['template_dir'].'/'.$controllerName.'/')){
						mkdir($GLOBALS['G_SP']['view']['config']['template_dir'].'/'.$controllerName.'/');
					}
					$successTemplate = file_put_contents($template['path'], $template['contents']);
					if(empty($successTemplate)) $this->tpl_success_template = -1;
				}
			}
			$this -> genCURD();
		}
	}
        
        /**
         * Login page
         */
        public function login(){
                if($_POST){
                        if(isset($_POST['password']) && $_POST['password'] == $this->password){
                                //login success
                                $_SESSION['codeBuilder_admin'] = true;
                                $this ->jump(spUrl('CodeBuilder','index'));
                        }
                        else{
                                //login fail
                                $this->tpl_msg = 'Wrong Password!';
                        }
                }
                $this ->display('codeBuilder/login.html');
        }
        
        /**
         * logout page
         */
        public function logout(){
                unset($_SESSION['codeBuilder_admin']);
                $this->jump (spUrl ('CodeBuilder','login'));
        }
	
	private function _findPK($tableColInfo){
		foreach($tableColInfo as $col){
			if($col['Key'] == 'PRI')
				return $col['Field'];
		}
		return $tableColInfo[0]['Field'];
	}
	
	private function _getAttributes($tableColInfo){
		$str = '';
		foreach($tableColInfo as $col){
			$str .= '\''.$col['Field'].'\''.'=>'.'\''.$col['Field'].'\','."\n\t\t\t";
		}
		return $str;
	}
	
	private function _checkOverwrite($codeFilePath, &$tempFiles = array()){
		if(is_file($codeFilePath)){
			$this -> tpl_codeFile_overwrite = true;
		}
		foreach($tempFiles as $key => $item){
			if(is_file($item['path'])){
				$tempFiles[$key]['overwrite'] = true;
			}
		}
	}
}
?>
