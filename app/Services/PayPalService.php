<?php

namespace App\Services;

use App\Models\Billing;
use App\Models\Bookings;
use App\Models\BookingsPayments;

use PayPal\Api\Agreement;
use PayPal\Api\AgreementStateDescriptor;
use PayPal\Api\AgreementTransaction;
use PayPal\Api\Amount;
use PayPal\Api\Authorization;
use PayPal\Api\Capture;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConfigurationException;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Exception\PayPalInvalidCredentialException;
use PayPal\Exception\PayPalMissingCredentialException;
use PayPal\Rest\ApiContext;


class PayPalService
{

	public function __construct(  ) {

	}


	public function charge($booking_id)
	{

		$booking_payments = BookingsPayments::where('booking_id', '=', $booking_id)->first();
		$booking = Bookings::where('booking_id', '=', $booking_id)->first();

		if(!$booking_payments)
			return FALSE;


		$booking_payments = $booking_payments->toArray();
		$booking = $booking->toArray();

		$data = (array)json_decode($booking_payments['data'], TRUE);

		$authorization_id = NULL;
		if(isset($data['response']['authorization_id']))
			$authorization_id = $data['response']['authorization_id'];

		if(isset($data['auth_id']))
			$authorization_id = $data['auth_id'];




		try {
			$apiContext = new ApiContext(new OAuthTokenCredential(
				env( 'PAYPAL_CLIENT_ID' ),
				env( 'PAYPAL_SECRET' )
			));

			if($authorization_id)
			{

				$amt = new \PayPal\Api\Amount();
				$amt->setCurrency('USD');
				$amt->setTotal($booking['price']);

				$capture = new \PayPal\Api\Capture();
				$capture->setIsFinalCapture(true);
				$capture->setAmount($amt);

				$authorization = \PayPal\Api\Authorization::get($authorization_id, $apiContext);

				$res = $authorization->capture($capture, $apiContext);

				\Log::useDailyFiles(storage_path('logs/paypal.log'));
				\Log::info(
					'#### PAYPAL CHARGE ####' . "\n" .
					'- BOOKING ID' . "\n" .
					$booking_id . "\n" .
					'- PAYPAL AUTHORISATION'  . "\n" .
					$authorization_id  . "\n" .
					'- PAYPAL RESPONSE' . "\n" .
					$res
				);


			}
			else
			{
				\Log::useDailyFiles(storage_path('logs/paypal.log'));
				\Log::info(
					'#### PAYPAL PAYMENT ####' . "\n" .
					'- BOOKING ID' . "\n" .
					$booking_id
				);
			}

		} catch (\Exception $e){
			\Log::useDailyFiles(storage_path('logs/paypal-errors.log'));
			\Log::info(
				'#### PAYPAL CHARGE ERROR ####' . "\n" .
				'- BOOKING ID' . "\n" .
				$booking_id . "\n" .
				'- EXCEPTION' . "\n" .
				$e
			);
		} catch (PayPalConfigurationException $e){
			\Log::useDailyFiles(storage_path('logs/paypal-errors.log'));
			\Log::info(
				'#### PAYPAL CHARGE ERROR ####' . "\n" .
				'- BOOKING ID' . "\n" .
				$booking_id . "\n" .
				'- EXCEPTION' . "\n" .
				$e
			);
		} catch (PayPalConnectionException $e){
			\Log::useDailyFiles(storage_path('logs/paypal-errors.log'));
			\Log::info(
				'#### PAYPAL CHARGE ERROR ####' . "\n" .
				'- BOOKING ID' . "\n" .
				$booking_id . "\n" .
				'- EXCEPTION' . "\n" .
				$e
			);
		} catch (PayPalInvalidCredentialException $e){
			\Log::useDailyFiles(storage_path('logs/paypal-errors.log'));
			\Log::info(
				'#### PAYPAL CHARGE ERROR ####' . "\n" .
				'- BOOKING ID' . "\n" .
				$booking_id . "\n" .
				'- EXCEPTION' . "\n" .
				$e
			);
		} catch (PayPalMissingCredentialException $e){
			\Log::useDailyFiles(storage_path('logs/paypal-errors.log'));
			\Log::info(
				'#### PAYPAL CHARGE ERROR ####' . "\n" .
				'- BOOKING ID' . "\n" .
				$booking_id . "\n" .
				'- EXCEPTION' . "\n" .
				$e
			);
		}



	}


	public function void( $booking_id )
	{

		$data = BookingsPayments::where('booking_id', '=', $booking_id)->first();

		if(!$data)
			return FALSE;

		$data = $data->toArray();

		$data = (array)json_decode($data['data'], TRUE);

		$authorization_id = NULL;
		if(isset($data['response']['authorization_id']))
			$authorization_id = $data['response']['authorization_id'];

		if(isset($data['auth_id']))
			$authorization_id = $data['auth_id'];



		try {

			$apiContext = new ApiContext(new OAuthTokenCredential(
				env( 'PAYPAL_CLIENT_ID' ),
				env( 'PAYPAL_SECRET' )
			));


			if($authorization_id)
			{


				$authorization = \PayPal\Api\Authorization::get($authorization_id, $apiContext);

				$res = $authorization->void($apiContext);

				\Log::useDailyFiles(storage_path('logs/paypal.log'));
				\Log::info(
					'#### PAYPAL VOID ####' . "\n" .
					'- BOOKING ID' . "\n" .
					$booking_id . "\n" .
					'- PAYPAL AUTHORISATION'  . "\n" .
					$authorization_id  . "\n" .
					'- PAYPAL RESPONSE' . "\n" .
					$res
				);

			}
			else
			{
				\Log::useDailyFiles(storage_path('logs/paypal.log'));
				\Log::info(
					'#### PAYPAL PAYMENT ####' . "\n" .
					'- BOOKING ID' . "\n" .
					$booking_id
				);
			}


		} catch (\Exception $e){
			\Log::useDailyFiles(storage_path('logs/paypal-errors.log'));
			\Log::info(
				'#### PAYPAL VOID ERROR ####' . "\n" .
				'- BOOKING ID' . "\n" .
				$booking_id . "\n" .
				'- EXCEPTION' . "\n" .
				$e
			);
		} catch (PayPalConfigurationException $e){
			\Log::useDailyFiles(storage_path('logs/paypal-errors.log'));
			\Log::info(
				'#### PAYPAL VOID ERROR ####' . "\n" .
				'- BOOKING ID' . "\n" .
				$booking_id . "\n" .
				'- EXCEPTION' . "\n" .
				$e
			);
		} catch (PayPalConnectionException $e){
			\Log::useDailyFiles(storage_path('logs/paypal-errors.log'));
			\Log::info(
				'#### PAYPAL VOID ERROR ####' . "\n" .
				'- BOOKING ID' . "\n" .
				$booking_id . "\n" .
				'- EXCEPTION' . "\n" .
				$e
			);
		} catch (PayPalInvalidCredentialException $e){
			\Log::useDailyFiles(storage_path('logs/paypal-errors.log'));
			\Log::info(
				'#### PAYPAL VOID ERROR ####' . "\n" .
				'- BOOKING ID' . "\n" .
				$booking_id . "\n" .
				'- EXCEPTION' . "\n" .
				$e
			);
		} catch (PayPalMissingCredentialException $e){
			\Log::useDailyFiles(storage_path('logs/paypal-errors.log'));
			\Log::info(
				'#### PAYPAL VOID ERROR ####' . "\n" .
				'- BOOKING ID' . "\n" .
				$booking_id . "\n" .
				'- EXCEPTION' . "\n" .
				$e
			);
		}


	}


	public function cancelSubscription($user_id)
	{


		$apiContext = new ApiContext(new OAuthTokenCredential(
			env( 'PAYPAL_CLIENT_ID' ),
			env( 'PAYPAL_SECRET' )
		));
		$agreementStateDescriptor = new AgreementStateDescriptor();
		$agreementStateDescriptor->setNote("Canceling subscription");

		$billing = Billing::where('user_id', '=', $user_id)->where('status', '=', Billing::STATUS_ACTIVE)->get();

		if($billing)
		{
			foreach ( $billing as $item )
			{

				if($item->paypal_agrement_id)
				{

					$agreement = Agreement::get( $item->paypal_agrement_id, $apiContext );
					$agreement->cancel( $agreementStateDescriptor, $apiContext );

				}

			}

		}

	}

}