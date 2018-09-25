<?php

namespace MediaWiki\Extension\MetaStore;

use OutputPage, Parser, PPFrame, Skin;

/**
 * Class MW_EXT_Donate
 */
class MW_EXT_Donate {

	/**
	 * Register tag function.
	 *
	 * @param Parser $parser
	 *
	 * @return bool
	 * @throws \MWException
	 */
	public static function onParserFirstCallInit( Parser $parser ) {
		$parser->setFunctionHook( 'donate', [ __CLASS__, 'onRenderTag' ], Parser::SFH_OBJECT_ARGS );

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
	 */
	public static function onRenderTag( Parser $parser, PPFrame $frame, array $args ) {
		// Get options parser.
		$getOption = MW_EXT_Kernel::extractOptions( $args, $frame );

		// Argument: receiver.
		$getWallet = MW_EXT_Kernel::outClear( $getOption['ya-wallet'] ?? '' ?: '' );
		$outWallet = $getWallet;

		// Argument: fio.
		$getFIO = MW_EXT_Kernel::outClear( $getOption['ya-fio'] ?? '' ?: '' );
		$outFIO = $getFIO;

		// Argument: email.
		$getEmail = MW_EXT_Kernel::outClear( $getOption['ya-email'] ?? '' ?: '' );
		$outEmail = $getEmail;

		// Argument: phone.
		$getPhone = MW_EXT_Kernel::outClear( $getOption['ya-phone'] ?? '' ?: '' );
		$outPhone = $getPhone;

		// Argument: address.
		$getAddress = MW_EXT_Kernel::outClear( $getOption['ya-address'] ?? '' ?: '' );
		$outAddress = $getAddress;

		// Argument: target.
		$getTargets = MW_EXT_Kernel::outClear( $getOption['ya-target'] ?? '' ?: MW_EXT_Kernel::getMessageText( 'donate', 'targets' ) );
		$outTargets = $getTargets;

		// Argument: sum.
		$getSum = MW_EXT_Kernel::outClear( $getOption['ya-sum'] ?? '' ?: '100' );
		$outSum = $getSum;

		// Get form comment.
		$getFormComment = MW_EXT_Kernel::outClear( MW_EXT_Kernel::getConfig( 'Sitename' ) ) . ': ' . MW_EXT_Kernel::outClear( MW_EXT_Kernel::getTitle()->getText() );
		$outFormComment = $getFormComment;

		// Get short destination.
		$getShortDest = MW_EXT_Kernel::outClear( MW_EXT_Kernel::getConfig( 'Sitename' ) ) . ': ' . MW_EXT_Kernel::outClear( MW_EXT_Kernel::getTitle()->getText() );
		$outShortDest = $getShortDest;

		// Argument: card.
		$getCard = MW_EXT_Kernel::outClear( $getOption['card'] ?? '' ?: '' );
		$outCard = $getCard;

		// Argument: paypal.
		$getPayPal = MW_EXT_Kernel::outClear( $getOption['paypal'] ?? '' ?: '' );
		$outPayPal = $getPayPal;

		// Argument: bitcoin.
		$getBitcoin = MW_EXT_Kernel::outClear( $getOption['bitcoin'] ?? '' ?: '' );
		$outBitcoin = $getBitcoin;

		// Argument: ethereum.
		$getEthereum = MW_EXT_Kernel::outClear( $getOption['ethereum'] ?? '' ?: '' );
		$outEthereum = $getEthereum;

		// Argument: monero.
		$getMonero = MW_EXT_Kernel::outClear( $getOption['monero'] ?? '' ?: '' );
		$outMonero = $getMonero;

		// Out HTML.
		$outHTML = '<div class="mw-ext-donate navigation-not-searchable mw-ext-box">';

		// Yandex.Money
		if ( $outWallet ) {
			$outHTML .= '<div class="mw-ext-donate-body">';
			$outHTML .= '<div class="mw-ext-donate-icon"><div><i class="fab fa-yandex-international"></i></div></div>';
			$outHTML .= '<div class="mw-ext-donate-content">';
			$outHTML .= '<div class="mw-ext-donate-title">' . MW_EXT_Kernel::getMessageText( 'donate', 'to-yandex' ) . '</div>';
			$outHTML .= '<form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml">';
			$outHTML .= '<input name="receiver" value="' . $outWallet . '" type="hidden" />';
			$outHTML .= '<input name="formcomment" value="' . $outFormComment . '" type="hidden" />';
			$outHTML .= '<input name="short-dest" value="' . $outShortDest . '" type="hidden" />';
			$outHTML .= '<input name="label" value="" type="hidden" />';
			$outHTML .= '<input name="quickpay-form" value="donate" type="hidden" />';
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
			$outHTML .= '<div><div><input name="sum" value="' . $outSum . '" data-type="number" type="number" placeholder="' . MW_EXT_Kernel::getMessageText( 'donate', 'sum-placeholder' ) . '" /></div>';
			$outHTML .= '<div class="mw-ext-donate-desc">' . MW_EXT_Kernel::getMessageText( 'donate', 'to-yandex-desc-sum' ) . '</div></div>';
			$outHTML .= '<div><div><textarea name="comment" placeholder="' . MW_EXT_Kernel::getMessageText( 'donate', 'comment-placeholder' ) . '"></textarea></div>';
			$outHTML .= '<div class="mw-ext-donate-desc">' . MW_EXT_Kernel::getMessageText( 'donate', 'to-yandex-desc-comment' ) . '</div></div>';
			$outHTML .= '</div><div class="mw-ext-donate-select"><div>';
			$outHTML .= '<div><input id="paymentTypePC" name="paymentType" value="PC" type="radio" /><label title="' . MW_EXT_Kernel::getMessageText( 'donate', 'payment-pc' ) . '" for="paymentTypePC"><i class="fab fa-yandex-international fa-fw"></i></label></div>';
			$outHTML .= '<div><input id="paymentTypeAC" name="paymentType" value="AC" type="radio" checked /><label title="' . MW_EXT_Kernel::getMessageText( 'donate', 'payment-ac' ) . '" for="paymentTypeAC"><i class="far fa-credit-card fa-fw"></i></label></div>';
			$outHTML .= '<div><input id="paymentTypeMC" name="paymentType" value="MC" type="radio" /><label title="' . MW_EXT_Kernel::getMessageText( 'donate', 'payment-mc' ) . '" for="paymentTypeMC"><i class="fas fa-mobile-alt fa-fw"></i></label></div>';
			$outHTML .= '</div><div class="mw-ext-donate-desc">' . MW_EXT_Kernel::getMessageText( 'donate', 'to-yandex-desc-payment-type' ) . '</div>';
			$outHTML .= '</div><div class="mw-ext-donate-submit">';
			$outHTML .= '<input class="mw-ext-donate-button" value="' . MW_EXT_Kernel::getMessageText( 'donate', 'submit' ) . '" type="submit" />';
			$outHTML .= '</div></form></div></div>';
		}

		// Card.
		if ( $outCard ) {
			$outHTML .= '<div class="mw-ext-donate-body">';
			$outHTML .= '<div class="mw-ext-donate-icon"><div><i class="fas fa-credit-card"></i></div></div>';
			$outHTML .= '<div class="mw-ext-donate-content">';
			$outHTML .= '<div class="mw-ext-donate-title">' . MW_EXT_Kernel::getMessageText( 'donate', 'to-card' ) . '</div>';
			$outHTML .= '<div><input value="' . $outCard . '" type="text" readonly /></div>';
			$outHTML .= '<div class="mw-ext-donate-desc">' . MW_EXT_Kernel::getMessageText( 'donate', 'to-card-desc' ) . '</div>';
			$outHTML .= '</div></div>';
		}

		// PayPal.
		if ( $outPayPal ) {
			$outHTML .= '<div class="mw-ext-donate-body">';
			$outHTML .= '<div class="mw-ext-donate-icon"><div><i class="fab fa-paypal"></i></div></div>';
			$outHTML .= '<div class="mw-ext-donate-content">';
			$outHTML .= '<div class="mw-ext-donate-title">' . MW_EXT_Kernel::getMessageText( 'donate', 'to-paypal' ) . '</div>';
			$outHTML .= '<div><a class="mw-ext-donate-button" href="https://www.paypal.me/' . $outPayPal . '" rel="nofollow" target="_blank">' . $outPayPal . '</a></div>';
			$outHTML .= '<div class="mw-ext-donate-desc">' . MW_EXT_Kernel::getMessageText( 'donate', 'to-paypal-desc' ) . '</div>';
			$outHTML .= '</div></div>';
		}

		// Bitcoin
		if ( $outBitcoin ) {
			$outHTML .= '<div class="mw-ext-donate-body">';
			$outHTML .= '<div class="mw-ext-donate-icon"><div><i class="fab fa-bitcoin"></i></div></div>';
			$outHTML .= '<div class="mw-ext-donate-content">';
			$outHTML .= '<div class="mw-ext-donate-title">' . MW_EXT_Kernel::getMessageText( 'donate', 'to-bitcoin' ) . '</div>';
			$outHTML .= '<div><input value="' . $outBitcoin . '" type="text" readonly /></div>';
			$outHTML .= '<div class="mw-ext-donate-desc">' . MW_EXT_Kernel::getMessageText( 'donate', 'to-bitcoin-desc' ) . '</div>';
			$outHTML .= '</div></div>';
		}

		// Ethereum
		if ( $outEthereum ) {
			$outHTML .= '<div class="mw-ext-donate-body">';
			$outHTML .= '<div class="mw-ext-donate-icon"><div><i class="fab fa-ethereum"></i></div></div>';
			$outHTML .= '<div class="mw-ext-donate-content">';
			$outHTML .= '<div class="mw-ext-donate-title">' . MW_EXT_Kernel::getMessageText( 'donate', 'to-ethereum' ) . '</div>';
			$outHTML .= '<div><input value="' . $outEthereum . '" type="text" readonly /></div>';
			$outHTML .= '<div class="mw-ext-donate-desc">' . MW_EXT_Kernel::getMessageText( 'donate', 'to-ethereum-desc' ) . '</div>';
			$outHTML .= '</div></div>';
		}

		// Monero
		if ( $outMonero ) {
			$outHTML .= '<div class="mw-ext-donate-body">';
			$outHTML .= '<div class="mw-ext-donate-icon"><div><i class="fab fa-monero"></i></div></div>';
			$outHTML .= '<div class="mw-ext-donate-content">';
			$outHTML .= '<div class="mw-ext-donate-title">' . MW_EXT_Kernel::getMessageText( 'donate', 'to-monero' ) . '</div>';
			$outHTML .= '<div><input value="' . $outMonero . '" type="text" readonly /></div>';
			$outHTML .= '<div class="mw-ext-donate-desc">' . MW_EXT_Kernel::getMessageText( 'donate', 'to-monero-desc' ) . '</div>';
			$outHTML .= '</div></div>';
		}

		$outHTML .= '</div>';

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
	 */
	public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
		$out->addModuleStyles( [ 'ext.mw.donate.styles' ] );

		return true;
	}
}
