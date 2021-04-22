<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;use Symfony\Component\Filesystem\Filesystem;

class ICSController extends AbstractController
{
    /**
     * @param $meetingStart
     * @param $meetingEnd
     * @param $userName
     * @param $description
     * @param string $location
     */
    public function createICSFile($meetingStart, $meetingEnd, $userName, $description, $location="24 PLACE ST MARC 76000 ROUEN") {
        $tmp = $_ENV['SERVER'];
        $fs = new Filesystem();
        $fileName = "meeting.ics";

        $icsContent = "BEGIN:VCALENDAR
VERSION:2.0
CALSCALE:GREGORIAN
METHOD:REQUEST
BEGIN:VEVENT
DTSTART:".date('Ymd\THis', strtotime($meetingStart))."
DTEND:".date('Ymd\THis', strtotime($meetingEnd))."
DTSTAMP:".date('Ymd\THis', strtotime($meetingStart))."
ORGANIZER;CN=XYZ:mailto:crmwebpartener@gmail.com
UID:".rand(5, 1500)."
ATTENDEE;PARTSTAT=NEEDS-ACTION;RSVP= TRUE;CN=Sample:emailaddress@testemail.com
DESCRIPTION:". $description . "
LOCATION: " . $location . "
SEQUENCE:0
STATUS:CONFIRMED
SUMMARY: Rendez-vous organisé par ".$userName."
TRANSP:OPAQUE
END:VEVENT
END:VCALENDAR";
        $fs->dumpFile($tmp.$fileName, $icsContent);
    }
}
