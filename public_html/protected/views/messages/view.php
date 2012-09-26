<?php
/* @var $this MessagesController */
/* @var $model Messages */

$this->breadcrumbs=array(
	'Messages'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Messages', 'url'=>array('index')),
	array('label'=>'Create Messages', 'url'=>array('create')),
	array('label'=>'Update Messages', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Messages', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Messages', 'url'=>array('admin')),
);
?>

<h1>View Messages #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'header',
		'body',
		'footer',
		'type',
		'user_id',
	),
)); ?>

<?php
echo CHtml::ajaxButton ("send To Students",CController::createUrl('messages/PushMessageToStudents/'.$model->id), array('type'=>'POST', 'update' => '#data'));
echo CHtml::ajaxButton ("send To Teachers",CController::createUrl('messages/PushMessageToTeachers/'.$model->id), array('type'=>'POST', 'update' => '#data'));
echo CHtml::ajaxButton ("send To All",CController::createUrl('messages/PushMessageToAll/'.$model->id), array('type'=>'POST', 'update' => '#data'))

?>

<div id="data">
    <?php 
        $this->renderPartial('_message', array('message'=>$message));
    ?>
</div>

