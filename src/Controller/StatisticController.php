<?php

// src/Controller/StatisticController.php

namespace App\Controller;

use App\Repository\CandidatureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatisticController extends AbstractController
{
    #[Route('/statistics', name: 'statistics')]
   // In your StatisticController
   public function index(CandidatureRepository $candidatureRepository): Response
   {
       // Fetch statistics from the repository
       $statistics = $candidatureRepository->findStatisticsBySpecialityAndExperience();
   
       // Initialize arrays to hold the data
       $experienceCounts = [];
       $specialityCounts = [];
   
       foreach ($statistics as $stat) {
           // If using getArrayResult(), use the key directly
           // Assuming $stat is an associative array with keys like 'specialite' and 'count'
           $speciality = $stat['specialite']; 
           $count = $stat['count'];
   
           // Count by speciality
           if (!isset($specialityCounts[$speciality])) {
               $specialityCounts[$speciality] = 0;
           }
           $specialityCounts[$speciality] += $count;
   
           // Handle experience counts if you have experience data in your statistics
           // (assuming the same structure for experience data, adjust as needed)
           // For example:
           $experience = $stat['experience']; // Add this line only if your data has experience
           if (!isset($experienceCounts[$experience])) {
               $experienceCounts[$experience] = 0;
           }
           $experienceCounts[$experience] += $count;
       }
   
       // Prepare the data for the charts
       $experienceLabels = array_keys($experienceCounts);
       $userCounts = array_values($experienceCounts);
       $specialityLabels = array_keys($specialityCounts);
       $specialityUserCounts = array_values($specialityCounts);
   
       return $this->render('statistics/index.html.twig', [
           'statistics' => $statistics,
           'experienceLabels' => $experienceLabels,
           'userCounts' => $userCounts,
           'specialityLabels' => $specialityLabels,
           'specialityUserCounts' => $specialityUserCounts,
       ]);
   }
   
}


