<?php

namespace Stub\common;

class StatusDetails
{

    public $isApprove;
    public $isMessage;
    public $isDocumentUploaded;
	public $isAgreementUploaded;
   

    public function setData($data)
    {
        $this->isApprove = $data['is_approve'];
		$this->isMessage = $data['is_message'];
		$this->isDocumentUploaded = $data['documentUpload'];
		$this->isAgreementUploaded = $data['agreementUpload'];
    }

}
