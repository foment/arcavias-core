<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://www.arcavias.com/en/license
 */


class TestHelper
{
	private static $_arcavias;
	private static $_context;


	public static function bootstrap()
	{
		$arcavias = self::_getArcavias();

		$includepaths = $arcavias->getIncludePaths();
		$includepaths[] = get_include_path();
		set_include_path( implode( PATH_SEPARATOR, $includepaths ) );

		spl_autoload_register( 'Arcavias::autoload' );
	}


	public static function getContext( $site = 'unittest' )
	{
		if( !isset( self::$_context[$site] ) ) {
			self::$_context[$site] = self::_createContext( $site );
		}

		return clone self::$_context[$site];
	}


	private static function _getArcavias()
	{
		if( !isset( self::$_arcavias ) )
		{
			require_once 'Arcavias.php';
			spl_autoload_register( 'Arcavias::autoload' );

			$extdir = dirname( dirname( dirname( dirname( __FILE__ ) ) ) );
			self::$_arcavias = new Arcavias( array( $extdir ), false );
		}

		return self::$_arcavias;
	}


	public static function getControllerPaths()
	{
		return self::getArcavias()->getCustomPaths( 'controller/jobs' );
	}


	private static function _createContext( $site )
	{
		$ctx = new MShop_Context_Item_Default();
		$arcavias = self::_getArcavias();


		$paths = $arcavias->getConfigPaths( 'mysql' );
		$paths[] = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'config';

		$conf = new MW_Config_Array( array(), $paths );
		$conf = new MW_Config_Decorator_Memory( $conf );
		$ctx->setConfig( $conf );


		$dbm = new MW_DB_Manager_PDO( $conf );
		$ctx->setDatabaseManager( $dbm );


		$logger = new MW_Logger_File( $site . '.log', MW_Logger_Abstract::DEBUG );
		$ctx->setLogger( $logger );


		$session = new MW_Session_None();
		$ctx->setSession( $session );


		$i18n = new MW_Translation_None( 'de' );
		$ctx->setI18n( array( 'de' => $i18n ) );


		$localeManager = MShop_Locale_Manager_Factory::createManager( $ctx );
		$locale = $localeManager->bootstrap( $site, 'de', '', false );
		$ctx->setLocale( $locale );


		$view = self::_createView( $conf );
		$ctx->setView( $view );


		$ctx->setEditor( 'core:controller/jobs' );

		return $ctx;
	}


	protected static function _createView( MW_Config_Interface $config )
	{
		$view = new MW_View_Default();

		$helper = new MW_View_Helper_Config_Default( $view, $config );
		$view->addHelper( 'config', $helper );

		$sepDec = $config->get( 'client/html/common/format/seperatorDecimal', '.' );
		$sep1000 = $config->get( 'client/html/common/format/seperator1000', ' ' );
		$helper = new MW_View_Helper_Number_Default( $view, $sepDec, $sep1000 );
		$view->addHelper( 'number', $helper );

		$helper = new MW_View_Helper_Encoder_Default( $view );
		$view->addHelper( 'encoder', $helper );

		return $view;
	}


	public static function errorHandler($code, $message, $file, $row)
	{
		return true;
	}

}
