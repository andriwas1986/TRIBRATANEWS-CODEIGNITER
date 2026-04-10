<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\PostModel;
use App\Models\SettingsModel;
use App\Models\TagModel;

class CategoryController extends BaseAdminController
{
    protected $categoryModel;
    protected $tagModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->categoryModel = new CategoryModel();
        $this->tagModel = new TagModel();
    }

    /**
     * Categories
     */
    public function addCategory()
    {
        checkPermission('categories');
        $data['title'] = trans("add_category");
        $data['parentCategories'] = $this->categoryModel->getParentCategoriesByLang($this->activeLang->id);
        $data['panelSettings'] = panelSettings();
        $data['type'] = inputGet('type');
        if (empty($data['type']) || $data['type'] != 'sub') {
            $data['type'] = 'parent';
        }
        $settingsModel = new SettingsModel();
        $data['widgets'] = $settingsModel->getWidgets();

        echo view('admin/includes/_header', $data);
        echo view('admin/category/add_category', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Add Category Post
     */
    public function addCategoryPost()
    {
        checkPermission('categories');
        $type = inputPost('type');
        if (empty($type) || $type != 'sub') {
            $type = 'parent';
        }
        $val = \Config\Services::validation();
        $val->setRule('name', trans("category_name"), 'required|max_length[200]');
        $data['panelSettings'] = panelSettings();
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->to(adminUrl('add-category?type=' . $type))->withInput();
        } else {
            if ($this->categoryModel->addCategory($type)) {
                setSuccessMessage("msg_added");
                resetCacheDataOnChange();
            } else {
                setErrorMessage("msg_error");
            }
        }
        return redirect()->to(adminUrl('add-category?type=' . $type));
    }

    /**
     * Categories
     */
    public function categories()
    {
        checkPermission('categories');
        $data['title'] = trans("categories");
        $data['panelSettings'] = panelSettings();
        $numRows = $this->categoryModel->getCategoriesCount();
        $data['pager'] = paginate($this->perPage, $numRows);
        $data['categories'] = $this->categoryModel->getCategoriesPaginated($this->perPage, $data['pager']->offset);

        $langId = clrNum(inputGet('lang_id'));
        if (!empty($langId)) {
            $data['parentCategories'] = $this->categoryModel->getParentCategoriesByLang($langId);
        } else {
            $data['parentCategories'] = $this->categoryModel->getParentCategories();
        }

        echo view('admin/includes/_header', $data);
        echo view('admin/category/categories', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Edit Category
     */
    public function editCategory($id)
    {
        checkPermission('categories');
        $data['title'] = trans("update_category");
        $data['panelSettings'] = panelSettings();
        $data['category'] = $this->categoryModel->getCategory($id);
        if (empty($data['category'])) {
            return redirect()->to(adminUrl('categories'));
        }
        $data['parentCategories'] = $this->categoryModel->getParentCategoriesByLang($data['category']->lang_id);
        $settingsModel = new SettingsModel();
        $data['widgets'] = $settingsModel->getWidgets();

        echo view('admin/includes/_header', $data);
        echo view('admin/category/edit_category', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Edit Category Post
     */
    public function editCategoryPost()
    {
        checkPermission('categories');
        $val = \Config\Services::validation();
        $val->setRule('name', trans("category_name"), 'required|max_length[200]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            redirectToBackURL();
        } else {
            $id = inputPost('id');
            if ($this->categoryModel->editCategory($id)) {
                setSuccessMessage("msg_updated");
                resetCacheDataOnChange();
                redirectToBackURL();
            }
        }
        setErrorMessage("msg_error");
        redirectToBackURL();
    }

    /**
     * Subcategories
     */
    public function subCategories()
    {
        checkPermission('categories');
        $data['title'] = trans("subcategories");
        $data['categories'] = $this->categoryModel->getSubCategories();
        $data['panelSettings'] = panelSettings();
        $data['parentCategories'] = $this->categoryModel->getParentCategoriesByLang($this->activeLang->id);
        $data['langSearchColumn'] = 2;

        echo view('admin/includes/_header', $data);
        echo view('admin/category/subcategories', $data);
        echo view('admin/includes/_footer');
    }

    //get parent categories by language
    public function getParentCategoriesByLang()
    {
        $langId = inputPost('lang_id');
        if (!empty($langId)) {
            $categories = $this->categoryModel->getParentCategoriesByLang($langId);
        } else {
            $categories = $this->categoryModel->getParentCategories();
        }
        if (!empty($categories)) {
            foreach ($categories as $item) {
                echo '<option value="' . $item->id . '">' . $item->name . '</option>';
            }
        }
    }

    //get subcategories
    public function getSubCategories()
    {
        $parentId = inputPost('parent_id');
        if (!empty($parentId)) {
            $subCategories = $this->categoryModel->getSubCategoriesByParentId($parentId);
            foreach ($subCategories as $item) {
                echo '<option value="' . $item->id . '">' . $item->name . '</option>';
            }
        }
    }

    /**
     * Delete Category Post
     */
    public function deleteCategoryPost()
    {
        if (!hasPermission('categories')) {
            exit();
        }
        $id = inputPost('id');
        if (!empty($this->categoryModel->getSubCategoriesByParentId($id))) {
            setErrorMessage("msg_delete_subcategories");
            exit();
        }
        $postModel = new PostModel();
        $categories = $this->categoryModel->getCategories();
        $categoryTree = getCategoryTree($id, $categories);
        if (!empty($postModel->getPostCountByCategory($id, $categoryTree))) {
            setErrorMessage("msg_delete_posts");
            exit();
        }
        if ($this->categoryModel->deleteCategory($id)) {
            setSuccessMessage("msg_deleted");
            resetCacheDataOnChange();
        } else {
            setErrorMessage("msg_error");
        }
    }

    /*
     * --------------------------------------------------------------------
     * FITUR BARU: EXPORT & IMPORT KATEGORI (EXCEL/CSV FIX)
     * --------------------------------------------------------------------
     */

    public function exportCategories()
    {
        if (!authCheck() || !isAdmin()) return redirect()->to(adminUrl('login'));

        $db = \Config\Database::connect();
        $categories = $db->table('categories')->orderBy('id', 'ASC')->get()->getResultArray();

        $filename = 'kategori_excel_' . date('Y-m-d_H-i') . '.csv';

        // [FIX] Bersihkan output buffer bawaan server agar download tidak blank/gagal
        if (ob_get_length()) {
            ob_end_clean();
        }

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: text/csv; charset=UTF-8");

        $file = fopen('php://output', 'w');

        // [MANTRA 1] Tambahkan karakter BOM (Byte Order Mark) agar Excel otomatis mendeteksi font dan tabel
        fputs($file, "\xEF\xBB\xBF");

        if (count($categories) > 0) {
            $header = array_keys($categories[0]);
            // [MANTRA 2] Gunakan Titik Koma (;) karena standar Excel Indonesia membacanya sebagai pemisah kolom
            fputcsv($file, $header, ';');

            foreach ($categories as $row) {
                fputcsv($file, $row, ';');
            }
        } else {
            // Header Default Jika Kosong
            fputcsv($file, ['id', 'lang_id', 'name', 'slug', 'description', 'parent_id', 'category_order', 'color', 'category_status', 'show_on_menu', 'block_type'], ';');
        }
        
        fclose($file);
        exit;
    }

    public function importCategories()
    {
        if (!authCheck() || !isAdmin()) return redirect()->to(adminUrl('login'));

        $file = $this->request->getFile('file_csv');
        
        if ($file && $file->isValid() && $file->getExtension() == 'csv') {
            $filepath = $file->getTempName();
            $fileHandle = fopen($filepath, "r");
            
            // [Deteksi Otomatis] Cek apakah user save di Excel pakai Titik Koma (;) atau Koma (,)
            $firstLine = fgets($fileHandle);
            $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';
            rewind($fileHandle); // Kembalikan ke baris pertama

            // Baca Header
            $header = fgetcsv($fileHandle, 10000, $delimiter); 
            // Bersihkan karakter BOM tersembunyi di awal nama kolom agar tidak terjadi error mapping
            if(isset($header[0])) {
                $header[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header[0]);
            }

            $db = \Config\Database::connect();
            $count = 0;

            while (($data = fgetcsv($fileHandle, 10000, $delimiter)) !== FALSE) {
                // Pastikan jumlah kolom baris sama dengan jumlah kolom header
                if (count($header) === count($data)) {
                    $row = array_combine($header, $data);
                    
                    if (!empty($row['name'])) { 
                        $insertData = [
                            'lang_id'        => $row['lang_id'] ?? 1,
                            'name'           => $row['name'],
                            'slug'           => !empty($row['slug']) ? $row['slug'] : strSlug($row['name']),
                            'description'    => $row['description'] ?? '',
                            'parent_id'      => $row['parent_id'] ?? 0,
                            'category_order' => $row['category_order'] ?? 1,
                            'color'          => $row['color'] ?? '#00a8ff'
                        ];

                        // Menyesuaikan beberapa field opsi tambahan bila ada
                        if (isset($row['category_status'])) $insertData['category_status'] = $row['category_status'];
                        if (isset($row['show_on_menu'])) $insertData['show_on_menu'] = $row['show_on_menu'];
                        if (isset($row['block_type'])) $insertData['block_type'] = $row['block_type'];

                        // [Mencegah Dobel] Cek Duplikat berdasarkan SLUG
                        $existing = $db->table('categories')->where('slug', $insertData['slug'])->get()->getRow();
                        
                        if ($existing) {
                            // Jika sudah ada, TIMPA/UPDATE Kategori Lama
                            $db->table('categories')->where('id', $existing->id)->update($insertData);
                        } else {
                            // Jika belum ada, MASUKKAN Kategori Baru
                            $db->table('categories')->insert($insertData);
                        }
                        $count++;
                    }
                }
            }
            fclose($fileHandle);
            
            // Wajib: Reset Cache agar menu Kategori di Frontend terupdate otomatis
            resetCacheDataOnChange(); 
            
            return redirect()->back()->with('success', "$count Kategori berhasil diproses (Di-update/Ditambahkan)!");
        }
        
        return redirect()->back()->with('error', "Gagal! Pastikan file berakhiran .csv");
    }

    /*
     * --------------------------------------------------------------------
     * Tags
     * --------------------------------------------------------------------
     */

    /**
     * Tags
     */
    public function tags()
    {
        checkPermission('tags');
        $data['title'] = trans("tags");
        $data['panelSettings'] = panelSettings();
        $numRows = $this->tagModel->getTagsCount();
        $data['pager'] = paginate($this->perPage, $numRows);
        $data['tags'] = $this->tagModel->getTagsPaginated($this->perPage, $data['pager']->offset);

        echo view('admin/includes/_header', $data);
        echo view('admin/tag/tags', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Add Tag Post
     */
    public function addTagPost()
    {
        checkPermission('tags');
        $tag = inputPost('tag');
        $langId = inputPost('lang_id');

        if ($this->tagModel->addTag($tag, $langId)) {
            setSuccessMessage("msg_added");
        } else {
            setErrorMessage("msg_tag_exists");
        }
        redirectToBackURL();
    }

    /**
     * Edit Tag Post
     */
    public function editTagPost()
    {
        checkPermission('tags');
        $id = inputPost('id');
        $tag = inputPost('tag');
        $langId = inputPost('lang_id');

        if ($this->tagModel->editTag($id, $tag, $langId)) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackURL();
    }

    /**
     * Delete Tag Post
     */
    public function deleteTagPost()
    {
        checkPermission('tags');
        $id = inputPost('id');
        if ($this->tagModel->deleteTag($id)) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackURL();
    }
}