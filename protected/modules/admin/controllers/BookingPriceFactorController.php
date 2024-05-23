<?php

class BookingPriceFactorController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     *
     */
  
    public $layout = 'admin1';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            ['allow', 'actions' => ['list', 'showlog', 'dynamicList', 'dynamicPriceEdit'], 'roles' => ['surgeList']],
            ['allow', 'actions' => ['surgeform', 'delete1'], 'roles' => ['surgeUpdate']],
            ['deny', 'users' => ['*']],
        );
    }

    //fetch list
    public function actionList() {
        $this->pageTitle = "Price surge quoted situation report";
        $model           = new BookingPriceFactor();
        $bkgModel        = new Booking();

        $bpf_pickup_date1 = $_REQUEST['BookingPriceFactor']['bpf_pickup_date1'];
        $bpf_pickup_date2 = $_REQUEST['BookingPriceFactor']['bpf_pickup_date2'];
        if ($bpf_pickup_date1 != '' && $bpf_pickup_date2 != '') {
            $date1 = DateTimeFormat::DatePickerToDate($bpf_pickup_date1);
            $date2 = DateTimeFormat::DatePickerToDate($bpf_pickup_date2);
        }
        else {
            $date1 = date('Y-m-d');
            $date2 = date('Y-m-d', strtotime("+1 days"));
        }
        if (isset($_REQUEST['Booking'])) {
            $arr             = Yii::app()->request->getParam('Booking');
            $sourcezone      = implode(',', $arr['sourcezone']);
            $destinationzone = implode(',', $arr['destinationzone']);
        }
        $model->bpf_pickup_date1   = $date1;
        $model->bpf_pickup_date2   = $date2;
        $bkgModel->sourcezone      = $arr['sourcezone'];
        $bkgModel->destinationzone = $arr['destinationzone'];
        $dataProvider              = $model->getList($date1, $date2, $sourcezone, $destinationzone);
        $dataProvider->setSort(['params' => array_filter($_REQUEST)]);
        $dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
        $outputJs                  = Yii::app()->request->isAjaxRequest;
        $method                    = "render" . ( $outputJs ? "Partial" : "");
        $this->$method('list', array('model' => $model, 'dataProvider' => $dataProvider, 'bkgmodel' => $bkgModel,), false, $outputJs);
    }

}
