<?php


namespace KgBot\SO24\Classmaps\AuthenticateService;


use Illuminate\Support\Collection;
use KgBot\SO24\Classmaps\SingleResource;

class Identity extends SingleResource
{
	/** @var int */
	public $Id;
	/** @var User */
	public $User;
	/** @var Client */
	public $Client;
	/** @var bool */
	public $IsCurrent;
	/** @var bool */
	public $IsDefault;
	/** @var bool */
	public $IsProtected;
	/** @var Collection */
	private $Servers;
	/** @var bool */
	public $IsDisabled;

	public function getServers() {
		return collect( $this->Servers ?? [] );
	}
}