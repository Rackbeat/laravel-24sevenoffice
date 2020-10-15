<?php


namespace KgBot\SO24\Contracts;


interface SessionContract
{
	public function __construct( string $sessionKey );

	public function setSessionId( $sessionId );

	public function getSessionId();

	public function getSessionKey();
}