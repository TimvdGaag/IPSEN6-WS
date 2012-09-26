<?php
/* @var $this SystemclientController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'System Clients',
);

$this->menu=array(
	array('label'=>'Create SystemClient', 'url'=>array('create')),
	array('label'=>'Manage SystemClient', 'url'=>array('admin')),
);
?>

<h1>System Clients</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
