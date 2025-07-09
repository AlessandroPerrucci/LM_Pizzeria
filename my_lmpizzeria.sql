-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Lug 07, 2025 alle 11:10
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_lmpizzeria`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `antipasto`
--

CREATE TABLE `antipasto` (
  `nome` varchar(50) NOT NULL,
  `disponibile` tinyint(1) NOT NULL DEFAULT 0,
  `descrizione` varchar(500) DEFAULT 'inserire descrizione',
  `prezzo` decimal(8,2) NOT NULL DEFAULT 0.00
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `antipasto`
--

INSERT INTO `antipasto` (`nome`, `disponibile`, `descrizione`, `prezzo`) VALUES
('Sagne e fajul', 1, 'La Toyota Corolla è una autovettura media prodotta dalla Toyota dal 1966 in diverse versioni. Dal 1997 è considerata l\'auto più venduta di tutti i tempi e la prima automobile ad aver superato la soglia delle 30 milioni di unità prodotte.', 1.00);

-- --------------------------------------------------------

--
-- Struttura della tabella `anti_in_ordini`
--

CREATE TABLE `anti_in_ordini` (
  `id_ordine` int(10) UNSIGNED NOT NULL,
  `nome_anti` varchar(50) NOT NULL,
  `num_anti` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `anti_in_ordini`
--

INSERT INTO `anti_in_ordini` (`id_ordine`, `nome_anti`, `num_anti`) VALUES
(13, 'Sagne e fajul', 1),
(14, 'Sagne e fajul', 1),
(17, 'Sagne e fajul', 1),
(18, 'Sagne e fajul', 1),
(19, 'Sagne e fajul', 1),
(20, 'Sagne e fajul', 1),
(21, 'Sagne e fajul', 1),
(22, 'Sagne e fajul', 1),
(23, 'Sagne e fajul', 1),
(24, 'Sagne e fajul', 1),
(25, 'Sagne e fajul', 1),
(26, 'Sagne e fajul', 1),
(27, 'Sagne e fajul', 1),
(28, 'Sagne e fajul', 1),
(29, 'Sagne e fajul', 1),
(30, 'Sagne e fajul', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `bevanda`
--

CREATE TABLE `bevanda` (
  `nome` varchar(50) NOT NULL,
  `centilitri` int(11) NOT NULL,
  `descrizione` varchar(2000) DEFAULT 'inserire descrizione',
  `tipologia` enum('lattina','bottiglia') DEFAULT NULL,
  `prezzo` decimal(8,2) NOT NULL DEFAULT 0.00,
  `disponibile` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `bevanda`
--

INSERT INTO `bevanda` (`nome`, `centilitri`, `descrizione`, `tipologia`, `prezzo`, `disponibile`) VALUES
('bebsi', 8, 'Tiziano Ferro (Latina, 21 febbraio 1980) è un cantautore, paroliere e produttore discografico italiano.\r\n\r\nSalito alla ribalta grazie al singolo di debutto Xdono, che ottenne il terzo posto nella classifica dei singoli più venduti in Europa nel 2002, è ritenuto uno dei più influenti e innovativi cantautori italiani contemporanei. L\'alta popolarità del suo repertorio lo ha reso tra gli artisti italiani più apprezzati e famosi in Italia e nel mondo, nonché un punto di riferimento della musica internazionale.', 'lattina', 1.00, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `bevande_in_ordini`
--

CREATE TABLE `bevande_in_ordini` (
  `id_ordine` int(10) UNSIGNED NOT NULL,
  `nome_bevanda` varchar(50) NOT NULL,
  `num_bevande` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `bevande_in_ordini`
--

INSERT INTO `bevande_in_ordini` (`id_ordine`, `nome_bevanda`, `num_bevande`) VALUES
(30, 'bebsi', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `contorni_in_ordini`
--

CREATE TABLE `contorni_in_ordini` (
  `id_ordine` int(10) UNSIGNED NOT NULL,
  `nome_contorno` varchar(50) NOT NULL,
  `num_contorni` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `contorni_in_ordini`
--

INSERT INTO `contorni_in_ordini` (`id_ordine`, `nome_contorno`, `num_contorni`) VALUES
(19, 'Patatine fritte', 1),
(20, 'Patatine fritte', 1),
(21, 'Patatine fritte', 1),
(22, 'Patatine fritte', 1),
(23, 'Patatine fritte', 1),
(24, 'Patatine fritte', 1),
(25, 'Patatine fritte', 1),
(26, 'Patatine fritte', 1),
(27, 'Patatine fritte', 1),
(28, 'Patatine fritte', 1),
(29, 'Patatine fritte', 1),
(30, 'Patatine fritte', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `contorno`
--

CREATE TABLE `contorno` (
  `nome` varchar(50) NOT NULL,
  `disponibile` tinyint(1) NOT NULL DEFAULT 0,
  `descrizione` varchar(200) DEFAULT 'inserire descrizione',
  `prezzo` decimal(8,2) NOT NULL DEFAULT 0.00
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `contorno`
--

INSERT INTO `contorno` (`nome`, `disponibile`, `descrizione`, `prezzo`) VALUES
('Patatine fritte', 1, 'Sono patate, ma piccole, quindi ecco il diminutivo, e fritte, e quindi ecco il fritte', 20.00);

-- --------------------------------------------------------

--
-- Struttura della tabella `gruppo`
--

CREATE TABLE `gruppo` (
  `nome` varchar(50) NOT NULL,
  `descrizione` varchar(200) DEFAULT 'inserire descrizione'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `gruppo`
--

INSERT INTO `gruppo` (`nome`, `descrizione`) VALUES
('admin', 'Può fare tutto.'),
('user', 'Può fare alcune cose.'),
('bloggers', 'Gestiscono i blog.');

-- --------------------------------------------------------

--
-- Struttura della tabella `ingrediente`
--

CREATE TABLE `ingrediente` (
  `nome` varchar(50) NOT NULL,
  `descrizione` varchar(500) DEFAULT 'inserire descrizione'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `ingrediente`
--

INSERT INTO `ingrediente` (`nome`, `descrizione`) VALUES
('a', 'a');

-- --------------------------------------------------------

--
-- Struttura della tabella `ingredienti_pizze`
--

CREATE TABLE `ingredienti_pizze` (
  `nome_pizza` varchar(50) NOT NULL,
  `nome_ingrediente` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ordine_online`
--

CREATE TABLE `ordine_online` (
  `id` int(11) NOT NULL,
  `data_orario` datetime NOT NULL,
  `stato` enum('in_consegna','consegnato','in_preparazione','non_cominciato') DEFAULT 'non_cominciato',
  `note` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `ordine_online`
--

INSERT INTO `ordine_online` (`id`, `data_orario`, `stato`, `note`) VALUES
(3, '2025-07-03 22:56:51', 'in_preparazione', ''),
(4, '2025-07-03 22:56:52', 'in_preparazione', ''),
(5, '2025-07-04 22:17:13', 'in_preparazione', 'Ordine multiplo con 2 persone'),
(6, '2025-07-04 22:21:21', 'in_preparazione', 'Ordine multiplo con 1 persone'),
(7, '2025-07-04 22:27:56', 'in_preparazione', 'Ordine per: Alessandro'),
(8, '2025-07-04 22:27:58', 'in_preparazione', 'Ordine per: Alessandro'),
(9, '2025-07-04 22:28:08', 'in_preparazione', 'Ordine per: Alessandro'),
(10, '2025-07-04 22:28:50', 'in_preparazione', 'Ordine per: Alessandro'),
(11, '2025-07-04 22:29:30', 'in_preparazione', 'Ordine per: Alessandro'),
(12, '2025-07-04 22:30:18', 'in_preparazione', 'Ordine per: Alessandro'),
(13, '2025-07-04 22:31:13', 'in_preparazione', 'Ordine per: Alessandro'),
(14, '2025-07-04 22:31:14', 'in_preparazione', 'Ordine per: Alessandro'),
(15, '2025-07-04 22:34:45', 'in_preparazione', ''),
(16, '2025-07-04 22:39:34', 'in_preparazione', 'Ordine per: Alessandro, caparezza'),
(17, '2025-07-04 22:39:44', 'in_preparazione', 'Ordine per: Alessandro'),
(18, '2025-07-04 22:39:55', 'in_preparazione', 'Ordine per: Alessandro'),
(19, '2025-07-04 22:42:09', 'in_preparazione', 'Ordine per: Alessandro'),
(20, '2025-07-04 22:42:56', 'in_preparazione', 'Ordine per: Alessandro, cabarezza'),
(21, '2025-07-04 22:49:27', 'in_preparazione', 'Ordine per: Alessandro, cabarezza'),
(22, '2025-07-04 22:55:19', 'in_preparazione', 'Ordine per: Alessandro'),
(23, '2025-07-04 22:57:02', 'in_preparazione', 'Ordine per: Alessandro'),
(24, '2025-07-04 22:58:45', 'in_preparazione', 'Ordine per: Alessandro'),
(25, '2025-07-04 22:58:52', 'in_preparazione', 'Ordine per: Alessandro'),
(26, '2025-07-04 23:06:46', 'in_preparazione', 'Ordine per: Alessandro'),
(27, '2025-07-04 23:09:43', 'in_preparazione', 'Ordine per: Alessandro'),
(28, '2025-07-04 23:23:22', 'in_preparazione', 'Ordine per: Alessandro'),
(29, '2025-07-04 23:24:19', 'in_preparazione', 'Ordine per: Alessandro'),
(30, '2025-07-04 23:24:44', 'in_preparazione', 'Ordine per: Alessandro');

-- --------------------------------------------------------

--
-- Struttura della tabella `ordini_utenti`
--

CREATE TABLE `ordini_utenti` (
  `id_ordine` int(10) UNSIGNED NOT NULL,
  `email_utente` varchar(100) NOT NULL,
  `indirizzo` varchar(100) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `metodo_pagamento` enum('paypal','carta','bancomat','contanti') DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `pizza`
--

CREATE TABLE `pizza` (
  `nome` varchar(100) NOT NULL,
  `descrizione` varchar(1000) DEFAULT 'inserire descrizione',
  `prezzo` decimal(8,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `disponibile` tinyint(1) DEFAULT 0,
  `tempo_cottura` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `pizza`
--

INSERT INTO `pizza` (`nome`, `descrizione`, `prezzo`, `disponibile`, `tempo_cottura`) VALUES
('Margherita', 'my brother in christ è una margherita', 0.20, 1, 2000);

-- --------------------------------------------------------

--
-- Struttura della tabella `pizze_in_ordine`
--

CREATE TABLE `pizze_in_ordine` (
  `nome_pizza` varchar(50) NOT NULL,
  `id_ordine` int(10) UNSIGNED NOT NULL,
  `num_pizze` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `aggiunte` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `pizze_in_ordine`
--

INSERT INTO `pizze_in_ordine` (`nome_pizza`, `id_ordine`, `num_pizze`, `aggiunte`) VALUES
('Margherita', 3, 1, NULL),
('Margherita', 4, 1, NULL),
('Margherita', 5, 1, ' - Per: Alessandro'),
('Margherita', 6, 1, ' - Per: Alessandro'),
('Margherita', 19, 1, NULL),
('Margherita', 20, 1, NULL),
('Margherita', 21, 1, NULL),
('Margherita', 22, 1, NULL),
('Margherita', 23, 1, NULL),
('Margherita', 24, 1, NULL),
('Margherita', 25, 1, NULL),
('Margherita', 26, 1, NULL),
('Margherita', 27, 1, NULL),
('Margherita', 28, 1, NULL),
('Margherita', 29, 1, NULL),
('Margherita', 30, 1, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `prenotazione`
--

CREATE TABLE `prenotazione` (
  `id` int(11) NOT NULL,
  `data_` date NOT NULL,
  `periodo` enum('AM','PM') NOT NULL,
  `num_persone` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `note` varchar(200) DEFAULT '',
  `email_utente` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `prenotazione`
--

INSERT INTO `prenotazione` (`id`, `data_`, `periodo`, `num_persone`, `note`, `email_utente`) VALUES
(1, '2025-07-07', 'PM', 1, 'sdfsfsf', 'cotugnol90@gmail.com'),
(3, '2025-07-11', 'PM', 12, '', 'cotugnol90@gmail.com');

-- --------------------------------------------------------

--
-- Struttura della tabella `privilegio`
--

CREATE TABLE `privilegio` (
  `nome` varchar(50) NOT NULL,
  `descrizione` varchar(200) DEFAULT 'inserire una descrizione'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `privilegio`
--

INSERT INTO `privilegio` (`nome`, `descrizione`) VALUES
('lettura', 'Può solo visualizzare il sito.'),
('scrittura', 'Può modificare gli elementi del sito ma non cancellarli.'),
('cancellare', 'Può eliminare contenuti dal sito.'),
('modifica_blog', 'Può modificare i blog.');

-- --------------------------------------------------------

--
-- Struttura della tabella `privilegi_gruppo`
--

CREATE TABLE `privilegi_gruppo` (
  `nome_gruppo` varchar(50) NOT NULL,
  `nome_privilegio` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `privilegi_gruppo`
--

INSERT INTO `privilegi_gruppo` (`nome_gruppo`, `nome_privilegio`) VALUES
('admin', 'cancellare'),
('admin', 'lettura'),
('admin', 'modifica_blog'),
('admin', 'scrittura'),
('bloggers', 'lettura'),
('bloggers', 'modifica_blog'),
('bloggers', 'scrittura'),
('user', 'lettura');

-- --------------------------------------------------------

--
-- Struttura della tabella `recensione`
--

CREATE TABLE `recensione` (
  `id` int(11) NOT NULL,
  `stelle` int(11) NOT NULL DEFAULT 5,
  `commento` varchar(2000) DEFAULT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `foto` varchar(200) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `recensione`
--

INSERT INTO `recensione` (`id`, `stelle`, `commento`, `data`, `foto`) VALUES
(5, 4, 'sium da prova1', '2025-07-07 10:37:39', NULL),
(16, 5, 'Le caratteristiche della recensione. La recensione è un testo che analizza ed esprime giudizi su avvenimenti culturali (film, romanzi, libri, dischi, spettacoli teatrali, mostre di pittura...). Di solito pubblicata su giornali o\r\nriviste, presenta dimensioni contenute, stile discorsivo e non specialistico (le\r\nespressioni tecniche e settoriali sono spiegate perché il testo possa essere\r\ncompreso da un lettore non esperto). Lo scopo dell’emittente non è soltanto\r\nquello di fornire informazioni al lettore su un avvenimento culturale, ma anche\r\nquello di spingerlo a condividere il suo punto di vista sull’evento. Il testo deve\r\nrisultare convincente, quindi la recensione richiede conoscenze specifiche nell’ambito artistico o culturale in oggetto, poiché le opinioni espresse devono essere sostenute da argomentazioni fondate.', '2025-07-07 11:09:58', 'rec_686b8ee64114e.jpeg');

-- --------------------------------------------------------

--
-- Struttura della tabella `secondi_in_ordini`
--

CREATE TABLE `secondi_in_ordini` (
  `id_ordine` int(10) UNSIGNED NOT NULL,
  `nome_secondo` varchar(50) NOT NULL,
  `num_secondi` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `secondi_in_ordini`
--

INSERT INTO `secondi_in_ordini` (`id_ordine`, `nome_secondo`, `num_secondi`) VALUES
(25, 'Braciole di brace', 1),
(26, 'Braciole di brace', 1),
(27, 'Braciole di brace', 1),
(28, 'Braciole di brace', 1),
(29, 'Braciole di brace', 1),
(30, 'Braciole di brace', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `secondo`
--

CREATE TABLE `secondo` (
  `nome` varchar(50) NOT NULL,
  `disponibile` tinyint(1) NOT NULL DEFAULT 0,
  `descrizione` varchar(500) DEFAULT 'inserire descrizione',
  `prezzo` decimal(8,2) NOT NULL DEFAULT 0.00
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `secondo`
--

INSERT INTO `secondo` (`nome`, `disponibile`, `descrizione`, `prezzo`) VALUES
('Braciole di brace', 1, 'chiedo scusa a chi dovrà pulire gli shitpost che sto mettendo', 1.00);

-- --------------------------------------------------------

--
-- Struttura della tabella `str_preferenze`
--

CREATE TABLE `str_preferenze` (
  `id` int(11) NOT NULL,
  `dark_mode` tinyint(1) NOT NULL DEFAULT 0,
  `keep_logged` tinyint(1) NOT NULL DEFAULT 0,
  `pub` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `email` varchar(100) NOT NULL,
  `password` varchar(600) NOT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `cognome` varchar(50) DEFAULT NULL,
  `foto_profilo` varchar(500) DEFAULT NULL,
  `bio` varchar(1000) DEFAULT 'inserisci una bio',
  `favorite_pizza` varchar(50) DEFAULT NULL,
  `str_preferenze` int(11) DEFAULT NULL,
  `gruppo` varchar(50) NOT NULL,
  `recensione` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`email`, `password`, `nickname`, `nome`, `cognome`, `foto_profilo`, `bio`, `favorite_pizza`, `str_preferenze`, `gruppo`, `recensione`) VALUES
('cotugnol90@gmail.com', '$2y$10$t7h0CHgbYT0deYodpqQKRuAfk76WLl5Vm4ZbSGhLb3f4Hw0gaSZMy', 'bombardiroCrocodiro98', 'sium', 'sium', 'images/profilo/cotugnol90_gmail_com.jpg', 'inserisci una bio', NULL, -1, 'admin', 16),
('prova1@gmail.com', '$2y$10$AxQtpO0mUBBwZ0sp7bwsUeD3emBCFHzPpNG6S1zEcOSWphHWwRaum', 'prova1', 'prova1', 'prova1', 'images/profilo/default.jpg', 'inserisci una bio', NULL, -1, 'user', 5);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `antipasto`
--
ALTER TABLE `antipasto`
  ADD PRIMARY KEY (`nome`);

--
-- Indici per le tabelle `anti_in_ordini`
--
ALTER TABLE `anti_in_ordini`
  ADD PRIMARY KEY (`id_ordine`,`nome_anti`),
  ADD KEY `nome_anti` (`nome_anti`);

--
-- Indici per le tabelle `bevanda`
--
ALTER TABLE `bevanda`
  ADD PRIMARY KEY (`nome`);

--
-- Indici per le tabelle `bevande_in_ordini`
--
ALTER TABLE `bevande_in_ordini`
  ADD PRIMARY KEY (`id_ordine`,`nome_bevanda`),
  ADD KEY `nome_bevanda` (`nome_bevanda`);

--
-- Indici per le tabelle `contorni_in_ordini`
--
ALTER TABLE `contorni_in_ordini`
  ADD PRIMARY KEY (`id_ordine`,`nome_contorno`),
  ADD KEY `nome_contorno` (`nome_contorno`);

--
-- Indici per le tabelle `contorno`
--
ALTER TABLE `contorno`
  ADD PRIMARY KEY (`nome`);

--
-- Indici per le tabelle `gruppo`
--
ALTER TABLE `gruppo`
  ADD PRIMARY KEY (`nome`);

--
-- Indici per le tabelle `ingrediente`
--
ALTER TABLE `ingrediente`
  ADD PRIMARY KEY (`nome`);

--
-- Indici per le tabelle `ingredienti_pizze`
--
ALTER TABLE `ingredienti_pizze`
  ADD PRIMARY KEY (`nome_pizza`,`nome_ingrediente`),
  ADD KEY `nome_ingrediente` (`nome_ingrediente`);

--
-- Indici per le tabelle `ordine_online`
--
ALTER TABLE `ordine_online`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `ordini_utenti`
--
ALTER TABLE `ordini_utenti`
  ADD KEY `id_ordine` (`id_ordine`),
  ADD KEY `email_utente` (`email_utente`);

--
-- Indici per le tabelle `pizza`
--
ALTER TABLE `pizza`
  ADD PRIMARY KEY (`nome`);

--
-- Indici per le tabelle `pizze_in_ordine`
--
ALTER TABLE `pizze_in_ordine`
  ADD PRIMARY KEY (`nome_pizza`,`id_ordine`),
  ADD KEY `id_ordine` (`id_ordine`);

--
-- Indici per le tabelle `prenotazione`
--
ALTER TABLE `prenotazione`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_utente` (`email_utente`);

--
-- Indici per le tabelle `privilegio`
--
ALTER TABLE `privilegio`
  ADD PRIMARY KEY (`nome`);

--
-- Indici per le tabelle `privilegi_gruppo`
--
ALTER TABLE `privilegi_gruppo`
  ADD PRIMARY KEY (`nome_gruppo`,`nome_privilegio`),
  ADD KEY `nome_privilegio` (`nome_privilegio`);

--
-- Indici per le tabelle `recensione`
--
ALTER TABLE `recensione`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `secondi_in_ordini`
--
ALTER TABLE `secondi_in_ordini`
  ADD PRIMARY KEY (`id_ordine`,`nome_secondo`),
  ADD KEY `nome_secondo` (`nome_secondo`);

--
-- Indici per le tabelle `secondo`
--
ALTER TABLE `secondo`
  ADD PRIMARY KEY (`nome`);

--
-- Indici per le tabelle `str_preferenze`
--
ALTER TABLE `str_preferenze`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`email`),
  ADD KEY `str_preferenze` (`str_preferenze`),
  ADD KEY `gruppo` (`gruppo`),
  ADD KEY `recensione` (`recensione`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `ordine_online`
--
ALTER TABLE `ordine_online`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT per la tabella `prenotazione`
--
ALTER TABLE `prenotazione`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `recensione`
--
ALTER TABLE `recensione`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT per la tabella `str_preferenze`
--
ALTER TABLE `str_preferenze`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
