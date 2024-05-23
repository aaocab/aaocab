<?php

namespace Stub\vendor;

/**
 * Description of Booking Count Response
 *
 * 
 */
class BookingCountResponse
{

    public $totalBookings;
    public $drvAssignmentPending;
    public $overDue;

    /**
     * @param $totalBookings 
     * @param $drvAssignmentPend 
     * @param $overDueCount 
     */
    public function setData($totalBookings, $drvAssignmentPend, $overDueCount)
    {
        $this->totalBookings        = (int) $overDueCount;
        $this->drvAssignmentPending = (int) $drvAssignmentPend;
        $this->overDue              = (int) $overDueCount;
    }

}
