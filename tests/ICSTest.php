<?php


namespace App\Tests;


use App\Controller\ICSController;
use PHPUnit\Framework\TestCase;

class ICSTest extends TestCase
{

    public function testCreateICSFile() {
        $icsController = new ICSController();
        $icsController->createICSFile('18/03/2021', "19/03/2021", 'Baptiste', 'test Description', 'Bolbec');
        $tmp = $_ENV['SERVER'];
        $this->assertFileExists($tmp."meeting.ics");
    }
}