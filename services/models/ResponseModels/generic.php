<?php
    class generic {
		public $user;

		public function __construct(){
			$this->user = new UserModel();
        }
}
?>