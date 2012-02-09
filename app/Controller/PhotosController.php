<?php
App::uses('AppController', 'Controller');
/**
 * Photos Controller
 *
 * @property Photo $Photo
 */
class PhotosController extends AppController {

	public $components = array(
		'Paginator' => array(
			'settings' => array('recent')
		)
	);

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Photo->recursive = 0;
		$this->set('photos', $this->paginate());
	}

	public function archive($year, $month = null, $day = null) {
		if (empty($month)) {
			$month = '01';
			$yearBondary = intval($year) + 1;
		}
		if (empty($day)) {
			$day = '01';
			$monthBoundary = str_pad(max(1, ((intval($month) + 1) % 13)), 2, '0', STR_PAD_LEFT);
		}

			$conditions['Photo.created <'] = sprintf('%s-%s-%s 00:00:00', 
				isset($yearBondary) ? $yearBondary : $year,
				isset($monthBoundary) ? $monthBoundary : $month,
				isset($monthBoundary) ? $day : str_pad(max(1, ((intval($day) + 1) % 31)), 2, '0', STR_PAD_LEFT)
			);

		$conditions['Photo.created >='] = sprintf('%s-%s-%s', $year, $month, $day);
		$this->Paginator->settings = compact('conditions');
		$this->set('photos', $this->paginate());
		$this->render('index');
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Photo->id = $id;
		if (!$this->Photo->exists()) {
			throw new NotFoundException(__('Invalid photo'));
		}
		$this->set('photo', $this->Photo->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->request->data('Photo.user_id', $this->Auth->user('id'));
			if ($this->Photo->add($this->request->data)) {
				$this->Session->setFlash(__('The photo has been saved'), 'sucess');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The photo could not be saved. Please, try again.'), 'error');
			}
		}
		$users = $this->Photo->User->find('list');
		$labels = $this->Photo->Label->find('list');
		$this->set(compact('users', 'labels'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Photo->id = $id;
		if (!$this->Photo->exists()) {
			throw new NotFoundException(__('Invalid photo'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Photo->save($this->request->data)) {
				$this->Session->setFlash(__('The photo has been saved'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The photo could not be saved. Please, try again.'), 'error');
			}
		} else {
			$this->request->data = $this->Photo->read(null, $id);
		}
		$users = $this->Photo->User->find('list');
		$labels = $this->Photo->Label->find('list');
		$this->set(compact('users', 'labels'));
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Photo->id = $id;
		if (!$this->Photo->exists()) {
			throw new NotFoundException(__('Invalid photo'));
		}
		if ($this->Photo->delete()) {
			$this->Session->setFlash(__('Photo deleted'), 'sucess');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Photo was not deleted'), 'error');
		$this->redirect(array('action' => 'index'));
	}
}
