<?php

namespace App\Service;

class DateService
{
    public function getTodayDate() : string
    {
        return date('Y-m-d');
    }

    public function isDatePast($date) : bool
    {
        return $date < $this->getTodayDate();
    }

    public function isDateInProgress(\DateTime $date, $duration) : bool
    {
        $now = new \DateTime();
        $now->modify('+1 hour');

        $oldDate = clone $date;
        $date->modify('+' . $duration . ' minutes');

        dd($now, $oldDate, $date);

        return $now > $oldDate && $now < $date;
    }

}