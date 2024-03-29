<?php
/**
 * MediaWiki math extension
 *
 * @file
 * @ingroup Extensions
 * @version 1.0
 * @author Tomasz Wegrzanowski
 * @author Brion Vibber
 * @author Moritz Schubotz
 * @copyright © 2002-2012 various MediaWiki contributors
 * @license GPLv2 license; info in main package.
 * @link http://www.mediawiki.org/wiki/Extension:Math Documentation
 * @see https://bugzilla.wikimedia.org/show_bug.cgi?id=14202
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( "This is not a valid entry point to MediaWiki.\n" );
}

// Extension credits that will show up on Special:Version
$wgExtensionCredits['parserhook'][] = array(
	'path' => __FILE__,
	'name' => 'Math',
	'version' => '1.0',
	'author' => array( 'Tomasz Wegrzanowski', 'Brion Vibber', '...' ),
	'descriptionmsg' => 'math-desc',
	'url' => 'https://www.mediawiki.org/wiki/Extension:Math',
);

/**@{
 * Maths constants
 */
define( 'MW_MATH_PNG',    0 );
define( 'MW_MATH_SIMPLE', 1 ); /// @deprecated
define( 'MW_MATH_HTML',   2 ); /// @deprecated
define( 'MW_MATH_SOURCE', 3 );
define( 'MW_MATH_MODERN', 4 ); /// @deprecated
define( 'MW_MATH_MATHML', 5 ); /// @deprecated
define( 'MW_MATH_MATHJAX', 6 ); /// new in 1.19/1.20
/**@}*/

/** For back-compat */
$wgUseTeX = true;

/** Location of the texvc binary */
$wgTexvc = dirname( __FILE__ ) . '/math/texvc';
/**
 * Texvc background color
 * use LaTeX color format as used in \special function
 * for transparent background use value 'Transparent' for alpha transparency or
 * 'transparent' for binary transparency.
 */
$wgTexvcBackgroundColor = 'transparent';

/**
 * Normally when generating math images, we double-check that the
 * directories we want to write to exist, and that files that have
 * been generated still exist when we need to bring them up again.
 *
 * This lets us give useful error messages in case of permission
 * problems, and automatically rebuild images that have been lost.
 *
 * On a big site with heavy NFS traffic this can be slow and flaky,
 * so sometimes we want to short-circuit it by setting this to false.
 */
$wgMathCheckFiles = true;

/**
 * The URL path of the math directory. Defaults to "{$wgUploadPath}/math".
 *
 * See http://www.mediawiki.org/wiki/Manual:Enable_TeX for details about how to
 * set up mathematical formula display.
 */
$wgMathPath = false;

/**
 * The name of a file backend ($wgFileBackends) to use for storing math renderings.
 * Defaults to FSFileBackend using $wgMathDirectory as a base path.
 *
 * See http://www.mediawiki.org/wiki/Manual:Enable_TeX for details about how to
 * set up mathematical formula display.
 */
$wgMathFileBackend = false;

/**
 * The filesystem path of the math directory.
 * Defaults to "{$wgUploadDirectory}/math".
 *
 * See http://www.mediawiki.org/wiki/Manual:Enable_TeX for details about how to
 * set up mathematical formula display.
 */
$wgMathDirectory = false;

/**
 * Experimental option to use MathJax library to do client-side math rendering
 * when JavaScript is available. In supporting browsers this makes nice output
 * that's scalable for zooming, printing, and high-resolution displays.
 *
 * Not guaranteed to be stable at this time.
 */
$wgUseMathJax = false;

/**
 * Use of MathJax's CDN is governed by terms of service
 * <http://www.mathjax.org/download/mathjax-cdn-terms-of-service/>
 *
 * If you don't like them, install your own copy to load.
 */
$wgMathJaxUrl = 'http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_HTML';

////////// end of config settings.

$wgDefaultUserOptions['math'] = MW_MATH_PNG;

$wgExtensionFunctions[] = 'MathHooks::setup';
$wgHooks['ParserFirstCallInit'][] = 'MathHooks::onParserFirstCallInit';
$wgHooks['GetPreferences'][] = 'MathHooks::onGetPreferences';
$wgHooks['LoadExtensionSchemaUpdates'][] = 'MathHooks::onLoadExtensionSchemaUpdates';
$wgHooks['ParserTestTables'][] = 'MathHooks::onParserTestTables';
$wgHooks['ParserTestParser'][] = 'MathHooks::onParserTestParser';
$wgHooks['UnitTestsList'][] = 'MathHooks::onRegisterUnitTests';

$dir = dirname( __FILE__ ) . '/';
$wgAutoloadClasses['MathHooks'] = $dir . 'Math.hooks.php';
$wgAutoloadClasses['MathRenderer'] = $dir . 'MathRenderer.php';
$wgAutoloadClasses['MathTexvc'] = $dir . 'MathTexvc.php';
$wgAutoloadClasses['MathSource'] = $dir . 'MathSource.php';
$wgAutoloadClasses['MathMathJax'] = $dir . 'MathMathJax.php';
$wgExtensionMessagesFiles['Math'] = $dir . 'Math.i18n.php';

$wgParserTestFiles[] = $dir . 'mathParserTests.txt';

$moduleTemplate = array(
	'localBasePath' => dirname( __FILE__ ) . '/modules',
	'remoteExtPath' => 'Math/modules',
);

$wgResourceModules['ext.math.mathjax'] = array(
	'scripts' => array(
		'MathJax/MathJax.js',
		// We'll let the other parts be loaded by MathJax's
		// own module/config loader.
	),
	'group' => 'ext.math.mathjax',
) + $moduleTemplate;

$wgResourceModules['ext.math.mathjax.enabler'] = array(
	'scripts' => 'ext.math.mathjax.enabler.js',
) + $moduleTemplate;
