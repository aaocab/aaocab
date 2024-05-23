<?php

class GozenController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';
	public $email_receipient;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
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
	public function accessRules()
	{
		return array(
			['allow', 'actions' => ['list', 'index', 'download'], 'users' => ['@']],
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}
	
	public function restEvents()
	{
		$this->onRest('req.cors.access.control.allow.methods', function ()
		{
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function ($validation)
		{
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array();

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

	public function actionIndex()
	{
		$this->pageTitle = 'Internal Gozen ONLY section ';
		//file_get_contents(Yii::getPathOfAlias('webroot.assets.gozen') . '/vendorpdf.css');
		$gozonLinks = ['GozenFeedbackLink'	 => 'https://goo.gl/forms/gMn2eVCq8lPotKnG2',
			'GozoEmployeeLink'	 => 'https://goo.gl/forms/SyM0QKc2s0iUZ3Fi1'];
		$this->render('index', array('gozonLinks' => $gozonLinks));
	}

	public function actionDownload()
	{
//		echo Yii::getPathOfAlias('webroot');
//		echo "<br>";
//		echo Yii::getPathOfAlias('application.gozen');
		$doc = Yii::app()->request->getParam('doc');
		switch ($doc)
		{
			case 1:
				$docPdf	 = "/FAQs_ESOPs.pdf";
				break;
			case 2:
				$docPdf	 = "/Gozo_-_Employees_Stock_Option_plan_2019.pdf";
				break;
			case 3:
				$docPdf	 = "/ESOP_Exercise_Form.pdf";
				break;
		}
		if (isset($docPdf) && $docPdf != '')
		{
			//$path = Yii::getPathOfAlias('webroot.assets.gozen') . $docPdf;
			$path = Yii::getPathOfAlias('application.gozen') . $docPdf;
			$this->downloadFile($path);
		}
	}

	public function downloadFile($fullpath)
	{
		if (!empty($fullpath))
		{
			header("Content-type:application/pdf"); //for pdf file
			//header('Content-Type:text/plain; charset=ISO-8859-15');
			//if you want to read text file using text/plain header 
			header('Content-Disposition: attachment; filename="' . basename($fullpath) . '"');
			header('Content-Length: ' . filesize($fullpath));
			readfile($fullpath);
			Yii::app()->end();
		}
	}

	public function actionFAQsESOPs()
	{
		$html2pdf					 = Yii::app()->ePdf->mPdf();
		$css						 = file_get_contents(Yii::getPathOfAlias('webroot.assets.css') . '/vendorpdf.css');
		$html2pdf->writeHTML($css, 1);
		$html2pdf->setAutoTopMargin	 = 'stretch';
		$html2pdf->setHTMLHeader('<table border="0" cellpadding="0" cellspacing="0" width="702" class="no-border header">
                    <tbody>
						<tr>
							<td style="text-align: left"><img src="http://www.gozocabs.com/images/print-logo.png" style="height: 60px"/><br><span style="font-weight: normal; font-size: 12pt">Gozo Technologies Private Limited</span></td>
							<td style="text-align: right"><table class="borderless"><tr><td style="text-align: left; font-size: 10pt">
									<strong>Corporate Office:</strong><br>
									F - 210, UGF,<br>
                                                                        Sushant Shopping Arcade, Sushant Lok Phase-I,<br>
                                                                        Gurugram, Haryana -122001.
										</td></tr></table></td>
						</tr></tbody></table><hr>');
		/* $html2pdf->setHTMLFooter('<table id="footer" style="width: 100%"><tr><td style="text-align: left; font-size: 10pt"><strong>Corporate Office:</strong><br>
		  H-215, Block H, Upper Ground Floor,<br>
		  Sushant Shopping Arcade, Sushant Lok Phase -1,<br>
		  Gurgaon , Haryana, PIN - 122001</td><td></td></tr><tr><td style="text-align: center"><hr><b>www.gozocabs.com</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>info@gozocabs.com</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>9051 877 000</b></td></tr></table>'); */
		$html2pdf->writeHTML($this->renderPartial('generate_FAQs_ESOPs', array(
					'model'	 => $model,
					'data'	 => $data,
						), true));

		$html2pdf->Output();
	}

	public function actionList($qry = [])
	{
		$this->pageTitle = "Unsubscribe List";
		$pageSize		 = Yii::app()->params['listPerPage'];
		$model			 = new Unsubscribe();
		$dataProvider	 = $model->getAll();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('unsubscribe_list', array('model' => $model, 'dataProvider' => $dataProvider, 'qry' => $qry));
	}
}
