<?php

namespace App\Action;
use Symfony\Component\Validator\Constraints as Assert;

class CalculateTravel
{
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    private int $basicPrice;

    #[Assert\NotBlank]
    private \DateTimeImmutable $dateStart;


    #[Assert\NotBlank]
    private \DateTimeImmutable $userBirthday;

    #[Assert\NotBlank]
    private \DateTimeImmutable $datePay;

    public function __construct(int $basicPrice, \DateTimeImmutable $dateStart,
    \DateTimeImmutable $userBirthday, \DateTimeImmutable $datePay)
    {
        $this->basicPrice = $basicPrice;
        $this->dateStart = $dateStart;
        $this->userBirthday = $userBirthday;
        $this->datePay = $datePay;
    }


    public function getBasicPrice(): int
    {
        return $this->basicPrice;
    }

    public function setBasicPrice(int $basicPrice): void
    {
        $this->basicPrice = $basicPrice;
    }

    public function getDateStart(): \DateTimeImmutable
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeImmutable $dateStart): void
    {
        $this->dateStart = $dateStart;
    }

    public function getUserBirthday(): \DateTimeImmutable
    {
        return $this->userBirthday;
    }

    public function setUserBirthday(\DateTimeImmutable $userBirthday): void
    {
        $this->userBirthday = $userBirthday;
    }

    public function getDatePay(): \DateTimeImmutable
    {
        return $this->datePay;
    }

    public function setDatePay(\DateTimeImmutable $datePay): void
    {
        $this->datePay = $datePay;
    }


}