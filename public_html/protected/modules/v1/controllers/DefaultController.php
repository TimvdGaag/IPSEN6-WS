<?php

class DefaultController extends Controller
{
    public function filters()
	{
		return array(
			'restfilter',
            'rights', // perform access control for CRUD operations
		);
	}
    
	public function actionIndex()
	{
		$this->render('index');
	}
    
   
}