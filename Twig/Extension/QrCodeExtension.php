<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Bundle\QrCodeBundle\Twig\Extension;

use Endroid\QrCode\QrCode;
use Twig_Extension;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class QrCodeExtension extends Twig_Extension implements ContainerAwareInterface
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
            'qrcode_url' => new \Twig_Function_Method($this, 'qrcodeUrlFunction'),
            'qrcode_data_uri' => new \Twig_Function_Method($this, 'qrcodeDataUriFunction')
        );
    }

    /**
     * Creates the QR code URL corresponding to the given message.
     *
     * @param $text
     * @param  string $extension
     * @param  int    $size
     * @param  int    $padding
     * @return mixed
     */
    public function qrcodeUrlFunction($text, $extension = null, $size = null, $padding = null)
    {
        $router = $this->container->get('router');

        if ($extension === null) {
            $extension = $this->container->getParameter('endroid_qrcode.extension');
        }

        if ($size === null) {
            $size = $this->container->getParameter('endroid_qrcode.size');
        }

        if ($padding === null) {
            $padding = $this->container->getParameter('endroid_qrcode.padding');
        }

        $url = $router->generate('endroid_qrcode', array(
            'text' => $text,
            'extension' => $extension,
            'size' => $size,
            'padding' => $padding,
        ), true);

        return $url;
    }

    /**
     * Creates the QR code data corresponding to the given message.
     *
     * @param $text
     * @param  string $extension
     * @param  int    $size
     * @param  int    $padding
     * @param  int    $errorCorrectionLevel
     * @return mixed
     */
    public function qrcodeDataUriFunction($text, $extension = null, $size = null, $padding = null, $errorCorrectionLevel = null)
    {
        if ($extension === null) {
            $extension = $this->container->getParameter('endroid_qrcode.extension');
        }

        if ($size === null) {
            $size = $this->container->getParameter('endroid_qrcode.size');
        }

        if ($padding === null) {
            $padding = $this->container->getParameter('endroid_qrcode.padding');
        }

        if ($errorCorrectionLevel === null) {
            $errorCorrectionLevel = $this->container->getParameter('endroid_qrcode.error_correction_level');
        }
        
        // Resolve error correction level to the appropriate constant
        if (!is_numeric ($errorCorrectionLevel)) {
            $levelConstant = 'Endroid\QrCode\QrCode::LEVEL_'.strtoupper($errorCorrectionLevel);
            $errorCorrectionLevel = constant ($levelConstant);
        }

        $dataUri = new QrCode();
        $dataUri = $dataUri
            ->setText($text)
            ->setSize($size)
            ->setPadding($padding)
            ->setExtension($extension)
            ->setErrorCorrection($errorCorrectionLevel)
            ->getDataUri()
        ;

        return $dataUri;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'endroid_qrcode';
    }
}
