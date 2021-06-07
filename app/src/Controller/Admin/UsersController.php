<?php declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Participant;
use App\Entity\Provider;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends BaseController
{
    /**
     * @Route("/admin/users", name="admin_users")
     * @Template()
    */
    public function users()
    {
        return [
            'users' => $this->getRepository(User::class)->findBy([], ['id' => 'desc'])
        ];
    }
}
