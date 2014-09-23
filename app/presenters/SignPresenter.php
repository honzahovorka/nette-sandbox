<?php

namespace App;

use Nette\Application\UI,
	Nette\Security;


class SignPresenter extends BasePresenter
{

	/**
	 * @return UI\Form
	 */
	protected function createComponentSignInForm()
	{
		$form = new UI\Form;

		$form->addText('username', 'Username:')
			->setRequired('Please enter your username.');

		$form->addPassword('password', 'Password:')
			->setRequired('Please enter your password.');

		$form->addCheckbox('remember', 'Keep me signed in');

		$form->addSubmit('submit', 'Sign in');

		$form->onSuccess[] = $this->signInFormSucceeded;

		return $form;
	}


	public function signInFormSucceeded($form, $values)
	{
		if ($values->remember) {
			$this->getUser()->setExpiration('14 days', FALSE);
		} else {
			$this->getUser()->setExpiration('20 minutes', TRUE);
		}

		try {
			$this->getUser()->login($values->username, $values->password);
			$this->redirect('Default:');

		} catch (Security\AuthenticationException $e) {
			if ($e->getCode() === Security\IAuthenticator::IDENTITY_NOT_FOUND) {
				$form['username']->addError($e->getMessage());
			} elseif ($e->getCode() === Security\IAuthenticator::INVALID_CREDENTIAL) {
				$form['password']->addError($e->getMessage());
			} else {
				$form->addError($e->getMessage());
			}
		}
	}


	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('You have been signed out');
		$this->redirect('in');
	}

}
