<?php

namespace App\DataFixtures;

use App\DTO\Admin\UrbinoEventRequestDTO;
use App\Entity\UrbinoEdition;
use App\Entity\UrbinoEvent;
use App\Service\UrbinoEventService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UrbinoEventFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UrbinoEventService $urbinoEventService,
    ) {}

    public function getDependencies(): array
    {
        return [
            UrbinoEditionFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        /** @var UrbinoEdition $edition2025 */
        $edition2025 = $this->getReference(UrbinoEditionFixtures::EDITION_2025_REF, UrbinoEdition::class);

        $events = [
            new UrbinoEventRequestDTO(
                urbinoEditionId: $edition2025->getId(),
                eventDatetime: '2025-07-20 21:00:00',
                title: 'Concerto inaugurale',
                subtitleIt: 'Concerto Italiano diretto da Rinaldo Alessandrini',
                subtitleEn: 'Concerto Italiano directed by Rinaldo Alessandrini',
                locationShort: 'Cortile del Palazzo Ducale',
                locationLong: 'Cortile del Palazzo Ducale, Piazza Duca Federico, Urbino (PU)',
                descriptionIt: 'Il concerto inaugurale della Settimana Musicale di Urbino 2025 vedrà sul palco il Concerto Italiano con un programma dedicato a Claudio Monteverdi e Carlo Gesualdo da Venosa. Un viaggio nel madrigale italiano tra Cinque e Seicento.',
                descriptionEn: 'The opening concert of the Urbino Music Week 2025 will feature Concerto Italiano with a programme dedicated to Claudio Monteverdi and Carlo Gesualdo da Venosa. A journey through the Italian madrigal between the 16th and 17th centuries.',
                isPublic: true,
                isDeleted: false,
                category: UrbinoEvent::EVENT_CATEGORY_FESTIVAL,
            ),
            new UrbinoEventRequestDTO(
                urbinoEditionId: $edition2025->getId(),
                eventDatetime: '2025-07-25 20:30:00',
                title: 'Serata di gala con i docenti',
                subtitleIt: 'I maestri in concerto: musica da camera e brani solistici',
                subtitleEn: 'Masters in concert: chamber music and solo pieces',
                locationShort: 'Teatro Sanzio',
                locationLong: 'Teatro Raffaello Sanzio, Via Puccinotti 36, Urbino (PU)',
                ticketLink: 'https://www.teatrosanzio.it/biglietti',
                descriptionIt: 'Una serata eccezionale in cui i docenti della Settimana Musicale si esibiranno insieme in un programma di musica da camera e brani solistici. Repertorio dal tardo Rinascimento al primo Classicismo.',
                descriptionEn: 'An exceptional evening in which the teachers of the Music Week will perform together in a programme of chamber music and solo pieces. Repertoire from the late Renaissance to early Classicism.',
                isPublic: true,
                isDeleted: false,
                category: UrbinoEvent::EVENT_CATEGORY_FESTIVAL,
            ),
            new UrbinoEventRequestDTO(
                urbinoEditionId: $edition2025->getId(),
                eventDatetime: '2025-07-30 18:00:00',
                title: 'Concerto degli allievi',
                subtitleIt: 'Gli studenti della Settimana Musicale in concerto',
                subtitleEn: 'Students of the Music Week in concert',
                locationShort: 'Sala del Trono',
                locationLong: 'Sala del Trono, Palazzo Ducale, Urbino (PU)',
                descriptionIt: 'Il tradizionale concerto degli allievi, momento culminante della settimana di studio. Gli studenti presenteranno i lavori svolti durante i corsi con i loro docenti.',
                descriptionEn: 'The traditional students\' concert, a culminating moment of the week of study. Students will present the work carried out during their courses with their teachers.',
                isPublic: true,
                isDeleted: false,
                category: UrbinoEvent::EVENT_CATEGORY_FESTIVAL,
            ),
            new UrbinoEventRequestDTO(
                urbinoEditionId: $edition2025->getId(),
                eventDatetime: '2025-08-03 21:00:00',
                title: 'Concerto di chiusura',
                subtitleIt: 'Arrivederci Urbino: concerto di commiato con tutti i docenti',
                subtitleEn: 'Arrivederci Urbino: farewell concert with all teachers',
                locationShort: 'Cortile del Palazzo Ducale',
                locationLong: 'Cortile del Palazzo Ducale, Piazza Duca Federico, Urbino (PU)',
                descriptionIt: 'Il grande concerto finale che chiude la Settimana Musicale di Urbino 2025. Tutti i docenti si riuniranno per un programma straordinario in collaborazione con gli allievi più meritevoli.',
                descriptionEn: 'The grand final concert closing the Urbino Music Week 2025. All teachers will come together for an extraordinary programme in collaboration with the most deserving students.',
                isPublic: false,
                isDeleted: false,
                category: UrbinoEvent::EVENT_CATEGORY_FESTIVAL,
            ),
        ];

        foreach ($events as $dto) {
            $this->urbinoEventService->create($dto);
        }
    }
}
