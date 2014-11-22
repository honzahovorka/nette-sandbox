<?php

namespace App;

use Nette,
	Nette\Application\UI,
	Nette\Security;


/**
 * @property callable formSucceeded
 */
class SignInFormFactory extends Nette\Object
{

	/** @var Security\User */
	private $user;


	public function __construct(Security\User $user)
	{
		$this->user = $user;
	}


	/**
	 * @return UI\Form
	 */
	public function create()
	{
		$form = new UI\Form;

		$form->addText('username', 'Username:')
			->setRequired('Please enter your username.');

		$form->addPassword('password', 'Password:')
			->setRequired('Please enter your password.');

		$form->addCheckbox('remember', 'Keep me signed in');

		$form->addSubmit('submit', 'Sign in');

		$form->onSuccess[] = $this->formSucceeded;

		return $form;
	}


	public function formSucceeded($form, $values)
	{
		if ($values->remember) {
			$this->user->setExpiration('14 days', FALSE);
		} else {
			$this->user->setExpiration('20 minutes', TRUE);
		}

		try {
			$this->user->login($values->username, $values->password);
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

}
