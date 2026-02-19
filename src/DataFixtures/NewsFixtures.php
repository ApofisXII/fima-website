<?php

namespace App\DataFixtures;

use App\DTO\Admin\NewsRequestDTO;
use App\Service\NewsService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NewsFixtures extends Fixture
{
    public function __construct(
        private readonly NewsService $newsService,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $news = [
            new NewsRequestDTO(
                newsId: null,
                titleIt: 'FIMA presenta la stagione concertistica 2025',
                titleEn: 'FIMA presents the 2025 concert season',
                bodyIt: 'La Fondazione Italiana Musica Antica è lieta di presentare la stagione concertistica 2025, ricca di appuntamenti dedicati alla musica barocca e rinascimentale in tutta Italia.',
                bodyEn: 'The Italian Foundation for Early Music is pleased to present the 2025 concert season, featuring a rich program of baroque and renaissance music events throughout Italy.',
                isPublic: true,
                isEvent: false,
            ),
            new NewsRequestDTO(
                newsId: null,
                titleIt: 'Masterclass di clavicembalo con Rinaldo Alessandrini',
                titleEn: 'Harpsichord masterclass with Rinaldo Alessandrini',
                bodyIt: 'Si terrà il prossimo 15 marzo presso il Conservatorio di Bologna una masterclass dedicata al clavicembalo barocco, tenuta dal maestro Rinaldo Alessandrini. Le iscrizioni sono aperte fino al 28 febbraio.',
                bodyEn: 'A masterclass dedicated to the baroque harpsichord will be held on March 15 at the Bologna Conservatory, led by maestro Rinaldo Alessandrini. Registrations are open until February 28.',
                isPublic: true,
                isEvent: true,
                eventDatetime: '2025-03-15 10:00:00',
            ),
            new NewsRequestDTO(
                newsId: null,
                titleIt: 'Concerto inaugurale della Settimana di Urbino',
                titleEn: 'Opening concert of the Urbino Week',
                bodyIt: 'La Settimana Musicale di Urbino si apre con un concerto straordinario nel Cortile del Palazzo Ducale. L\'ensemble Concerto Italiano eseguirà musiche di Monteverdi e Gesualdo da Venosa.',
                bodyEn: 'The Urbino Music Week opens with an extraordinary concert in the Courtyard of the Ducal Palace. The ensemble Concerto Italiano will perform works by Monteverdi and Gesualdo da Venosa.',
                isPublic: true,
                isEvent: true,
                eventDatetime: '2025-07-20 21:00:00',
            ),
            new NewsRequestDTO(
                newsId: null,
                titleIt: 'Bando di concorso per giovani esecutori di musica antica',
                titleEn: 'Competition for young early music performers',
                bodyIt: 'FIMA indice il Concorso Nazionale per Giovani Esecutori di Musica Antica 2025. Il concorso è aperto a musicisti under 30 specializzati in musica rinascimentale e barocca. Scadenza domande: 30 aprile 2025.',
                bodyEn: 'FIMA announces the National Competition for Young Early Music Performers 2025. Open to musicians under 30 specialised in Renaissance and Baroque music. Application deadline: April 30, 2025.',
                isPublic: false,
                isEvent: false,
            ),
            new NewsRequestDTO(
                newsId: null,
                titleIt: 'Pubblicata la registrazione integrale del concerto di Urbino 2024',
                titleEn: 'Full recording of the 2024 Urbino concert now available',
                bodyIt: 'È ora disponibile sul canale YouTube di FIMA la registrazione integrale del concerto di chiusura della Settimana Musicale di Urbino 2024. Il programma, interamente dedicato a Henry Purcell, ha visto la partecipazione di oltre duecento spettatori.',
                bodyEn: 'The full recording of the closing concert of the 2024 Urbino Music Week is now available on the FIMA YouTube channel. The programme, entirely dedicated to Henry Purcell, was attended by over two hundred spectators.',
                isPublic: true,
                isEvent: false,
            ),
            new NewsRequestDTO(
                newsId: null,
                titleIt: 'Nuova borsa di studio per studenti under 25',
                titleEn: 'New scholarship for students under 25',
                bodyIt: 'FIMA, in collaborazione con la Fondazione Cariverona, istituisce una borsa di studio per coprire integralmente la quota di iscrizione alla Settimana Musicale di Urbino 2025. La borsa è destinata a studenti under 25 residenti in Italia. Domande entro il 15 marzo 2025.',
                bodyEn: 'FIMA, in collaboration with Fondazione Cariverona, establishes a scholarship to fully cover the registration fee for the Urbino Music Week 2025. The scholarship is open to students under 25 residing in Italy. Applications by March 15, 2025.',
                isPublic: true,
                isEvent: false,
            ),
            new NewsRequestDTO(
                newsId: null,
                titleIt: 'Conferenza: "La prassi esecutiva nel madrigale del Cinquecento"',
                titleEn: 'Conference: "Performance practice in the 16th-century madrigal"',
                bodyIt: 'Il professor Marco Bizzarini terrà una conferenza aperta al pubblico sul tema della prassi esecutiva nel madrigale cinquecentesco. L\'incontro si svolgerà il 22 febbraio alle ore 17:00 presso la Biblioteca Nazionale Marciana di Venezia.',
                bodyEn: 'Professor Marco Bizzarini will give a public lecture on performance practice in the sixteenth-century madrigal. The event will take place on February 22 at 5:00 PM at the Biblioteca Nazionale Marciana in Venice.',
                isPublic: true,
                isEvent: true,
                eventDatetime: '2025-02-22 17:00:00',
            ),
            new NewsRequestDTO(
                newsId: null,
                titleIt: 'FIMA collabora con il Festival di Bruges 2025',
                titleEn: 'FIMA collaborates with the Bruges Festival 2025',
                bodyIt: 'La Fondazione Italiana Musica Antica è lieta di annunciare una nuova partnership con il Musica Antiqua Brugge Festival. Nell\'ambito di questa collaborazione, tre ensemble italiani selezionati da FIMA si esibiranno nel programma principale del festival belga nel mese di agosto.',
                bodyEn: 'The Italian Foundation for Early Music is pleased to announce a new partnership with the Musica Antiqua Brugge Festival. As part of this collaboration, three Italian ensembles selected by FIMA will perform in the main programme of the Belgian festival in August.',
                isPublic: true,
                isEvent: false,
            ),
            new NewsRequestDTO(
                newsId: null,
                titleIt: 'Disponibile il nuovo catalogo discografico FIMA 2025',
                titleEn: 'New FIMA 2025 discography catalogue now available',
                bodyIt: 'È online il catalogo aggiornato delle pubblicazioni discografiche sostenute da FIMA negli ultimi dieci anni. Il catalogo include oltre settanta titoli tra CD e registrazioni digitali, con musiche dal Medioevo al tardo Barocco, realizzate da ensemble italiani.',
                bodyEn: 'The updated catalogue of discographic publications supported by FIMA over the past ten years is now online. The catalogue includes over seventy titles in CD and digital recordings, featuring music from the Middle Ages to the late Baroque, by Italian ensembles.',
                isPublic: true,
                isEvent: false,
            ),
            new NewsRequestDTO(
                newsId: null,
                titleIt: 'Seminario gratuito: introduzione agli strumenti storici',
                titleEn: 'Free seminar: introduction to historical instruments',
                bodyIt: 'FIMA organizza un seminario gratuito di introduzione agli strumenti musicali storici, rivolto a studenti di conservatorio e appassionati. Il seminario si terrà il 10 aprile 2025 a Roma e includerà dimostrazioni pratiche con viola da gamba, liuto, clavicembalo e flauto di traversa.',
                bodyEn: 'FIMA organises a free introductory seminar on historical musical instruments, aimed at conservatory students and enthusiasts. The seminar will be held on April 10, 2025 in Rome and will include practical demonstrations with viola da gamba, lute, harpsichord and transverse flute.',
                isPublic: true,
                isEvent: true,
                eventDatetime: '2025-04-10 10:00:00',
            ),
            new NewsRequestDTO(
                newsId: null,
                titleIt: 'Completata la digitalizzazione dell\'archivio storico FIMA',
                titleEn: 'Digitisation of the FIMA historical archive completed',
                bodyIt: 'Si è concluso il progetto pluriennale di digitalizzazione dell\'archivio storico della Fondazione, avviato nel 2021. L\'archivio, che comprende locandine, programmi di sala, corrispondenze e fotografie dal 1972 a oggi, sarà presto accessibile online gratuitamente.',
                bodyEn: 'The multi-year project to digitise the Foundation\'s historical archive, launched in 2021, has been completed. The archive, comprising posters, concert programmes, correspondence and photographs from 1972 to the present, will soon be freely accessible online.',
                isPublic: true,
                isEvent: false,
            ),
            new NewsRequestDTO(
                newsId: null,
                titleIt: 'Concerto di Pasqua: musica sacra del Seicento italiano',
                titleEn: 'Easter concert: Italian sacred music of the 17th century',
                bodyIt: 'In occasione delle festività pasquali, FIMA presenta un concerto di musica sacra italiana del Seicento presso la Basilica di Santa Maria in Trastevere a Roma. L\'ensemble La Venexiana eseguirà mottetti e messe di Frescobaldi, Carissimi e Cavalli.',
                bodyEn: 'For the Easter celebrations, FIMA presents a concert of Italian sacred music from the 17th century at the Basilica of Santa Maria in Trastevere in Rome. The ensemble La Venexiana will perform motets and masses by Frescobaldi, Carissimi and Cavalli.',
                isPublic: true,
                isEvent: true,
                eventDatetime: '2025-04-20 18:30:00',
            ),
            new NewsRequestDTO(
                newsId: null,
                titleIt: 'Apertura iscrizioni Settimana Musicale di Urbino 2025',
                titleEn: 'Registrations open for the Urbino Music Week 2025',
                bodyIt: 'Sono ufficialmente aperte le iscrizioni alla Settimana Musicale di Urbino 2025. Quest\'anno sono disponibili corsi di violino barocco, clavicembalo, canto, violoncello barocco, oboe barocco e musica da camera. I posti sono limitati: si raccomanda di iscriversi entro il 31 maggio.',
                bodyEn: 'Registrations for the Urbino Music Week 2025 are officially open. This year courses are available in baroque violin, harpsichord, singing, baroque cello, baroque oboe and chamber music. Places are limited: early registration by May 31 is strongly recommended.',
                isPublic: true,
                isEvent: false,
            ),
            new NewsRequestDTO(
                newsId: null,
                titleIt: 'Premio FIMA 2025 per la ricerca musicologica',
                titleEn: 'FIMA 2025 Award for musicological research',
                bodyIt: 'FIMA indice la quarta edizione del Premio per la Ricerca Musicologica sulla Musica Antica, destinato a dottorandi e giovani ricercatori italiani e stranieri. Il premio, del valore di cinquemila euro, verrà assegnato alla migliore tesi di dottorato in materia di musica medievale, rinascimentale o barocca discussa nel 2024.',
                bodyEn: 'FIMA announces the fourth edition of the Award for Musicological Research on Early Music, open to Italian and international doctoral students and young researchers. The award, worth five thousand euros, will be given to the best doctoral thesis on medieval, Renaissance or Baroque music defended in 2024.',
                isPublic: false,
                isEvent: false,
            ),
        ];

        foreach ($news as $dto) {
            $this->newsService->create($dto);
        }
    }
}
