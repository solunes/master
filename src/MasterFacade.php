<?php
namespace Solunes\Master;

use Illuminate\Support\Facades\Facade;

class MasterFacade extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'master';
	}
}