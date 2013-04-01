<?php
class UserIdentity extends CUserIdentity
{
    /**
     * @var int
     */
    private $_id;

    /**
     * @return boolean
     */
    public function authenticate()
    {
        $model = User::model()->findByAttributes(array(
            'username' => $this->username,
        ));
        if (!$model) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } elseif ($model->password != md5($this->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->_id = $model->id;
            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }
}