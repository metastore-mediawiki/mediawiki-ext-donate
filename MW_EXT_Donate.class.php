<?php

namespace MediaWiki\Extension\MW_EXT_Donate;

use OutputPage, Parser, PPFrame, Skin;
use MediaWiki\Extension\MW_EXT_Core\MW_EXT_Core;

/**
 * Class MW_EXT_YaQuickPay
 * ------------------------------------------------------------------------------------------------------------------ */
class MW_EXT_Donate {

	/**
	 * Register tag function.
	 *
	 * @param Parser $parser
	 *
	 * @return bool
	 * @throws \MWException
	 * -------------------------------------------------------------------------------------------------------------- */

	public static function onParserFirstCallInit( Parser $parser ) {
		$parser->setFunctionHook( 'donate', __CLASS__ . '::onRenderTag', Parser::SFH_OBJECT_ARGS );

		return true;
	}

	/**
	 * Render tag function.
	 *
	 * @param Parser $parser
	 * @param PPFrame $frame
	 * @param array $args
	 *
	 * @return string
	 * @throws \ConfigException
	 * -------------------------------------------------------------------------------------------------------------- */

	public static function onRenderTag( Parser $parser, PPFrame $frame, $args = [] ) {
		// Get options parser.
		$getOption = MW_EXT_Core::extractOptions( $args, $frame );

		// Argument: receiver.
		$getWallet = MW_EXT_Core::outClear( $getOption['wallet'] ?? '' ?: '' );
		$outWallet = $getWallet;

		// Argument: form.
		$getPayForm = MW_EXT_Core::outClear( $getOption['form'] ?? '' ?: 'donate' );
		$outPayForm = $getPayForm;

		// Argument: fio.
		$getFIO = MW_EXT_Core::outClear( $getOption['fio'] ?? '' ?: '' );
		$outFIO = $getFIO;

		// Argument: email.
		$getEmail = MW_EXT_Core::outClear( $getOption['email'] ?? '' ?: '' );
		$outEmail = $getEmail;

		// Argument: phone.
		$getPhone = MW_EXT_Core::outClear( $getOption['phone'] ?? '' ?: '' );
		$outPhone = $getPhone;

		// Argument: address.
		$getAddress = MW_EXT_Core::outClear( $getOption['address'] ?? '' ?: '' );
		$outAddress = $getAddress;

		// Argument: target.
		$getTargets = MW_EXT_Core::outClear( $getOption['target'] ?? '' ?: MW_EXT_Core::getMessageText( 'donate', 'targets' ) );
		$outTargets = $getTargets;

		// Argument: sum.
		$getSum = MW_EXT_Core::outClear( $getOption['sum'] ?? '' ?: '' );
		$outSum = $getSum;

		// Get form comment.
		$getFormComment = MW_EXT_Core::outClear( MW_EXT_Core::getConfig( 'Sitename' ) ) . ': ' . MW_EXT_Core::outClear( MW_EXT_Core::getTitle()->getText() );
		$outFormComment = $getFormComment;

		// Get short destination.
		$getShortDest = MW_EXT_Core::outClear( MW_EXT_Core::getConfig( 'Sitename' ) ) . ': ' . MW_EXT_Core::outClear( MW_EXT_Core::getTitle()->getText() );
		$outShortDest = $getShortDest;

		// Out HTML.
		$outHTML = '<div class="mw-ext-donate">';
		$outHTML .= '<div class="mw-ext-donate-body">';
		$outHTML .= '<div class="mw-ext-donate-icon"><div><i class="fas fa-donate"></i></div></div>';
		$outHTML .= '<div class="mw-ext-donate-content">';
		$outHTML .= '<h4>' . MW_EXT_Core::getMessageText( 'donate', 'title' ) . '</h4>';
		$outHTML .= '<form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml">';
		$outHTML .= '<input name="receiver" value="' . $outWallet . '" type="hidden" />';
		$outHTML .= '<input name="formcomment" value="' . $outFormComment . '" type="hidden" />';
		$outHTML .= '<input name="short-dest" value="' . $outShortDest . '" type="hidden" />';
		$outHTML .= '<input name="label" value="" type="hidden" />';
		$outHTML .= '<input name="quickpay-form" value="' . $outPayForm . '" type="hidden" />';
		$outHTML .= '<input name="targets" value="' . $outTargets . '" type="hidden" />';

		if ( $outFIO ) {
			$outHTML .= '<input name="need-fio" value="true" type="hidden" />';
		}

		if ( $outEmail ) {
			$outHTML .= '<input name="need-email" value="true" type="hidden" />';
		}

		if ( $outPhone ) {
			$outHTML .= '<input name="need-phone" value="true" type="hidden" />';
		}

		if ( $outAddress ) {
			$outHTML .= '<input type="hidden" name="need-address" value="true" />';
		}

		$outHTML .= '<div class="mw-ext-donate-form">';
		$outHTML .= '<div><input name="sum" value="' . $outSum . '" data-type="number" type="number" placeholder="' . MW_EXT_Core::getMessageText( 'donate', 'sum-placeholder' ) . '" /></div>';
		$outHTML .= '<div><textarea name="comment" placeholder="' . MW_EXT_Core::getMessageText( 'donate', 'comment-placeholder' ) . '"></textarea></div>';
		$outHTML .= '</div><div class="mw-ext-donate-select">';
		$outHTML .= '<div><input id="paymentTypePC" name="paymentType" value="PC" type="radio" /><label title="' . MW_EXT_Core::getMessageText( 'donate', 'payment-pc' ) . '" for="paymentTypePC"><i class="fab fa-yandex-international fa-fw"></i></label></div>';
		$outHTML .= '<div><input id="paymentTypeAC" name="paymentType" value="AC" type="radio" checked /><label title="' . MW_EXT_Core::getMessageText( 'donate', 'payment-ac' ) . '" for="paymentTypeAC"><i class="far fa-credit-card fa-fw"></i></label></div>';
		$outHTML .= '<div><input id="paymentTypeMC" name="paymentType" value="MC" type="radio" /><label title="' . MW_EXT_Core::getMessageText( 'donate', 'payment-mc' ) . '" for="paymentTypeMC"><i class="fas fa-mobile-alt fa-fw"></i></label></div>';
		$outHTML .= '</div><div class="mw-ext-donate-submit">';
		$outHTML .= '<input value="' . MW_EXT_Core::getMessageText( 'donate', 'submit' ) . '" type="submit" />';
		$outHTML .= '</div></form></div></div></div>';

		// Out parser.
		$outParser = $parser->insertStripItem( $outHTML, $parser->mStripState );

		return $outParser;
	}

	/**
	 * Load resource function.
	 *
	 * @param OutputPage $out
	 * @param Skin $skin
	 *
	 * @return bool
	 * -------------------------------------------------------------------------------------------------------------- */

	public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
		$out->addModuleStyles( [ 'ext.mw.donate.styles' ] );

		return true;
	}
}
