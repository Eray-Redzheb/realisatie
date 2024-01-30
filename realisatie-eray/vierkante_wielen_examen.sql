-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 30 jan 2024 om 16:25
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vierkante_wielen_examen`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `auto`
--

CREATE TABLE `auto` (
  `auto_id` int(11) NOT NULL,
  `merk` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `kenteken` varchar(255) NOT NULL,
  `soort_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gebruiker`
--

CREATE TABLE `gebruiker` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gebruikersnaam` varchar(255) NOT NULL,
  `wachtwoord` varchar(255) NOT NULL,
  `voornaam` varchar(255) NOT NULL,
  `tussenvoegsel` varchar(255) DEFAULT NULL,
  `achternaam` varchar(255) NOT NULL,
  `adres` varchar(255) NOT NULL,
  `woonplaats` varchar(255) NOT NULL,
  `postcode` varchar(255) NOT NULL,
  `huisnr` varchar(255) NOT NULL,
  `rol` int(11) NOT NULL,
  `exameninformatie` text DEFAULT NULL,
  `actief` int(11) NOT NULL,
  `geslaagd` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `gebruiker`
--

INSERT INTO `gebruiker` (`id`, `email`, `gebruikersnaam`, `wachtwoord`, `voornaam`, `tussenvoegsel`, `achternaam`, `adres`, `woonplaats`, `postcode`, `huisnr`, `rol`, `exameninformatie`, `actief`, `geslaagd`) VALUES
(3, 'bifa@mailinator.com', 'erik12', '$2y$10$Dgv1TXuWXKpyX7ARJUYWC.hMqNwu4waoSJXYGfXQsWvO.TZiRCnQq', 'asd', 'asd', 'asd', 'asd', 'asd', 'asd', 'asd', 0, NULL, 1, 0),
(4, 'test@test.com', 'bumza', '$2y$10$ZkQRw27.AWU.JWD1kcijXuRDYeNiK4lZDgCjSbj2hMaWuQGIzO3c2', 'Brenda', 'Chadwick Ross', 'Sayed', '28 Hortensialaan', 'Oudemanstraat', '5701WN', '2882', 1, NULL, 1, 0),
(5, 'pro2003theof@gmail.com', 'Eray', '$2y$10$cocAfQ65f2y27V/4AXff/.5puUdtXvu8rOiyclFXITLmGnO1vHGYS', 'Eray', 'I', 'Redzheb', 'test', 'test', 'test', 'test', 2, NULL, 1, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gebruiker_lespakket`
--

CREATE TABLE `gebruiker_lespakket` (
  `gebruiker_lespakket_id` int(11) NOT NULL,
  `gebruiker_id` int(11) NOT NULL,
  `lespakket_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `cancelled` tinyint(1) DEFAULT 0,
  `cancellation_reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `gebruiker_lespakket`
--

INSERT INTO `gebruiker_lespakket` (`gebruiker_lespakket_id`, `gebruiker_id`, `lespakket_id`, `created_at`, `cancelled`, `cancellation_reason`) VALUES
(3, 3, 1, '2024-01-29 23:21:21', 1, 'asdfafadfab  a afadf'),
(4, 4, 4, '2024-01-29 23:54:03', 0, NULL),
(5, 4, 2, '2024-01-29 23:54:07', 0, NULL),
(6, 3, 5, '2024-01-30 00:52:53', 1, 'adfadfadf'),
(7, 3, 3, '2024-01-30 01:34:56', 1, 'this is a canellation'),
(8, 3, 6, '2024-01-30 01:37:30', 0, NULL),
(9, 5, 2, '2024-01-30 08:36:23', 0, NULL),
(10, 5, 1, '2024-01-30 08:53:04', 1, 'test'),
(11, 5, 5, '2024-01-30 08:58:08', 0, NULL),
(12, 5, 3, '2024-01-30 09:01:25', 0, NULL),
(13, 5, 6, '2024-01-30 09:05:18', 0, NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `les`
--

CREATE TABLE `les` (
  `les_id` int(11) NOT NULL,
  `lestijd` datetime NOT NULL,
  `ophaallocatie_id` int(11) NOT NULL,
  `instructeur_id` int(11) NOT NULL,
  `doel` text NOT NULL,
  `opmerking_student` text DEFAULT NULL,
  `opmerking_instructeur` text DEFAULT NULL,
  `lespakket_id` int(11) NOT NULL,
  `geannuleerd` int(11) NOT NULL,
  `reden_annuleren` text DEFAULT NULL,
  `auto_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `lespakket`
--

CREATE TABLE `lespakket` (
  `lespakket_id` int(11) NOT NULL,
  `naam` varchar(100) NOT NULL,
  `omschrijving` text NOT NULL,
  `aantal` int(11) NOT NULL,
  `prijs` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `lespakket`
--

INSERT INTO `lespakket` (`lespakket_id`, `naam`, `omschrijving`, `aantal`, `prijs`) VALUES
(1, 'Basispakket', 'Kennismaking met Autorijden', 10, 99.99),
(2, 'Tussenpakket', 'Fundamenten van Auto Rijden', 15, 149.99),
(3, 'Gevorderd Pakket', 'Beheersing van Autorijden', 20, 199.99),
(4, 'Pakket voor Rijexamen', 'Voorbereiding op Rijexamen', 12, 129.99),
(5, 'Mobiel Ontwikkelingspakket', 'Rijden met Mobiele Apps', 18, 179.99),
(6, 'Basispakket Een Les', 'Kennismaking met Autorijden', 1, 9.99);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `les_onderwerp`
--

CREATE TABLE `les_onderwerp` (
  `les_id` int(11) NOT NULL,
  `onderwerp_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `onderwerp`
--

CREATE TABLE `onderwerp` (
  `onderwerp_id` int(11) NOT NULL,
  `onderwerp` varchar(255) NOT NULL,
  `omschrijving` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ophaallocatie`
--

CREATE TABLE `ophaallocatie` (
  `ophaallocatie_id` int(11) NOT NULL,
  `adres` varchar(255) NOT NULL,
  `postcode` varchar(255) NOT NULL,
  `plaats` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `soort`
--

CREATE TABLE `soort` (
  `soort_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ziekmelding`
--

CREATE TABLE `ziekmelding` (
  `ziekmelding_id` int(11) NOT NULL,
  `van` date NOT NULL,
  `tot` date DEFAULT NULL,
  `toelichting` text NOT NULL,
  `gebruiker_id` int(11) NOT NULL,
  `lespakket_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `ziekmelding`
--

INSERT INTO `ziekmelding` (`ziekmelding_id`, `van`, `tot`, `toelichting`, `gebruiker_id`, `lespakket_id`) VALUES
(1, '2024-01-30', '2024-01-30', 'asd', 5, 6),
(2, '2024-01-30', '2024-02-01', 'sick', 5, 6),
(3, '2024-01-31', '2024-02-01', 'test', 5, 6);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `auto`
--
ALTER TABLE `auto`
  ADD PRIMARY KEY (`auto_id`),
  ADD KEY `fk_auto_soort` (`soort_id`);

--
-- Indexen voor tabel `gebruiker`
--
ALTER TABLE `gebruiker`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `gebruiker_lespakket`
--
ALTER TABLE `gebruiker_lespakket`
  ADD PRIMARY KEY (`gebruiker_lespakket_id`,`gebruiker_id`,`lespakket_id`),
  ADD KEY `fk_gebruiker_lespakket_gebruiker` (`gebruiker_id`),
  ADD KEY `fk_gebruiker_lespakket_lespakket` (`lespakket_id`);

--
-- Indexen voor tabel `les`
--
ALTER TABLE `les`
  ADD PRIMARY KEY (`les_id`),
  ADD KEY `fk_les_instructeur` (`instructeur_id`),
  ADD KEY `fk_les_ophaallocatie` (`ophaallocatie_id`),
  ADD KEY `fk_les_lespakket` (`lespakket_id`),
  ADD KEY `fk_les_auto` (`auto_id`);

--
-- Indexen voor tabel `lespakket`
--
ALTER TABLE `lespakket`
  ADD PRIMARY KEY (`lespakket_id`);

--
-- Indexen voor tabel `les_onderwerp`
--
ALTER TABLE `les_onderwerp`
  ADD PRIMARY KEY (`les_id`,`onderwerp_id`),
  ADD KEY `fk_les_onderwerp_onderwerp` (`onderwerp_id`);

--
-- Indexen voor tabel `onderwerp`
--
ALTER TABLE `onderwerp`
  ADD PRIMARY KEY (`onderwerp_id`);

--
-- Indexen voor tabel `ophaallocatie`
--
ALTER TABLE `ophaallocatie`
  ADD PRIMARY KEY (`ophaallocatie_id`);

--
-- Indexen voor tabel `soort`
--
ALTER TABLE `soort`
  ADD PRIMARY KEY (`soort_id`);

--
-- Indexen voor tabel `ziekmelding`
--
ALTER TABLE `ziekmelding`
  ADD PRIMARY KEY (`ziekmelding_id`),
  ADD KEY `fk_ziekmelding_gebruiker` (`gebruiker_id`),
  ADD KEY `fk_ziekmelding_lespakket` (`lespakket_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `auto`
--
ALTER TABLE `auto`
  MODIFY `auto_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `gebruiker`
--
ALTER TABLE `gebruiker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `gebruiker_lespakket`
--
ALTER TABLE `gebruiker_lespakket`
  MODIFY `gebruiker_lespakket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT voor een tabel `les`
--
ALTER TABLE `les`
  MODIFY `les_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `lespakket`
--
ALTER TABLE `lespakket`
  MODIFY `lespakket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT voor een tabel `onderwerp`
--
ALTER TABLE `onderwerp`
  MODIFY `onderwerp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `ophaallocatie`
--
ALTER TABLE `ophaallocatie`
  MODIFY `ophaallocatie_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `soort`
--
ALTER TABLE `soort`
  MODIFY `soort_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `ziekmelding`
--
ALTER TABLE `ziekmelding`
  MODIFY `ziekmelding_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `auto`
--
ALTER TABLE `auto`
  ADD CONSTRAINT `fk_auto_soort` FOREIGN KEY (`soort_id`) REFERENCES `soort` (`soort_id`);

--
-- Beperkingen voor tabel `gebruiker_lespakket`
--
ALTER TABLE `gebruiker_lespakket`
  ADD CONSTRAINT `fk_gebruiker_lespakket_gebruiker` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruiker` (`id`),
  ADD CONSTRAINT `fk_gebruiker_lespakket_lespakket` FOREIGN KEY (`lespakket_id`) REFERENCES `lespakket` (`lespakket_id`);

--
-- Beperkingen voor tabel `les`
--
ALTER TABLE `les`
  ADD CONSTRAINT `fk_les_auto` FOREIGN KEY (`auto_id`) REFERENCES `auto` (`auto_id`),
  ADD CONSTRAINT `fk_les_instructeur` FOREIGN KEY (`instructeur_id`) REFERENCES `gebruiker` (`id`),
  ADD CONSTRAINT `fk_les_lespakket` FOREIGN KEY (`lespakket_id`) REFERENCES `lespakket` (`lespakket_id`),
  ADD CONSTRAINT `fk_les_ophaallocatie` FOREIGN KEY (`ophaallocatie_id`) REFERENCES `ophaallocatie` (`ophaallocatie_id`);

--
-- Beperkingen voor tabel `les_onderwerp`
--
ALTER TABLE `les_onderwerp`
  ADD CONSTRAINT `fk_les_onderwerp_les` FOREIGN KEY (`les_id`) REFERENCES `les` (`les_id`),
  ADD CONSTRAINT `fk_les_onderwerp_onderwerp` FOREIGN KEY (`onderwerp_id`) REFERENCES `onderwerp` (`onderwerp_id`);

--
-- Beperkingen voor tabel `ziekmelding`
--
ALTER TABLE `ziekmelding`
  ADD CONSTRAINT `fk_ziekmelding_gebruiker` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruiker` (`id`),
  ADD CONSTRAINT `fk_ziekmelding_lespakket` FOREIGN KEY (`lespakket_id`) REFERENCES `lespakket` (`lespakket_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
