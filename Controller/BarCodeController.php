<?php

/*
 * (c) Johannes Lauter <hannes@lautr.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Bundle\QrCodeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Zend\Barcode\Barcode;


/**
 * BarCodeController ( BarCodeController.php )
 *
 * @package EndroidQrCodeBundle:Controller
 * @author  Johannes Lauter <hannes@lautr.com>
 * @since   20.08.14 11:17
 */
class BarCodeController extends Controller
{
    /**
     * generates bar code
     *
     * @param Request $request   current request
     * @param string  $text      file name
     * @param string  $extension file extension
     *
     * @Route("/barcode/{text}.{extension}", name="endroid_barcode", requirements={"text"="[\w\W]+", "extension"="jpg|png|gif"})
     *
     * @throws \Exception
     * @return Response
     */
    public function generateAction(Request $request, $text, $extension)
    {
        $barcodeOptions = [
            'text' => $text,
            'barHeight' => $request->get('size'),
        ];

        $barcodeClass = '\Zend\Barcode\Object\\' .  $request->get('type', 'Code128');

        if (!\class_exists($barcodeClass)) {
            throw new \Exception('Barcode type ' . $request->get('type', 'Code128') . ' is not supported');
        }

        $barcode = new $barcodeClass( $barcodeOptions );

        $rendererOptions = [];

        $renderer = new \Zend\Barcode\Renderer\Image( $rendererOptions );

        $renderer->setBarcode( $barcode )->render();

        $mime_type = 'image/'.$extension;
        if ($extension == 'jpg') {
            $mime_type = 'image/jpeg';
        }

        return new Response($renderer, null, ['Content-Type' => $mime_type]);
    }

}
 