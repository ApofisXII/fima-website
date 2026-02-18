<?php

namespace App\DataFixtures;

use App\DTO\Admin\UrbinoEditionRequestDTO;
use App\Entity\UrbinoEdition;
use App\Service\UrbinoEditionService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UrbinoEditionFixtures extends Fixture
{
    public const EDITION_2025_REF = 'urbino-edition-2025';
    public const EDITION_2024_REF = 'urbino-edition-2024';

    public function __construct(
        private readonly UrbinoEditionService $urbinoEditionService,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $editions = [
            self::EDITION_2025_REF => new UrbinoEditionRequestDTO(
                editionId: null,
                editionName: 'Urbino 2025',
                dateStart: '2025-07-20',
                dateEnd: '2025-08-03',
                isPublicVisible: true,
                enrollmentInfoIt: 'Le iscrizioni per la Settimana Musicale di Urbino 2025 sono aperte. I corsi si svolgono presso il Palazzo Ducale di Urbino. Per iscriversi è necessario compilare il modulo online entro il 30 maggio 2025. La quota di iscrizione comprende lezioni, materiale didattico e accesso a tutti gli eventi serali.',
                enrollmentInfoEn: 'Registrations for the Urbino Music Week 2025 are now open. Courses take place at the Ducal Palace of Urbino. To register, please fill in the online form by May 30, 2025. The registration fee includes lessons, teaching materials and access to all evening events.',
            ),
            self::EDITION_2024_REF => new UrbinoEditionRequestDTO(
                editionId: null,
                editionName: 'Urbino 2024',
                dateStart: '2024-07-21',
                dateEnd: '2024-08-04',
                isPublicVisible: false,
                enrollmentInfoIt: 'Le iscrizioni per la Settimana Musicale di Urbino 2024 sono chiuse. Si ringraziano tutti i partecipanti per la loro entusiastica adesione.',
                enrollmentInfoEn: 'Registrations for the Urbino Music Week 2024 are now closed. We thank all participants for their enthusiastic participation.',
            ),
        ];

        foreach ($editions as $ref => $dto) {
            $edition = $this->urbinoEditionService->create($dto);
            $this->addReference($ref, $edition);
        }
    }
}
