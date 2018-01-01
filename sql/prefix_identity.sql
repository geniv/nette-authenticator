-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Počítač: localhost:3306
-- Vytvořeno: Pon 01. led 2018, 19:19
-- Verze serveru: 10.1.26-MariaDB-0+deb9u1
-- Verze PHP: 7.0.19-1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `netteweb`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `prefix_identity`
--

CREATE TABLE `prefix_identity` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `login` varchar(50) DEFAULT NULL COMMENT 'login',
  `hash` varchar(100) DEFAULT NULL COMMENT 'otisk hesla',
  `id_role` bigint(20) UNSIGNED DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL COMMENT 'jmeno uzivatele',
  `email` varchar(100) DEFAULT NULL COMMENT 'email',
  `active` tinyint(1) DEFAULT '0' COMMENT 'aktivni',
  `added` datetime DEFAULT NULL COMMENT 'pridano'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='tabulka uzivatelu pro prihlaseni do administrace webu';

--
-- Vypisuji data pro tabulku `prefix_identity`
--


--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `prefix_identity`
--
ALTER TABLE `prefix_identity`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login_UNIQUE` (`login`),
  ADD KEY `fk_identity_acl_role_idx` (`id_role`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `prefix_identity`
--
ALTER TABLE `prefix_identity`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `prefix_identity`
--
ALTER TABLE `prefix_identity`
  ADD CONSTRAINT `fk_identity_acl_role` FOREIGN KEY (`id_role`) REFERENCES `prefix_acl_role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
