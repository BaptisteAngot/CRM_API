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
<<<<<<< HEAD
    public function createICSFile($meetingStart, $meetingEnd, $userName, $description, $location="24 PLACE ST MARC 76000 ROUEN") {
=======
    public function createICSFile($meetingStart, $meetingEnd, $userName, $description, $location="") {
>>>>>>> 52e385ea6af96fb753733f5950af34cdbc56f722
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
<<<<<<< HEAD
ORGANIZER;CN=XYZ:mailto:crmwebpartener@gmail.com
=======
ORGANIZER;CN=XYZ:mailto:do-not-reply@CRMStMarc.com
>>>>>>> 52e385ea6af96fb753733f5950af34cdbc56f722
UID:".rand(5, 1500)."
ATTENDEE;PARTSTAT=NEEDS-ACTION;RSVP= TRUE;CN=Sample:emailaddress@testemail.com
DESCRIPTION:". $description . "
LOCATION: " . $location . "
SEQUENCE:0
STATUS:CONFIRMED
<<<<<<< HEAD
SUMMARY: Rendez-vous organisé par ".$userName."
=======
SUMMARY:Meeting has been scheduled by ".$userName."
>>>>>>> 52e385ea6af96fb753733f5950af34cdbc56f722
TRANSP:OPAQUE
END:VEVENT
END:VCALENDAR";
        $fs->dumpFile($tmp.$fileName, $icsContent);
    }
}
