<?php

class SyncCommand extends BaseCommand
{

    public function actionUploadAATAll($start = 0, $threads = 0)
    {
        $check = Filter::checkProcess("uploadAATAll");
        if (!$check)
        {
            return;
        }
        AgentApiTracking::uploadAllToS3(100000, 0, $start, $threads);
    }

    public function actionReuploadAAT($type = '14')
    {
        $check = Filter::checkProcess("reuploadAAT");
        if (!$check)
        {
            return;
        }
        AgentApiTracking::reuploadAllToS3(5000000, $type);
    }

    public function actionUploadAATOthers()
    {
        $check = Filter::checkProcess("uploadAATOthers");
        if (!$check)
        {
            return;
        }
        AgentApiTracking::uploadAllToS3(1000000);
    }

    public function actionUploadAATArchive()
    {
        $check = Filter::checkProcess("uploadAATArchive");
        if (!$check)
        {
            return;
        }
        AgentApiTracking::uploadArchiveToS3(5000000, 0);
    }

    public function actionUploadAATArchive1()
    {
        $check = Filter::checkProcess("uploadAATArchive1");
        if (!$check)
        {
            return;
        }
        AgentApiTracking::uploadArchiveToS3(5000000, 0, 15000);
    }

    public function actionUploadAATQuote()
    {
        $check = Filter::checkProcess("uploadAATQuote");
        if (!$check)
        {
            return;
        }
        AgentApiTracking::uploadAllToS3(3000000, 2);
    }

    public function actionUploadBkgDocs()
    {
        $check = Filter::checkProcess("uploadBkgDocs");
        if (!$check)
        {
            return;
        }
        BookingPayDocs::uploadAllToS3(100000);
    }

    public function actionUploadDocs()
    {
        $check = Filter::checkProcess("uploadDocs");
        if (!$check)
        {
            return;
        }
        Document::uploadAllToS3(100000);
    }

    public function actionUploadMails()
    {
        $check = Filter::checkProcess("uploadMails");
        if (!$check)
        {
            return;
        }
        EmailLog::uploadAllToS3(1000000);
    }

    /////////////////////////////////////////////////////////////////////////////////
    public function actionUploadPATAll()
    {
        $check = Filter::checkProcess("uploadPATAll");
        if (!$check)
        {
            return;
        }
        PartnerApiTracking::uploadAllToS3(5000000, 0);
    }

    public function actionUploadPATOthers()
    {
        $check = Filter::checkProcess("uploadPATOthers");
        if (!$check)
        {
            return;
        }
        PartnerApiTracking::uploadAllToS3(1000000);
    }

    public function actionUploadPATQuote()
    {
        $check = Filter::checkProcess("uploadPATQuote");
        if (!$check)
        {
            return;
        }
        PartnerApiTracking::uploadAllToS3(3000000, 4);
    }
	////////////
	public function actionUploadOPTAll()
    {
        $check = Filter::checkProcess("uploadOPTAll");
        if (!$check)
        {
            return;
        }
        OperatorApiTracking::uploadAllToS3(10000, 0);
    }

//    public function actionUploadOPTOthers()
//    {
//        $check = Filter::checkProcess("uploadOPTOthers");
//        if (!$check)
//        {
//            return;
//        }
//        OperatorApiTracking::uploadAllToS3(1000000);
//    }
//
//    public function actionUploadOPTQuote()
//    {
//        $check = Filter::checkProcess("uploadOPTQuote");
//        if (!$check)
//        {
//            return;
//        }
//        OperatorApiTracking::uploadAllToS3(3000000, 4);
//    }

   /////////
    public function actionUploadVendorLouDocs()
    {
        $check = Filter::checkProcess("uploadVendorLouDocs");
        if (!$check)
        {
            return;
        }
        VendorVehicle::uploadAllToS3(1000000);
    }

    public function actionUploadVhcDocs()
    {
        $check = Filter::checkProcess("uploadVhcDocs");
        if (!$check)
        {
            return;
        }
        VehicleDocs::uploadAllToS3(100000);
    }

    public function actionUploadAgentAgreement()
    {
        $check = Filter::checkProcess("UploadAgentAgreement");
        if (!$check)
        {
            return;
        }
        AgentAgreement::uploadAllToS3(100000);
    }

    public function actionUploadVendorAgreement()
    {
        $check = Filter::checkProcess("UploadVendorAgreement");
        if (!$check)
        {
            return;
        }
        VendorAgreement::uploadAllToS3(100000);
    }

    public function actionUploadVendorAgmtDocs()
    {
        $check = Filter::checkProcess("UploadVendorAgmtDocs");
        if (!$check)
        {
            return;
        }
        VendorAgmtDocs::uploadAllToS3(100000);
    }

    public function actionUploadAudioCalls()
    {
        $check = Filter::checkProcess("UploadAudioCalls");
        if (!$check)
        {
            return;
        }
        CallStatus::uploadAllToS3(100000);
    }

    public function actionUploadQrDocs()
    {
        $check = Filter::checkProcess("uploadQrDocs");
        if (!$check)
        {
            return;
        }
        QrCode::uploadAllToS3(100000);
    }

    public function actionUploadCallBackDocs()
    {
        $check = Filter::checkProcess("UploadCallBackDocs");
        if (!$check)
        {
            return;
        }
        CallBackDocuments::uploadAllToS3(100000);
    }

	public function actionUploadUserQRCode()
    {
        $check = Filter::checkProcess("UploadUserQRCode");
        if (!$check)
        {
            return;
        }
        Users::uploadAllToS3(10);
    }
	
	public function actionUploadGPXFile()
    {
        $check = Filter::checkProcess("uploadGPXFile");
        if (!$check)
        {
            return;
        }
        BookingTrack::uploadAllToS3(1000);
    }
}
