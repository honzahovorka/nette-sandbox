<?php

namespace App;

use Nette\Application\UI,
	WebLoader;


abstract class BasePresenter extends UI\Presenter
{
	/** @var string */
	public $siteName;

	/** @var WebLoader\Nette\LoaderFactory @inject */
	public $webLoaderFactory;


	public function beforeRender()
	{
		parent::beforeRender();

		$this->template->siteName = $this->siteName;
	}


	/**
	 * @return WebLoader\Nette\CssLoader
	 */
	protected function createComponentCss()
	{
		return $this->webLoaderFactory->createCssLoader('default');
	}


	/**
	 * @return WebLoader\Nette\JavaScriptLoader
	 */
	protected function createComponentJs()
	{
		return $this->webLoaderFactory->createJavaScriptLoader('default');
	}
}
