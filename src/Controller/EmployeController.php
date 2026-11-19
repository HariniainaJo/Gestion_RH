<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/employes')]
#[IsGranted('ROLE_USER')]
class EmployeController extends AbstractController
{
    #[Route('/', name: 'app_employe_index', methods: ['GET'])]
    public function index(EmployeRepository $employeRepository): Response
    {
        return $this->render('employe/index.html.twig', [
            'employes' => $employeRepository->findAll(),
        ]);
    }

    #[Route('/export', name: 'app_employe_export', methods: ['GET'])]
    public function export(EmployeRepository $employeRepo): Response
    {
        $employes = $employeRepo->findAll();
        $csv = "ID;Nom;Prenom;Email;Poste;Statut\n";
        
        foreach ($employes as $emp) {
            $csv .= sprintf(
                "%s;%s;%s;%s;%s;%s\n",
                $emp->getId(),
                $emp->getNom(),
                $emp->getPrenom(),
                $emp->getEmail(),
                $emp->getPoste()->getLibelle(),
                $emp->getStatut()
            );
        }

        $response = new Response($csv);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="employes_export.csv"');

        return $response;
    }

    #[Route('/import', name: 'app_employe_import', methods: ['GET', 'POST'])]
    public function import(Request $request, EntityManagerInterface $em, \App\Repository\PosteRepository $posteRepo, \App\Service\FileUploader $fileUploader): Response
    {
        if ($request->isMethod('POST')) {
            $csvFile = $request->files->get('csv_file');
            if ($csvFile) {
                $fileHandle = fopen($csvFile->getPathname(), 'r');
                $header = fgetcsv($fileHandle, 0, ';'); // Skip header
                
                while (($row = fgetcsv($fileHandle, 0, ';')) !== false) {
                    // Mapping: nom;prenom;email;contact;date_naissance;adresse;situation_matrimoniale;date_embauche;poste;photo_profil_url;photo_cin_url;cv_url
                    if (count($row) < 9) continue;

                    $employe = new Employe();
                    $employe->setNom($row[0]);
                    $employe->setPrenom($row[1]);
                    $employe->setEmail($row[2]);
                    $employe->setContact($row[3]);
                    $employe->setDateNaissance(new \DateTime($row[4]));
                    $employe->setAdresse($row[5]);
                    $employe->setSituationMatrimoniale($row[6]);
                    $employe->setDateEmbauche(new \DateTime($row[7]));
                    $employe->setStatut('Actif');

                    // Poste
                    $posteLibelle = $row[8];
                    $poste = $posteRepo->findOneBy(['libelle' => $posteLibelle]);
                    if (!$poste) {
                        $poste = new \App\Entity\Poste();
                        $poste->setLibelle($posteLibelle);
                        $em->persist($poste);
                        $em->flush(); // Flush info to query it next time if needed or simply persist
                    }
                    $employe->setPoste($poste);

                    // Files Download
                    $uploadDir = $fileUploader->getTargetDirectory();
                    
                    if (!empty($row[9])) {
                        $fileName = $this->downloadFile($row[9], $uploadDir);
                        if ($fileName) $employe->setPhotoProfil($fileName);
                    }
                    if (!empty($row[10])) {
                        $fileName = $this->downloadFile($row[10], $uploadDir);
                        if ($fileName) $employe->setPhotoCin($fileName);
                    }
                    if (!empty($row[11])) {
                        $fileName = $this->downloadFile($row[11], $uploadDir);
                        if ($fileName) $employe->setCvFichier($fileName);
                    }

                    $em->persist($employe);
                }
                fclose($fileHandle);
                $em->flush();
                $this->addFlash('success', 'Importation terminée avec succès.');
                return $this->redirectToRoute('app_employe_index');
            }
        }

        return $this->render('employe/import.html.twig');
    }

    private function downloadFile(string $url, string $targetDir): ?string
    {
        try {
            $content = file_get_contents($url);
            if ($content === false) return null;

            // Guess extension or default to jpg/pdf?
            // Simple way: get extension from url or default
            $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));
            $extension = $pathInfo['extension'] ?? 'bin';
            if (str_contains($extension, 'php')) $extension = 'txt'; // Sec

            $fileName = uniqid() . '.' . $extension;
            file_put_contents($targetDir . '/' . $fileName, $content);
            
            return $fileName;
        } catch (\Exception $e) {
            return null;
        }
    }


    #[Route('/new', name: 'app_employe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, \App\Service\FileUploader $fileUploader): Response
    {
        $employe = new Employe();
        $form = $this->createForm(\App\Form\EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photoFile */
            $photoFile = $form->get('photoProfilFile')->getData();
            if ($photoFile) {
                $employe->setPhotoProfil($fileUploader->upload($photoFile));
            }

            /** @var UploadedFile $cinFile */
            $cinFile = $form->get('photoCinFile')->getData();
            if ($cinFile) {
                $employe->setPhotoCin($fileUploader->upload($cinFile));
            }

            /** @var UploadedFile $cvFile */
            $cvFile = $form->get('cvFile')->getData();
            if ($cvFile) {
                $employe->setCvFichier($fileUploader->upload($cvFile));
            }

            $entityManager->persist($employe);
            $entityManager->flush();

            return $this->redirectToRoute('app_employe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('employe/new.html.twig', [
            'employe' => $employe,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_employe_show', methods: ['GET'])]
    public function show(Employe $employe): Response
    {
        return $this->render('employe/show.html.twig', [
            'employe' => $employe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_employe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Employe $employe, EntityManagerInterface $entityManager, \App\Service\FileUploader $fileUploader): Response
    {
        $form = $this->createForm(\App\Form\EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photoFile */
            $photoFile = $form->get('photoProfilFile')->getData();
            if ($photoFile) {
                $employe->setPhotoProfil($fileUploader->upload($photoFile));
            }

            /** @var UploadedFile $cinFile */
            $cinFile = $form->get('photoCinFile')->getData();
            if ($cinFile) {
                $employe->setPhotoCin($fileUploader->upload($cinFile));
            }

            /** @var UploadedFile $cvFile */
            $cvFile = $form->get('cvFile')->getData();
            if ($cvFile) {
                $employe->setCvFichier($fileUploader->upload($cvFile));
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_employe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('employe/edit.html.twig', [
            'employe' => $employe,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}/delete', name: 'app_employe_delete', methods: ['POST'])]
    public function delete(Request $request, Employe $employe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$employe->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($employe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_employe_index', [], Response::HTTP_SEE_OTHER);
    }

}
