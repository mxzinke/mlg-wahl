-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 17. Apr 2019 um 18:26
-- Server-Version: 10.1.38-MariaDB
-- PHP-Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `mlg-wahl`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `entries`
--

CREATE TABLE `entries` (
  `eid` int(11) NOT NULL,
  `pid` smallint(6) NOT NULL,
  `uid` int(11) NOT NULL,
  `ctimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rights`
--

CREATE TABLE `rights` (
  `rid` int(11) NOT NULL,
  `rname` varchar(64) NOT NULL,
  `description` varchar(64) NOT NULL,
  `permission` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `rights`
--

INSERT INTO `rights` (`rid`, `rname`, `description`, `permission`) VALUES
(1, 'access_settings', 'Zugriff zu den Einstellungen', 50),
(2, 'ableto_delete_self', 'Sich selbst aus der Liste löschen', 0),
(3, 'ableto_delete_others', 'Andere aus der Liste löschen', 50),
(4, 'access_users', 'Zugriff zur Nutzerübersicht', 80),
(5, 'ableto_add_user', 'Nutzer hinzufügen', 80),
(6, 'ableto_edit_user', 'Nutzer bearbeiten', 80),
(7, 'ableto_del_user', 'Nutzer löschen', 80),
(8, 'access_usergroups', 'Zugriff zur Nutzergruppenübersicht', 80),
(9, 'ableto_add_usergroup', 'Nutzergruppe hinzufügen', 80),
(10, 'ableto_edit_usergroup', 'Nutzergruppe bearbeiten', 80),
(11, 'access_dev', 'Zugriff Entwicklerübersicht', 200),
(12, 'dev_state', 'Entwicklerstatus', 200),
(13, 'access_galerie', 'Zugriff zu den Galerieeinstellungen', 50),
(14, 'access_rights', 'Zugriff zu den Rechteeinstellungen', 80),
(15, 'ableto_edit_right', 'P-Points bearbeiten', 80);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `selection`
--

CREATE TABLE `selection` (
  `pid` int(11) NOT NULL,
  `pname` varchar(64) CHARACTER SET latin1 NOT NULL,
  `maxusers` tinyint(4) DEFAULT '10',
  `description` text CHARACTER SET latin1 NOT NULL,
  `need_class` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `selection`
--

INSERT INTO `selection` (`pid`, `pname`, `maxusers`, `description`, `need_class`) VALUES
(8, 'Wandern (Kl.5-7)', 12, 'Du bist naturverliebt und hast Spaß am wandern? Dann kannst du dies hier in Gemeinschaft genießen und auch noch nützliches über unsere Umgebung lernen.\r\n', NULL),
(9, 'Geocaching (Kl.5-9)', 11, 'Geo- Caching, GPS- Schnitzeljagd- beides klingt spaßig und das kannst du hier machen!\r\nACHTUNG: Buskosten können anfallen!', NULL),
(11, 'Tierschutz (Kl.5-9)', 10, 'Setzt euch für Tiere ein, die eine schlechte Vergangenheit hatten und bietet ihnen eine Zukunft.', NULL),
(12, 'Graffiti, Malen und Zeichnen (Kl.5-9)', 11, 'Entwirf eigene kreative Ideen und verwirkliche diese auf einer Leinwand.', NULL),
(13, 'T-Shirt Design (Kl.5-7)', 11, 'Du hast Lust aus der Masse zu stechen, dann kreiere hier deine eigenen Sachen.', NULL),
(14, 'Handwerken: mit Holz arbeiten Vogelhaus I und II (Kl.5-7)', 14, 'Du packst die Dinge gern selbst an und hast handwerkliches Talent?\r\nBau dir dein Vogelhäuschen aus Holz.\r\nVerziere es mit Keramikelementen.', NULL),
(15, 'Fotografie & Bearbeiten (Kl.7-9)', 8, 'Einen besonderen Moment festhalten, egal ob Portrait oder Natur. Lerne die verschiedensten Funktionen einer Kamera kennen und verbessere deine Kunstwerke im Nachhinein mit Bearbeitungsprogrammen', NULL),
(16, 'Holzhüttenbau (Kl.5-9)', 10, 'Raus aus der Schule und rein in die Hütte! Konstruiere und baue sie dir selbst.', NULL),
(17, 'Instrumente aus aller Welt (Kl.6-9)', 11, 'Du kannst kein Instrument spielen, bist aber weltoffen und interessiert? Dann bist du hier richtig!', NULL),
(18, 'Modelleisenbahn (Kl.5-9)', 8, 'Egal ob planen, bauen oder fahren – wenn du Eisenbahn magst. bist du hier richtig!', NULL),
(19, 'Schulhymne (Kl.8-9)', 10, 'Du hast Spaß an Musik und Experimenten? Entwirf eine Schulhymne, mit typischen Geräuschen deiner Schule und vielen anderen kreativen Elementen.', NULL),
(20, 'Theaterprojekt 1 (Kl.7-9)', 10, 'Interpretation, Text lernen – kein Problem? Theater ist deine Leidenschaft? Hier ist dein Projekt für die DRAMAtische Woche.', NULL),
(22, 'Kreatives Gestalten (Kl.5-9)', 11, 'Bilder, Schmuck, Deko für’s Zimmer...\r\nDeiner Phantasie sind keine Grenzen gesetzt.\r\nDo it yourself!', NULL),
(23, 'Fotogeschichte (Kl.5-9)', 11, 'Comics mal anders- sei Teil deines eigenen Comics und lebe deine Kreativität komplett aus. Welche Rolle wirst Du sein?', NULL),
(24, 'Manga/ Anime (Kl.5-9)', 11, 'Die japanischen Comics sind deine Lieblingslektüre? Dann komm in dieses Projekt! Zeichne deine eigenen Animes und beschäftige dich mit der japanischen Kultur.', NULL),
(25, 'Zimmer gestalten (Kl. 8-9)', 11, 'Du bist auch gelangweilt von unseren Klassenzimmern? Dann packe doch die Sache selbst an und hilf mit unsere Schule zu verschönern!', NULL),
(26, 'Mode im Wandel der Zeiten (Kl.6-9)', 11, 'Mode ist dein Ding? Dann begib dich doch mal in andere Zeiten der Modewelt. Recherchiere, zeichne und stelle evtl. ein typisches Accessoire her.', NULL),
(27, 'Erste Hilfe (Kl.5-9)', 10, 'Erlerne die wichtigsten Grundlagen und probe mit Spaß den Ernstfall.', NULL),
(28, 'Kochen/ Gesunde Ernährung (Kl.5-6)', 7, 'Lass dich von ausgewogener Ernährung und ihrer Vorteile inspirieren und kreiere eigene Rezepte.\r\nACHTUNG: Ca. 7€ für Zutaten sind zu zahlen.', NULL),
(29, 'Küche international (Kl.8-9)', 10, 'Reise durch andere kulinarische Welten!\r\nProbiere dich durch die internationale Küche.', NULL),
(30, 'Die Welt der Dinosaurier (Kl.5-7)', 11, 'Tauche in die Zeit der Dinosaurier ein lerne tolle Fakten über die Dinosaurierarten.', NULL),
(31, 'Im alten Rom (Kl.5-7)', 11, 'Du warst vielleicht schon in Rom, aber sicherlich nicht im alten Rom? Rom einmal nicht wie im Unterricht erleben.', NULL),
(32, 'Schule früher vs. heute (Kl.6-9)', 10, '„Früher war alles besser.“ Wer hat diesen Satz noch nicht gehört? Aber ob es wirklich so ist, könnt ihr hier herausfinden.', NULL),
(33, 'Politik gestalten (Kl.8-9)', 11, 'Du interessierst dich für Politik? Dann erarbeite dir hier in der Gruppe dein eigenes politisches Programm und führe Diskussionen mit den anderen Gruppen.', NULL),
(34, 'Politik gestalten (Kl.7-9)', 10, 'Du kannst dir Politik erklären lassen, vielleicht von einem Politiker vor Ort?\r\nOder gestalte dein eigenes politisches Programm.', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings`
--

CREATE TABLE `settings` (
  `sid` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `settings`
--

INSERT INTO `settings` (`sid`, `name`, `value`) VALUES
(1, 'max_entries', '1'),
(2, 'state_blocked', 'on'),
(3, 'wahl_infotext', 'Die Einschreibung zu den Projekten findet am [b]03.05.2018 ab 7:30 Uhr bis 04.05.2018 um 20 Uhr[/b] statt.\r\nJeder Schüler kann sich für genau [b]1 Projekt[/b] einschreiben (und sich ggf. auch wieder herauslöschen, falls ein falsches Projekt gewählt wurde).\r\nJeder Schüler ist selbst dafür verantwortlich, ein Projekt mit einer [b]geeigneten Klassenstufe[/b] zu wählen. Wer sich in einem Projekt mit falscher Klassenstufe einträgt, wird später gelöscht und von uns zugeteilt.\r\n[p] ACHTUNG! Es wurden [b] neue Plätze [/b] eröffnet. Bitte prüft noch einmal, ob ihr euch vielleicht neu eintragen möchtet!\r\n\r\n(Vorher alten Eintrag löschen!)'),
(4, 'website_title', 'Martin-Luther-Gymnasium Hartha'),
(5, 'galerie_password', 'Herbstwind2018'),
(6, 'timerstart', '1553607840'),
(7, 'timerend', '1553694240');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sites`
--

CREATE TABLE `sites` (
  `sid` int(11) NOT NULL,
  `title` varchar(64) NOT NULL DEFAULT 'Titel',
  `content` text NOT NULL,
  `rank` int(11) NOT NULL,
  `is_homepage` tinyint(1) DEFAULT NULL,
  `undersite_of` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `sites`
--

INSERT INTO `sites` (`sid`, `title`, `content`, `rank`, `is_homepage`, `undersite_of`) VALUES
(1, 'Startseite', '[b]Herzlich Willkommen auf der Internetseite des Martin-Luther-Gymnasiums Hartha.[/b][br]\r\n[br]\r\nAuf unserer Homepage sind viele Informationen rund um die Schule versammelt.\r\nHier finden Sie wichtige Termine, Links, die ihnen weiter helfen und Texte, bzw. Fotos, die ihnen einen Einblick in den schulischen Alltag unserer Schule geben.[br][br]\r\n[IMG]design/images/out.jpg[/IMG][br]\r\n', 1, 1, NULL),
(2, 'Neuigkeiten', '[NEWS][/NEWS]', 2, NULL, NULL),
(3, 'Schulprotrait', 'Das ist unsere Schule in den Unterpunkten gibt es noch paar mehr Info\'s!\r\n\r\n[B]Viel Spaß beim Stöbern[/B]', 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `usergroups`
--

CREATE TABLE `usergroups` (
  `gid` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `permission` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `usergroups`
--

INSERT INTO `usergroups` (`gid`, `name`, `permission`) VALUES
(1, 'Schüler', 0),
(2, 'Hausmeister', 50),
(3, 'Lehrer', 80),
(4, 'Schulverwaltung', 100),
(5, 'Entwickler', 200);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `uid` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` char(255) NOT NULL,
  `gid` tinyint(4) NOT NULL DEFAULT '1',
  `class` tinyint(4) DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`uid`, `username`, `password`, `gid`, `class`) VALUES
(1, 'developer', '4a872e209d4993b844ee152641f6f0fd37b8b79358e2573e7043e2dc7d0ddf83', 5, 12),
(2, 'Tob_Mer', 'e732ab669289b4508bb35b4c54a19d6f98cc9334a21647259b48a1230d2345e8', 3, 0);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `entries`
--
ALTER TABLE `entries`
  ADD PRIMARY KEY (`eid`),
  ADD KEY `uid` (`uid`) COMMENT 'User ID';

--
-- Indizes für die Tabelle `rights`
--
ALTER TABLE `rights`
  ADD PRIMARY KEY (`rid`),
  ADD UNIQUE KEY `rname` (`rname`);

--
-- Indizes für die Tabelle `selection`
--
ALTER TABLE `selection`
  ADD PRIMARY KEY (`pid`);

--
-- Indizes für die Tabelle `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`sid`);

--
-- Indizes für die Tabelle `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`sid`),
  ADD UNIQUE KEY `is_homepage` (`is_homepage`),
  ADD UNIQUE KEY `is_homepage_2` (`is_homepage`);

--
-- Indizes für die Tabelle `usergroups`
--
ALTER TABLE `usergroups`
  ADD PRIMARY KEY (`gid`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `entries`
--
ALTER TABLE `entries`
  MODIFY `eid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `rights`
--
ALTER TABLE `rights`
  MODIFY `rid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT für Tabelle `selection`
--
ALTER TABLE `selection`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT für Tabelle `settings`
--
ALTER TABLE `settings`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT für Tabelle `sites`
--
ALTER TABLE `sites`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `usergroups`
--
ALTER TABLE `usergroups`
  MODIFY `gid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
