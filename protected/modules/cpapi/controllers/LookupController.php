<?php

class LookupController extends Controller
{

    public function filters()
    {
        return array(
            array(
                'application.filters.HttpsFilter + create',
                'bypass' => false),
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
            array(
                'RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS'
            ),
        );
    }

    public function actions()
    {
        return array(
            'REST.' => 'RestfullYii.actions.ERestActionProvider',
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array(),
                'users'   => array('@'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array(
                    'REST.GET', 'REST.PUT', 'REST.REQUEST', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS', 'generateInvoice'),
                'users'   => array('*'),
            ),
            ['allow',
                'actions' => ['new', 'list'],
                'users'   => ['@']
            ],
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function restEvents()
    {
        $this->onRest('req.cors.access.control.allow.methods', function() {
            return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
        });

        $this->onRest('post.filter.req.auth.user', function($validation) {
            $pos = false;
            $arr = $this->getURIAndHTTPVerb();
            $ri  = array('');
            foreach ($ri as $value)
            {
                if (strpos($arr[0], $value))
                {
                    $pos = true;
                }
            }
            return $validation ? $validation : ($pos != false);
        });
    }

}
