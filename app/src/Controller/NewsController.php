<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\News;
use App\Entity\Provider;
use App\Entity\Referral;
use App\Form\NewsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends BaseController
{
    /**
     * @Route("/news", name="news")
     * @Template()
    */
    public function news()
    {
        $newsList = $this->getRepository(News::class)->findBy(
            ['provider' => $this->getUser()->getProvider()],
            ['id' => 'desc']
        );

        return [
            'newsList' => $newsList
        ];
    }

    /**
     * @Route("/news/create", name="news_create")
     * @Template()
     */
    public function create(Request $request)
    {
        $form = $this->createForm(NewsType::class, new News());
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            /** @var News $news */
            $news = $form->getData();

            $news->setProvider($this->getUser()->getProvider());
            $em = $this->getDoctrine()->getManager();
            $em->persist($news);
            $em->flush();

            $this->addFlash('success', 'Новость успешно создана!');
            return $this->redirectToRoute('news');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/news/edit/{id}", name="news_edit", requirements={"id":"\d+"})
     * @Template()
     */
    public function edit(int $id, Request $request)
    {
        $news = $this->getRepository(News::class)->find($id);
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            /** @var News $news */
            $news = $form->getData();

            $news->setProvider($this->getUser()->getProvider());
            $em = $this->getDoctrine()->getManager();
            $em->persist($news);
            $em->flush();

            $this->addFlash('success', 'Новость успешно сохранена!');
            return $this->redirectToRoute('news');
        }

        return ['form' => $form->createView(), 'news' => $news];
    }

    /**
     * @Route("/news/remove/{id}", name="news_remove", requirements={"id":"\d+"})
     * @Template()
     */
    public function remove(int $id, Request $request)
    {
        $news = $this->getRepository(News::class)->find($id);
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($news);
            $em->flush();

            $this->addFlash('success', 'Новость успешно удалена!');
            return $this->redirectToRoute('news');
        }
        return ['news' => $news];
    }

    /**
     * @Route("/news/{id}", name="news_details", requirements={"id":"\d+"}, methods={"GET"})
     * @Template()
    */
    public function details(int $id)
    {
        return ["news" => $this->getRepository(News::class)->find($id)];
    }
}
