<?php declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Participant;
use App\Entity\Provider;
use App\Entity\User;
use App\Form\UserEditType;
use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserRemoveController extends BaseController
{
    /**
     * @Route("/admin/user/remove/{idUser}", name="admin_user_remove", methods={"GET"})
     * @Template()
    */
    public function remove(string $idUser)
    {
        return [
            'user' => $this->getRepository(User::class)->find($idUser)
        ];
    }

    /**
     * @Route("/admin/user/remove", name="admin_user_remove_process", methods={"POST"})
     */
    public function removeProcess(Request $request)
    {
        /** @var User $user */
        $user = $this->getRepository(User::class)->find($request->get('id_user'));

        $manager = $this->getDoctrine()->getManager();

        try {
            $manager->remove($user);
            $manager->flush();

            $this->addFlash('success', 'Пользователь успешно удален');
            return $this->redirectToRoute('admin_users');
        } catch (\Exception $ex) {
            $this->addFlash('danger', 'Ошибка при удалении пользователи. Удалите все связанные данные пользователи.');
            return $this->redirectToRoute('admin_user_remove', ['idUser' => $user->getId()]);
        }
    }
}
