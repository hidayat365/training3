<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\TransactionTypes;

/* @var $this yii\web\View */
/* @var $model app\models\Transactions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transactions-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'trans_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'trans_date')->textInput() ?>

    <?= $form->field($model, 'type_id')->dropDownList(
        ArrayHelper::map(TransactionTypes::find()->all(), 'id', 'name'),           // Flat array ('id'=>'label')
        ['prompt'=>'* Pilih Jenis Transaksis *']    // options
    ) ?>

    <?= $form->field($model, 'remarks')->textInput(['maxlength' => true]) ?>

        <div class="form-group">

            <div class="col-xs-12 col-sm-12 col-md-12">
                <h3><i class="fa fa-tasks"></i> <strong>Transactions Details</strong>
                </h3>
            </div>

            <div id="details">
                <div data-bind="template: {name:'listTemplate', foreach:details}"></div>
                <input type="button" class="btn btn-primary pull-right" 
                	data-bind='click: addDetail' value="Add Detail"/>
            </div>
            <div id="footer">
            </div>

            <script id="listTemplate" type="text/html">
            <div class="row">
                <div class="form-group col-xs-12 col-sm-3">
                    <select class="form-control" 
                            data-bind="options: items,
                                       optionsText: 'display',
                                       optionsValue: 'id',
                                       value: selectedItem,
                                       attr: { name: 'Details-item_id['+$index()+']' } "></select>
                    <span class="help-block" data-bind="visible: $parent.details().length==$index()+1">select item</span>
                </div>
                <div class="form-group col-xs-6 col-sm-2">
                    <input type="text" class="form-control number" 
                        data-bind="value: quantity, attr: { name: 'Details-quantity['+$index()+']' } " />
                    <span class="help-block" data-bind="visible: $parent.details().length==$index()+1">quantity</span>
                </div>
                <div class="form-group col-xs-10 col-sm-4">
                    <input type="text" class="form-control" 
                        data-bind="value: remarks, attr: { name: 'Details-remarks['+$index()+']' } " />
                    <span class="help-block" data-bind="visible: $parent.details().length==$index()+1">remarks</span>
                </div>
                <div class="form-group col-xs-2 col-sm-1 clearfix">
                    <input type="button" class="btn btn-warning pull-right" data-bind='click: remove' value="Delete"/>
                </div>
            </div>
            </script>
        </div>

    <div class="clearfix">
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
