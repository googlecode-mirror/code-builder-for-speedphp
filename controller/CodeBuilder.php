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
}
?>
