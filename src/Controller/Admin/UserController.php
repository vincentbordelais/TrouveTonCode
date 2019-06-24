<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class UserController extends Controller
{
    /**
     * @Route("/admin/user/list", name="user_list")
     */
    public function getList(UserRepository $userRepo)
    {
        $users = $userRepo->findAll();
        return $this->render('admin/user_list.html.twig', [
            'users' => $users
        ]);
    }
}