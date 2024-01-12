<?php

/** hello */

use Chevere\Parameter\Interfaces\ArrayParameterInterface;
use function Chevere\Parameter\arrayp;
use function Chevere\Parameter\boolInt;
use function Chevere\Parameter\date;
use function Chevere\Parameter\datetime;
use function Chevere\Parameter\enum;
use function Chevere\Parameter\float;
use function Chevere\Parameter\int;
use function Chevere\Parameter\null;
use function Chevere\Parameter\string;
use function Chevere\Parameter\union;

// @codeCoverageIgnoreStart

function companyEmployeeTable(): ArrayParameterInterface
{
    return arrayp(
        id: int(min: 0),
        datetime_utc: datetime(),
        name: string(regex: "/^.{0,255}$/"),
        initials: union(
            null(),
            string(regex: "/^.{0,10}$/")
        ),
        phone_number: union(
            null(),
            string(regex: "/^.{0,100}$/")
        ),
        photo_url: union(
            null(),
            string()
        ),
        is_active: boolInt(),
        hourly_rate: union(
            null(),
            int(min: 0)
        ),
        is_clocked_in: boolInt(),
        jobs_total: int(min: 0),
        seconds_total: int(min: 0),
        last_seen_datetime_utc: union(
            null(),
            datetime()
        ),
        last_seen_coordinates: union(
            null(),
            string()
        ),
        comments_made_total: int(),
        login_method: union(
            null(),
            enum('email', 'badge')
        ),
        email: union(
            null(),
            string(regex: "/^.{0,255}$/")
        ),
        hours_total: float(),
        deleted_at: union(
            null(),
            int()
        )
    );
}

// @codeCoverageIgnoreEnd
