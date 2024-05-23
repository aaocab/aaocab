<?php

namespace Stub\telegram;

class AuthenticationResponse
{
    public $telegramId;
    public $apiKey;
    
    /** @var \Stub\common\Person $contact */
	public $contact;
    public $agentId;
    
	public function setData($request)
	{
        $this->contact = new \Stub\common\Person();
        if($request->agentId)
        {
            $model   = \Agents::model()->findByPk($request->agentId);
            $this->apiKey     = $model->agt_api_key;
            $this->agentId   = $model->agt_id;
            $this->contact->setPersonData($model->agtContact);
        }
        else
        {
            $this->vendorId   = $request->vendorId;
            $model = \Vendors::model()->findByPk($request->vendorId);
            $this->contact->setPersonData($model->vndContact);
        }

        $this->telegramId = $request->pid;
        

        
        
        
    }

}
