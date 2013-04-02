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

    /**
     * @param int $id
     * @throws CHttpException
     */
    public function actionView($id)
    {
        /** @var $model Person */
        $model = Person::model()->findByPk($id);
        if (!$model) {
            throw new CHttpException(404);
        }
        if (!$model->processed || strtotime($model->processed) < time() - Yii::app()->params['inteliusUpdatePeriod']) {
            $model->process();
        }
        $this->render('view', array(
            'model' => $model,
        ));
    }
}