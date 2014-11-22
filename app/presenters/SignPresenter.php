<?php

namespace App;

use Nette\Application\UI;


/**
 * @property callable signInFormSucceeded
 */
class SignPresenter extends BasePresenter
{
	/** @var SignInFormFactory @inject */
	public $signinFormFactory;


	public function actionIn()
	{
		if ($this->getUser()->isLoggedIn()) {
			$this->redirect('Default:');
		}
	}


	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('You have been signed out');
		$this->redirect('Default:');
	}


	/**
	 * @return UI\Form
	 */
	protected function createComponentSignInForm()
	{
		$form = $this->signinFormFactory->create();
		$form->onSuccess[] = $this->signInFormSucceeded;

		return $form;
	}


	public function signInFormSucceeded($form)
	{
		$this->redirect('Default:');
	}
}
