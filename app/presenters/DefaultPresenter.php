<?php

namespace App;


class DefaultPresenter extends BasePresenter
{

	public function renderDefault()
	{
	}


	/**
	 * @return UserPortlet
	 */
	protected function createComponentUserPortlet()
	{
		return new UserPortlet($this->getUser());
	}

}
