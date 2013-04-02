<?php
class PersonController extends Controller
{
    /**
     * @return array
     */
    public function accessRules()
    {
        return CMap::mergeArray(array(
            array('allow',
                'actions' => array('index', 'view',),
                'users' => array('@'),
            ),
        ), parent::accessRules());
    }

    /**
     * @param PersonSearchForm $personSearchForm
     */
    public function actionIndex(PersonSearchForm $personSearchForm = null)
    {
        if (!$personSearchForm) {
            $personSearchForm = new PersonSearchForm();
            $personSearchForm->unsetAttributes();
        }
        $request = Yii::app()->request;
        if ($request->isPostRequest) {
            $personSearchForm->attributes = $request->getPost('PersonSearchForm');
        }
        $dataProvider = $personSearchForm->search();
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'personSearchForm' => $personSearchForm,
        ));
    }
}