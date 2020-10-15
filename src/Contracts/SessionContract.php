<?php


namespace KgBot\SO24\Contracts;


interface SessionContract
{
	public function __construct( string $sessionKey );

	/**
	 * @param string $sessionId
	 *
	 * @return void
	 */
	public function setSessionId( string $sessionId ): void;

	/**
	 * @return string|null
	 */
	public function getSessionId(): ?string;

	/**
	 * @return string
	 */
	public function getSessionKey(): string;
}