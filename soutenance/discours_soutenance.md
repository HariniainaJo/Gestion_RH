# Discours de soutenance — 20 minutes
### Gestion des Ressources Humaines (GRH) — ZANOVA
### HARINIAINA Ambinintsoa Jonathan Allys

> Ce script est calé sur les 15 slides du PowerPoint fourni. Chaque bloc indique le minutage, la slide correspondante, et le texte à dire (tel quel, ou comme trame si vous préférez parler plus librement). Les deux blocs en gras [VIDÉO] et [CODE] sont tenus par vous en dehors du texte écrit.

---

## [0:00 – 1:00] — Slide 1 : Couverture

Bonjour / Bonsoir Mesdames et Messieurs les membres du jury,

Je vous remercie de me recevoir aujourd'hui pour la soutenance de mon mémoire de fin de licence, intitulé *« Gestion des Ressources Humaines »*, réalisé durant mon stage de trois mois au sein de l'entreprise ZANOVA.

Je me présente : HARINIAINA Ambinintsoa Jonathan Allys, étudiant en licence à l'IT University.

Durant ces vingt minutes, je vais vous présenter le contexte de ce projet, la démarche que j'ai suivie, les choix techniques effectués, les modules développés, ainsi qu'une courte démonstration de l'application et un extrait de code, avant de conclure sur le bilan de cette expérience.

## [1:00 – 1:30] — Slide 2 : Sommaire

Voici le plan de ma présentation : je commencerai par le contexte — l'entreprise et la problématique — puis les besoins et les choix techniques, l'architecture retenue, et les modules développés. Je ferai ensuite une démonstration vidéo suivie d'une courte explication de code, avant de conclure sur les difficultés rencontrées, le bilan et les perspectives.

## [1:30 – 3:00] — Slide 3 : Contexte — ITU & ZANOVA

L'IT University, fondée en 2011, est une université privée spécialisée en informatique, qui forme en trois ans des étudiants vers une licence professionnalisante, ou en cinq ans vers un master. C'est un établissement très proche du monde professionnel, grâce à des partenariats industriels forts qui participent à la formation et recrutent directement ses sortants.

C'est dans ce cadre que j'ai effectué un stage de trois mois, de janvier à mars 2026, au sein du département informatique de ZANOVA. ZANOVA est une jeune startup malgache spécialisée dans l'informatisation et la transformation digitale des entreprises. Étant elle-même une structure récente, elle ne disposait d'aucun outil de gestion de son propre personnel — c'est précisément la mission qui m'a été confiée.

## [3:00 – 4:30] — Slide 4 : Problématique & Objectifs

Le constat de départ était simple : en tant que startup récemment créée, ZANOVA gérait manuellement l'ensemble de ses processus liés au personnel — les congés, la paie, le suivi des absences et des retards, le recrutement. Cette gestion manuelle entraînait des erreurs, une perte de temps considérable, et surtout un manque de traçabilité des décisions.

L'objectif de mon stage était donc clair : concevoir et développer une application web capable de **centraliser** toutes ces informations, d'**automatiser** les processus répétitifs comme le calcul de la paie, de **sécuriser** les données sensibles — salaires, informations personnelles — et de rester suffisamment **évolutive** pour accompagner la croissance de l'entreprise.

## [4:30 – 6:00] — Slide 5 : Besoins fonctionnels & non fonctionnels

Sur le plan fonctionnel, le cahier des charges couvrait six grands domaines : la gestion des employés et de leurs documents, la gestion des congés et absences, la gestion de la paie avec ses primes et cotisations, le recrutement et le suivi des stagiaires, la gestion des équipes et des projets, et enfin la gestion des navettes pour le transport du personnel.

Sur le plan non fonctionnel, le système devait pouvoir gérer simultanément au moins deux cents employés sans perte de performance, sécuriser les données sensibles par une authentification robuste, rester facilement maintenable grâce à une architecture bien structurée, et offrir une interface intuitive et responsive, aussi bien sur ordinateur que sur tablette.

## [6:00 – 7:30] — Slide 6 : Choix technologiques

Pour répondre à ces besoins, j'ai choisi **Symfony** comme framework backend. C'est un framework PHP open-source reconnu pour sa robustesse, son architecture MVC stricte, et son intégration native avec Doctrine ORM. Comparé à des alternatives comme Laravel ou CodeIgniter, Symfony offre une meilleure organisation pour un projet complexe comportant plusieurs modules interdépendants — ce qui correspondait exactement à notre cas.

Côté base de données, j'ai retenu **MariaDB**, un système de gestion de base de données relationnelle, performant, fiable et pleinement compatible avec MySQL, avec un moteur transactionnel InnoDB qui garantit la cohérence des données — un point essentiel quand on manipule des calculs de paie.

Enfin, **Twig** comme moteur de templates permet de séparer clairement la logique métier de l'affichage, tout en protégeant automatiquement contre les failles XSS, ce qui est indispensable lorsqu'on affiche des données personnelles et financières.

## [7:30 – 9:00] — Slide 7 : Architecture 3-tiers

L'application est conçue selon une **architecture trois tiers**. La couche de présentation, construite en MVC avec Twig, représente à elle seule **52 écrans**. La couche de contrôle compte **15 contrôleurs**, qui orchestrent les actions des utilisateurs. La couche métier s'appuie sur **26 classes modèles** qui portent les règles de gestion RH. Et enfin, la couche d'accès aux données dialogue, via Doctrine ORM, avec les **29 tables** de notre modèle physique de données.

Cette séparation stricte des responsabilités facilite la réutilisation du code, simplifie les tests, et surtout rend l'application capable d'évoluer sans tout remettre en cause à chaque nouvelle fonctionnalité.

## [9:00 – 11:00] — Slide 8 : Vue d'ensemble des modules

Le développement s'est fait module par module. On retrouve d'abord le socle : l'authentification sécurisée par rôles, le tableau de bord central, et la structure organisationnelle avec les directions, services et postes.

Vient ensuite la gestion opérationnelle : les groupes et équipes, la gestion complète du personnel avec ses documents administratifs, le suivi des stagiaires, la gestion des congés, et le module de recrutement et talents qui va jusqu'à la conversion automatique d'un candidat validé en employé.

On trouve aussi le suivi quotidien avec les absences et retards, et le planning visuel sous forme de calendrier.

Et enfin, le cœur du système RH : les KPI et primes, la paie, les avances sur salaire, les cotisations et l'IRSA, sans oublier la gestion des navettes et le module d'états et statistiques qui donne une vue d'ensemble pour la prise de décision.

## [11:00 – 12:30] — Slide 9 : Zoom — Paie, KPI & Primes

Je voudrais m'arrêter un instant sur le module le plus complexe techniquement : la **paie**. Le système calcule automatiquement le salaire net de chaque employé en intégrant les primes, les cotisations sociales — IRSA, CNAPS, OSTIE — et les retenues, puis génère directement la fiche de paie.

Ce calcul s'appuie sur le module **KPI et primes** : chaque employé ou groupe est évalué sur des indicateurs de performance, et des formules configurables calculent automatiquement les primes individuelles et collectives, garantissant une rémunération variable juste et transparente.

Enfin, les **avances sur salaire** sont strictement encadrées par des règles métier : un employé ne peut pas demander plus de 50 % de son salaire, ni dépasser deux avances validées par mois.

## [12:30 – 15:30] — Slide 10 : **[DÉMONSTRATION VIDÉO — 3 minutes]**

*(Transition orale avant de lancer la vidéo)*

Je vous propose à présent de passer à une courte démonstration vidéo de l'application, pour vous montrer concrètement le fonctionnement des modules que je viens de présenter : le tableau de bord, la gestion du personnel, les congés et la paie.

**→ Lancer la vidéo (3 min).**

## [15:30 – 17:30] — Slide 11 : **[EXPLICATION DE CODE — 2 minutes, par vous]**

*(Transition orale avant l'explication de code)*

Je vais maintenant prendre deux minutes pour vous présenter un extrait de code source, afin d'illustrer concrètement l'implémentation de la logique métier derrière l'un de ces modules.

**→ Explication de code (2 min, menée par vous).**

## [17:30 – 19:00] — Slide 12 : Difficultés rencontrées & solutions

Durant mon stage, j'ai été confronté à trois défis techniques majeurs.

Le premier concernait la **modélisation de la base de données** : structurer l'ensemble des entités RH — employés, contrats, congés, paie, navettes — dans un schéma cohérent et évolutif a demandé une analyse rigoureuse en amont, pour aboutir aux 29 tables de notre modèle physique.

Le deuxième était le **calcul de la paie** lui-même, qui intègre des règles fiscales variables, des cotisations sociales par tranches, et un système de primes basé sur des KPI configurables par poste.

Le troisième concernait la **gestion des congés et des absences**, notamment le calcul proratisé des soldes de congés et la détection automatique des retards à partir des données de pointage.

Face à chacun de ces problèmes, j'ai privilégié des solutions durables plutôt que des correctifs temporaires, en m'appuyant sur une architecture orientée services et les bonnes pratiques de Symfony, pour garantir la maintenabilité et l'évolutivité de l'application.

## [19:00 – 19:40] — Slide 13 & 14 : Bilan & Conclusion

Pour l'entreprise, ce stage s'est traduit par un outil concret et déjà fonctionnel en interne, qui fluidifie et simplifie la gestion du personnel au quotidien. ZANOVA m'a fait confiance en me confiant un projet réel, avec un accompagnement régulier de mon encadreur tout au long du stage.

Sur le plan personnel, cette expérience m'a permis d'améliorer mes compétences en développement web full-stack, de renforcer mon esprit d'analyse et ma capacité à travailler en équipe, et de relier mes connaissances théoriques à une pratique professionnelle concrète.

Pour la suite, l'application pourrait évoluer vers l'automatisation de l'envoi des fiches de paie et de la planification des entretiens professionnels, ainsi que vers l'intégration de modules d'intelligence artificielle capables d'aider à la décision, d'anticiper les besoins en recrutement, ou de détecter des tendances liées à l'absentéisme.

En conclusion, cette application constitue un grand pas pour ZANOVA : elle permet désormais aux dirigeants d'avoir, d'un simple coup d'œil, une vision complète et fiable de leur personnel, sans recourir aux méthodes archaïques des dossiers papier. L'application est aujourd'hui fonctionnelle en interne, tout en restant en phase d'amélioration continue.

## [19:40 – 20:00] — Slide 15 : Remerciements

Je vous remercie pour votre attention, et je reste à votre entière disposition pour répondre à vos questions.

---

### Repères de minutage (résumé)

| Minute | Slide | Contenu |
|---|---|---|
| 0:00 – 1:00 | 1 | Couverture / Introduction |
| 1:00 – 1:30 | 2 | Sommaire |
| 1:30 – 3:00 | 3 | Contexte (ITU + ZANOVA) |
| 3:00 – 4:30 | 4 | Problématique & objectifs |
| 4:30 – 6:00 | 5 | Besoins fonctionnels / non fonctionnels |
| 6:00 – 7:30 | 6 | Choix technologiques |
| 7:30 – 9:00 | 7 | Architecture 3-tiers |
| 9:00 – 11:00 | 8 | Vue d'ensemble des modules |
| 11:00 – 12:30 | 9 | Zoom Paie / KPI / Primes |
| 12:30 – 15:30 | 10 | **Démonstration vidéo (3 min)** |
| 15:30 – 17:30 | 11 | **Explication de code (2 min)** |
| 17:30 – 19:00 | 12 | Difficultés & solutions |
| 19:00 – 19:40 | 13-14 | Bilan & Conclusion |
| 19:40 – 20:00 | 15 | Remerciements / Questions |

**Total : 20 minutes.**
