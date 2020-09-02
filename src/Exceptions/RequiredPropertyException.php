<?php


namespace KgBot\SO24\Exceptions;


class RequiredPropertyException extends \Exception
{
	protected $message = 'You must declare property ';
}