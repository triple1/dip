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

class UserEditController extends BaseController
{
    /**
     * @Route("/admin/user/edit/{idUser}", name="admin_user_edit", methods={"GET"})
     * @Template()
    */
    public function edit(string $idUser)
    {
        $user = $this->getRepository(User::class)->find($idUser);
        $form = $this->createForm(UserEditType::class, $user);
        return [
            'user' => $user,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/admin/user/edit", name="admin_user_edit_process", methods={"POST"})
     */
    public function editProcess(Request $request)
    {
        /** @var User $user */
        $user = $this->getRepository(User::class)->find($request->get('id_user'));

        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'Данные о пользователе успешно сохранены');
            return $this->redirectToRoute('admin_users');
        }
    }
}
