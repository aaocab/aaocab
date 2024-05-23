<?php

class Accounting
{

	const LI_CASH					 = 1;
	const LI_BOOKING				 = 13;
	const LI_OPERATOR				 = 14;
	const LI_PARTNER				 = 15;
	const LI_DRIVER				 = 40;
	const LI_PAYTM				 = 16;
	const LI_MOBIKWIK				 = 17;
	const LI_FREECHARGE			 = 18;
	const LI_ZZAAKPAY				 = 19;
	const LI_PAYU					 = 20;
	const LI_EBS					 = 21;
	const LI_TRIP					 = 22;
	const LI_BANK					 = 23;
	const LI_DISCOUNT				 = 24;
	const LI_CANCELLATION			 = 25;
	const LI_PARTNERCOINS			 = 26;
	const LI_COMPENSATION			 = 27;
	const LI_PENALTY				 = 28;
	const LI_HDFC					 = 29;
	const LI_ICICI				 = 30;
	const LI_BANKCHARGE			 = 31;
	const LI_BTREE				 = 32;
	const LI_JOURNAL				 = 33;
	const LI_SECURITY_DEPOSIT		 = 34;
	const LI_COMMISSION			 = 35;
	const LI_GOZOCOINS			 = 36;
	const LI_ADVANCE_TAX			 = 37;
	const LI_ADJUSTMENT			 = 38;
	const LI_LazyPay				 = 39;
	const LI_BONUS				 = 41;
	const LI_EPayLater			 = 42;
	const LI_PROMOTIONS_MARKETING	 = 43;
	const LI_PAYNIMO				 = 46;
	const LI_WALLET				 = 47;
	const LI_GIFTCARD				 = 48;
	const LI_PARTNERWALLET		 = 49;
	const LI_BAD_DEBT				 = 50;
	const LI_JOINING_BONUS		 = 51;
	const LI_VOUCHER				 = 52;
	const LI_RAZORPAY				 = 53;
	const LI_PAYTM_APP			 = 54;
	const LI_TDS					 = 55;
	const LI_CLOSING				 = 56;
	const LI_OPENING				 = 57;
	const LI_EASEBUZZ				 = 58;
/////////
	const AT_BOOKING				 = 1;
	const AT_OPERATOR				 = 2;
	const AT_PARTNER				 = 3;
	const AT_ONLINEPAYMENT		 = 4;
	const AT_TRIP					 = 5;
	const AT_DRIVER				 = 6;
	const AT_USER					 = 7;
	const AT_GIFTCARD				 = 8;
	const AT_VOUCHER				 = 9;
	const AT_CIB					 = 10;
	const AT_OTHER				 = 11;

	public static function getOnlineLedgers($bankcharge = true)
	{
		$arr = [Accounting::LI_HDFC, Accounting::LI_ICICI, Accounting::LI_BANK, Accounting::LI_PAYTM,
			Accounting::LI_MOBIKWIK, Accounting::LI_FREECHARGE, Accounting::LI_ZZAAKPAY, Accounting::LI_PAYU,
			Accounting::LI_EBS, Accounting::LI_LazyPay, Accounting::LI_EPayLater, Accounting::LI_BANKCHARGE,
			Accounting::LI_BTREE, Accounting::LI_PAYNIMO, Accounting::LI_RAZORPAY,
			Accounting::LI_PAYTM_APP, Accounting::LI_EASEBUZZ];
		if (!$bankcharge)
		{
			$arr = [Accounting::LI_HDFC, Accounting::LI_ICICI, Accounting::LI_BANK, Accounting::LI_PAYTM,
				Accounting::LI_MOBIKWIK, Accounting::LI_FREECHARGE, Accounting::LI_ZZAAKPAY, Accounting::LI_PAYU,
				Accounting::LI_EBS, Accounting::LI_BTREE, Accounting::LI_PAYNIMO, Accounting::LI_RAZORPAY,
				Accounting::LI_PAYTM_APP, Accounting::LI_EASEBUZZ];
		}
		return $arr;
	}

	public static function getOfflineLedgers($partnerCoins = true)
	{
		$arr = [Accounting::LI_TDS, Accounting::LI_CASH, Accounting::LI_PARTNERCOINS, Accounting::LI_JOURNAL, Accounting::LI_SECURITY_DEPOSIT, Accounting::LI_GOZOCOINS, Accounting::LI_WALLET, Accounting::LI_GIFTCARD, Accounting::LI_PARTNERWALLET];
		if (!$partnerCoins)
		{
			$arr = [Accounting::LI_TDS, Accounting::LI_CASH, Accounting::LI_JOURNAL, Accounting::LI_SECURITY_DEPOSIT, Accounting::LI_GOZOCOINS, Accounting::LI_WALLET, Accounting::LI_GIFTCARD];
		}
		return $arr;
	}

	public static function getBookingPaymentSource($bankcharge = true)
	{
		$arr = [
			Accounting::LI_HDFC,
			Accounting::LI_ICICI,
			Accounting::LI_BANK,
			Accounting::LI_PAYTM,
			Accounting::LI_MOBIKWIK,
			Accounting::LI_FREECHARGE,
			Accounting::LI_ZZAAKPAY,
			Accounting::LI_PAYU,
			Accounting::LI_EBS,
			Accounting::LI_LazyPay,
			Accounting::LI_EPayLater,
			Accounting::LI_BTREE,
			Accounting::LI_CASH,
			Accounting::LI_WALLET,
			Accounting::LI_PARTNERWALLET,
			Accounting::LI_PAYNIMO,
			Accounting::LI_RAZORPAY,
			Accounting::LI_PAYTM_APP,
			Accounting::LI_EASEBUZZ
		];

		if ($bankcharge)
		{
			$arr[] = Accounting::LI_BANKCHARGE;
		}
		return $arr;
	}

}

//echo YourClass::SOME_CONSTANT;