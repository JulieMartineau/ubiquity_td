<?php
namespace controllers\crud\datas;

use Ubiquity\controllers\crud\CRUDDatas;
 /**
  * Class CrudUsersDatas
  */
class CrudUsersDatas extends CRUDDatas{
    public function getFieldNames($model){
        return ['Prénom','Nom','Email','Suspendu?','Groupes'];
    }

    public function getFormFieldNames($model, $instance)
    {
        return parent::getFormFieldNames($model, $instance);
    }


}
