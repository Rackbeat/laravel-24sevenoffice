<?php
/**
 * Created by PhpStorm.
 * User: nts
 * Date: 2.4.18.
 * Time: 01.02
 */

namespace KgBot\SO24;


use Illuminate\Support\ServiceProvider;

class SO24ServiceProvider extends ServiceProvider
{
	/**
	 * Boot.
	 */
	public function boot() {
		$configPath = __DIR__ . '/config/laravel-24so.php';

		$this->mergeConfigFrom( $configPath, 'laravel-24so.php' );

		if ( function_exists( 'config_path' ) ) {

			$publishPath = config_path( 'laravel-24so.php' );

		} else {

			$publishPath = base_path( 'config/laravel-24so.php' );

		}

		$this->publishes( [ $configPath => $publishPath ], 'config' );
	}

	public function register() {
	}
}