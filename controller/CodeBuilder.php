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
        
        public function genModel(){
                $model = new spModel();
                $this -> tpl_prefix = $GLOBALS['G_SP']["db"]['prefix'];
                $this -> tpl_tables = $model ->findSql('SHOW TABLES;');
                $this ->display('codeBuilder/genModel.html');
        }
	
	public function genModelAction(){
		$actionType = $this->spArgs('genAction', 'preview');
		$modelName = $this->spArgs('modelName');
		$modelTable = $this->spArgs('modelTable');
		$tableColInfo = spClass('spModel') -> findSql('show columns from '.$modelTable.';');
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
			echo $genCode;
		}
		if($actionType == 'gen'){
			
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
