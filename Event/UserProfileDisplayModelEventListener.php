<?php
/**
 * [ModelEventListener] UserProfileDisplay
 *
 * @link			http://www.materializing.net/
 * @author			arata
 * @license			MIT
 */
class UserProfileDisplayModelEventListener extends BcModelEventListener {
/**
 * 登録イベント
 *
 * @var array
 */
	public $events = array(
		'User.beforeFind',
		'User.afterSave',
		'User.afterDelete',
		'Blog.BlogPost.afterFind',
	);
	
/**
 * モデル初期化：UserProfileDisplay
 * 
 * @return void
 */
	public function setup() {
		if (ClassRegistry::isKeySet('UserProfileDisplay.UserProfileDisplay')) {
			$this->UserProfileDisplayModel = ClassRegistry::getObject('UserProfileDisplay.UserProfileDisplay');
		} else {
			$this->UserProfileDisplayModel = ClassRegistry::init('UserProfileDisplay.UserProfileDisplay');
		}
	}
	
/**
 * blogBlogPostAfterFind
 * ブログ記事取得時に、記事作成者のユーザープロフィールディスプレイデータを取得する
 * 
 * @param CakeEvent $event
 * @return array
 */
	function blogBlogPostAfterFind (CakeEvent $event) {
		//$Model = $event->subject();
		if (!BcUtil::isAdminSystem()) {
			if (!empty($event->data[0])) {
				$this->setup();
				foreach ($event->data[0] as $key => $value) {
					// 記事作成者のユーザープロフィールディスプレイデータを取得
					if (!empty($value['BlogPost']['user_id'])) {
						$data = $this->UserProfileDisplayModel->find('first', array(
							'conditions' => array(
								'UserProfileDisplay.user_id' => $value['BlogPost']['user_id'],
							),
							'recursive' => -1,
						));
						if ($data) {
							$event->data[0][$key]['UserProfileDisplay'] = $data['UserProfileDisplay'];
						}
					}
				}
			}
		}
	}
	
/**
 * userBeforeFind
 * ユーザー情報取得の際に、UserProfileDisplay 情報も併せて取得する
 * 
 * @param CakeEvent $event
 */
	public function userBeforeFind (CakeEvent $event) {
		$Model = $event->subject();
		$association = array(
			'UserProfileDisplay' => array(
				'className' => 'UserProfileDisplay.UserProfileDisplay',
				'foreignKey' => 'user_id'
			)
		);
		$Model->bindModel(array('hasOne' => $association));
	}
	
/**
 * userAfterSave
 * ユーザー情報保存時に、UserProfileDisplay 情報を保存する
 * 
 * @param CakeEvent $event
 */
	public function userAfterSave (CakeEvent $event) {
		$Model = $event->subject();
		$created = $event->data[0];
		$saveData = array();
		
		if ($created) {
			$saveData['UserProfileDisplay'] = $Model->data['UserProfileDisplay'];
			$saveData['UserProfileDisplay']['user_id'] = $Model->getLastInsertId();
		} else {
			$saveData['UserProfileDisplay'] = $Model->data['UserProfileDisplay'];
		}
		
		if (isset($saveData['UserProfileDisplay']['id'])) {
			$Model->UserProfileDisplay->set($saveData);
		} else {
			$Model->UserProfileDisplay->create($saveData);
		}
		if (!$Model->UserProfileDisplay->save()) {
			$this->log(sprintf('ID：%s のUserProfileDisplayの保存に失敗しました。', $Model->data['UserProfileDisplay']['id']));
		} else {
			clearAllCache();
		}
	}
	
/**
 * userAfterDelete
 * ユーザー情報削除時、そのユーザーが持つ UserProfileDisplay 情報を削除する
 * 
 * @param CakeEvent $event
 */
	public function userAfterDelete (CakeEvent $event) {
		$Model = $event->subject();
		$data = $Model->UserProfileDisplay->find('first', array(
			'conditions' => array('UserProfileDisplay.user_id' => $Model->id),
			'recursive' => -1
		));
		if ($data) {
			if (!$Model->UserProfileDisplay->delete($data['UserProfileDisplay']['id'])) {
				$this->log('ID:' . $data['UserProfile']['id'] . 'のUserProfileDisplayの削除に失敗しました。');
			}
		}
	}
	
}
