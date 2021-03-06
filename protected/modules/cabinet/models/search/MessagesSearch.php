<?php

namespace app\modules\cabinet\models\search;

use TaggedCache\Dependency;
use TaggedCache\Tag;

class MessagesSearch extends \UserMessages
{
    public function search()
    {
        $userId = user()->getId();

        $dependency = new Dependency([
            new Tag(\UserMessages::class . 'user_id:' . $userId)
        ]);

        $criteria = new \CDbCriteria;

        $criteria->condition = 'user_id = :user_id';
        $criteria->params['user_id'] = $userId;
        $criteria->order = 'created_at DESC';

        $model = \UserMessages::model()->cache(3600 * 24, $dependency, 2);

        return new \CActiveDataProvider($model, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => (int) config('cabinet.user_messages_limit'),
                'pageVar' => 'page',
            ],
        ]);
    }
}
