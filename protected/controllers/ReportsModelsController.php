<?php
class ReportsModelsController extends Controller{
	public function filters(){
		return array("LoginRequiredFilter");
	}
	
	public function actionAjaxListBlock(){
		$this->renderPartial('ajax_block_model_list');
	}
	
  
  public function actionIndex(){
    $this->render('index');
  }
}