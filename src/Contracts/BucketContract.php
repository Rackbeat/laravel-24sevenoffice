<?php


namespace KgBot\SO24\Contracts;


use Illuminate\Support\Facades\Cache;

interface BucketContract
{

	public function __construct( string $limitKey );

	public function setCallsLeft( int $callsLeft );

	public function getCallsLeft();

	public function getCacheKey();
}