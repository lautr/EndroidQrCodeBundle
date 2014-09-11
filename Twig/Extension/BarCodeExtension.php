<?php

/*
 * (c) Johannes Lauter <hannes@lautr.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Bundle\QrCodeBundle\Twig\Extension;

use Endroid\QrCode\QrCode;
use Twig_Extension;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * BarCodeController ( BarCodeController.php )
 *
 * @package EndroidQrCodeBundle:Twig
 * @author  Johannes Lauter <hannes@lautr.com>
 * @since   20.08.14 11:17
 */
class BarCodeExtension extends Twig_Extension implements ContainerAwareInterface
{
    /**
     * {@inheritdoc}
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'barcode_url' => new \Twig_Function_Method($this, 'barcodeUrlFunction'),
        );
    }

    /**
     * Creates the QR code URL corresponding to the given message.
     * @param      $text
     * @param null $extension
     * @param null $size
     * @param null $type
     * @param int  $factor
     *
     * @return mixed
     */
    public function barcodeUrlFunction($text, $extension = null, $size = null, $type = null, $factor = 1)
    {
        $router = $this->container->get('router');

        if ($extension === null) {
            $extension = $this->container->getParameter('endroid_qrcode.extension');
        }

        if ($size === null) {
            $size = $this->container->getParameter('endroid_qrcode.size');
        }

        $url = $router->generate('endroid_barcode', array(
            'text' => $text,
            'extension' => $extension,
            'size' => $size,
            'type' => $type,
            'factor' => $factor,
        ), true);

        return $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'endroid_barcode';
    }
}
