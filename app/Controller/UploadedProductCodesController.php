<?php
App::uses('AppController', 'Controller');
/**
 * UploadedProductCodes Controller
 *
 * @property UploadedProductCode $UploadedProductCode
 */
class UploadedProductCodesController extends AppController {
	public $components = array('Giftology');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->UploadedProductCode->recursive = 0;
		$this->set('uploadedProductCodes', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->UploadedProductCode->id = $id;
		if (!$this->UploadedProductCode->exists()) {
			throw new NotFoundException(__('Invalid uploaded product code'));
		}
		$this->set('uploadedProductCode', $this->UploadedProductCode->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$new_file_name = 'files/'.$this->request->data['UploadedProductCode']['product_id'].$this->request->data['UploadedProductCode']['upload']['name'];
			copy($this->request->data['UploadedProductCode']['upload']['tmp_name'], $new_file_name);
			$query = "load data local infile '".WWW_ROOT.'/'.$new_file_name."' into table uploaded_product_codes fields terminated by ',' lines terminated by '\n' (product_id, code, value, expiry);";
			echo debug($query);
			$results = $this->UploadedProductCode->query($query);
			echo debug($results);
		}
		$products = $this->UploadedProductCode->Product->find('list');
		$this->set(compact('products'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->UploadedProductCode->id = $id;
		if (!$this->UploadedProductCode->exists()) {
			throw new NotFoundException(__('Invalid uploaded product code'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->UploadedProductCode->save($this->request->data)) {
				$this->Session->setFlash(__('The uploaded product code has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The uploaded product code could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->UploadedProductCode->read(null, $id);
		}
		$products = $this->UploadedProductCode->Product->find('list');
		$this->set(compact('products'));
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
		$this->UploadedProductCode->id = $id;
		if (!$this->UploadedProductCode->exists()) {
			throw new NotFoundException(__('Invalid uploaded product code'));
		}
		if ($this->UploadedProductCode->delete()) {
			$this->Session->setFlash(__('Uploaded product code deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Uploaded product code was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->UploadedProductCode->recursive = 0;
		$this->set('uploadedProductCodes', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->UploadedProductCode->id = $id;
		if (!$this->UploadedProductCode->exists()) {
			throw new NotFoundException(__('Invalid uploaded product code'));
		}
		$this->set('uploadedProductCode', $this->UploadedProductCode->read(null, $id));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->UploadedProductCode->create();
			if ($this->UploadedProductCode->save($this->request->data)) {
				$this->Session->setFlash(__('The uploaded product code has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The uploaded product code could not be saved. Please, try again.'));
			}
		}
		$products = $this->UploadedProductCode->Product->find('list');
		$this->set(compact('products'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->UploadedProductCode->id = $id;
		if (!$this->UploadedProductCode->exists()) {
			throw new NotFoundException(__('Invalid uploaded product code'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->UploadedProductCode->save($this->request->data)) {
				$this->Session->setFlash(__('The uploaded product code has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The uploaded product code could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->UploadedProductCode->read(null, $id);
		}
		$products = $this->UploadedProductCode->Product->find('list');
		$this->set(compact('products'));
	}

/**
 * admin_delete method
 *
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->UploadedProductCode->id = $id;
		if (!$this->UploadedProductCode->exists()) {
			throw new NotFoundException(__('Invalid uploaded product code'));
		}
		if ($this->UploadedProductCode->delete()) {
			$this->Session->setFlash(__('Uploaded product code deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Uploaded product code was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	
	public function generate_codes ($prod_id, $value, $num) {
		$data = array();
		for($count = $num;$count>0;$count--) {
			array_push($data, array(
				'code' => $this->Giftology->generateGiftCode($prod_id),
				'product_id' => $prod_id,
				'value' => $value));
		}
		$this->UploadedProductCode->create();
		$this->UploadedProductCode->saveMany($data);
		$this->Session->setFlash(__($num.' new gift codes of '.$value.' created for product '.$prod_id));
		$this->set('data', $data);
	}
}
