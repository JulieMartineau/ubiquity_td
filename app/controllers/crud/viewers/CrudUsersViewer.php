<?php
namespace controllers\crud\viewers;

use Ajax\semantic\html\elements\HtmlLabel;
use Ubiquity\controllers\crud\viewers\ModelViewer;
 /**
  * Class CrudUsersViewer
  */
class CrudUsersViewer extends ModelViewer{
    public function getCaptions($captions, $className){
        return parent::getCaptions($captions, $className);//todo
    }

    public function getModelDataTable($instances, $model, $totalCount, $page = 1){
        $dt = parent::getModelDataTable($instances,$model,$totalCount,$page);
        $dt->fieldAsLabel('name');
        $dt->setValueFunction('groups',function($v, $instance){
            return HtmlLabel::tag('',count($v));
        });
        return $dt;
    }

    public function recordsPerPage($model, $totalCount = 0){
        return parent::recordsPerPage($model, $totalCount);//todo
    }

    protected function getDataTableRowButtons(){
        return ['display', 'edit', 'delete'];
    }

    public function getModelDataElement($instance, $model, $modal){
        return parent::getModelDataElement($instance, $model, $modal); //TODO
    }

    public function getElementCaptions($captions, $className, $instance){
        return parent::getElementCaptions($captions, $className, $instance); //TODO
    }

    public function isModal($objects, $model){
    }

    public function getForm($identifier, $instance, $updateUrl = 'updateModel'){
    }

    public function getFormCaptions($captions, $className, $instance){
    }


}
