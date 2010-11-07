<?php
#include_once 'A/User/Session.php';
#include_once 'A/Model/Form.php';

class user extends A_Controller_Action {

	public function login($locator) {

		$session = $locator->get('Session');
		$user = $locator->get('UserSession');
		$session->start();		// controller and view use session
		$session->set('foo', 'bar');
		
		$form = new A_Model_Form();
		$field = new A_Model_Form_Field('username');
		$field->addRule(new A_Rule_Notnull('username', 'Username required'));
		$form->addField($field);
		$field = new A_Model_Form_Field('password');
		$field->addRule(new A_Rule_Notnull('password', 'Password required'));
		$form->addField($field);
		
		$errmsg = '';

		// If username and password valid and isPost
		if($form->isValid($this->request)){ 
#echo "form->isValid<br/>";
			
			// How to translate URL in correct action variable?
			$model = $this->_load('app')->model('users');
			$userdata = $model->login($form->get('username'), $form->get('password'));

			if ($userdata) {	// user record matching userid and password found
				unset($userdata['password']);		// don't save passwords in the session
				$user->login($userdata);
				$this->_redirect($locator->get('Config')->get('BASE') . 'user/login/');	// build redirect URL back to this page
			} else {
				$errmsg = $model->loginErrorMsg();
			}
		} elseif($form->isSubmitted()){		// submitted form has errors
			$errmsg =  $form->getErrorMsg(', ');
		}
		
		$template = $this->_load()->template('login');
		$template->set('errmsg', $errmsg);
		$template->set('username', $form->get('username'));
		$template->set('user', $user);
		
		$this->response->set('maincontent', $template);
	}
	
	public function logout($locator) {
		
		$session = $locator->get('Session');
		$user = $locator->get('UserSession');
		$session->start();
		
		if ($user->isLoggedIn()) {	// user record matching userid and password found
			$user->logout();
		}
		
		$this->_redirect($locator->get('Config')->get('BASE') . 'user/login/');	// build redirect URL back to this page
	}
	
	public function register($locator){
		
		$session = $locator->get('Session');
		$user = $locator->get('UserSession');
		$session->start();	
		// Set the default status for the view
		$errorstatus = 'S0';
		$errmsg = '';
		$model = $this->_load('app')->model('users');
		if($this->request->isPost()){
			
			$result = $model->register($this->request);
			if($result === 'S4'){
				// Registration succesful
				$errmsg = 'You are succesfully registered!';
			
			} else {
				// Return the registration status to the view
				$errorstatus = $result;
				$errmsg = $model->getErrorMsg("</li>\n<li>");
			}
		}
		
		// Show registration form
		$template = $this->_load()->template('register');
		$template->set('errorstatus', $errorstatus);
		$template->set('errmsg', $errmsg);
		$template->set('user', $user);
		$template->set('model', $model);
		
		$this->response->set('maincontent', $template);
	}
	
	public function activate($locator){}
	
	public function password($locator){
		
		$session = $locator->get('Session');
		$user = $locator->get('UserSession');
		$session->start();
		
		$errmsg = '';

		$form = new A_Model_Form();
		$field = new A_Model_Form_Field('username');
		$field->addRule(new A_Rule_Notnull('username', 'username required'));
		$form->addField($field);
		// @todo: should we check in db if filled in username even exists
		
		$model = $this->_load('app')->model('users');
		
		// If password forgot form is posted and is valid
		if($form->isValid($this->request)){
			// @todo: retrieve email+password from user model and send email with pw
			
		} elseif($form->isSubmitted()){		// submitted form has errors
			$errmsg =  $form->getErrorMsg(', ');
		}
		
		// Show password forgot page and form
		$template = $this->_load()->template('password');
		$template->set('errmsg', $errmsg);
		$template->set('user', $user);
		$this->response->set('maincontent', $template);
	}
		
	public function profile($locator){
		$session = $locator->get('Session');
		$user = $locator->get('UserSession');
		$session->start();
		
		$errmsg = '';

		// If user is not signed in don't show profile page but redirect to login?
		if (!$user->isLoggedIn()) {
			$this->_redirect($locator->get('Config')->get('BASE') . 'user/login/');	// build redirect URL back to this page		
		}
				
		$form = new A_Model_Form();
		// @todo: what info do we want
		
		// To show the profile we need the model
		$model = $this->_load('app')->model('users');
		// @todo: load user data from db
		
		// If profile form is posted and is valid
		if($form->isValid($this->request)){
			// @todo: save/update profile data
			
		} elseif($form->isSubmitted()){		// submitted form has errors
			$errmsg =  $form->getErrorMsg(', ');
		}
		
		// Show profile page and form
		$template = $this->_load()->template('profile');
		$template->set('errmsg', $errmsg);
		$template->set('user', $user);
		$this->response->set('maincontent', $template);
	}
	

	
}