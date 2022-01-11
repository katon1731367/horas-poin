<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\ProjectModel;
use App\Models\AcquisitionsModel;
use App\Models\ProductRulesModel;
use App\Models\UsersModel;
use App\Models\UnitsModel;
use App\Models\DoubleByDateModel;
use DateTime;

class User extends BaseController
{
   protected $productModel;
   protected $productRulesModel;
   protected $projectModel;
   protected $acquisitionsModel;
   protected $usersModel;
   protected $units;
   protected $doubleByDateModel;

   public function __construct()
   {
      $this->productModel = new ProductModel();
      $this->productRulesModel = new ProductRulesModel();
      $this->projectModel = new ProjectModel();
      $this->acquisitionsModel = new AcquisitionsModel();
      $this->usersModel = new UsersModel();
      $this->units = new UnitsModel();
      $this->doubleByDateModel = new DoubleByDateModel();
   }

   public function index()
   {
      $product = $this->productModel->findAll();
      $project = $this->projectModel->findAll();
      $acquisitions = $this->acquisitionsModel->getUserAcquisitions(user()->nip)->get()->getResult('array');
      $keyword = $this->request->getVar('keyword');
      $patriotDay = $this->doubleByDateModel->getAllDate();

      $countAcquisitions = count($acquisitions);
      $countPoint = $this->acquisitionsCount();

      $visitation = [
         'Akuisisi', 'Visit'
      ];

      $leadSources = [
         'Non LMS', 'LMS'
      ];

      $costumerType = [
         'Existing', 'New'
      ];

      $status = [
         'Follow Up', 'Booking / Issued / Cair'
      ];

      $data = [
         'title'             => 'Horas Poin',
         'validation'        => $this->validation,
         'product'           => $product,
         'project'           => $project,
         'visitation'        => $visitation,
         'leadSources'       => $leadSources,
         'status'            => $status,
         'costumerType'      => $costumerType,
         'countPoint'        => $countPoint,
         'countAcquisitions' => $countAcquisitions,
         'keyword'           => $keyword,
         'patriotDay'        => $patriotDay
      ];

      if ($this->usersModel->getUserGroup(user()->id) == 1) {
         redirect()->to(base_url('admin/'));
      } else {
         return view('user/index', $data);
      }
   }

   public function editProfile()
   {
      $countAdmin = $this->usersModel->countAdmin();
      $units = $this->units->findAll();

      $data = [
         'title' => 'Edit Profile',
         'user' => $this->usersModel->getUser(user()->id),
         'validation' => $this->validation,
         'units' => $units
      ];

      return view('user/editProfile', $data);
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
         $fileImage->move('img/user_image/', $imageName);
         //hapus file sampul lama

         //cek gambar default
         if ($user->user_image != 'default.svg') {
            unlink('img/user_image/' . $this->request->getVar('image_user_old'));
         }
      }

      $data = [
         'username'     => $this->request->getVar('username'),
         'nip'          => $this->request->getVar('nip'),
         'id_unit'      => $this->request->getVar('id_unit'),
         'email'        => $this->request->getVar('email'),
         'fullname'     => $this->request->getVar('fullname'),
         'user_image'   => $imageName
      ];

      // Update User
      if ($this->usersModel->update($id, $data)) {
         session()->setFlashdata('pesan', 'Data user ' . $this->request->getVar('username') . ' berhasil diubah');
         return redirect()->back();
      }
   }

   // encripsi password
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

   // Update password user
   public function updatePass($id)
   {
      $rules = [
         'old_password'    => 'required',
         'password'        => 'required|strong_password',
         'pass_confirm'    => 'required|matches[password]'
      ];

      if (!$this->validate($rules)) {
         session()->setFlashdata('gagal', 'Validasi gagal');
         return redirect()->back()->withInput();
      }

      $old_password = trim($this->request->getVar('old_password'));

      if (!password_verify(base64_encode(hash('sha384', $old_password, true)), user()->password_hash)) {
         session()->setFlashdata('gagal', 'Password lama tidak sesuai');
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

   // Edit Akuisisi Pengguna
   public function editAcquisitions($id)
   {
      $acquisitions = $this->acquisitionsModel->getAcquisitionsId($id);
      $product = $this->productModel->findAll();
      $project = $this->projectModel->findAll();

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
         'title'        => 'Edit Akuisisi',
         'product'      => $product,
         'project'      => $project,
         'acquisitions' => $acquisitions,
         'visitation'   => $visitation,
         'leadSources'  => $leadSources,
         'status'       => $status,
         'customerType' => $customerType,
         'validation'   => $this->validation,
      ];

      return view('user/editAcquisitions', $data);
   }

   //--------------------------------------------------------------------

   // Submit Akuisisi user
   public function submitAcquisitions()
   {
      //validate input
      if (!$this->validate([
         'customer_name' => [
            'rules' => 'required|is_unique[acquisitions_data.customer_name]',
            'errors' => [
               'required' => 'Nama nasabah harus diisi',
               'is_unique' => 'Nama nasabah sudah terdaftar'
            ]
         ],
         'product' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Jenis Produk harus diisi',
            ]
         ],
         'project' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Jenis project harus diisi',
            ]
         ],
         'visitation' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Form ini harus diisi',
            ]
         ],
         'lead_sources' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Form ini harus diisi',
            ]
         ],
         'costumer_type' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Form ini harus diisi',
            ]
         ],
         'status' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Form ini harus diisi',
            ]
         ],
         'rekening' => [
            'rules' => 'required|is_unique[acquisitions_data.rekening]|exact_length[13]|numeric',
            'errors' => [
               'required' => 'Nomor rekening harus diisi',
               'is_unique' => 'Nomor rekening komik sudah terdaftar',
               'exact_length' => 'Panjang nomor rekening harus 13 Karakter',
               'numeric' => 'Nomor rekening harus berupa angka'
            ]
         ],
         'cif' => [
            'rules' => 'required|is_unique[acquisitions_data.cif]|numeric|max_length[11]',
            'errors' => [
               'required' => 'Nomor CIF harus diisi',
               'is_unique' => 'Nomor CIF sudah terdaftar',
               'numeric' => 'Nomor CIF harus berupa angka',
               'max_lenght' => 'Nomor CIF maksimal 11 Karakter'
            ]
         ],
         'handphone' => [
            'rules' => 'required|is_unique[acquisitions_data.no_handphone]|numeric|max_length[13]',
            'errors' => [
               'required' => 'Nomor telepon harus diisi',
               'is_unique' => 'Nomor telepon sudah terdaftar',
               'numeric' => 'Nomor telepon harus berupa angka',
               'max_lenght' => 'Nomor telepon maksimal 13 Karakter'
            ]
         ],
         'acquisitions_dates' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Tanggal Akuisisi telepon harus diisi'
            ]
         ],
      ])) {
         session()->setFlashdata('pesan', 'Upload akuisisi ' . $this->request->getVar('customer_name') . ' gagal dilakukan');

         return redirect()->to('/user')->withInput();
      }
      
      $data = [
      'id_product' => $this->request->getVar('product'),
         'nip' => user()->nip,
         'id_project' => $this->request->getVar('project'),
         'nominal' => $this->request->getVar('nominal'),
         'customer_name' => $this->request->getVar('customer_name'),
         'visitation' => $this->request->getVar('visitation'),
         'rekening' => $this->request->getVar('rekening'),
         'acquisitions_dates' => $this->request->getVar('acquisitions_dates'),
         'cif' => $this->request->getVar('cif'),
         'no_handphone' => $this->request->getVar('handphone'),
         'lead_sources' => $this->request->getVar('lead_sources'),
         'costumer_type' => $this->request->getVar('costumer_type'),
         'status' => $this->request->getVar('status')    
      ];
      
      if ($this->acquisitionsModel->save($data)) {
         session()->setFlashdata('pesan', 'Akuisisi atas nama nasabah ' . $this->request->getVar('customer_name') . ' berhasil ditambahkan');
      } else {
         ession()->setFlashdata('pesan', 'Upload akuisisi ' . $this->request->getVar('customer_name') . ' gagal dilakukan');
      }
      
      return redirect()->to('/user');
   }

   //hapus akuisisi user
   public function deleteAcquisitions($id)
   {
      if ($this->acquisitionsModel->deleteAcquisitions($id)) {
         session()->setFlashdata('gagal', 'Data akuisisi gagal dihapus');
         return redirect()->back();
      } else {
         session()->setFlashdata('pesan', 'Data akuisisi berhasil dihapus');
         return redirect()->back();
      }
   }

   // Update akuisisi user
   public function updateAcquisitions($id)
   {
      $acquisitions = $this->acquisitionsModel->getAcquisitionsId($id);

      $rules = [
         'customer_name' => [
            'rules' => 'is_unique[acquisitions_data.customer_name]|required',
            'errors' => [
               'required' => 'Nama nasabah harus diisi',
               'is_unique' => 'Nama nasabah sudah terdaftar'
            ]
         ],
         'product' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Jenis produk harus diisi',
            ]
         ],
         'project' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Jenis project harus diisi',
            ]
         ],
         'visitation' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Form ini harus diisi',
            ]
         ],
         'lead_sources' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Form ini harus diisi',
            ]
         ],
         'customer_type' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Form ini harus diisi',
            ]
         ],
         'status' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Form ini harus diisi',
            ]
         ],
         'rekening' => [
            'rules' => 'is_unique[acquisitions_data.rekening]|required|exact_length[13]|numeric',
            'errors' => [
               'required' => 'Nomor rekening harus diisi',
               'is_unique' => 'Nomor rekening komik sudah terdaftar',
               'exact_length' => 'Panjang nomor rekening harus 13 Karakter',
               'numeric' => 'Nomor rekening harus berupa angka'
            ]
         ],
         'cif' => [
            'rules' => 'is_unique[acquisitions_data.cif]|required|numeric|max_length[11]',
            'errors' => [
               'required' => 'Nomor CIF harus diisi',
               'is_unique' => 'Nomor CIF sudah terdaftar',
               'numeric' => 'Nomor CIF harus berupa angka',
               'max_lenght' => 'Nomor CIF maksimal 11 Karakter'
            ]
         ],
         'handphone' => [
            'rules' => 'is_unique[acquisitions_data.no_handphone]|required|numeric|max_length[13]',
            'errors' => [
               'required' => 'Nomor telepon harus diisi',
               'is_unique' => 'Nomor telepon sudah terdaftar',
               'numeric' => 'Nomor telepon harus berupa angka',
               'max_lenght' => 'Nomor telepon maksimal 13 Karakter'
            ]
         ],
         'acquisitions_dates' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Tanggal Akuisisi telepon harus diisi'
            ]
         ],
      ];

      if ($acquisitions['customer_name'] == $this->request->getVar('customer_name')) {
         $rules['customer_name']['rules'] = substr($rules['customer_name']['rules'], 43);
      }

      if ($acquisitions['rekening'] == $this->request->getVar('rekening')) {
         $rules['rekening']['rules'] = substr($rules['rekening']['rules'], 38);
      }

      if ($acquisitions['cif'] == $this->request->getVar('cif')) {
         $rules['cif']['rules'] = substr($rules['cif']['rules'], 33);
      }

      if ($acquisitions['no_handphone'] == $this->request->getVar('handphone')) {
         $rules['handphone']['rules'] = substr($rules['handphone']['rules'], 42);
      }

      //validate input
      if (!$this->validate($rules)) {
         session()->setFlashdata('gagal', 'Update akuisisi ' . $this->request->getVar('customer_name') . ' gagal dilakukan');
      }

      $data = [
         'id_product' => $this->request->getVar('product'),
         'nip' => user()->nip,
         'id_project' => $this->request->getVar('project'),
         'nominal' => $this->request->getVar('nominal'),
         'customer_name' => $this->request->getVar('customer_name'),
         'visitation' => $this->request->getVar('visitation'),
         'rekening' => $this->request->getVar('rekening'),
         'acquisitions_dates' => $this->request->getVar('acquisitions_dates'),
         'cif' => $this->request->getVar('cif'),
         'no_handphone' => $this->request->getVar('handphone'),
         'lead_sources' => $this->request->getVar('lead_sources'),
         'customer_type' => $this->request->getVar('customer_type'),
         'status' => $this->request->getVar('status')
      ];

      // Update User
      if ($this->acquisitionsModel->update($id, $data)) {
         session()->setFlashdata('pesan', 'Akusisi atas nama ' . $this->request->getVar('customer_name') . ' berhasil diubah');
         return redirect()->back();
      }
   }

   // Ajax filter user
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
         $acquisitions = $this->acquisitionsModel->searchAcquisitions(user()->nip, $keyword)->get()->getResult('array');
      } else {
         $acquisitions = $this->acquisitionsModel->getUserAcquisitions(user()->nip)->get()->getResult('array');
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
                     'product_name' => $a['product_name'],
                     'id_project' => $a['id_project'],
                     'project_name' => $a['project_name'],
                     'id_product' => $a['id_product'],
                     'customer_name' => $a['customer_name'],
                     'cif' => $a['cif'],
                     'rekening' => $a['rekening'],
                     'acquisitions_dates' => $a['acquisitions_dates'],
                     'updated_at' => $a['updated_at'],
                     'created_at' => $a['created_at'],
                     'nominal' => $a['nominal'],
                  ];
                  $acquisitionsFiltered[$i] = $data;
               }
            }
         } elseif ($from != '' && $to != '') {
            if (($dateEarly <= $date && $dateEnd >= $date) ||($dateEnd == $date || $dateEarly == $date)) {
               $data = [
                  'id' => $a['id'],
                  'product_name' => $a['product_name'],
                  'id_project' => $a['id_project'],
                  'project_name' => $a['project_name'],
                  'id_product' => $a['id_product'],
                  'customer_name' => $a['customer_name'],
                  'cif' => $a['cif'],
                  'rekening' => $a['rekening'],
                  'acquisitions_dates' => $a['acquisitions_dates'],
                  'updated_at' => $a['updated_at'],
                  'created_at' => $a['created_at'],
                  'nominal' => $a['nominal'],
               ];
               $acquisitionsFiltered[$i] = $data;
            }
         } elseif ($product != '') {
            if ($a['id_product'] == $product) {
               $data = [
                  'id' => $a['id'],
                  'product_name' => $a['product_name'],
                  'id_project' => $a['id_project'],
                  'project_name' => $a['project_name'],
                  'id_product' => $a['id_product'],
                  'customer_name' => $a['customer_name'],
                  'cif' => $a['cif'],
                  'rekening' => $a['rekening'],
                  'acquisitions_dates' => $a['acquisitions_dates'],
                  'updated_at' => $a['updated_at'],
                  'created_at' => $a['created_at'],
                  'nominal' => $a['nominal'],
               ];
               $acquisitionsFiltered[$i] = $data;
            }
         } else {
            $data = [
                  'id' => $a['id'],
                  'product_name' => $a['product_name'],
                  'id_project' => $a['id_project'],
                  'project_name' => $a['project_name'],
                  'id_product' => $a['id_product'],
                  'customer_name' => $a['customer_name'],
                  'cif' => $a['cif'],
                  'rekening' => $a['rekening'],
                  'acquisitions_dates' => $a['acquisitions_dates'],
                  'updated_at' => $a['updated_at'],
                  'created_at' => $a['created_at'],
                  'nominal' => $a['nominal'],
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

      return view('ajax_view/acquisitions_table', $data);
   }

   // Pagination table akuisisi halaman index user
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
      $acquisitions = $this->acquisitionsModel->getAcquisitionsPaginate($limit, $start_from, user()->nip);
      $totalData = count($this->acquisitionsModel->findAll());
      $totalPage = ceil($totalData / $limit);

      $data = [
         'acquisitions' => $acquisitions,
         'totalPage' => $totalPage,
         'page' => $page,
         'start' => $start_from
      ];

      return view('ajax_view/acquisitions_table', $data);
   }

   //Hitung Akuisisi user
   private function acquisitionsCount()
   {
      $acquisitions = $this->acquisitionsModel->getUserAcquisitions(user()->nip)->get()->getResult('array');
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
