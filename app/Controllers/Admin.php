<?php

namespace App\Controllers;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Models\ProductModel;
use App\Models\ProjectModel;
use App\Models\AcquisitionsModel;
use App\Models\UsersModel;
use App\Models\ProductRulesModel;
use App\Models\AuthGroupModel;
use App\Models\DoubleByDateModel;
use App\Models\UnitsModel;
use DateTime;


class Admin extends BaseController
{
   protected $productModel;
   protected $productRulesModel;
   protected $projectModel;
   protected $acquisitionsModel;
   protected $usersModel;
   protected $unitsModel;
   protected $authGroupModel;
   protected $doubleByDateModel;

   // construktor kelas admin
   public function __construct()
   {
      $this->productModel = new ProductModel();
      $this->projectModel = new ProjectModel();
      $this->productRulesModel = new ProductRulesModel();
      $this->acquisitionsModel = new AcquisitionsModel();
      $this->usersModel = new UsersModel();
      $this->authGroupModel = new AuthGroupModel();
      $this->unitsModel = new UnitsModel();
      $this->doubleByDateModel = new DoubleByDateModel();
   }

   // Index Admin tampilan table user
   public function index()
   {
      $currentPage = $this->request->getVar('page_users') ? $this->request->getVar('page_users') : 1;

      $keyword = $this->request->getVar('keyword');
      $units = $this->unitsModel->findAll();

      if ($keyword) {
         session()->setFlashdata('hasil_cari', 'Mencari dengan kata kunci "' . $keyword . '"');
         $users = $this->usersModel->search($keyword);
      } else {
         $users = $this->usersModel;
      }

      $data = [
         'title'       => 'User List | Horas Poin',
         'users'       => $users->join('auth_groups_users as as', 'as.user_id = users.id')->join('auth_groups as ag', 'ag.id =  as.group_id')->paginate(50, 'users'),
         'pager'       => $this->usersModel->pager,
         'currentPage' => $currentPage,
         'units'       => $units,
         'keyword'     => $keyword,
         'validation'  => $this->validation
      ];

      return view('admin/index', $data);
   }

   public function listAcquisitions()
   {
      $product = $this->productModel->findAll();
      $project = $this->projectModel->findAll();
      $acquisitions = $this->acquisitionsModel->getAllAcquisitions()->get()->getResult('array');
      $countAcquisitions= count($acquisitions);

      $keyword = $this->request->getVar('keyword');

      $visitation = [
         'Akuisisi', 'Visit'
      ];

      $leadSources = [
         'Non LMS', 'LMS'
      ];

      $customerType = [
         'Existing', 'New'
      ];

      $status = [
         'Follow Up', 'Booking / Issued / Cair'
      ];

      $data = [
         'title' => 'Horas Poin',
         'validation' => $this->validation,
         'product' => $product,
         'project' => $project,
         'countAcquisitions' => $countAcquisitions,
         'keyword' => $keyword,
         'visitation' => $visitation,
         'leadSources' => $leadSources,
         'status' => $status,
         'customerType' => $customerType,
      ];

      if ($this->usersModel->getUserGroup(user()->id) == 1) {
         redirect()->to(base_url('admin/'));
      } else {
         return view('admin/list_acquisitions', $data);
      }
   }

   // view user detail page
   public function detail($id = 0)
   {
      $countAdmin = $this->usersModel->countAdmin();
      $makeUser = 1;
      $units = $this->unitsModel->findAll();

      if (user()->id == $id) {
         if ($countAdmin == 1) {
            $makeUser = 0;
         }
      }

      $user = $this->usersModel->getUser($id);

      $data = [
         'title' => 'User Details',
         'user' => $user,
         'makeUser' => $makeUser,
         'validation' => $this->validation,
         'units' => $units,
         'points' =>  $this->acquisitionsCount($user->nip)
      ];

      if (empty($data['user'])) {
         return redirect()->to('/admin');
      }

      return view('admin/detail', $data);
   }

   // view project page
   public function productList()
   {
      $data = [
         'product' => $this->productModel->findAll(),
         'title' => 'Project List',
         'validation' => $this->validation,
      ];

      return view('admin/product', $data);
   }


   // view project page
   public function projectList()
   {
      $data = [
         'title' => 'Product List',
         'project' => $this->projectModel->findAll(),
         'validation' => $this->validation,
      ];

      return view('admin/project', $data);
   }

   // view project page
   public function productRule()
   {
      $double = [1, 0];
      $minimal = [1, 0];
      $limit_amount_nominal = [1, 0];
      $multiple = [1, 0];

      $data = [
         'title' => 'Product Rules List',
         'rules' => $this->productRulesModel->findAll(),
         'validation' => $this->validation,
         'double' => $double,
         'minimal' => $minimal,
         'limit_amount_nominal' => $limit_amount_nominal,
         'multiple' => $multiple,
      ];

      return view('admin/product_rules', $data);
   }

   // view detail product
   public function detailProject($id)
   {
      $project = $this->projectModel->find($id);

      $data = [
         'title' => 'Product Detail',
         'project' => $project,
         'validation' => $this->validation,
      ];

      return view('admin/detail_project', $data);
   }

   // view detail product
   public function detailProduct($id)
   {
      $product = $this->productModel->find($id);
      $rules = $this->productRulesModel->findAll();
      $productRule = $this->productModel->getProductRules($id);

      $data = [
         'title' => 'Product Detail',
         'rules' => $rules,
         'product' => $product,
         'productRules' => $productRule,
         'validation' => $this->validation,
      ];

      return view('admin/detail_product', $data);
   }
   
   // view units page
   public function units()
   {
      $data = [
         'units' => $this->unitsModel->findAll(),
         'title' => 'List Unit Cabang',
         'validation' => $this->validation,
      ];

      return view('admin/units', $data);
   }
   
   // view detail product
   public function detailUnit($id)
   {
      $unit = $this->unitsModel->find($id);

      $data = [
         'title' => 'Unit Detail',
         'unit' => $unit,
         'validation' => $this->validation,
      ];

      return view('admin/detail_unit', $data);
   }

   // view detail rule product
   public function detailRulesProduct($id)
   {
      $rule = $this->productRulesModel->find($id);

      $double = [1, 0];
      $minimal = [1, 0];
      $limit_amount_nominal = [1, 0];
      $multiple = [1, 0];

      $data = [
         'title' => 'Product Rules Detail',
         'rule' => $rule,
         'validation' => $this->validation,
         'double' => $double,
         'minimal' => $minimal,
         'limit_amount_nominal' => $limit_amount_nominal,
         'multiple' => $multiple,
      ];

      return view('admin/detail_rules_product', $data);
   }

   // view Patriot day
   public function PatriotDay()
   {
      $date = $this->doubleByDateModel->findAll();
      $data = [
         'title'      => 'Patriot Day',
         'date'       => $date,
         'validation' => $this->validation
      ];

      return view('admin/patriot_day', $data);
   }
   //--------------------------------------------------------------------

   // method tambah tanggal patriot day
   public function addPatriotDay()
   {
      $rules = [
         'date'         => 'is_unique[double_by_date.dates]|required',
         'information'  => 'alpha_numeric_space',
      ];

      if (!$this->validate($rules)) {
         return redirect()->back()->withInput();
      }

      $data = [
         'dates' => $this->request->getVar('date'),
         'information'   => $this->request->getVar('information'),
      ];

      if ($this->doubleByDateModel->save($data)) {
         session()->setFlashdata('pesan', 'Patriot Day telah ditambahkan pada tanggal  ' . $this->request->getVar('date'));
      } else {
         session()->setFlashdata('gagal', 'Tanggal patriot day gagal ditambahkan');
      }
      return redirect()->back();
   }

   public function deletePatriotDay($id)
   {
      $date = $this->doubleByDateModel->find($id);

      if ($this->doubleByDateModel->deleteDate($id)) {
         session()->setFlashdata('pesan', 'Patriot Day pada tanggal ' . $date['date'] . ' gagal dihapus');
      } else {
         session()->setFlashdata('pesan', 'Patriot Day pada tanggal ' . $date['date'] . ' berhasil dihapus');
         return redirect()->back();
      }
   }

   // method userActivation
   public function userActivation()
   {
      $user = new \Myth\Auth\Models\UserModel();

      $data['title'] = 'User List';

      $this->builder->select('users.id as userid, id_unit, nip, username, email,');
      $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
      $this->builder->where('active !=', 1);
      $query = $this->builder->get();

      $data['users'] = $query->getResult();

      return view('admin/user-activation', $data);
   }

   // method hapus User 
   public function deleteUser($id)
   {
      $countAdmin = $this->usersModel->countAdmin();
      $user = $this->usersModel->username($id);

      if (user()->id == $id) {
         session()->setFlashdata('gagal', 'Tidak dapat menghapus akun admin yang sedang login');
         return redirect()->back();
      } else {
         if ($countAdmin == 1 && user()->id == $id) {
            session()->setFlashdata('gagal', 'Gagal menghapus, Anda Admin');
            return redirect()->back();
         } else {
            if ($this->usersModel->deleteUser($id)) {
               session()->setFlashdata('gagal', 'Data user ' . $user['username'] . ' gagal dihapus');
               return redirect()->back();
            } else {
               session()->setFlashdata('pesan', 'Data user ' . $user['username'] . ' berhasil dihapus');
               return redirect()->back();
            }
         }
      }
   }

   public function makeAdmin($id)
   {
      $this->authGroupModel->makeAdmin($id);
      $user = $this->usersModel->username($id);

      session()->setFlashdata('pesan', 'Berhasil merubah user ' . $user['username'] . ' menjadi admin');
      return redirect()->back();
   }

   public function makeUser($id)
   {
      $this->authGroupModel->makeUser($id);
      $user = $this->usersModel->username($id);

      session()->setFlashdata('pesan', 'Berhasil merubah user ' . $user['username'] . ' menjadi user');
      return redirect()->back();
   }

   public function activate($id)
   {
      $this->usersModel->active($id);
      $user = $this->usersModel->username($id);

      session()->setFlashdata('pesan', 'User ' . $user['username'] . ' berhasil diaktifkan');
      return redirect()->back();
   }

   public function deactivate($id)
   {
      $this->usersModel->diactive($id);
      $user = $this->usersModel->username($id);

      session()->setFlashdata('pesan', 'User ' . $user['username'] . ' berhasil di non-aktifkan');
      return redirect()->back();
   }

   public function updateUser($id)
   {
      $user = $this->usersModel->getUser($id);

      $rules = [
         'username'     => 'is_unique[users.username]|required|alpha_numeric_space|min_length[3]',
         'nip'          => 'is_unique[users.nip,id,{id}]|required|exact_length[9]',
         'id_unit'      => 'required|exact_length[5]',
         'fullname'     => 'alpha_numeric_space|min_length[5]',
         'email'        => 'is_unique[users.email]|required|valid_email',
         'user_image'   => 'max_size[user_image,3024]|is_image[user_image]|mime_in[user_image,image/jpg,image/jpeg,image/png]'
      ];

      if ($user->username == $this->request->getVar('username')) {
         $rules['username'] = substr($rules['username'], 26);
      }

      if ($user->nip == $this->request->getVar('nip')) {
         $rules['nip'] = substr($rules['nip'], 29);
      }

      if ($user->email == $this->request->getVar('email')) {
         $rules['email'] = substr($rules['email'], 23);
      }

      if (!$this->validate($rules)) {
         return redirect()->back()->withInput();
      }

      $fileImage = $this->request->getFile('user_image');
      //cek gambar upload
      if ($fileImage->getError() == 4) {
         $imageName = $this->request->getVar('image_user_old');
      } else {
         //generate random name sampul
         $imageName = $fileImage->getRandomName();
         //pindahkan gambar
         $fileImage->move('img/user_image', $imageName);
         //hapus file sampul lama

         //cek gambar default
         if ($user->user_image != 'default.svg') {
            unlink('img/user_image/' . $this->request->getVar('image_user_old'));
         }
      }

      $data = [
         'username'      => $this->request->getVar('username'),
         'nip'           => $this->request->getVar('nip'),
         'id_unit'       => $this->request->getVar('id_unit'),
         'email'         => $this->request->getVar('email'),
         'fullname'      => $this->request->getVar('fullname'),
         'user_image'    => $imageName
      ];

      // Update User
      if ($this->usersModel->update($id, $data)) {
         session()->setFlashdata('pesan', 'Data user ' . $this->request->getVar('username') . ' berhasil diubah');
         return redirect()->back();
      } else {
         session()->setFlashdata('pesan', 'Data user ' . $this->request->getVar('username') . ' gagal diubah');
         return redirect()->back();
      }
   }

   //generate user's password
   public function hashingPass($password)
   {
      $config = config('Auth');

      if (
         (defined('PASSWORD_ARGON2I') && $config->hashAlgorithm == PASSWORD_ARGON2I)
         ||
         (defined('PASSWORD_ARGON2ID') && $config->hashAlgorithm == PASSWORD_ARGON2ID)
      ) {
         $hashOptions = [
            'memory_cost' => $config->hashMemoryCost,
            'time_cost'   => $config->hashTimeCost,
            'threads'     => $config->hashThreads
         ];
      } else {
         $hashOptions = [
            'cost' => $config->hashCost
         ];
      }

      $result = password_hash(
         base64_encode(
            hash('sha384', $password, true)
         ),
         $config->hashAlgorithm,
         $hashOptions
      );

      return $result;
   }

   //Update user's password 
   public function updatePass($id)
   {
      $rules = [
         'password'       => 'required|strong_password',
         'pass_confirm'    => 'required|matches[password]'
      ];

      if (!$this->validate($rules)) {
         session()->setFlashdata('gagal', 'Validasi gagal, silahkan buka kembali form ganti password ');
         return redirect()->back()->withInput();
      }

      $password = trim($this->request->getVar('password'));

      $result  = $this->hashingPass($password);

      if (!$this->usersModel->passUpdate($id, $result)) {
         session()->setFlashdata('pesan', 'Password user ' . $this->request->getVar('username') . ' berhasil diubah');
         return redirect()->back();
      } else {
         session()->setFlashdata('gagal', 'Password user ' . $this->request->getVar('username') . ' gagal diubah');
         return redirect()->back();
      }
   }

   //add user via admin
   public function register()
   {
      // Check if registration is allowed
      if (!$this->config->allowRegistration) {
         return redirect()->back()->withInput()->with('error', lang('Auth.registerDisabled'));
      }

      $users = model('UserModel');

      // Validate here first, since some things,
      // like the password, can only be validated properly here.
      $rules = [
         'username'      => 'required|alpha_numeric_space|min_length[3]|is_unique[users.username]',
         'nip'           => 'required|exact_length[9]|is_unique[users.NIP,id,{id}]',
         'id_unit_kerja' => 'required|exact_length[5]',
         'email'         => 'required|valid_email|is_unique[users.email]',
         'password'      => 'required|strong_password',
         'pass_confirm'  => 'required|matches[password]',
      ];

      if (!$this->validate($rules)) {
         session()->setFlashdata('gagal', 'Inputan Form tidak valid');
         return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
      }

      // Save the user
      $allowedPostFields = array_merge(['password'], $this->config->validFields, $this->config->personalFields);
      $user = new User($this->request->getPost($allowedPostFields));

      // Ensure default group gets assigned if set
      if (!empty($this->config->defaultUserGroup)) {
         $users = $users->withGroup($this->config->defaultUserGroup);
      }

      if (!$users->save($user)) {
         session()->setFlashdata('gagal', 'User ' . $this->request->getVar('username') . ' gagal dibuat');
         return redirect()->back()->withInput()->with('errors', $users->errors());
      }

      if ($this->config->requireActivation !== false) {
         $activator = service('activator');
         $sent = $activator->send($user);

         if (!$sent) {
            session()->setFlashdata('gagal', 'User ' . $this->request->getVar('username') . ' gagal dibuat');
            return redirect()->back()->withInput()->with('error', $activator->error() ?? lang('Auth.unknownError'));
         }

         // Success!
         session()->setFlashdata('pesan', 'User ' . $this->request->getVar('username') . ' berhasil dibuat');
         return redirect()->route('admin/index')->with('message', lang('Auth.activationSuccess'));
      }

      // Success!
      session()->setFlashdata('pesan', 'User ' . $this->request->getVar('username') . ' berhasil dibuat');
      return redirect()->route('admin/index')->with('message', lang('Auth.registerSuccess'));
   }

   // ListAkuisisi akuisisi section =============================================== 

   // belum diperbaiki
   public function applyfilters()
   {
      $from = $this->request->getVar('from');
      $to = $this->request->getVar('to');
      $product = $this->request->getVar('product');
      $keyword = $this->request->getVar('keyword');
      $acquisitionsFinal = array();

      $early = new DateTime($from);
      $end = new DateTime($to);
      $dateEarly = $early->format('m/d/Y');
      $dateEnd = $end->format('m/d/Y');

      $acquisitionsFiltered = array();

      $limit = 50;
      $page = 0;

      if ($keyword) {
         $acquisitions = $this->acquisitionsModel->searchAll($keyword)->get()->getResult('array');
      } else {
         $acquisitions = $this->acquisitionsModel->getAllAcquisitions()->get()->getResult('array');
      }
      
      foreach ($acquisitions as $i => $a) :
         //case jika keduanya
         $acquisitionsDate = new DateTime($a['created_at']);
         $date = $acquisitionsDate->format('m/d/Y');
            
         if ($from != '' && $to != '' && $product != '') {
            if (($dateEarly <= $date && $dateEnd >= $date) || ($dateEnd == $date || $dateEarly == $date)) {
               if ($a['id_product'] == $product) {
                  $data = [
                     'id' => $a['id'],
                     'nip' => $a['nip'],
                     'nominal' => $a['nominal'],
                     'project_name' => $a['project_name'],
                     'id_project' => $a['id_project'],
                     'product_name' => $a['product_name'],
                     'id_product' => $a['id_product'],
                     'customer_name' => $a['customer_name'],
                     'cif' => $a['cif'],
                     'rekening' => $a['rekening'],
                     'acquisitions_dates' => $a['acquisitions_dates'],
                     'updated_at' => $a['updated_at'],
                     'created_at' => $a['created_at'],
                     'username' => $a['username'],
                     'fullname' => $a['fullname'],
                     'id_unit' => $a['id_unit'],
                     'unit_name' => $a['unit_name'],
                  ];
                  $acquisitionsFiltered[$i] = $data;
               }
            }
         } elseif ($from != '' && $to != '') {
            if (($dateEarly <= $date && $dateEnd >= $date) ||($dateEnd == $date || $dateEarly == $date)) {
               $data = [
                     'id' => $a['id'],
                     'nip' => $a['nip'],
                     'nominal' => $a['nominal'],
                     'project_name' => $a['project_name'],
                     'id_project' => $a['id_project'],
                     'product_name' => $a['product_name'],
                     'id_product' => $a['id_product'],
                     'customer_name' => $a['customer_name'],
                     'cif' => $a['cif'],
                     'rekening' => $a['rekening'],
                     'acquisitions_dates' => $a['acquisitions_dates'],
                     'updated_at' => $a['updated_at'],
                     'created_at' => $a['created_at'],
                     'username' => $a['username'],
                     'fullname' => $a['fullname'],
                     'id_unit' => $a['id_unit'],
                     'unit_name' => $a['unit_name'],
                  ];
               $acquisitionsFiltered[$i] = $data;
            }
         } elseif ($product != '') {
            if ($a['id_product'] == $product) {
               $data = [
                     'id' => $a['id'],
                     'nip' => $a['nip'],
                     'nominal' => $a['nominal'],
                     'project_name' => $a['project_name'],
                     'id_project' => $a['id_project'],
                     'product_name' => $a['product_name'],
                     'id_product' => $a['id_product'],
                     'customer_name' => $a['customer_name'],
                     'cif' => $a['cif'],
                     'rekening' => $a['rekening'],
                     'acquisitions_dates' => $a['acquisitions_dates'],
                     'updated_at' => $a['updated_at'],
                     'created_at' => $a['created_at'],
                     'username' => $a['username'],
                     'fullname' => $a['fullname'],
                     'id_unit' => $a['id_unit'],
                     'unit_name' => $a['unit_name'],
                  ];
               $acquisitionsFiltered[$i] = $data;
            }
         } else {
            $data = [
                     'id' => $a['id'],
                     'nip' => $a['nip'],
                     'nominal' => $a['nominal'],
                     'project_name' => $a['project_name'],
                     'id_project' => $a['id_project'],
                     'product_name' => $a['product_name'],
                     'id_product' => $a['id_product'],
                     'customer_name' => $a['customer_name'],
                     'cif' => $a['cif'],
                     'rekening' => $a['rekening'],
                     'acquisitions_dates' => $a['acquisitions_dates'],
                     'updated_at' => $a['updated_at'],
                     'created_at' => $a['created_at'],
                     'username' => $a['username'],
                     'fullname' => $a['fullname'],
                     'id_unit' => $a['id_unit'],
                     'unit_name' => $a['unit_name'],
                  ];
               $acquisitionsFiltered[$i] = $data;
         }
      endforeach;
      

      if (isset($_POST['page'])) {
         $page = $_POST['page'];
      } else {
         $page = 1;
      }

      $start_from = ($page - 1) * $limit;
      $totalData = count($acquisitionsFiltered);
      $totalPage = ceil($totalData / $limit);

      if ($page != 1) {
         $page = $_POST['page'];
         $acquisitionsFinal = array_slice($acquisitionsFiltered, $start_from, 50);
      } else {
         $acquisitionsFinal = array_slice($acquisitionsFiltered, 0, 50);
      }

      $data = [
         'acquisitions' => $acquisitionsFinal,
         'totalPage' => $totalPage,
         'page' => $page,
         'start' => $start_from
      ];

      return view('ajax_view/acquisitions_table_admin', $data);
   }

   // belum diperbaiki
   public function paginationAcquisitions()
   {
      $limit = 50;
      $page = 0;

      if (isset($_POST['page'])) {
         $page = $_POST['page'];
      } else {
         $page = 1;
      }

      $start_from = ($page - 1) * $limit;
      $acquisitions = $this->acquisitionsModel->getAllAcquisitionsPaginate($limit, $start_from);
      $totalData = count($this->acquisitionsModel->findAll());
      $totalPage = ceil($totalData / $limit);

      $data = [
         'acquisitions' => $acquisitions,
         'totalPage' => $totalPage,
         'page' => $page,
         'start' => $start_from
      ];

      return view('ajax_view/acquisitions_table_admin', $data);
   }

   // product section ===============================================
   public function addRule()
   {
      $idProduct = $this->request->getVar('id_product');
      $idRule = $this->request->getVar('id_rule');
      $rule = $this->productRulesModel->find($idRule);

      $this->productModel->addRule($idProduct, $idRule);

      session()->setFlashdata('pesan', 'rule ' . $rule['rule_name'] . ' berhasil di tambahkan');
      return redirect()->back();
   }

   public function deleteRule($idProduct, $idRule)
   {
      $rule = $this->productRulesModel->find($idRule);

      $this->productModel->deleteRule($idProduct, $idRule);

      session()->setFlashdata('pesan', 'rule ' . $rule['rule_name'] . ' berhasil di dihapus');
      return redirect()->back();
   }

   public function updateProduct($id)
   {
      $product = $this->productModel->find($id);

      $rules = [
         'product_name'     => 'is_unique[product.product_name]|required',
         'points'           => 'required|numeric',
      ];

      if ($product['product_name'] == $this->request->getVar('product_name')) {
         $rules['product_name'] = substr($rules['product_name'], 32);
      }

      if (!$this->validate($rules)) {
         session()->setFlashdata('gagal', 'Data project gagal diubah');
         return redirect()->back()->withInput();
      }

      $data = [
         'product_name' => $this->request->getVar('product_name'),
         'points'         => $this->request->getVar('points'),
         'information'   => $this->request->getVar('information'),
      ];

      // Update product
      if ($this->productModel->update($id, $data)) {
         session()->setFlashdata('pesan', 'Data product ' . $this->request->getVar('product_name') . ' berhasil diubah');
      } else {
         session()->setFlashdata('gagal', 'Data product ' . $this->request->getVar('product_name') . ' gagal diubah');
      }

      return redirect()->back();
   }

   public function updateRuleProduct($id)
   {
      $productRule = $this->productRulesModel->find($id);

      $rules = [
         'rule_name'             => 'is_unique[product_rules.rule_name]|required|alpha_numeric_space',
         'information'           => 'required',
         'limit_nominal'         => 'required',
         'limit_points'          => 'required',
         'double'                => 'required',
         'limit_amount_nominal'  => 'required',
         'minimal'               => 'required',
         'double'                => 'required',
         'multiple'              => 'required',
      ];

      if ($productRule['rule_name'] == $this->request->getVar('rule_name')) {
         $rules['rule_name'] = substr($rules['rule_name'], 35);
      }

      if (!$this->validate($rules)) {
         return redirect()->back()->withInput();
      }

      $data = [
         'rule_name'            => $this->request->getVar('rule_name'),
         'limit_nominal'        => $this->request->getVar('limit_nominal'),
         'information'          => $this->request->getVar('information'),
         'limit_points'         => $this->request->getVar('limit_points'),
         'double'               => $this->request->getVar('double'),
         'minimal'              => $this->request->getVar('minimal'),
         'limit_amount_nominal' => $this->request->getVar('limit_amount_nominal'),
         'multiple'             => $this->request->getVar('multiple'),
      ];

      // Update product rule
      if ($this->productRulesModel->update($id, $data)) {
         session()->setFlashdata('pesan', 'Data product rule ' . $this->request->getVar('rule_name') . ' berhasil diubah');
      } else {
         session()->setFlashdata('gagal', 'Data product rule ' . $this->request->getVar('rule_name') . ' gagal diubah');
      }
      
      return redirect()->back();
   }

   public function addProduct()
   {
      $rules = [
         'product_name'     => 'is_unique[product.product_name]|required',
         'points'           => 'required|numeric',
      ];

      if (!$this->validate($rules)) {
         session()->setFlashdata('gagal', 'Product gagal ditambahkan');
         return redirect()->back()->withInput();
      }

      $data = [
         'product_name'  => $this->request->getVar('product_name'),
         'points'        => $this->request->getVar('points'),
         'information'   => $this->request->getVar('information'),
      ];

      // tambah product
      if ($this->productModel->save($data)) {
         session()->setFlashdata('pesan', 'Product ' . $this->request->getVar('product_name') . ' berhasil ditambahkan');
      } else {
         session()->setFlashdata('gagal', 'Product ' . $this->request->getVar('product_name') . ' gagal');
      }
      return redirect()->back();
   }

   public function addProductRule()
   {
      $rules = [
         'rule_name'            => 'is_unique[product_rules.rule_name]|required|alpha_numeric_space',
         'information'          => 'required',
         'limit_nominal'        => 'required',
         'limit_points'         => 'required',
         'double'               => 'required',
         'limit_amount_nominal' => 'required',
         'minimal'              => 'required',
         'double'               => 'required',
         'multiple'             => 'required',
      ];

      if (!$this->validate($rules)) {
         session()->setFlashdata('gagal', 'Product ' . $this->request->getVar('product_name') . ' gagal ditambahkan');
         return redirect()->back()->withInput();
      }

      $data = [
         'rule_name'              => $this->request->getVar('rule_name'),
         'limit_nominal'          => $this->request->getVar('limit_nominal'),
         'information'            => $this->request->getVar('information'),
         'limit_points'           => $this->request->getVar('limit_points'),
         'double'                 => $this->request->getVar('double'),
         'minimal'                => $this->request->getVar('minimal'),
         'limit_amount_nominal'   => $this->request->getVar('limit_amount_nominal'),
         'multiple'               => $this->request->getVar('multiple'),
      ];

      // tambah product
      if ($this->productRulesModel->save($data)) {
         session()->setFlashdata('pesan', 'Product ' . $this->request->getVar('product_name') . ' berhasil ditambahkan');
         return redirect()->back();
      }
   }

   public function deleteProduct($id)
   {
      $product = $this->productModel->find($id);

      if ($this->productModel->deleteProduct($id)) {
         session()->setFlashdata('gagal', 'Data product ' . $product['product_name'] . ' gagal dihapus');
         return redirect()->back();
      } else {
         session()->setFlashdata('pesan', 'Data product ' . $product['product_name'] . ' berhasil dihapus');
         return redirect()->back();
      }
   }

   public function deleteRulesProduct($id)
   {
      $productRules = $this->productRulesModel->find($id);

      if ($this->productRulesModel->deleteRuleProduct($id)) {
         session()->setFlashdata('gagal', 'Data Product rules ' . $productRules['rule_name'] . ' gagal dihapus');
         return redirect()->back();
      } else {
         session()->setFlashdata('pesan', 'Data Product rules ' . $productRules['rule_name'] . ' berhasil dihapus');
         return redirect()->back();
      }
   }

   // Project section ===============================================
   public function updateProject($id)
   {
      $project = $this->projectModel->find($id);

      $rules = [
         'project_name'     => 'is_unique[project.project_name]|required|alpha_numeric_space',
      ];

      if ($project['project_name'] == $this->request->getVar('project_name')) {
         $rules['project_name'] = substr($rules['project_name'], 32);
      }

      if (!$this->validate($rules)) {
         session()->setFlashdata('gagal', 'Data project gagal ditambahkan');
         return redirect()->back()->withInput();
      }

      $data = [
         'project_name' => $this->request->getVar('project_name'),
         'keterangan'   => $this->request->getVar('keterangan'),
      ];

      // Update User
      if ($this->projectModel->update($id, $data)) {
         session()->setFlashdata('pesan', 'Data user ' . $this->request->getVar('project_name') . ' berhasil diubah');
         return redirect()->back();
      }
   }
   
   // Project section ===============================================
   public function updateUnit($id)
   {
      $project = $this->unitsModel->find($id);

       $rules = [
         'unit_name'     => 'is_unique[units.unit_name]|required|alpha_numeric_space',
         'id'            => 'is_unique[units.id]|required|numeric',
      ];
      
     if ($project['unit_name'] == $this->request->getVar('unit_name')) {
         $rules['unit_name'] = substr($rules['unit_name'], 27);
     }
     
     if ($project['id'] == $this->request->getVar('id')) {
         $rules['id'] = substr($rules['id'], 20);
     }

      if (!$this->validate($rules)) {
         return redirect()->back()->withInput();
         session()->setFlashdata('gagal', 'unit gagal ditambahkan');
      }

      $data = [
         'unit_name' => $this->request->getVar('unit_name'),
         'id'   => $this->request->getVar('id'),
      ];

      // Update User
      if ($this->unitsModel->update($id, $data)) {
         session()->setFlashdata('pesan', 'Data unit ' . $this->request->getVar('unit_name') . ' berhasil diubah');
         return redirect()->back();
      } else {
          session()->setFlashdata('pesan', 'Data unit ' . $this->request->getVar('unit_name') . ' gagal diubah');
          return redirect()->back();
      }
   }

   public function deleteProject($id)
   {
      $project = $this->projectModel->find($id);

      if ($this->projectModel->deleteProject($id)) {
         session()->setFlashdata('gagal', 'Data project ' . $project['project_name'] . ' gagal dihapus');
         return redirect()->back();
      } else {
         session()->setFlashdata('pesan', 'Data project ' . $project['project_name'] . ' berhasil dihapus');
         return redirect()->back();
      }
   }
   
   public function deleteUnit($id)
   {
      $units = $this->unitsModel->find($id);

      if ($this->unitsModel->deleteUnit($id)) {
         session()->setFlashdata('gagal', 'Data project ' . $units['unit_name'] . ' gagal dihapus');
         return redirect()->back();
      } else {
         session()->setFlashdata('pesan', 'Data project ' . $units['unit_name'] . ' berhasil dihapus');
         return redirect()->back();
      }
   }


   public function addProject()
   {
      $rules = [
         'project_name'     => 'is_unique[project.project_name]|required|alpha_numeric_space',
      ];

      if (!$this->validate($rules)) {
         return redirect()->back()->withInput();
         session()->setFlashdata('gagal', 'product gagal ditambahkan');
      }

      $data = [
         'project_name' => $this->request->getVar('project_name'),
         'information'   => $this->request->getVar('information'),
      ];

      // tambah project
      if ($this->projectModel->save($data)) {
         session()->setFlashdata('pesan', 'project ' . $this->request->getVar('project_name') . ' berhasil ditambahkan');
      } else {
         session()->setFlashdata('gagal', 'project ' . $this->request->getVar('project_name') . ' gagal ditambahkan');
      }
      return redirect()->back();
   }
   
   public function addUnit()
   {
      $rules = [
         'unit_name'     => 'is_unique[units.unit_name]|required|alpha_numeric_space',
         'id'            => 'is_unique[units.id]|required|numeric',
      ];

      if (!$this->validate($rules)) {
         return redirect()->back()->withInput();
         session()->setFlashdata('gagal', 'unit gagal ditambahkan');
      }

      $data = [
         'unit_name' => $this->request->getVar('unit_name'),
         'id'   => $this->request->getVar('id'),
      ];
      
      $this->unitsModel->insert($data);
     
      // tambah project
      if ($this->unitsModel->save($data)) {
         session()->setFlashdata('pesan', 'Unit Cabang ' . $this->request->getVar('unit_name') . ' berhasil ditambahkan');
      } else {
         session()->setFlashdata('gagal', 'Unit Cabang ' . $this->request->getVar('unit_name') . ' gagal ditambahkan');
      }
      return redirect()->back();
   }

   public function deleteAllAcquisitions()
   {
      if ($this->acquisitionsModel->deleteAcquisitionsAll()) {
         session()->setFlashdata('gagal', 'Gagal hapus semua akuisisi');
         return redirect()->back();
      } else {
         session()->setFlashdata('pesan', 'Berhasil hapus semua akuisisi');
         return redirect()->back();
      }
   }

   // Export Akuisisi ===============================================
   public function exportAcquisitions()
   {

      //ambil data akuisisi
      $acquisitions = $this->acquisitionsModel->getAllAcquisitions()->get()->getResult('array');

      $spreadsheet = new Spreadsheet;    // Buat custom header pada file excel
      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setCellValue('A1', 'No');
      $sheet->setCellValue('B1', 'PRODUCT');
      $sheet->setCellValue('C1', 'PROJECT');
      $sheet->setCellValue('D1', 'NIP');
      $sheet->setCellValue('E1', 'UNIT CABANG');
      $sheet->setCellValue('F1', 'NAMA NASABAH');
      $sheet->setCellValue('G1', 'CIF');
      $sheet->setCellValue('H1', 'NO. REKENING');
      $sheet->setCellValue('I1', 'NOMINAL');
      $sheet->setCellValue('J1', 'TANGGAL AKUISISI');
      $sheet->setCellValue('K1', 'TANGGAL UPLOAD');

      // define kolom dan nomor
      $kolom = 2;
      $nomor = 1;

      // tambahkan data transaction ke dalam file excel
      foreach ($acquisitions as $data) {

         $sheet->setCellValue('A' . $kolom, $nomor);
         $sheet->setCellValue('B' . $kolom, $data['product_name']);
         $sheet->setCellValue('C' . $kolom, $data['project_name']);
         $sheet->setCellValue('D' . $kolom, $data['nip']);
         $sheet->setCellValue('E' . $kolom, $data['id_unit']);
         $sheet->setCellValue('F' . $kolom, $data['customer_name']);
         $sheet->setCellValue('G' . $kolom, $data['cif']);
         $sheet->setCellValue('H' . $kolom, $data['rekening']);
         $sheet->setCellValue('I' . $kolom, "Rp. " . number_format($data['nominal']));
         $sheet->setCellValue('J' . $kolom, $data['acquisitions_dates']);
         $sheet->setCellValue('K' . $kolom, $data['created_at']);

         $kolom++;
         $nomor++;
      }
      
      // download spreadsheet dalam bentuk excel .xlsx
      $writer = new Xlsx($spreadsheet);

      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="Akusisi.xlsx"');
      header('Cache-Control: max-age=0');

      ob_end_clean();
      $writer->save('php://output');
      exit();
   }

   public function exportPoinUser()
   {
      //ambil data akuisisi
      $user = $this->usersModel->getUsers();

      $spreadsheet = new Spreadsheet;    // Buat custom header pada file excel
      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setCellValue('A1', 'No');
      $sheet->setCellValue('B1', 'NIP');
      $sheet->setCellValue('C1', 'NAMA PEGAWAI');
      $sheet->setCellValue('D1', 'UNIT CABANG');
      $sheet->setCellValue('E1', 'POIN');
      // define kolom dan nomor
      $col = 2;
      $no = 1;

      // tambahkan data transaction ke dalam file excel
      foreach ($user as $u) {
         $sheet->setCellValue('A' . $col, $no);
         $sheet->setCellValue('B' . $col, $u['nip']);
         $sheet->setCellValue('C' . $col, $u['username']);
         $sheet->setCellValue('D' . $col, $u['id_unit']);
         $sheet->setCellValue('E' . $col, $this->acquisitionsCount($u['nip']));
         $col++;
         $no++;
      }

      // download spreadsheet dalam bentuk excel .xlsx
      $writer = new Xlsx($spreadsheet);

      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="PoinUser.xlsx"');
      header('Cache-Control: max-age=0');

      ob_end_clean();
      $writer->save('php://output');
      exit();
   }

   // ===============================================================

   //Hitung Akuisisi user
   private function acquisitionsCount($nip)
   {
      $acquisitions = $this->acquisitionsModel->getUserAcquisitions($nip);
      $doubleByDateModel = $this->doubleByDateModel->getAllDate();
      $point = 0;
      $point_final = 0;

      foreach ($acquisitions as $a) :
         $rules = $this->productModel->getProductRules($a['id_product']);
         $point = $a['points'];
         foreach ($rules as $r) :
            if ($r['minimal'] == 1) {
               $point = $this->minimal($a['nominal'], $r['limit_nominal'], $point, $r['limit_point']);
            }

            if ($r['limit_amount_nominal'] == 1) {
               $point = $this->limit($a['nominal'], $r['limit_nominal'], $point, $r['limit_point']);
            }

            if ($r['multiple'] == 1) {
               $point = $this->multiple($a['nominal'], $r['limit_nominal'], $point);
            }

            if ($r['double'] == 1) {
               $point = $this->double($point);
            }

            if ($a['lead_sources'] == 'LMS') {
               $point = $this->double($point);
            }
         endforeach;

         //cek tanggal
         foreach ($doubleByDateModel as $d) :
            if ($d['dates'] == $a['acquisitions_dates']) {
               $point = $this->double($point);
            }
         endforeach;

         $point_final += $point;

         if ($point_final == 0) {
            $point_final = $point;
         }
      endforeach;

      return $point_final;
   }

   private function multiple($nominal, $limit, $point)
   {
      if ($nominal > $limit) {
         $point = $nominal / $limit;
         $point *= floor($point);
      }

      return $point;
   }

   private function limit($nominal, $limit, $point, $pointLimit)
   {
      if ($nominal > $limit) {
         $point = $pointLimit;
      }

      return $point;
   }

   private function minimal($nominal, $limit, $point)
   {
      if ($nominal < $limit) {
         $point = 0;
      }
      return $point;
   }

   private function double($point)
   {
      $point *= 2;
      return $point;
   }
}