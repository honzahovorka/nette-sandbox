<?php

namespace App;

use Nette\Application\UI;


abstract class BasePresenter extends UI\Presenter
{
	/** @var string */
	public $siteName;


	public function beforeRender()
	{
		parent::beforeRender();

		$this->template->siteName = $this->siteName;
	}
}
