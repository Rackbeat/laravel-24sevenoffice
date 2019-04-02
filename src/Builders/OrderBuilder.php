<?php
/**
 * Created by PhpStorm.
 * User: nts
 * Date: 19.4.18.
 * Time: 01.32
 */

namespace KgBot\SO24\Builders;


use KgBot\SO24\Models\Order;

class OrderBuilder extends Builder
{
	protected $entity = 'orders';
	protected $model  = Order::class;
}