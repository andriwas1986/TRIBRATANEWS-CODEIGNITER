<?php

namespace App\Controllers;

use App\Models\AuthModel;
use App\Models\CategoryModel;
use App\Models\CommonModel;
use App\Models\EmailModel;
use App\Models\LanguageModel;
use App\Models\NewsletterModel;
use App\Models\PageModel;
use App\Models\PollModel;
use App\Models\PostAdminModel;
use App\Models\PostModel;
use App\Models\RewardModel;
use App\Models\SettingsModel;
use App\Models\SitemapModel;
use Config\Globals;

class AdminController extends BaseAdminController
{
    protected $postAdminModel;
    protected $settingsModel;
    protected $pageModel;
    protected $authModel;
    protected $commonModel;
    protected $newsletterModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->postAdminModel = new PostAdminModel();
        $this->settingsModel = new SettingsModel();
        $this->pageModel = new PageModel();
        $this->authModel = new AuthModel();
        $this->commonModel = new CommonModel();
        $this->newsletterModel = new NewsletterModel();

        if (checkCronTime(1)) {
            //delete old posts
            $this->postAdminModel->deleteOldPosts();
            //delete old page views
            $postModel = new PostModel();
            $postModel->deleteOldPageviews();
            //delete old sessions
            $this->settingsModel->deleteOldSessions();
            //update cron time
            $this->settingsModel->setLastCronUpdate();
        }
    }

    /**
     * Index Page
     */
    public function index()
    {
        checkPermission('admin_panel');
        $data['title'] = trans("index");
        $data['latestComments'] = $this->commonModel->getLatestComments(1, 5);
        $data['latestPendingComments'] = $this->commonModel->getLatestComments(0, 5);
        $data['latestContactMessages'] = $this->commonModel->getContactMessages(5);
        $data['latestUsers'] = $this->authModel->getLatestUsers();
        $data['postsCount'] = $this->postAdminModel->getPostsCount();
        $data['pendingPostsCount'] = $this->postAdminModel->getPendingPostsCount();
        $data['draftsCount'] = $this->postAdminModel->getDraftsCount();
        $data['scheduledPostsCount'] = $this->postAdminModel->getScheduledPostsCount();
        $data['panelSettings'] = panelSettings();

        $this->commonModel->fixNullRecords();

        echo view('admin/includes/_header', $data);
        if ($this->adminTheme == 'wordpress') {
             echo view('admin/dashboard/theme_wordpress', $data);
        } elseif ($this->adminTheme == 'modern') {
             echo view('admin/dashboard/theme_modern', $data);
        } else {
             echo view('admin/index', $data);
        }
        echo view('admin/includes/_footer');
    }

    /**
     * Navigation
     */
    public function navigation()
    {
        checkPermission('navigation');
        $data["selectedLang"] = inputGet("lang");
        if (empty($data["selectedLang"])) {
            $data["selectedLang"] = $this->activeLang->id;
            return redirect()->to(adminUrl('navigation?lang=' . $data["selectedLang"]));
        }
        $data['title'] = trans("navigation");
        $data['panelSettings'] = panelSettings();
        $data['menuLinks'] = $this->pageModel->getMenuLinks($data["selectedLang"]);

        echo view('admin/includes/_header', $data);
        echo view('admin/navigation/navigation', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Add Menu Link Post
     */
    public function addMenuLinkPost()
    {
        checkPermission('navigation');
        $val = \Config\Services::validation();
        $val->setRule('title', trans("title"), 'required|max_length[500]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            if ($this->pageModel->addLink()) {
                setSuccessMessage("msg_added");
            } else {
                setErrorMessage("msg_error");
                return redirect()->back()->withInput();
            }
        }
        return redirect()->to(adminUrl('navigation?lang=' . $this->activeLang->id));
    }

    /**
     * Update Menu Link
     */
    public function editMenuLink($id)
    {
        checkPermission('navigation');
        $data['title'] = trans("navigation");
        $data['page'] = $this->pageModel->getPageById($id);
        if (empty($data['page'])) {
            return redirect()->to(adminUrl('navigation'));
        }
        $data['panelSettings'] = panelSettings();
        $data['menuLinks'] = $this->pageModel->getMenuLinks($data["page"]->lang_id);

        echo view('admin/includes/_header', $data);
        echo view('admin/navigation/edit_link', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Update Men��� Link Post
     */
    public function editMenuLinkPost()
    {
        checkPermission('navigation');
        $val = \Config\Services::validation();
        $val->setRule('title', trans("title"), 'required|max_length[500]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            $id = inputPost('id');
            if ($this->pageModel->editLink($id)) {
                setSuccessMessage("msg_updated");
            } else {
                setErrorMessage("msg_error");
            }
        }
        return redirect()->to(adminUrl('navigation?lang=' . $this->activeLang->id));
    }

    /**
     * Sort Menu Items
     */
    public function sortMenuItems()
    {
        checkPermission('navigation');
        $this->pageModel->sortMenuItems();
    }

    /**
     * Hide Show Home Link
     */
    public function hideShowHomeLink()
    {
        checkPermission('navigation');
        $this->pageModel->hideShowHomeLink();
    }

    /**
     * Delete Navigation Post
     */
    public function deleteNavigationPost()
    {
        if (!hasPermission('navigation')) {
            exit();
        }
        $id = inputPost('id');
        $data["page"] = $this->pageModel->getPageById($id);
        if (!empty($data['page'])) {
            if (!empty($this->pageModel->getSubpages($id))) {
                setErrorMessage("msg_delete_subpages");
                exit();
            }
            if ($this->pageModel->deletePage($id)) {
                setSuccessMessage("msg_deleted");
            } else {
                setErrorMessage("msg_error");
            }
        }
    }

    /**
     * Menu Limit Post
     */
    public function menuLimitPost()
    {
        checkPermission('navigation');
        if ($this->pageModel->updateMenuLimit()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackURL();
    }

    /**
     * Themes
     */
    public function themes()
    {
        checkSuperAdmin();
        
        // Auto-seed JNews themes if missing
        $db = \Config\Database::connect();
        $check = $db->table('themes')->where('theme', 'jnews_tech')->get()->getRow();
        if (empty($check)) {
            $this->seedThemes();
        }

        $data['title'] = trans("themes");
        $data['themes'] = $this->settingsModel->getThemes();

        echo view('admin/includes/_header', $data);
        echo view('admin/themes', $data);
        echo view('admin/includes/_footer');
    }

    public function seedThemes()
    {
        checkSuperAdmin();
        $db = \Config\Database::connect();
        $themes = [
            [
                'theme' => 'jnews_tech',
                'theme_name' => 'Elite Tech',
                'theme_folder' => 'jnews_tech',
                'theme_color' => '#1e3799',
                'block_color' => '#1e3799',
                'mega_menu_color' => '#0a3d62',
                'is_active' => 0
            ],
            [
                'theme' => 'jnews_lifestyle',
                'theme_name' => 'Luxury Lifestyle',
                'theme_folder' => 'jnews_lifestyle',
                'theme_color' => '#60a3bc',
                'block_color' => '#60a3bc',
                'mega_menu_color' => '#3c6382',
                'is_active' => 0
            ],
            [
                'theme' => 'jnews_viral',
                'theme_name' => 'National Viral',
                'theme_folder' => 'jnews_viral',
                'theme_color' => '#eb2f06',
                'block_color' => '#eb2f06',
                'mega_menu_color' => '#b71540',
                'is_active' => 0
            ]
        ];

        foreach ($themes as $theme) {
            $check = $db->table('themes')->where('theme', $theme['theme'])->get()->getRow();
            if (!$check) {
                $db->table('themes')->insert($theme);
            }
        }
        return redirect()->to(adminUrl('themes'))->with('success', 'Themes seeded successfully!');
    }


    /**
     * Set Theme Post
     */
    public function setThemePost()
    {
        checkSuperAdmin();
        $this->settingsModel->setTheme();
        return redirect()->to(adminUrl('themes'));
    }

    /**
     * Set Theme Settings Post
     */
    public function setThemeSettingsPost()
    {
        checkSuperAdmin();
        $this->settingsModel->setThemeSettings();
        return redirect()->to(adminUrl('themes'));
    }

    /**
     * Pages
     */
    public function pages()
    {
        checkPermission('pages');
        $data['title'] = trans("pages");
        $data['panelSettings'] = panelSettings();
        $data['pages'] = $this->pageModel->getPages();
        $data['langSearchColumn'] = 2;

        echo view('admin/includes/_header', $data);
        echo view('admin/page/pages', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Add Page
     */
    public function addPage()
    {
        checkPermission('pages');
        $data['title'] = trans("add_page");
        $data['menuLinks'] = $this->pageModel->getMenuLinks($this->activeLang->id);

        echo view('admin/includes/_header', $data);
        echo view('admin/page/add', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Add Page Post
     */
    public function addPagePost()
    {
        checkPermission('pages');
        $val = \Config\Services::validation();
        $val->setRule('title', trans("title"), 'required|max_length[500]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            if ($this->pageModel->addPage()) {
                setSuccessMessage("msg_added");
                redirectToBackURL();
            }
        }
        setErrorMessage("msg_error");
        return redirect()->back()->withInput();
    }

    /**
     * Edit Page
     */
    public function editPage($id)
    {
        checkPermission('pages');
        $data['title'] = trans("update_page");
        $data['page'] = $this->pageModel->getPageById($id);
        if (empty($data['page'])) {
            redirectToBackURL();
        }
        $data['menuLinks'] = $this->pageModel->getMenuLinks($data['page']->lang_id);

        echo view('admin/includes/_header', $data);
        echo view('admin/page/edit', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Update Page Post
     */
    public function editPagePost()
    {
        checkPermission('pages');
        $val = \Config\Services::validation();
        $val->setRule('title', trans("title"), 'required|max_length[500]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            $id = inputPost('id');
            $redirectUrl = inputPost('redirect_url');
            if ($this->pageModel->editPage($id)) {
                setSuccessMessage("msg_updated");
                if (!empty($redirectUrl)) {
                    return redirect()->to($redirectUrl);
                }
                return redirect()->to(adminUrl('pages'));
            }
        }
        setErrorMessage("msg_error");
        return redirect()->back()->withInput();
    }

    /**
     * Delete Page Post
     */
    public function deletePagePost()
    {
        checkPermission('pages');
        $id = inputPost('id');
        $page = $this->pageModel->getPageById($id);
        if (!empty($page)) {
            if ($page->is_custom == 0) {
                setErrorMessage("msg_page_delete");
                exit();
            } else {
                if (countItems($this->pageModel->getSubpages($id)) > 0) {
                    setErrorMessage("msg_delete_subpages");
                }
                if ($this->pageModel->deletePage($id)) {
                    setSuccessMessage("msg_deleted");
                } else {
                    setErrorMessage("msg_error");
                }
            }
        }
    }

    //get menu links by language
    public function getMenuLinksByLang()
    {
        $langId = inputPost('lang_id');
        if (!empty($langId)) {
            $menuLinks = $this->pageModel->getMenuLinks($langId);
            if (!empty($menuLinks)) {
                foreach ($menuLinks as $item) {
                    if ($item->item_type != 'category' && $item->item_location == 'main' && $item->item_parent_id == 0) {
                        echo ' <option value="' . $item->item_id . '">' . esc($item->item_name) . '</option>';
                    }
                }
            }
        }
    }

    /**
     * Add Widget
     */
    public function addWidget()
    {
        checkPermission('widgets');
        $data['title'] = trans("add_widget");
        $categoryModel = new CategoryModel();
        $data['categories'] = $categoryModel->getParentCategories();

        echo view('admin/includes/_header', $data);
        echo view('admin/widget/add', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Widgets
     */
    public function widgets()
    {
        checkPermission('widgets');
        $data['title'] = trans("widgets");
        $data['panelSettings'] = panelSettings();
        $data['widgets'] = $this->settingsModel->getWidgets();
        $data['langSearchColumn'] = 2;

        echo view('admin/includes/_header', $data);
        echo view('admin/widget/widgets', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Add Widget Post
     */
    public function addWidgetPost()
    {
        checkPermission('widgets');
        $val = \Config\Services::validation();
        $val->setRule('title', trans("title"), 'required|max_length[500]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->to(adminUrl('add-widget'))->withInput();
        } else {
            if ($this->settingsModel->addWidget()) {
                setSuccessMessage("msg_added");
            } else {
                setErrorMessage("msg_error");
            }
        }
        return redirect()->to(adminUrl('add-widget'));
    }

    /**
     * Edit Widget
     */
    public function editWidget($id)
    {
        checkPermission('widgets');
        $data['title'] = trans("update_widget");
        $data['widget'] = $this->settingsModel->getWidget($id);
        if (empty($data['widget'])) {
            return redirect()->to(adminUrl('widgets'));
        }
        $categoryModel = new CategoryModel();
        $data['categories'] = $categoryModel->getParentCategories();

        echo view('admin/includes/_header', $data);
        echo view('admin/widget/edit', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Edit Widget Post
     */
    public function editWidgetPost()
    {
        checkPermission('widgets');
        $id = clrNum(inputPost('id'));
        $val = \Config\Services::validation();
        $val->setRule('title', trans("title"), 'required|max_length[500]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            redirectToBackURL();
        } else {
            if ($this->settingsModel->editWidget($id)) {
                setSuccessMessage("msg_updated");
            } else {
                setErrorMessage("msg_error");
                return redirect()->to(adminUrl('edit-widget/' . $id))->withInput();
            }
        }
        redirectToBackURL();
    }

    /**
     * Delete Widget Post
     */
    public function deleteWidgetPost()
    {
        checkPermission('widgets');
        $id = inputPost('id');
        $widget = $this->settingsModel->getWidget($id);
        if ($widget->is_custom == 0) {
            $lang = getLanguage($widget->lang_id);
            if (!empty($lang)) {
                setErrorMessage("msg_widget_delete");
            }
        } else {
            if ($this->settingsModel->deleteWidget($id)) {
                setSuccessMessage("msg_deleted");
            } else {
                setErrorMessage("msg_error");
            }
        }
    }

    /**
     * Polls
     */
    public function polls()
    {
        checkPermission('polls');
        $data['title'] = trans("polls");
        $pollModel = new PollModel();
        $data['panelSettings'] = panelSettings();
        $data['polls'] = $pollModel->getPolls();
        $data['langSearchColumn'] = 2;

        echo view('admin/includes/_header', $data);
        echo view('admin/poll/polls', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Add Poll
     */
    public function addPoll()
    {
        checkPermission('polls');
        $data['title'] = trans("add_poll");

        echo view('admin/includes/_header', $data);
        echo view('admin/poll/add', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Add Poll Post
     */
    public function addPollPost()
    {
        checkPermission('polls');
        $val = \Config\Services::validation();
        $val->setRule('question', trans("question"), 'required');
        $val->setRule('option1', trans("option_1"), 'required');
        $val->setRule('option2', trans("option_2"), 'required');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->to(adminUrl('add-poll'))->withInput();
        } else {
            $pollModel = new PollModel();
            if ($pollModel->addPoll()) {
                setSuccessMessage("msg_added");
            } else {
                setErrorMessage("msg_error");
            }
        }
        return redirect()->to(adminUrl('add-poll'));
    }

    /**
     * Edit Poll
     */
    public function editPoll($id)
    {
        checkPermission('polls');
        $data['title'] = trans("update_poll");
        $pollModel = new PollModel();
        $data['poll'] = $pollModel->getPoll($id);
        if (empty($data['poll'])) {
            return redirect()->to(adminUrl('polls'));
        }

        echo view('admin/includes/_header', $data);
        echo view('admin/poll/edit', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Update Poll Post
     */
    public function editPollPost()
    {
        checkPermission('polls');
        $id = clrNum(inputPost('id'));
        $val = \Config\Services::validation();
        $val->setRule('question', trans("question"), 'required');
        $val->setRule('option1', trans("option_1"), 'required');
        $val->setRule('option2', trans("option_2"), 'required');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->to(adminUrl('edit-poll/' . $id))->withInput();
        } else {
            $pollModel = new PollModel();
            if ($pollModel->editPoll($id)) {
                setSuccessMessage("msg_updated");
            } else {
                setErrorMessage("msg_error");
                return redirect()->to(adminUrl('edit-poll/' . $id))->withInput();
            }
        }
        return redirect()->to(adminUrl('polls'));
    }

    /**
     * Delete Poll Post
     */
    public function deletePollPost()
    {
        checkPermission('polls');
        $id = inputPost('id');
        $pollModel = new PollModel();
        $poll = $pollModel->getPoll($id);
        if (!empty($poll)) {
            if ($pollModel->deletePoll($id)) {
                setSuccessMessage("msg_deleted");
            } else {
                setErrorMessage("msg_error");
            }
        }
    }

    /**
     * Contact Messages
     */
    public function contactMessages()
    {
        checkPermission('comments_contact');
        $data['title'] = trans("contact_messages");
        $data['panelSettings'] = panelSettings();
        $data['messages'] = $this->commonModel->getContactMessages();

        echo view('admin/includes/_header', $data);
        echo view('admin/contact_messages', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Delete Contact Message Post
     */
    public function deleteContactMessagePost()
    {
        if (!hasPermission('comments_contact')) {
            exit();
        }
        $id = inputPost('id');
        if ($this->commonModel->deleteContactMessage($id)) {
            setSuccessMessage("msg_deleted");
        } else {
            setErrorMessage("msg_error");
        }
    }

    /**
     * Delete Selected Contact Messages
     */
    public function deleteSelectedContactMessages()
    {
        if (!hasPermission('comments_contact')) {
            exit();
        }
        $this->commonModel->deleteMultiMessages();
    }

    /**
     * Comments
     */
    public function comments()
    {
        checkPermission('comments_contact');
        $data['title'] = trans("approved_comments");
        $data['topButtonText'] = trans("pending_comments");
        $data['panelSettings'] = panelSettings();
        $data['topButtonURL'] = adminUrl('pending-comments');
        $data['showApproveButton'] = false;

        $numRows = $this->commonModel->getCommentsCount(1);
        $data['pager'] = paginate(30, $numRows);
        $data['comments'] = $this->commonModel->getCommentsPaginated(1, 30, $data['pager']->offset);

        echo view('admin/includes/_header', $data);
        echo view('admin/comments', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Pending Comments
     */
    public function pendingComments()
    {
        checkPermission('comments_contact');
        $data['title'] = trans("pending_comments");
        $data['panelSettings'] = panelSettings();
        $data['topButtonText'] = trans("approved_comments");
        $data['topButtonURL'] = adminUrl('comments');
        $data['showApproveButton'] = true;

        $numRows = $this->commonModel->getCommentsCount(0);
        $data['pager'] = paginate(30, $numRows);
        $data['comments'] = $this->commonModel->getCommentsPaginated(0, 30, $data['pager']->offset);

        echo view('admin/includes/_header', $data);
        echo view('admin/comments', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Aprrove Comment Post
     */
    public function approveCommentPost()
    {
        checkPermission('comments_contact');
        $id = inputPost('id');
        if ($this->commonModel->approveComment($id)) {
            setSuccessMessage("msg_comment_approved");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('pending-comments'));
    }


    /**
     * Delete Comment Post
     */
    public function deleteCommentPost()
    {
        if (!hasPermission('comments_contact')) {
            exit();
        }
        $id = inputPost('id');
        if ($this->commonModel->deleteComment($id)) {
            setSuccessMessage("msg_deleted");
        } else {
            setErrorMessage("msg_error");
        }
    }

    /**
     * Approve Selected Comments
     */
    public function approveSelectedComments()
    {
        if (!hasPermission('comments_contact')) {
            exit();
        }
        $this->commonModel->approveMultiComments();
    }

    /**
     * Delete Selected Comments
     */
    public function deleteSelectedComments()
    {
        if (!hasPermission('comments_contact')) {
            exit();
        }
        $this->commonModel->deleteMultiComments();
    }

    /**
     * Newsletter
     */
    public function newsletter()
    {
        checkPermission('newsletter');
        $data['title'] = trans("newsletter");
        $data['panelSettings'] = panelSettings();
        $data['subscribersCount'] = $this->newsletterModel->getSubscribersCount();
        $data['usersCount'] = $this->authModel->getUserCount();

        echo view('admin/includes/_header', $data);
        echo view('admin/newsletter/newsletter', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Send Email
     */
    public function newsletterSendEmail()
    {
        checkPermission('newsletter');
        $data['title'] = trans("newsletter");
        $data['submit'] = inputPost('submit');
        $data['emails'] = null;
        if ($data['submit'] == 'users') {
            $ids = inputPost('user_ids');
            $array = null;
            if (!empty($ids)) {
                $array = explode(',', $ids);
            }
            if (!empty($array)) {
                $data['emails'] = $this->authModel->getUserEmailsByIds($array);
            }
        } elseif ($data['submit'] == 'subscribers') {
            $ids = inputPost('subscriber_ids');
            $array = null;
            if (!empty($ids)) {
                $array = explode(',', $ids);
            }
            if (!empty($array)) {
                $data['emails'] = $this->newsletterModel->getSubscriberEmailsByIds($array);
            }
        }

        if (empty($data['emails'])) {
            setErrorMessage('newsletter_email_error');
            return redirect()->to(adminUrl('newsletter'));
        }

        echo view('admin/includes/_header', $data);
        echo view('admin/newsletter/send_email', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Send Email Post
     */
    public function newsletterSendEmailPost()
    {
        checkPermission('newsletter');
        if (@$this->newsletterModel->sendEmail()) {
            echo json_encode(['result' => 1]);
            exit();
        }
        echo json_encode(['result' => 0]);
    }

    /**
     * Newsletter Settings Post
     */
    public function newsletterSettingsPost()
    {
        checkPermission('newsletter');
        if ($this->newsletterModel->updateSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('newsletter'));
    }

    /**
     * Delete Subscriber Post
     */
    public function deleteSubscriberPost()
    {
        if (!hasPermission('newsletter')) {
            exit();
        }
        $id = inputPost('id');
        if ($this->newsletterModel->deleteSubscriber($id)) {
            setSuccessMessage("msg_deleted");
        } else {
            setErrorMessage("msg_error");
        }
    }

    /**
     * Ads
     */
    public function adSpaces()
    {
        checkPermission('ad_spaces');
        $data['title'] = trans("ad_spaces");
        $data['adSpaceKey'] = inputGet('ad_space');
        $data['langId'] = inputGet('lang');
        if (empty($data['adSpaceKey'])) {
            $data['adSpaceKey'] = 'header';
        }
        $data['panelSettings'] = panelSettings();
        $lang = getLanguage($data['langId']);
        if (empty($lang)) {
            $data['langId'] = $this->activeLang->id;
        }
        $data['adSpace'] = $this->commonModel->getAdSpace($data['langId'], $data['adSpaceKey']);
        if (empty($data['adSpace'])) {
            return redirect()->to(adminUrl('ad-spaces'));
        }
        $data['arrayAdSpaces'] = [
            'header' => trans('ad_space_header'),
            'index_top' => trans('ad_space_index_top'),
            'index_bottom' => trans('ad_space_index_bottom'),
            'post_top' => trans('ad_space_post_top'),
            'post_bottom' => trans('ad_space_post_bottom'),
            'posts_top' => trans('ad_space_posts_top'),
            'posts_bottom' => trans('ad_space_posts_bottom'),
            'sidebar_1' => trans('sidebar') . '-1',
            'sidebar_2' => trans('sidebar') . '-2',
            'in_article_1' => trans('ad_space_in_article') . '-1',
            'in_article_2' => trans('ad_space_in_article') . '-2'
        ];
        $categoryModel = new CategoryModel();
        $data['categories'] = $categoryModel->getParentCategoriesByLang($data['langId']);

        echo view('admin/includes/_header', $data);
        echo view('admin/ad_spaces', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Ad Spaces Post
     */
    public function adSpacesPost()
    {
        checkPermission('ad_spaces');
        $id = inputPost('id');
        if ($this->commonModel->updateAdSpaces($id)) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackURL();
    }

    /**
     * Google Adsense Code Post
     */
    public function googleAdsenseCodePost()
    {
        if ($this->commonModel->updateGoogleAdsenseCode()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('ad-spaces'));
    }

    /**
     * Users
     */
    public function users()
    {
        checkPermission('users');
        $data['title'] = trans("users");
        $data['panelSettings'] = panelSettings();
        $numRows = $this->authModel->getUsersCount();
        $data['pager'] = paginate($this->perPage, $numRows);
        $data['users'] = $this->authModel->getUsersPaginated($this->perPage, $data['pager']->offset);
        $data['roles'] = $this->authModel->getRoles();

        echo view('admin/includes/_header', $data);
        echo view('admin/users/users', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Add User
     */
    public function addUser()
    {
        checkSuperAdmin();
        $data['title'] = trans("add_user");
        $data['roles'] = $this->authModel->getRoles();

        echo view('admin/includes/_header', $data);
        echo view('admin/users/add_user');
        echo view('admin/includes/_footer');
    }

    /**
     * Add User Post
     */
    public function addUserPost()
    {
        checkSuperAdmin();
        $val = \Config\Services::validation();
        $val->setRule('username', trans("username"), 'required|max_length[255]');
        $val->setRule('email', trans("email"), 'required|max_length[255]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->to(adminUrl('add-user'))->withInput();
        } else {
            $id = inputPost('id');
            $email = inputPost('email');
            $username = inputPost('username');
            $slug = inputPost('slug');
            if (!$this->authModel->isUniqueUsername($username, $id)) {
                setErrorMessage("msg_username_unique_error");
                return redirect()->to(adminUrl('add-user'))->withInput();
            }
            if (!$this->authModel->isEmailUnique($email, $id)) {
                setErrorMessage("message_email_unique_error");
                return redirect()->to(adminUrl('add-user'))->withInput();
            }
            if ($this->authModel->isSlugUnique($slug, $id)) {
                setErrorMessage("msg_slug_used");
                return redirect()->to(adminUrl('add-user'))->withInput();
            }
            if ($this->authModel->addUser($id)) {
                setSuccessMessage("msg_updated");
            } else {
                setErrorMessage("msg_error");
                return redirect()->to(adminUrl('add-user'))->withInput();
            }
        }
        return redirect()->to(adminUrl('add-user'));
    }

    /**
     * Edit User
     */
    public function editUser($id)
    {
        checkPermission('users');
        $data['title'] = trans("update_profile");
        $data['user'] = getUserById($id);
        if (empty($data['user'])) {
            return redirect()->to(adminUrl('users'));
        }

        if ($data['user']->is_super_admin == 1 && !isSuperAdmin()) {
            return redirect()->to(adminUrl('users'));
        }

        echo view('admin/includes/_header', $data);
        echo view('admin/users/edit_user');
        echo view('admin/includes/_footer');
    }

    /**
     * Edit User Post
     */
    public function editUserPost()
    {
        checkPermission('users');
        $val = \Config\Services::validation();
        $val->setRule('username', trans("username"), 'required|max_length[255]');
        $val->setRule('email', trans("email"), 'required|max_length[255]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            redirectToBackURL();
        } else {
            $id = inputPost('id');
            $user = getUserById($id);
            if ($user->is_super_admin == 1 && !isSuperAdmin()) {
                return redirect()->to(adminUrl('users'));
            }

            $email = inputPost('email');
            $username = inputPost('username');
            $slug = inputPost('slug');
            if (!$this->authModel->isEmailUnique($email, $id)) {
                setErrorMessage("message_email_unique_error");
                redirectToBackURL();
            }
            if (!$this->authModel->isUniqueUsername($username, $id)) {
                setErrorMessage("msg_username_unique_error");
                redirectToBackURL();
            }
            if ($this->authModel->isSlugUnique($slug, $id)) {
                setErrorMessage("msg_slug_used");
                redirectToBackURL();
            }
            if ($this->authModel->editUser($id)) {
                setSuccessMessage("msg_updated");
            } else {
                setErrorMessage("msg_error");
            }
        }
        return redirect()->to(adminUrl('edit-user/' . clrNum($id)));
    }

    /**
     * User Options Post
     */
    public function userOptionsPost()
    {
        checkPermission('users');
        $id = inputPost('id');
        $submit = inputPost('submit');
        $backURL = inputPost('back_url');
        $user = getUserById($id);
        if (!empty($user)) {
            if ($user->is_super_admin == 1 && !isSuperAdmin()) {
                setErrorMessage("msg_error");
                redirectToBackURL();
            }
            if ($submit == 'reward_system') {
                $rewardModel = new RewardModel();
                if ($rewardModel->enableDisableRewardSystem($user)) {
                    setSuccessMessage("msg_updated");
                } else {
                    setErrorMessage("msg_error");
                }
            } elseif ($submit == 'confirm_email') {
                if ($this->authModel->verifyEmail($user)) {
                    setSuccessMessage("msg_updated");
                } else {
                    setErrorMessage("msg_error");
                }
            } elseif ($submit == 'ban_user') {
                if ($this->authModel->banUser($user)) {
                    setSuccessMessage("msg_updated");
                } else {
                    setErrorMessage("msg_error");
                }
            }
        }
        redirectToBackURL();
    }

    /**
     * Delete User Post
     */
    public function deleteUserPost()
    {
        if (!hasPermission('users')) {
            exit();
        }
        $id = inputPost('id');
        $user = getUserById($id);
        if (!empty($user) && $user->id == 1) {
            setErrorMessage("msg_error");
            exit();
        }
        if (!empty($user) && $user->is_super_admin == 1 && !isSuperAdmin()) {
            setErrorMessage("msg_error");
            exit();
        }
        if ($this->authModel->deleteUser($id)) {
            setSuccessMessage("msg_deleted");
        } else {
            setErrorMessage("msg_error");
        }
    }

    /**
     * Load Users Dropdown
     */
    public function loadUsersDropdown()
    {
        $query = inputPost('q');
        $users = $this->authModel->loadUsersDropdown($query);
        return $this->response->setJSON(['items' => $users]);
    }

    /*
     * --------------------------------------------------------------------
     * Roles & Permissions
     * --------------------------------------------------------------------
     */

    /**
     * Roles Permissions
     */
    public function rolesPermissions()
    {
        checkPermission('roles_permissions');
        $data['title'] = trans("roles_permissions");
        $data['description'] = trans("roles_permissions");
        $data['keywords'] = trans("roles_permissions");
        $data['roles'] = $this->authModel->getRoles();

        echo view('admin/includes/_header', $data);
        echo view('admin/users/roles_permissions');
        echo view('admin/includes/_footer');
    }

    /**
     * Add Role
     */
    public function addRole()
    {
        checkPermission('roles_permissions');
        $data['title'] = trans("add_role");

        echo view('admin/includes/_header', $data);
        echo view('admin/users/add_role');
        echo view('admin/includes/_footer');
    }

    /**
     * Add Role Post
     */
    public function addRolePost()
    {
        checkPermission('roles_permissions');
        if ($this->authModel->addRole()) {
            setSuccessMessage("msg_added");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackUrl();
    }

    /**
     * Edit Role
     */
    public function editRole($id)
    {
        checkPermission('roles_permissions');
        $data['title'] = trans("edit_role");
        $data['role'] = $this->authModel->getRole($id);
        if (empty($data['role'])) {
            return redirect()->to(adminUrl('roles-permissions'));
        }
        $data['permissionsArray'] = getPermissionsArray();
        $data['rolePermissions'] = array();
        $permissions = $data['role']->permissions;
        if (!empty($permissions)) {
            $data['rolePermissions'] = explode(",", $permissions);
        }

        echo view('admin/includes/_header', $data);
        echo view('admin/users/edit_role');
        echo view('admin/includes/_footer');
    }

    /**
     * Edit Role Post
     */
    public function editRolePost()
    {
        checkPermission('roles_permissions');
        $id = inputPost('id');
        if ($this->authModel->editRole($id)) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackUrl();
    }

    /**
     * Change User Role Post
     */
    public function changeUserRolePost()
    {
        checkPermission('users');
        $userId = inputPost('user_id');
        $roleId = inputPost('role_id');
        if ($this->authModel->changeUserRole($userId, $roleId)) {
            setSuccessMessage(trans("msg_updated"));
        } else {
            setErrorMessage(trans("msg_error"));
        }
        redirectToBackUrl();
    }

    /**
     * Delete Role Post
     */
    public function deleteRolePost()
    {
        checkPermission('roles_permissions');
        $id = inputPost('id');
        if ($this->authModel->deleteRole($id)) {
            setSuccessMessage("msg_deleted");
        } else {
            setErrorMessage("msg_error");
        }
        exit();
    }

    /**
     * Seo Tools
     */
    public function seoTools()
    {
        checkPermission('seo_tools');
        $data['title'] = trans("seo_tools");
        $data["selectedLangId"] = inputGet('lang');
        $data['panelSettings'] = panelSettings();
        if (empty($data["selectedLangId"])) {
            $data["selectedLangId"] = $this->activeLang->id;
        }
        $data['seoSettings'] = $this->settingsModel->getSettings($data["selectedLangId"]);
        $data['postsCount'] = $this->postAdminModel->getPostsCount();

        $data['numSitemaps'] = 1;
        if ($data['postsCount'] > SITEMAP_URL_LIMIT) {
            $data['numSitemaps'] = ceil($data['postsCount'] / SITEMAP_URL_LIMIT);
        }

        echo view('admin/includes/_header', $data);
        echo view('admin/seo_tools', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Seo Tools Post
     */
    public function seoToolsPost()
    {
        checkPermission('seo_tools');
        $langId = inputPost('lang_id');
        if ($this->settingsModel->updateSeoSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('seo-tools?lang=' . clrNum($langId)));
    }

    /**
     * Google Indexing Api Post
     */
    public function googleIndexingApiPost()
    {
        checkPermission('seo_tools');
        $submit = inputPost("submit");
        if ($this->settingsModel->updateGoogleIndexingApiSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }

        return redirect()->to(adminUrl('seo-tools'));
    }

    /**
     * Sitemap Settings Post
     */
    public function sitemapSettingsPost()
    {
        $model = new SitemapModel();
        if ($model->updateSitemapSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        redirectToBackURL();
    }

    /**
     * Sitemap Post
     */
    public function sitemapPost()
    {
        $submit = inputPost('submit');
        $index = inputPost('index');
        $fileName = $index == 0 ? 'sitemap.xml' : 'sitemap-' . $index . '.xml';

        if ($submit == 'generate' || $submit == 'refresh') {
            $model = new SitemapModel();
            $model->generateSitemap($index);
        } elseif ($submit == 'download') {
            if (file_exists(FCPATH . $fileName)) {
                return $this->response->download(FCPATH . $fileName, null)->setFileName($fileName);
            }
        } elseif ($submit == 'delete') {
            if (file_exists(FCPATH . $fileName)) {
                @unlink(FCPATH . $fileName);
            }
        }
        redirectToBackURL();
    }

    /**
     * Storage
     */
    public function storage()
    {
        $data['title'] = trans("storage");

        echo view('admin/includes/_header', $data);
        echo view('admin/storage', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Storage Post
     */
    public function storagePost()
    {
        if ($this->settingsModel->updateStorageSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('storage'));
    }

    /**
     * AWS S3 Post
     */
    public function awsS3Post()
    {
        if ($this->settingsModel->updateAwsS3()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('storage'));
    }

    /**
     * Cache System
     */
    public function cacheSystem()
    {
        checkSuperAdmin();
        $data['title'] = trans("cache_system");

        echo view('admin/includes/_header', $data);
        echo view('admin/cache_system', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Cache System Post
     */
    public function cacheSystemPost()
    {
        checkSuperAdmin();
        if (inputPost('action') == 'reset') {
            resetCacheData();
            setSuccessMessage("msg_reset_cache");
        } elseif (inputPost('action') == 'reset_static') {
            resetCacheStatic();
            setSuccessMessage("msg_reset_cache");
        } else {
            if ($this->settingsModel->updateCacheSystem()) {
                setSuccessMessage("msg_updated");
            } else {
                setErrorMessage("msg_error");
            }
        }
        $model = new \App\Models\PostModel();
        $model->setScheduledPostsCache();
        return redirect()->to(adminUrl('cache-system'));
    }

    /**
     * Google News
     */
    public function googleNews()
    {
        checkSuperAdmin();
        $data['title'] = trans("google_news");
        $data['users'] = [];
        echo view('admin/includes/_header', $data);
        echo view('admin/google_news', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Google News Post
     */
    public function googleNewsPost()
    {
        if ($this->settingsModel->updateGoogleNews()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('google-news'));
    }

    /**
     * Preferences
     */
    public function preferences()
    {
        checkPermission('settings');
        $data['title'] = trans("preferences");
        $data['panelSettings'] = panelSettings();

        echo view('admin/includes/_header', $data);
        echo view('admin/settings/preferences', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Preferences Post
     */
    public function preferencesPost()
    {
        checkPermission('settings');
        $form = inputPost('submit');
        if ($this->settingsModel->updatePreferences($form)) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('preferences?tab=' . cleanStr($form)));
    }

    /**
     * AI Writer Post
     */
    public function aiWriterPost()
    {
        checkPermission('settings');
        if ($this->settingsModel->updateAIWriterSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('preferences'));
    }

    /**
     * File Upload Settings Post
     */
    public function fileUploadSettingsPost()
    {
        checkPermission('settings');
        if ($this->settingsModel->updateFileUploadSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('preferences'));
    }

    /**
     * Route Settings
     */
    public function routeSettings()
    {
        checkPermission('settings');
        $data['title'] = trans("route_settings");
        $data['routes'] = Globals::$customRoutes;
        $data['panelSettings'] = panelSettings();

        echo view('admin/includes/_header', $data);
        echo view('admin/settings/route_settings', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Route Settings Post
     */
    public function routeSettingsPost()
    {
        checkPermission('settings');
        if ($this->settingsModel->updateRouteSettings()) {
            setSuccessMessage("msg_updated");
            $adminRoute = inputPost('admin');
            return redirect()->to(base_url($adminRoute . "/route-settings"));
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('route-settings'));
    }

    /**
     * Email Settings
     */
    public function emailSettings()
    {
        checkPermission('settings');
        $data['title'] = trans("email_settings");
        $data['panelSettings'] = panelSettings();
        $data['service'] = inputGet('service');
        $data['protocol'] = inputGet('protocol');
        if (empty($data['service'])) {
            $data['service'] = $this->generalSettings->mail_service;
        }
        if ($data['service'] != 'swift' && $data['service'] != 'php' && $data['service'] != 'mailjet') {
            $data['service'] = 'swift';
        }
        if (empty($data['protocol'])) {
            $data['protocol'] = $this->generalSettings->mail_protocol;
        }
        if ($data['protocol'] != 'smtp' && $data['protocol'] != 'mail') {
            $data['protocol'] = 'smtp';
        }

        echo view('admin/includes/_header', $data);
        echo view('admin/settings/email_settings', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Update Email Settings Post
     */
    public function emailSettingsPost()
    {
        checkPermission('settings');
        if ($this->settingsModel->updateEmailSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('email-settings'));
    }

    /**
     * Update Contact Email Settings Post
     */
    public function contactEmailSettingsPost()
    {
        checkPermission('settings');
        if ($this->settingsModel->updateContactEmailSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('email-settings'));
    }

    /**
     * Update Email Verification Settings Post
     */
    public function emailVerificationSettingsPost()
    {
        checkPermission('settings');
        if ($this->settingsModel->emailVerificationSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('email-settings'));
    }

    /**
     * Send Test Email Post
     */
    public function sendTestEmailPost()
    {
        checkPermission('settings');
        $email = inputPost('email');
        $email = inputPost('email');
        $subject = $this->settings->application_name . " Test Email";
        $message = "<p>This is a test email. This e-mail is sent to your e-mail address for test purpose only. If you have received this e-mail, your e-mail system is working.</p>";
        $emailModel = new EmailModel();
        if (!empty($email)) {
            if (!$emailModel->sendTestEmail($email, $subject, $message)) {
                return redirect()->to(adminUrl('email-settings'));
            }
            setSuccessMessage("msg_email_sent");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('email-settings'));
    }

    /**
     * Font Settings
     */
    public function fontSettings()
    {
        checkPermission('settings');
        $data["selectedLangId"] = clrNum(inputGet('lang'));
        if (empty($data["selectedLangId"])) {
            $data["selectedLangId"] = $this->activeLang->id;
        }
        $data['panelSettings'] = panelSettings();
        $data['title'] = trans("font_settings");
        $data['fonts'] = $this->settingsModel->getFonts();

        echo view('admin/includes/_header', $data);
        echo view('admin/font/fonts', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Set Site Font Post
     */
    public function setSiteFontPost()
    {
        checkPermission('settings');
        if ($this->settingsModel->setDefaultFonts()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('font-settings'));
    }

    /**
     * Add Font Post
     */
    public function addFontPost()
    {
        checkPermission('settings');
        $val = \Config\Services::validation();
        $val->setRule('font_name', trans("name"), 'required|max_length[255]');
        $val->setRule('font_family', trans("font_family"), 'required|max_length[500]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            if ($this->settingsModel->addFont()) {
                setSuccessMessage("msg_updated");
            } else {
                setErrorMessage("msg_error");
            }
        }
        return redirect()->to(adminUrl('font-settings'));
    }

    /**
     * Edit Font
     */
    public function editFont($id)
    {
        checkPermission('settings');
        $data['title'] = trans("update_font");
        $data['font'] = $this->settingsModel->getFont($id);
        if (empty($data['font'])) {
            return redirect()->to(adminUrl('font-settings'));
        }

        echo view('admin/includes/_header', $data);
        echo view('admin/font/edit', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Edit Font Post
     */
    public function editFontPost()
    {
        checkPermission('settings');
        $val = \Config\Services::validation();
        $val->setRule('font_name', trans("name"), 'required|max_length[255]');
        $val->setRule('font_family', trans("font_family"), 'required|max_length[500]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back()->withInput();
        } else {
            $id = inputPost('id');
            if ($this->settingsModel->editFont($id)) {
                setSuccessMessage("msg_updated");
            } else {
                setErrorMessage("msg_error");
            }
        }
        return redirect()->to(adminUrl('font-settings'));
    }

    /**
     * Delete Font Post
     */
    public function deleteFontPost()
    {
        checkPermission('settings');
        $id = inputPost('id');
        if ($this->settingsModel->deleteFont($id)) {
            setSuccessMessage("msg_deleted");
        } else {
            setErrorMessage("msg_error");
        }
    }

    /**
     * Social Login Configuration
     */
    public function socialLoginSettings()
    {
        checkPermission('settings');
        $data['title'] = trans("social_login_settings");
        $data['panelSettings'] = panelSettings();

        echo view('admin/includes/_header', $data);
        echo view('admin/social_login', $data);
        echo view('admin/includes/_footer');
    }


    /**
     * Social Login Facebook Post
     */
    public function socialLoginSettingsPost()
    {
        checkPermission('settings');
        if ($this->settingsModel->updateSocialSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('social-login-settings'));
    }

    /**
     * General Settings
     */
    public function generalSettings()
    {
        checkPermission('settings');
        $data["settingsLangId"] = clrNum(inputGet("lang"));
        if (empty($data["settingsLangId"])) {
            $data["settingsLangId"] = $this->activeLang->id;
            return redirect()->to(adminUrl('general-settings?lang=' . $data["settingsLangId"]));
        }
        $data['panelSettings'] = panelSettings();
        $data['title'] = trans("settings");
        $data['settings'] = $this->settingsModel->getSettings($data["settingsLangId"]);

        echo view('admin/includes/_header', $data);
        echo view('admin/settings/general_settings', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Update Settings Post
     */
    public function generalSettingsPost()
    {
        checkPermission('settings');
        $langId = clrNum(inputPost('lang_id'));
        $activeTab = clrNum(inputPost('active_tab'));
        if (empty($langId)) {
            $langId = $this->activeLang->id;
        }
        if ($this->settingsModel->updateSettings($langId)) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('general-settings?lang=' . $langId . '&tab=' . $activeTab));
    }

    /**
     * Recaptcha Settings Post
     */
    public function recaptchaSettingsPost()
    {
        checkPermission('settings');
        $langId = clrNum(inputPost('lang_id'));
        if (empty($langId)) {
            $langId = $this->activeLang->id;
        }
        if ($this->settingsModel->updateRecaptchaSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('general-settings?lang=' . $langId));
    }

    /**
     * Maintenance Mode Post
     */
    public function maintenanceModePost()
    {
        $langId = clrNum(inputPost('lang_id'));
        if (empty($langId)) {
            $langId = $this->activeLang->id;
        }
        if ($this->settingsModel->updateMaintenanceModeSettings()) {
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('general-settings?lang=' . $langId));
    }

    /**
     * Control Panel Language Post
     */
    public function setActiveLanguagePost()
    {
        $langId = inputPost('lang_id');
        $languageModel = new LanguageModel();
        $language = $languageModel->getLanguage($langId);
        if (!empty($language)) {
            $this->session->set('vr_control_panel_lang', $language->id);
        }
        redirectToBackURL();
    }

    /**
     * Download Database Backup
     */
    public function downloadDatabaseBackup()
    {
        checkSuperAdmin();
        $response = \Config\Services::response();
        $data = $this->settingsModel->downloadBackup();
        $name = 'db_backup-' . date('Y-m-d H-i-s') . '.sql';
        return $response->download($name, $data);
    }

    /*
     * --------------------------------------------------------------------
     * FITUR EXPORT & IMPORT USERS (EXCEL/CSV FIX)
     * --------------------------------------------------------------------
     */

    public function exportUsers()
    {
        if (!authCheck() || !isAdmin()) return redirect()->to(adminUrl('login'));

        $db = \Config\Database::connect();
        // Ambil semua data user
        $users = $db->table('users')->orderBy('id', 'ASC')->get()->getResultArray();

        $filename = 'users_excel_' . date('Y-m-d_H-i') . '.csv';

        if (ob_get_length()) {
            ob_end_clean();
        }

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: text/csv; charset=UTF-8");

        $file = fopen('php://output', 'w');

        // BOM agar Excel otomatis mendeteksi font dan tabel
        fputs($file, "\xEF\xBB\xBF");

        if (count($users) > 0) {
            $header = array_keys($users[0]);
            fputcsv($file, $header, ';');

            foreach ($users as $row) {
                // Sengaja kosongkan kolom password saat export untuk keamanan
                if (isset($row['password'])) {
                    $row['password'] = ''; 
                }
                fputcsv($file, $row, ';');
            }
        } else {
            fputcsv($file, ['id', 'username', 'email', 'password', 'role_id', 'status'], ';');
        }
        
        fclose($file);
        exit;
    }

    public function importUsers()
    {
        if (!authCheck() || !isAdmin()) return redirect()->to(adminUrl('login'));

        $file = $this->request->getFile('file_csv');
        
        if ($file && $file->isValid() && $file->getExtension() == 'csv') {
            $filepath = $file->getTempName();
            $fileHandle = fopen($filepath, "r");
            
            // Deteksi Delimiter Excel otomatis
            $firstLine = fgets($fileHandle);
            $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';
            rewind($fileHandle);

            $header = fgetcsv($fileHandle, 10000, $delimiter); 
            if(isset($header[0])) {
                $header[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header[0]);
            }

            $db = \Config\Database::connect();
            $count = 0;

            while (($data = fgetcsv($fileHandle, 10000, $delimiter)) !== FALSE) {
                if (count($header) === count($data)) {
                    $row = array_combine($header, $data);
                    
                    // Wajib ada email dan username untuk user baru
                    if (!empty($row['email']) && !empty($row['username'])) { 
                        $insertData = [
                            'username' => $row['username'],
                            'email'    => $row['email'],
                        ];

                        // Loop dinamis memasukkan kolom lain (kecuali id, password, email, username)
                        foreach($row as $key => $val) {
                            if (!in_array($key, ['id', 'email', 'username', 'password', 'created_at']) && $db->fieldExists($key, 'users')) {
                                $insertData[$key] = $val;
                            }
                        }

                        // Cek Duplikat berdasarkan EMAIL
                        $existing = $db->table('users')->where('email', $insertData['email'])->get()->getRow();
                        
                        if ($existing) {
                            // JIKA USER SUDAH ADA: Update data (Abaikan password jika di excel dikosongkan)
                            if (!empty($row['password'])) {
                                $insertData['password'] = password_hash($row['password'], PASSWORD_BCRYPT);
                            }
                            $db->table('users')->where('id', $existing->id)->update($insertData);
                        } else {
                            // JIKA USER BARU: Masukkan data
                            // Jika password kosong, set default "123456"
                            if (empty($row['password'])) {
                                $insertData['password'] = password_hash('123456', PASSWORD_BCRYPT);
                            } else {
                                $insertData['password'] = password_hash($row['password'], PASSWORD_BCRYPT);
                            }
                            
                            $insertData['created_at'] = date('Y-m-d H:i:s');
                            if(!isset($insertData['slug']) || empty($insertData['slug'])){
                                $insertData['slug'] = strSlug($row['username']);
                            }
                            
                            $db->table('users')->insert($insertData);
                        }
                        $count++;
                    }
                }
            }
            fclose($fileHandle);
            return redirect()->back()->with('success', "$count Data User berhasil diproses (Di-update/Ditambahkan)!");
        }
        
        return redirect()->back()->with('error', "Gagal! Pastikan file berakhiran .csv");
    }
    
  /*
     * --------------------------------------------------------------------
     * FITUR: MEGA MENU CUSTOM MANAGER (AUTO-DB)
     * --------------------------------------------------------------------
     */
    public function megaMenuSettings()
    {
        if (!authCheck() || !isAdmin()) return redirect()->to(adminUrl('login'));

        $data['title'] = "Pengaturan Mega Menu Custom";
        $data['panelSettings'] = panelSettings();
        $db = \Config\Database::connect();

        // 1. Auto-Create Table Banner Utama
        $db->query("CREATE TABLE IF NOT EXISTS custom_megamenu (
            id INT AUTO_INCREMENT PRIMARY KEY,
            menu_slug VARCHAR(255) NOT NULL,
            menu_style VARCHAR(50) DEFAULT 'banner_left',
            menu_image VARCHAR(500),
            menu_title VARCHAR(255),
            menu_desc TEXT
        )");

        // 2. Auto-Create Table Icon Sub-Menu
        $db->query("CREATE TABLE IF NOT EXISTS custom_submenu_icons (
            id INT AUTO_INCREMENT PRIMARY KEY,
            sub_slug VARCHAR(255) NOT NULL,
            sub_icon VARCHAR(100) DEFAULT 'icon-arrow-right',
            sub_image VARCHAR(500) DEFAULT NULL
        )");

        // AUTO-UPDATE TABEL LAMA: Tambah kolom sub_image & sub_desc
        if ($db->tableExists('custom_submenu_icons')) {
            if (!$db->fieldExists('sub_image', 'custom_submenu_icons')) {
                $db->query("ALTER TABLE custom_submenu_icons ADD sub_image VARCHAR(500) DEFAULT NULL");
            }
            if (!$db->fieldExists('sub_desc', 'custom_submenu_icons')) {
                $db->query("ALTER TABLE custom_submenu_icons ADD sub_desc TEXT DEFAULT NULL");
            }
        }

        $data['megamenus'] = $db->table('custom_megamenu')->get()->getResult();
        $data['submenus']  = $db->table('custom_submenu_icons')->get()->getResult();

        echo view('admin/includes/_header', $data);
        echo view('admin/navigation/mega_menu', $data);
        echo view('admin/includes/_footer');
    }

    public function megaMenuSettingsPost()
    {
        if (!authCheck() || !isAdmin()) return redirect()->to(adminUrl('login'));
        $db = \Config\Database::connect();
        $action = inputPost('action');
        $formType = inputPost('form_type');

        // ======= FORM ICON SUB-MENU =======
        if ($formType == 'submenu') {
            if ($action == 'add' || $action == 'edit') {
                $data = [
                    'sub_slug' => strSlug(inputPost('sub_slug')),
                    'sub_icon' => inputPost('sub_icon'),
                    'sub_image'=> inputPost('sub_image_url'),
                    'sub_desc' => inputPost('sub_desc') // TAMBAHAN DESKRIPSI
                ];

                $fileSub = $this->request->getFile('file_sub_image');
                if ($fileSub && $fileSub->isValid() && !$fileSub->hasMoved()) {
                    $path = FCPATH . 'uploads/megamenu/';
                    if (!is_dir($path)) { mkdir($path, 0755, true); }
                    $newName = $fileSub->getRandomName();
                    $fileSub->move($path, $newName);
                    $data['sub_image'] = 'uploads/megamenu/' . $newName;
                }

                if ($action == 'add') {
                    $db->table('custom_submenu_icons')->insert($data);
                } else {
                    $db->table('custom_submenu_icons')->where('id', inputPost('id'))->update($data);
                }
                resetCacheDataOnChange();
                return redirect()->back()->with('success', 'Pengaturan Sub Menu berhasil disimpan!');
            } elseif ($action == 'delete') {
                $db->table('custom_submenu_icons')->where('id', inputPost('id'))->delete();
                resetCacheDataOnChange();
                return redirect()->back()->with('success', 'Dihapus!');
            }
        } 
        // ======= FORM BANNER UTAMA =======
        else {
            if ($action == 'add' || $action == 'edit') {
                $data = [
                    'menu_slug'  => strSlug(inputPost('menu_slug')),
                    'menu_style' => inputPost('menu_style'),
                    'menu_title' => inputPost('menu_title'),
                    'menu_desc'  => inputPost('menu_desc'),
                    'menu_image' => inputPost('menu_image_url')
                ];

                $file = $this->request->getFile('file_image');
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $path = FCPATH . 'uploads/megamenu/';
                    if (!is_dir($path)) { mkdir($path, 0755, true); }
                    $newName = $file->getRandomName();
                    $file->move($path, $newName);
                    $data['menu_image'] = 'uploads/megamenu/' . $newName;
                }

                if ($action == 'add') {
                    $db->table('custom_megamenu')->insert($data);
                } else {
                    $db->table('custom_megamenu')->where('id', inputPost('id'))->update($data);
                }
                resetCacheDataOnChange();
                return redirect()->back()->with('success', 'Banner Menu berhasil disimpan!');
            } 
            elseif ($action == 'delete') {
                $db->table('custom_megamenu')->where('id', inputPost('id'))->delete();
                resetCacheDataOnChange();
                return redirect()->back()->with('success', 'Banner Menu dihapus!');
            }
        }
        return redirect()->back();
    }
    
   /*
     * --------------------------------------------------------------------
     * FITUR: HEADER CUSTOM MANAGER (AUTO-DB)
     * --------------------------------------------------------------------
     */
    public function headerSettings()
    {
        if (!authCheck() || !isAdmin()) return redirect()->to(adminUrl('login'));

        $data['title'] = "Pengaturan Visual Header";
        $data['panelSettings'] = panelSettings();
        $db = \Config\Database::connect();

        // 1. Auto-Create/Update Table jika belum ada
        $db->query("CREATE TABLE IF NOT EXISTS custom_header_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            top_menu_color VARCHAR(50) DEFAULT '#f8f9fa',
            top_menu_text VARCHAR(50) DEFAULT '#333333',
            bg_color VARCHAR(50) DEFAULT '#ffffff',
            main_menu_color VARCHAR(50) DEFAULT '#1e1e2d',
            main_menu_text VARCHAR(50) DEFAULT '#ffffff',
            bg_image VARCHAR(500) DEFAULT NULL
        )");

        // 2. Pastikan ada baris data id 1
        if ($db->table('custom_header_settings')->countAllResults() == 0) {
            $db->table('custom_header_settings')->insert(['id' => 1, 'bg_color' => '#ffffff']);
        }

        $data['headerSetting'] = $db->table('custom_header_settings')->where('id', 1)->get()->getRow();

        echo view('admin/includes/_header', $data);
        echo view('admin/settings/header_settings', $data); // Pastikan file ini ada di app/Views/admin/settings/
        echo view('admin/includes/_footer');
    }

    public function headerSettingsPost()
    {
        if (!authCheck() || !isAdmin()) return redirect()->to(adminUrl('login'));
        $db = \Config\Database::connect();

        // LOGIKA RESET KE DEFAULT
        if (inputPost('submit') == 'reset') {
            $default = [
                'top_menu_color'   => '#f8f9fa',
                'top_menu_text'    => '#333333',
                'bg_color'         => '#ffffff',
                'main_menu_color'  => '#1e1e2d',
                'main_menu_text'   => '#ffffff',
                'bg_image'         => null
            ];
            $db->table('custom_header_settings')->where('id', 1)->update($default);
            resetCacheDataOnChange();
            return redirect()->back()->with('success', 'Pengaturan berhasil dikembalikan ke default!');
        }

        // UPDATE DATA NORMAL
        $data = [
            'top_menu_color'  => inputPost('top_menu_color'),
            'top_menu_text'   => inputPost('top_menu_text'),
            'bg_color'        => inputPost('bg_color'),
            'main_menu_color' => inputPost('main_menu_color'),
            'main_menu_text'  => inputPost('main_menu_text')
        ];

        // Auto-Migration Kolom (Jika belum ada)
        if (!$db->fieldExists('top_menu_text', 'custom_header_settings')) {
            $db->query("ALTER TABLE custom_header_settings ADD top_menu_text VARCHAR(50) DEFAULT '#333333'");
        }
        if (!$db->fieldExists('main_menu_text', 'custom_header_settings')) {
            $db->query("ALTER TABLE custom_header_settings ADD main_menu_text VARCHAR(50) DEFAULT '#ffffff'");
        }

        if (inputPost('remove_image') == 1) {
            $data['bg_image'] = null;
        } else {
            $file = $this->request->getFile('bg_image');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $path = FCPATH . 'uploads/header/';
                if (!is_dir($path)) { mkdir($path, 0755, true); }
                $newName = $file->getRandomName();
                $file->move($path, $newName);
                $data['bg_image'] = 'uploads/header/' . $newName;
            }
        }

        $db->table('custom_header_settings')->where('id', 1)->update($data);
        resetCacheDataOnChange();
        return redirect()->back()->with('success', 'Pengaturan Header berhasil disimpan!');
    }

    /**
     * Dashboard Themes
     */
    public function dashboardThemes()
    {
        checkPermission('settings');
        $data['title'] = "Tema Dashboard";
        $data['panelSettings'] = panelSettings();

        // Auto-Fix Column if not exists
        $db = \Config\Database::connect();
        if (!$db->fieldExists('admin_theme', 'general_settings')) {
            $db->query("ALTER TABLE general_settings ADD COLUMN admin_theme VARCHAR(50) DEFAULT 'classic'");
        }

        echo view('admin/includes/_header', $data);
        echo view('admin/settings/dashboard_themes', $data);
        echo view('admin/includes/_footer');
    }

    /**
     * Set Dashboard Theme Post
     */
    public function setDashboardThemePost()
    {
        checkPermission('settings');
        
        // Ensure column exists (Migration Safe-Run)
        $db = \Config\Database::connect();
        if (!$db->fieldExists('admin_theme', 'general_settings')) {
            $db->query("ALTER TABLE general_settings ADD COLUMN admin_theme VARCHAR(50) DEFAULT 'classic'");
        }

        if ($this->settingsModel->setDashboardTheme()) {
            resetCacheDataOnChange(); // CUCI CACHE
            setSuccessMessage("msg_updated");
        } else {
            setErrorMessage("msg_error");
        }
        return redirect()->to(adminUrl('dashboard-themes'));
    }
}