-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Lug 03, 2025 alle 18:31
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

-- --------------------------------------------------------

--
-- Struttura della tabella `anti_in_ordini`
--

CREATE TABLE `anti_in_ordini` (
  `id_ordine` int(10) UNSIGNED NOT NULL,
  `nome_anti` varchar(50) NOT NULL,
  `num_anti` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `bevanda`
--

CREATE TABLE `bevanda` (
  `nome` varchar(50) NOT NULL,
  `litri` decimal(3,3) NOT NULL,
  `descrizione` varchar(200) DEFAULT 'inserire descrizione',
  `tipologia` enum('lattina','bottiglia') DEFAULT NULL,
  `prezzo` decimal(8,2) NOT NULL DEFAULT 0.00,
  `disponibile` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `bevande_in_ordini`
--

CREATE TABLE `bevande_in_ordini` (
  `id_ordine` int(10) UNSIGNED NOT NULL,
  `nome_bevanda` varchar(50) NOT NULL,
  `num_bevande` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `contorni_in_ordini`
--

CREATE TABLE `contorni_in_ordini` (
  `id_ordine` int(10) UNSIGNED NOT NULL,
  `nome_contorno` varchar(50) NOT NULL,
  `num_contorni` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
('admin', 'Può fare tutto'),
('user', 'Può solamente visualizzare il sito.');

-- --------------------------------------------------------

--
-- Struttura della tabella `ingrediente`
--

CREATE TABLE `ingrediente` (
  `nome` varchar(50) NOT NULL,
  `descrizione` varchar(500) DEFAULT 'inserire descrizione'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `tempo_cottura` time DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
('visualizzare', 'Solo visualizzare'),
('modificare', 'Solo modificare, non cancellare'),
('cancellare', 'Solo cancellare');

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
('admin', 'modificare'),
('admin', 'visualizzare'),
('user', 'visualizzare');

-- --------------------------------------------------------

--
-- Struttura della tabella `recensione`
--

CREATE TABLE `recensione` (
  `id` int(11) NOT NULL,
  `stelle` int(11) NOT NULL DEFAULT 5,
  `commento` varchar(200) NOT NULL DEFAULT ' ',
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `foto` varchar(200) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `secondi_in_ordini`
--

CREATE TABLE `secondi_in_ordini` (
  `id_ordine` int(10) UNSIGNED NOT NULL,
  `nome_secondo` varchar(50) NOT NULL,
  `num_secondi` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `str_preferenze` int(11) NOT NULL,
  `gruppo` varchar(50) NOT NULL,
  `recensione` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`email`, `password`, `nickname`, `nome`, `cognome`, `foto_profilo`, `bio`, `favorite_pizza`, `str_preferenze`, `gruppo`, `recensione`) VALUES
('default@email.com', 'default1234', 'defaulty', 'default_nome', 'default_nome', NULL, 'Default bio', NULL, -1, 'user', NULL),
('cotugnol90@gmail.com', '$2y$10$0EmZqmsmdPO42DLgd/iiCe4nEuly0RTunRecgsJbUtaPSuowC7SHC', 'leocot', 'leonardo', 'cotugno', 'images/profilo/cotugnol90_gmail_com.jpg', 'Account di prova di cotugno', NULL, 0, 'admin', NULL),
('prova1@gmail.com', '$2y$10$vupesn2Gu1nPVfvWA8Hsu.xBQOHi8GCKfd4qTTg9kxXBxCT..sOjC', 'prova', 'default', 'default', NULL, 'inserisci una bio', NULL, -1, 'user', NULL),
('prova2@gmail.com', '$2y$10$LDNXR9W36sk0PtNqruOVueODbHIKAqHJKl2dDCqOT0YQNFCxA8SG.', 'prova', 'default', 'default', NULL, 'inserisci una bio', NULL, -1, 'user', NULL),
('prova3@gmail.com', '$2y$10$W50Rcl4UGHiWf8NQlo/kjemfo/KGKZMQU7KhJkDiElpLgWbfoapuq', 'prova3', 'default', 'default', 'images/profilo/default.jpg', 'inserisci una bio', NULL, -1, 'user', NULL),
('prova4@gmail.com', '$2y$10$cU4iKP4XeSqTaPRJcAbhie6irKi5X.HLTGADF793OofRcfa1i5JM.', 'leocot', 'Cotugno', 'Leonardo', 'images/profilo/default.jpg', 'inserisci una bio', NULL, -1, 'user', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `prenotazione`
--
ALTER TABLE `prenotazione`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `recensione`
--
ALTER TABLE `recensione`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `str_preferenze`
--
ALTER TABLE `str_preferenze`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
