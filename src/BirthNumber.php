<?php

namespace DevFusion;

use DateTime;
use DateTimeZone;
use Exception;

class BirthNumber
{
    public const LENGTH_NINE = 9;
    public const LENGTH_TEN = 10;

    public const GENDER_MALE = 0;
    public const GENDER_FEMALE = 1;

    protected const ADULT_AGE = 18;
    protected const FEMALE_OFFSET_NUM = 50;
    protected const MODULO = 11;
    protected const TIMEZONE = 'Europe/Bratislava';

    protected int $number;

    public function __construct(...$values)
    {
        $this->createFromNumber(implode('', $values));

        return $this;
    }

    public function createFromNumber($number): BirthNumber
    {
        $this->number = (int)filter_var($number, FILTER_SANITIZE_NUMBER_INT);

        return $this;
    }

    public function createFromDate(int $year, int $month, int $day, int $suffix, $gender = self::GENDER_MALE): BirthNumber
    {
        $part['year'] = DateTime::createFromFormat('Y', $year)->format('y');
        $part['month'] = str_pad($month + ($gender === self::GENDER_FEMALE ? self::FEMALE_OFFSET_NUM : 0), 2, "0", STR_PAD_LEFT);
        $part['day'] = str_pad($day, 2, "0", STR_PAD_LEFT);
        $part['suffix'] = $suffix;

        $this->number = (int)filter_var(implode('', $part), FILTER_SANITIZE_NUMBER_INT);

        return $this;
    }

    public function isValid(): bool
    {
        if (empty($this->number) || is_null($this->number)) {
            return false;
        }

        if (!in_array($this->getLength(), [self::LENGTH_TEN, self::LENGTH_NINE])) {
            return false;
        }

        if ($this->getRemainder() !== 0) {
            return false;
        }

        return true;
    }

    public function getRawDate(): int
    {
        return substr($this->number, 0, 6);
    }

    public function getRawYear(): int
    {
        return substr($this->number, 0, 2);
    }

    public function getRawMonth(): int
    {
        return substr($this->number, 2, 2);
    }

    public function getRawDay(): int
    {
        return substr($this->number, 4, 2);
    }

    public function getYear(): int
    {
        $dt = DateTime::createFromFormat('y', $this->getRawYear());

        return $dt->format('Y');
    }

    public function getMonth(): int
    {
        $month = $this->getRawMonth();

        if ($month > self::FEMALE_OFFSET_NUM) {
            $month -= self::FEMALE_OFFSET_NUM;
        }

        $dt = DateTime::createFromFormat('m', $month);

        return $dt->format('m');
    }

    public function getDay(): int
    {
        $dt = DateTime::createFromFormat('d', $this->getRawDay());

        return $dt->format('d');
    }

    public function getBirthDate(): DateTime
    {
        return (new DateTime())
            ->setDate($this->getYear(), $this->getMonth(), $this->getDay())
            ->setTime(0, 0, 0)
            ->setTimezone($this->getTimezone());
    }

    /**
     * @throws Exception
     */
    public function getAge(): float
    {
        $birthDate = $this->getBirthDate();
        $age = $birthDate->diff(new DateTime('now', $this->getTimezone()));
        return $age->days / 365;
    }

    /**
     * @throws Exception
     */
    public function isAdult(): bool
    {
        $age = $this->getAge();

        return $age >= self::ADULT_AGE;
    }

    /**
     * @throws Exception
     */
    public function isAdolescent(): bool
    {
        return !$this->isAdult();
    }

    public function getSuffix(): int
    {
        $size = $this->getLength() == self::LENGTH_NINE ? 3 : 4;

        return substr($this->number, strlen($this->number) - $size, $size);
    }

    public function getLength(): int
    {
        return strlen($this->number);
    }

    public function getGender(): int
    {
        $month = substr($this->getRawDate(), 2, 2);

        if ($month > 12 && $this->getMonth() === $month - self::FEMALE_OFFSET_NUM) {
            return self::GENDER_FEMALE;
        }

        return self::GENDER_MALE;
    }

    public function isMale(): bool
    {
        $gender = $this->getGender();
        return $gender === self::GENDER_MALE;
    }

    public function isFemale(): bool
    {
        $gender = $this->getGender();
        return $gender === self::GENDER_FEMALE;
    }

    protected function getRemainder(): int
    {
        return $this->number % self::MODULO;
    }

    protected function getTimezone(): DateTimeZone
    {
        return new DateTimeZone(self::TIMEZONE);
    }


}