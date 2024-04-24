<?php

namespace App\Service;

use App\Action\CalculateTravel;
use Symfony\Component\HttpFoundation\JsonResponse;

class TravelService
{
    public static $discountChildren = [
        12 => 0.1,
        6 => 0.3,
        3 => 0.8,
    ];

    public static $discountChildrenSixYearsLimit = 4500;

    public static $discountEarlyLimit = 1500;

    public static $discountEarlyAprilSeptember = [
        11 => 0.07,
        12 => 0.05,
        1 => 0.03, //next year
    ];

    public static $discountEarlyOctober1January14 = [
        3 => 0.07,
        4 => 0.05,
        5 => 0.03, //next year
    ];

    public static $discountEarlyJanuary15 = [
        8 => 0.07,
        9 => 0.05,
        10 => 0.03, //next year
    ];
    public static $discountChildrenValue = null;
    public static $discountChildrenPercent = null;
    public static $discountEarlyValue = null;
    public static $discountEarlyPercent = null;
    public function getDiscountedPrice(CalculateTravel $travel): CalculateTravel
    {
        return $this->setDiscount($travel);
    }

    private function setDiscount(CalculateTravel $travel): CalculateTravel
    {
        $price = $travel->getBasicPrice();
        $isChild = true;
        $nowYear = (int) $travel->getDatePay()->format('Y');
        $nextYear = $nowYear + 1;

        $childYear = (int) $travel->getUserBirthday()->format('Y');
        $age = $nowYear - $childYear;
        if ($age >17) {
            $isChild = false;
        } else {
            switch ($age) {
                case $age >=12:
                    self::$discountChildrenValue = $price * self::$discountChildren[12];
                    self::$discountChildrenPercent = self::$discountChildren[12];
                    break;
                case $age >=6:
                    if ($price * self::$discountChildren[6] > 4500) {
                        self::$discountChildrenValue = 4500;
                    } else {
                        self::$discountChildrenValue = $price * self::$discountChildren[6];

                    }
                    self::$discountChildrenPercent = self::$discountChildren[6];
                    break;
                case $age >=3 :
                    self::$discountChildrenValue = $price * self::$discountChildren[3];
                    self::$discountChildrenPercent = self::$discountChildren[3];
                 break;
            }
        }


        $timeEarlyApril = new \DateTimeImmutable("$nextYear-4-1");
        $timeEarlySeptember = new \DateTimeImmutable("$nextYear-9-30");


        $timeEarlyOctober = new \DateTimeImmutable("$nowYear-10-1");
        $timeEarlyJanuaryFirst = new \DateTimeImmutable("$nextYear-01-14");


        $timeEarlyJanuarySecond = new \DateTimeImmutable("$nextYear-01-15");
        $month = (int) $travel->getDatePay()->format('m');

        if ($isChild) {
            $price = $price - self::$discountChildrenValue;
        }
            switch ($travel->getDateStart()) {

                case $travel->getDateStart() >= $timeEarlyApril && $travel->getDateStart() <= $timeEarlySeptember :
                    if (isset(self::$discountEarlyAprilSeptember[$month])) {
                        self::$discountEarlyValue = $price * self::$discountEarlyAprilSeptember[$month];
                        self::$discountEarlyPercent = self::$discountEarlyAprilSeptember[$month];
                    }
                    break;
                case $travel->getDateStart() >= $timeEarlyOctober && $travel->getDateStart() <= $timeEarlyJanuaryFirst :
                    if (isset(self::$discountEarlyOctober1January14[$month])) {
                        self::$discountEarlyValue = $price * self::$discountEarlyOctober1January14[$month];
                        self::$discountEarlyPercent = self::$discountEarlyOctober1January14[$month];
                    }
                    break;
                case $travel->getDateStart() >= $timeEarlyJanuarySecond:
                    if (isset(self::$discountEarlyJanuary15[$month])) {
                        self::$discountEarlyValue = $price * self::$discountEarlyJanuary15[$month];
                        self::$discountEarlyPercent = self::$discountEarlyJanuary15[$month];
                    }
            }

        $travel->setBasicPrice($price - self::$discountEarlyValue);
        return $travel;
    }
}