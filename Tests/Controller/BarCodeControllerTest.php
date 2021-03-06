<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Bundle\QrCodeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BarCodeControllerTest extends WebTestCase
{
    public function testCreateBarCode()
    {
        $client = static::createClient();
        $client->request('GET', $client->getContainer()->get('router')->generate('endroid_barcode', array(
            'text' => 'Life is too short to be generating QR codes',
            'extension' => 'png',
            'size' => 150
        )));

        return $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
