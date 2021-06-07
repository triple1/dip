<?php declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Service;
use App\Form\ServiceType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ServiceCreateUpdateController extends BaseController
{
    /**
     * @Route("/admin/service/create", name="admin_service_create", methods={"GET"})
     * @Template()
     */
    public function create()
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/admin/service/create", name="admin_service_create_process", methods={"POST"})
     */
    public function createProcess(Request $request)
    {
        $form = $this->createForm(ServiceType::class, new Service());
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            /** @var Service $service */
            $service = $form->getData();

            try {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($service);
                $manager->flush();

                $this->addFlash('success', 'Service created');
                return $this->redirectToRoute('admin_services');
            } catch (UniqueConstraintViolationException $ex) {
                $this->addFlash('danger', 'Name of Service should be unique');
                return $this->redirectToRoute('admin_service_create');
            } catch (\Exception $ex) {
                $this->addFlash('danger', $ex->getMessage());
                return $this->redirectToRoute('admin_service_create');
            }
        }

        $this->addFlash('danger', 'Please submit the form');
        return $this->redirectToRoute('admin_service_create');
    }
}
