<?php
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeBuilder
{

	public static function getQrCode($qrLink)
	{
		$result = Builder::create()
                ->writer(new PngWriter())
                ->writerOptions([])
                ->data($qrLink)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->size(370)
                ->margin(5)
                ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
                ->logoPath(PUBLIC_PATH . '/images/qr-car-etios-logo.png')
                ->logoResizeToHeight(85)
                //->logoPunchoutBackground(false)
                //->logoResizeToWidth(135)
                //->labelText('CX220812345')
                //->labelFont(new OpenSans(11))
                //->labelMargin(new Margin(2,0,10,0))
                //->labelAlignment(new LabelAlignmentCenter())
                ->build();
        return $result;
	}
}
