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
class {$modelName} extends spModel{
	public \$table = '{$modelTable}';
	public \$pk = '{$tablePk}';
		
	public function getAttributes(){
		return array(
			{$this->_getAttributes($tableColInfo)});
	}
}
EOT;
		if($actionType == 'preview'){
			$this -> tpl_preview = true;
			$this -> tpl_codePath = $GLOBALS['G_SP']['model_path'].'/'.$modelName.'.php';
			$this -> tpl_codePreview = $genCode;
			$this -> genModel();
		}
		if($actionType == 'gen'){
			if(!preg_match('/[0-9a-zA-Z]/', $modelName))
				$this -> tpl_success = -1;
			else
				$this -> tpl_success = file_put_contents($GLOBALS['G_SP']['model_path'].'/'.$modelName.'.php', $genCode);
			$this -> tpl_gen = true;
			$this -> tpl_codePath = $GLOBALS['G_SP']['model_path'].'/'.$modelName.'.php';
			$this -> genModel();
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
}
?>
