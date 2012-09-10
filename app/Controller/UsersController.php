<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('login','logout');
    }
    public function isAuthorized($user) {
        if (($this->action == 'login') || ($this->action == 'logout')) {
            return true;
        }
        return parent::isAuthorized($user);

    }
    //WEB SERVICES
    public function ws_add () {
        //parse json
        //Add User, Profile, Reminders
        $status = array('Status' => 'OK');
        $this->set('status', $status);
        $this->set('_serialize', array('status'));
    }
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
                $this->User->recursive = 2; 
		$this->set('user', $this->User->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
		$facebooks = $this->User->Facebook->find('list');
		$this->set(compact('facebooks'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
	}

/**
 * delete method
 *
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
    
    public function login() {
        if ($this->Connect->user() && $this->Auth->User('id')) {
            $this->redirect(array('controller'=>'reminders', 'action'=>'view_friends'));
        } else {
            if (isset($this->request->params['named']['gift_id'])) {
                // Set the FB OG stuff here
                $gift = $this->User->GiftsReceived->find('first', array(
                        'conditions' => array(
                            'GiftsReceived.id' => $this->request->params['named']['gift_id']),
                        'contain' => array(
                            'Product' => array(
                                'fields' => array('Product.id'),
                                'Vendor' => array('fields' => array('name','facebook_image'))))
                        ));
                if ($gift) {
                    $this->set('fb_title', "Gift Voucher to ".$gift['Product']['Vendor']['name']);
                    $this->set('fb_image', FULL_BASE_URL.'/'.$gift['Product']['Vendor']['facebook_image']);
                    $this->set('fb_description', "Giftology is the hip new replacement to the boring old Happy Birthday facebook post.  Click the link above to redeem your voucher.");
                }
            }            
            $this->set('message', 'The	 fun and easy way to give <b><u>free</u></b> gift vouchers to facebook friends');
            $this->layout = 'landing';
        }
       /* if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash(__('Invalid username or password, try again'));
            }
        }*/
    }

    public function logout() {
        session_destroy();
        session_start();
        $this->layout = 'landing';
        $this->set('message', 'Thanks for stopping by Giftology.  Come back soon !');
        //$this->redirect($this->referer());

     //   $this->redirect($this->Auth->logout());
    }
    
    function beforeFacebookSave() {
        $this->Connect->authUser['User']['last_login'] = date('Y-m-d');
        $this->Connect->authUser['User']['access_token'] = '"'.FB::getAccessToken().'"';
        $this->setUserProfile();
        $this->setUserUtm();
        $this->setUserReminders();
        return true;
    }
    
    function afterFacebookSave($last_insert_id) {
        $updatedGiftId = $this->updatePlaceholderGifts($last_insert_id);
        if ($updatedGiftId) {
            $this->updateUTMForReferredUser($updatedGiftId, $last_insert_id);
        }
    }
    function afterFacebookLogin() {
        $user = $this->Auth->user();

        if (!$user || !isset($user['id']))
        { return; }

        $daysSinceLogin =round (strtotime(date('Y-m-d')) - strtotime($user['last_login']))/86400;
        $numReminders = $this->User->Reminders->find('count', array(
                    'conditions' => array('user_id' => $user['id'])));

        if ($daysSinceLogin > 15 || $numReminders < 10) {
            // Delete old reminders
            if ($numReminders) {
                $this->User->Reminders->deleteAll(array('user_id' => $user['id']));
            }
            //refesh reminders
            $this->setUserReminders($user['id']);
            $this->User->Reminders->saveMany($this->Connect->authUser['Reminders']);
        }
        
        //update User Profile if necesary
        if (!$this->User->UserProfile->find('first', array('conditions' => array('user_id' => $user['id'])))) {
            //no user profile, create now
            $this->setUserProfile();
            $this->Connect->authUser['UserProfile']['user_id'] = $user['id'];
            $this->User->UserProfile->save($this->Connect->authUser);
        }
        
        // Update Access token and last_login
        $this->User->updateAll(array(
            'User.last_login' => date('Y-m-d'),
            'User.access_token' => '"'.FB::getAccessToken().'"'
        ), array(
            'User.id' => $user['id']));
    }
    function updatePlaceholderGifts ($last_insert_id) {

        // Main deal here is that we may have some dudes who didnt have a user account
        // before when gifts were sent to them, so the gifts went to the placeholder
        // account UNREGISTERED_GIFT_RECIPIENT_PLACEHODER_USER_ID.  Now that a
        // new user has been created, we want to check to see if this user already
        // has some placeholder gifts, and if that is the case then update the gifts
        // to the correct and valid user account, instead of placeholder
        // Might also be a good place to set User UTM, since its likely that
        // this user came in here from a sent gift - NS


        $this->User->GiftsReceived->recursive = -1;
        $giftsReceived = $this->User->GiftsReceived->find('all',
            array('conditions' => array(
                'receiver_fb_id' => $this->Connect->user('id'),
                'receiver_id' => UNREGISTERED_GIFT_RECIPIENT_PLACEHODER_USER_ID)));
        $gift_id_to_return = null;
        if (!$giftsReceived) {
            return $gift_id_to_return;
        }
        
        $this->Session->setFlash(__('Good Karma - Someone has sent you a gift. Click My Gifts above to redeem'));

        $updatedGifts = array();
        foreach($giftsReceived as $giftReceived) {
            $updatedGift['GiftsReceived']['receiver_id'] = $last_insert_id;
            $updatedGift['GiftsReceived']['id'] = $giftReceived['GiftsReceived']['id'];
            array_push($updatedGifts, $updatedGift);
            $gift_id_to_return = $giftReceived['GiftsReceived']['id'];
        }
        $this->User->GiftsReceived->saveMany($updatedGifts);
        return $gift_id_to_return;
    }
    function updateUTMForReferredUser ($updatedGiftId, $user_id) {
        $this->User->UserUtm->recursive = -1;
        $currUTM = $this->User->UserUtm->find('first',
                        array('conditions' => array(
                                'user_id' => $user_id
                        )));
        if ($currUTM) {
            return; //already has a good UTM, no need to hack in a new one 
        }
        $this->User->UserUtm->save(array('UserUtm' => array('utm_source' => 'GiftReceived',
                                              'utm_medium' => 'Generated',
                                              'utm_term' => $updatedGiftId,
                                              'user_id' => $user_id)));
    }
        
    function setUserProfile () {
        $this->Connect->authUser['UserProfile']['email'] = $this->Connect->user('email');
        
        $fb_user = $this->Connect->user();
        $location = isset($fb_user['location']) ? $fb_user['location'] : array('name' => 'not provided');
        $this->Connect->authUser['UserProfile']['city'] = $location['name'];
        
        $this->Connect->authUser['UserProfile']['gender'] = $this->Connect->user('gender');
        $this->Connect->authUser['UserProfile']['birthday'] = $this->Connect->user('birthday');
        $this->Connect->authUser['UserProfile']['first_name'] = $this->Connect->user('first_name');
        $this->Connect->authUser['UserProfile']['last_name'] = $this->Connect->user('last_name');
        
    }
    function setUserUtm() {
        if (isset($this->request->query['utm_source'])) {
            $this->Connect->authUser['UserUtm']['utm_source'] = $this->request->query['utm_source'];
        }
        if (isset($this->request->query['utm_medium'])) {
            $this->Connect->authUser['UserUtm']['utm_medium'] = $this->request->query['utm_medium'];
        }
        if (isset($this->request->query['utm_campaign'])) {
            $this->Connect->authUser['UserUtm']['utm_campaign'] = $this->request->query['utm_campaign'];
        }
        if (isset($this->request->query['utm_term'])) {
            $this->Connect->authUser['UserUtm']['utm_term'] = $this->request->query['utm_term'];
        }
    }
    function setUserReminders($user_id = null) {
        $friends = $this->getUserFriends();
        $this->Connect->authUser['Reminders'] = array();
        foreach ($friends as $friend) {
            array_push($this->Connect->authUser['Reminders'], array (
                    'user_id' => $user_id,
                    'friend_fb_id' => $friend['uid'],
                    'friend_name' => $friend['name'],
                    'friend_birthday' => $friend['birthday']
                ));
        }
    }
    
    function getUserFriends() {
        $Facebook = new FB();
        $friends = $Facebook->api(array('method' => 'fql.query',
                                        'query' => 'SELECT uid, birthday, pic_big, name FROM user WHERE uid IN (SELECT uid2 from friend where uid1=me()) ORDER BY birthday'));
        return $friends;
    }
    
    function send_welcome_email() {
        $email = new CakeEmail();
	$email->config('smtp')
              ->template('welcome', 'default') 
	      ->emailFormat('html')
	      ->to($this->Connect->user('email'))
	      ->from(array('care@giftology.com' => 'Giftology Customer Care'))
	      ->subject('Welcome to Giftology')
              ->viewVars(array('name' => $this->Connect->user('name')))
              ->send();
    }
    public function view_gifts() {
/*        $this->User->id = $this->Auth->user('id');
        if (!$this->User->exists()) {
                throw new NotFoundException(__('Invalid user'));
        }
*/
        $this->User->Behaviors->attach('Containable');
        $this->set('user', $this->User->find('first',
                array(
                    'contain' => array(
                        'UserProfile',
                        'GiftsReceived' => array(
                            'Product' => array('Vendor'),
                            'Sender',
                            'limit' => 30,
                            'order' => 'GiftsReceived.created DESC',
                            'conditions' => array('GiftsReceived.gift_status_id' => GIFT_STATUS_VALID)),
                        'GiftsSent' => array(
                            'Product' => array('Vendor'),
                            'limit' => 30,
                            'order' => 'GiftsSent.created DESC',
                            'conditions' => array('GiftsSent.gift_status_id' => GIFT_STATUS_VALID))),
                    'conditions' => array('User.id' => $this->User->id))));
    }
    
    /* Moved to Reminders 
    public function view_friends($type=null) {
        if (!$this->Connect->user()) {
            $this->redirect(array('controller'=>'users', 'action'=>'login'));
        }
        if ($type) {
            $this->set('all_users', $this->get_birthdays('mine','all'));
            $this->set('today_users', array('Reminders' =>array()));
            $this->set('this_month_users', array('Reminders' =>array()));

        } else {
            $this->set('all_users', array('Reminders' =>array()));
            $this->set('today_users', $this->get_birthdays('mine','today'));
            $this->set('this_month_users', $this->get_birthdays('mine','thismonth'));
        }
    }
    
        function get_birthdays ($whose, $when) {
        $find_type = 'all';
        
        if ($whose == 'mine') {
            $conditions = array(
                'User.id' => $this->Auth->user('id'));
            $find_type = 'first';
        }
        
        if ($when == 'today') {
            $reminder_conditions = array(
                            'MONTH(Reminders.friend_birthday)' => date('m'),
                            'DAY(Reminders.friend_birthday)' => date('d')); 
        } elseif ($when =='thisweek') {
            $reminder_conditions = array(
                            'Reminders.friend_birthday BETWEEN ? AND ?' => array(date("Y-m-d"), date("Y-m-d", strtotime(date("Y-m-d") . "+1 week"))));
        } elseif ($when == 'thismonth') {
            $reminder_conditions = array(
                            'Reminders.friend_birthday BETWEEN ? AND ?' => array(date("Y-m-d"), date("Y-m-d", strtotime(date("Y-m-d") . "+1 month"))));
        }
        
        $this->User->Behaviors->attach('Containable');
        $user = $this->User->find($find_type,
                array(
                    'contain' => array(
                        'UserProfile',
                        'Reminders' => array(
                            'conditions' => isset($reminder_conditions) ? $reminder_conditions : array(),
                            'order' => 'Reminders.friend_birthday ASC',
                            'limit' => 200
                    )),
                    'conditions' => isset($conditions) ? $conditions : array())
                );
        return $user;
    }

    */
}