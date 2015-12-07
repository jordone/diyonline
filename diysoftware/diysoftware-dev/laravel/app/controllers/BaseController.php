<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{

			if (Request::Ajax())
			{
				$this->layout = View::make('layouts.ajax');
			}
			else
			$this->layout = View::make($this->layout);

			$this->layout->content = " ";
		}
	}

}
