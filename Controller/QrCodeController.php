<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Bundle\QrCodeBundle\Controller;

use Endroid\QrCode\QrCode;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * QR code controller.
 */
class QrCodeController extends Controller
{
    /**
     *
     * @Route("/{text}.{extension}", name="endroid_qrcode", requirements={"text"="[\w\W]+", "extension"="jpg|png|gif"})
     *
     */
    public function generateAction(Request $request, $text, $extension)
    {
        $qrCode = new QrCode();
        if ($size = $request->get('size')) {
            $qrCode->setSize($size);
        }
        if (($padding = $request->get('padding')) !== null) {
            $qrCode->setPadding($padding);
        }
        $qrCode->setText($text);
        $qrCode = $qrCode->get($extension);

        $mime_type = 'image/'.$extension;
        if ($extension == 'jpg') {
            $mime_type = 'image/jpeg';
        }

        return new Response($qrCode, 200, array('Content-Type' => $mime_type));
    }
}
