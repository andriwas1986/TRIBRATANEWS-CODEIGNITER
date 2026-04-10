<?php

namespace App\Models;

use CodeIgniter\Model;

class TbNewsModel extends Model
{
    // 1. Statistik Harian (UPDATE: Tampilkan SEMUA Author & Ranking)
    public function getDailyStats($date)
    {
        $builder = $this->db->table('users');
        $builder->select('users.username, users.id as user_id, COUNT(posts.id) as total_posts');
        
        // LEFT JOIN agar user yang tidak posting tetap muncul
        // Filter tanggal & status ditaruh di dalam ON agar tidak memfilter user
        $builder->join('posts', 'users.id = posts.user_id AND DATE(posts.created_at) = ' . $this->db->escape($date) . ' AND posts.status = 1', 'left');
        
        // Filter hanya user aktif (opsional, sesuaikan jika ingin menampilkan semua user terlepas statusnya)
        $builder->where('users.status', 1); 
        // Filter role jika perlu (misal hanya admin & author), jika ingin semua user, hapus baris ini
        // $builder->whereIn('users.role', ['admin', 'author']); 

        $builder->groupBy('users.id');
        $builder->orderBy('total_posts', 'DESC'); // Ranking: Terbanyak di atas
        
        return $builder->get()->getResultArray();
    }

    // 2. Link Berita Harian (Detail)
    public function getUserLinks($userId, $date)
    {
        return $this->db->table('posts')
            ->select('title, slug') 
            ->where('user_id', $userId)
            ->where('DATE(created_at)', $date)
            ->where('status', 1)
            ->get()
            ->getResultArray();
    }

    // 3. Rekap Absensi Bulanan
    public function getMonthlyRecap($month, $year)
    {
        $users = $this->db->table('users')->where('status', 1)->get()->getResultArray();
        $posts = $this->db->table('posts')
            ->select('user_id, DAY(created_at) as day, COUNT(id) as total')
            ->where('MONTH(created_at)', $month)
            ->where('YEAR(created_at)', $year)
            ->where('status', 1)
            ->groupBy('user_id, day')
            ->get()
            ->getResultArray();

        $matrix = [];
        foreach ($users as $user) {
            $uid = $user['id'];
            $matrix[$uid]['name'] = $user['username'];
            for ($d = 1; $d <= 31; $d++) {
                $matrix[$uid]['days'][$d] = 0;
            }
        }
        foreach ($posts as $post) {
            $uid = $post['user_id'];
            $day = $post['day'];
            if (isset($matrix[$uid])) {
                $matrix[$uid]['days'][$day] = $post['total'];
            }
        }
        return $matrix;
    }

    // 4. Daftar Link Bulanan
    public function getMonthlyLinks($month, $year)
    {
        return $this->db->table('posts')
            ->select('posts.title, posts.slug, posts.created_at, posts.image_url, users.username')
            ->select('images.image_mid, images.storage') 
            ->join('users', 'users.id = posts.user_id')
            ->join('images', 'posts.image_id = images.id', 'left') 
            ->where('MONTH(posts.created_at)', $month)
            ->where('YEAR(posts.created_at)', $year)
            ->where('posts.status', 1)
            ->orderBy('posts.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }
}