<?php
namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController{

    /**
     * @var PropertyRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(PropertyRepository $repository, ObjectManager $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    /**
     * @Route("/admin", name="admin.property.index")
     * @return Response
     */
    public function index() : Response
    {
        $properties = $this->repository->findAll();
        return $this->render('admin/property/index.html.twig', [
           'properties' => $properties
        ]);
    }

    /**
     * @Route("/admin/property/create", name="admin.property.create")
     * @param Request $request
     * @return Response
     */
    public function create (Request $request) : Response {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($property);
            $this->manager->flush();
            $this->addFlash('success', 'Ce bien a bien été créé avec succès');
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin/property/create.html.twig', [
            'form' => $form->createView(),
            'property' => $property
        ]);
    }

    /**
     * @Route("/admin/property/{id}", name="admin.property.edit", methods="GET|POST")
     * @param Property $property
     * @param Request $request
     * @return Response
     */
    public function edit(Property $property, Request $request) : Response
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
            $this->addFlash('success', 'Ce bien a bien été modifié');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('/admin/property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/property/{id}", name="admin.property.delete", methods="DELETE")
     * @param Property $property
     * @param Request $request
     * @return Response
     */
    public function delete(Property $property, Request $request) : Response
    {
        if($this->isCsrfTokenValid('delete'. $property->getId(), $request->get('_token'))){
            $this->manager->remove($property);
            $this->manager->flush();
            $this->addFlash('success', 'Ce bien a bien été supprimé');
        }
        return $this->redirectToRoute('admin.property.index');
    }

}
