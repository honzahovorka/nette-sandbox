<?php

namespace App;

use Nette\Application\UI,
	Nette\Security;


class UserPortlet extends UI\Control
{
	/** @var Security\User */
	private $user;


	public function __construct(Security\User $user)
	{
		$this->user = $user;
	}


	public function render()
	{
		$this->template->setFile(__DIR__ . '/default.latte');
		$this->template->isLoggedIn = $this->user->isLoggedIn();
		$this->template->identity = $this->user->identity;

		$this->template->render();
	}
}
