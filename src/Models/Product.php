<?php
/**
 * Created by PhpStorm.
 * User: nts
 * Date: 19.4.18.
 * Time: 01.30
 */

namespace KgBot\SO24\Models;


use KgBot\SO24\Utils\Model;

class Product extends Model
{
	protected $entity        = 'ProductService';
	protected $resource_name = 'products';
	protected $primaryKey    = 'Id';
}
