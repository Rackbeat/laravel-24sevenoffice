<?php
/**
 * Created by PhpStorm.
 * User: nts
 * Date: 19.4.18.
 * Time: 01.32
 */

namespace KgBot\SO24\Builders;


use KgBot\SO24\Models\Product;

class ProductBuilder extends Builder
{
	protected $entity        = 'ProductService';
	protected $resource_name = 'products';
	protected $model         = Product::class;
}