<?php
class SiteController extends Controller
{
    public function accessRules()
    {
        return CMap::mergeArray(array(
            array('allow',
                'actions' => array('index', 'error'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('login'),
                'users' => array('?'),
            ),
            array('allow',
                'actions' => array('logout'),
                'users' => array('@'),
            ),
        ), parent::accessRules());
    }


    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionLogin(LoginForm $loginForm = null)
    {
        if (!$loginForm) {
            $loginForm = new LoginForm();
        }
        /** @var $form TbForm */
        $form = TbForm::createForm('application.views.site.forms.login', $loginForm);
        if ($form->submitted() && $form->validate() && $loginForm->login()) {
            $this->redirect(Yii::app()->user->returnUrl);
        }
        $this->render('login', array(
            'form' => $form,
        ));
    }

    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
}