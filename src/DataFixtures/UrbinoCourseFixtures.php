<?php

namespace App\DataFixtures;

use App\DTO\Admin\UrbinoCourseRequestDTO;
use App\Entity\UrbinoCourse;
use App\Entity\UrbinoCourseCategory;
use App\Entity\UrbinoEdition;
use App\Service\UrbinoCourseService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UrbinoCourseFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UrbinoCourseService $urbinoCourseService,
    ) {}

    public function getDependencies(): array
    {
        return [
            UrbinoEditionFixtures::class,
            UrbinoCourseCategoryFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        /** @var UrbinoEdition $edition2025 */
        $edition2025 = $this->getReference(UrbinoEditionFixtures::EDITION_2025_REF, UrbinoEdition::class);

        /** @var UrbinoCourseCategory $categoryArchi */
        $categoryArchi = $this->getReference(UrbinoCourseCategoryFixtures::CATEGORY_ARCHI_REF, UrbinoCourseCategory::class);
        /** @var UrbinoCourseCategory $categoryCanto */
        $categoryCanto = $this->getReference(UrbinoCourseCategoryFixtures::CATEGORY_CANTO_REF, UrbinoCourseCategory::class);
        /** @var UrbinoCourseCategory $categoryTastiere */
        $categoryTastiere = $this->getReference(UrbinoCourseCategoryFixtures::CATEGORY_TASTIERE_REF, UrbinoCourseCategory::class);
        /** @var UrbinoCourseCategory $categoryFiati */
        $categoryFiati = $this->getReference(UrbinoCourseCategoryFixtures::CATEGORY_FIATI_REF, UrbinoCourseCategory::class);
        /** @var UrbinoCourseCategory $categoryMusica */
        $categoryMusica = $this->getReference(UrbinoCourseCategoryFixtures::CATEGORY_MUSICA_CAMERA_REF, UrbinoCourseCategory::class);

        $courses = [
            new UrbinoCourseRequestDTO(
                teacherFullName: 'Giovanni Sollima',
                urbinoEditionId: $edition2025->getId(),
                urbinoCategoryId: $categoryArchi->getId(),
                programDescriptionIt: 'Il corso si concentra sul repertorio per violoncello barocco, con particolare attenzione alle suite di Bach e alle sonate di Vivaldi. Verranno affrontate le problematiche dell\'intonazione e della prassi esecutiva storicamente informata.',
                programDescriptionEn: 'The course focuses on the baroque cello repertoire, with particular attention to Bach\'s suites and Vivaldi\'s sonatas. Issues of intonation and historically informed performance practice will be addressed.',
                bioDescriptionIt: 'Giovanni Sollima è uno dei violoncellisti più versatili della sua generazione. Formatosi con Dario Fo, ha collaborato con i più importanti ensemble di musica antica europei.',
                bioDescriptionEn: 'Giovanni Sollima is one of the most versatile cellists of his generation. Trained with Dario Fo, he has collaborated with the most important European early music ensembles.',
                isPreselectionRequired: true,
                isSoldOut: false,
                scheduleType: UrbinoCourse::SCHEDULE_TYPE_MAIN,
                dateStart: '2025-07-20',
                dateEnd: '2025-08-03',
                priceEuros: 450.00,
            ),
            new UrbinoCourseRequestDTO(
                teacherFullName: 'Cecilia Bartoli',
                urbinoEditionId: $edition2025->getId(),
                urbinoCategoryId: $categoryCanto->getId(),
                programDescriptionIt: 'Il corso approfondisce il repertorio vocale barocco italiano, dalle cantate di Handel alle opere di Vivaldi. Verranno studiate le tecniche ornamentali del XVII e XVIII secolo.',
                programDescriptionEn: 'The course explores the Italian Baroque vocal repertoire, from Handel\'s cantatas to Vivaldi\'s operas. Ornamental techniques of the 17th and 18th centuries will be studied in depth.',
                bioDescriptionIt: 'Cecilia Bartoli è considerata una delle più importanti mezzosoprani del panorama internazionale. Specializzata nel repertorio barocco e classico, ha inciso decine di album pluripremiati.',
                bioDescriptionEn: 'Cecilia Bartoli is considered one of the most important mezzo-sopranos on the international scene. Specialised in baroque and classical repertoire, she has recorded dozens of award-winning albums.',
                isPreselectionRequired: true,
                isSoldOut: true,
                scheduleType: UrbinoCourse::SCHEDULE_TYPE_MAIN,
                dateStart: '2025-07-20',
                dateEnd: '2025-08-03',
                priceEuros: 520.00,
            ),
            new UrbinoCourseRequestDTO(
                teacherFullName: 'Rinaldo Alessandrini',
                urbinoEditionId: $edition2025->getId(),
                urbinoCategoryId: $categoryTastiere->getId(),
                programDescriptionIt: 'Il corso è dedicato al clavicembalo italiano del Cinquecento e Seicento. Verranno esaminati i principali metodi di improvvisazione e la realizzazione del basso continuo.',
                programDescriptionEn: 'The course is dedicated to the Italian harpsichord of the 16th and 17th centuries. The main methods of improvisation and the realisation of the basso continuo will be examined.',
                bioDescriptionIt: 'Rinaldo Alessandrini è fondatore e direttore del Concerto Italiano, ensemble di riferimento per la musica barocca italiana. Ha vinto numerosi Grammy Awards e Diapason d\'Or.',
                bioDescriptionEn: 'Rinaldo Alessandrini is founder and director of Concerto Italiano, a reference ensemble for Italian Baroque music. He has won numerous Grammy Awards and Diapason d\'Or.',
                isPreselectionRequired: false,
                isSoldOut: false,
                scheduleType: UrbinoCourse::SCHEDULE_TYPE_MAIN,
                dateStart: '2025-07-21',
                dateEnd: '2025-08-02',
                priceEuros: 480.00,
            ),
            new UrbinoCourseRequestDTO(
                teacherFullName: 'Rachel Podger',
                urbinoEditionId: $edition2025->getId(),
                urbinoCategoryId: $categoryArchi->getId(),
                programDescriptionIt: 'Corso di violino barocco incentrato sulle sonate e partite di J.S. Bach per violino solo. Particolare attenzione alla tecnica dell\'arco e all\'articolazione retorica.',
                programDescriptionEn: 'Baroque violin course focused on J.S. Bach\'s sonatas and partitas for solo violin. Particular attention to bow technique and rhetorical articulation.',
                bioDescriptionIt: 'Rachel Podger è considerata una delle interpreti più influenti della musica barocca. Ha fondato il suo ensemble Brecon Baroque e vinto numerosi BBC Music Magazine Awards.',
                bioDescriptionEn: 'Rachel Podger is considered one of the most influential interpreters of baroque music. She founded her own ensemble Brecon Baroque and has won numerous BBC Music Magazine Awards.',
                isPreselectionRequired: true,
                isSoldOut: false,
                scheduleType: UrbinoCourse::SCHEDULE_TYPE_SECONDARY_AFTERNOON,
                dateStart: '2025-07-22',
                dateEnd: '2025-08-01',
                priceEuros: 390.00,
            ),
            new UrbinoCourseRequestDTO(
                teacherFullName: 'Alfredo Bernardini',
                urbinoEditionId: $edition2025->getId(),
                urbinoCategoryId: $categoryFiati->getId(),
                programDescriptionIt: 'Il corso è dedicato all\'oboe barocco e agli strumenti a fiato del XVII e XVIII secolo. Verranno trattati i principali metodi d\'intonazione e il repertorio solistico e cameristico.',
                programDescriptionEn: 'The course is dedicated to the baroque oboe and wind instruments of the 17th and 18th centuries. The main tuning methods and the solo and chamber repertoire will be covered.',
                bioDescriptionIt: 'Alfredo Bernardini è oboista, direttore d\'orchestra e musicologo. Membro fondatore dell\'ensemble Zefiro, è considerato uno dei massimi esperti mondiali di oboe barocco.',
                bioDescriptionEn: 'Alfredo Bernardini is an oboist, conductor and musicologist. Founding member of the ensemble Zefiro, he is considered one of the world\'s foremost experts on the baroque oboe.',
                isPreselectionRequired: false,
                isSoldOut: false,
                scheduleType: UrbinoCourse::SCHEDULE_TYPE_MAIN,
                dateStart: '2025-07-20',
                dateEnd: '2025-08-03',
                priceEuros: 420.00,
            ),
            new UrbinoCourseRequestDTO(
                teacherFullName: 'Monica Huggett',
                urbinoEditionId: $edition2025->getId(),
                urbinoCategoryId: $categoryMusica->getId(),
                programDescriptionIt: 'Seminario di musica da camera barocca: metodologia di lavoro collettivo, lettura a prima vista e interpretazione del repertorio d\'insieme dal Seicento al Settecento.',
                programDescriptionEn: 'Baroque chamber music seminar: collective work methodology, sight-reading and interpretation of ensemble repertoire from the 17th to the 18th century.',
                bioDescriptionIt: 'Monica Huggett è violinista e direttore ospite di numerosi ensemble internazionali. Ha insegnato presso le più importanti istituzioni musicali del mondo.',
                bioDescriptionEn: 'Monica Huggett is a violinist and guest director of numerous international ensembles. She has taught at the most important musical institutions in the world.',
                isPreselectionRequired: true,
                isSoldOut: false,
                scheduleType: UrbinoCourse::SCHEDULE_TYPE_SECONDARY_AFTERNOON,
                dateStart: '2025-07-23',
                dateEnd: '2025-08-02',
                priceEuros: null,
            ),
        ];

        foreach ($courses as $dto) {
            $this->urbinoCourseService->create($dto);
        }
    }
}
