-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 20, 2026 at 10:26 PM
-- Server version: 5.7.44-48
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `loganmcc_scribe`
--

-- --------------------------------------------------------

--
-- Table structure for table `Characters`
--

CREATE TABLE `Characters` (
  `ID` int(11) NOT NULL,
  `isAt` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `race` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `gmNotes` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `partyNotes` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `img_src` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `visible` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Characters`
--

INSERT INTO `Characters` (`ID`, `isAt`, `name`, `race`, `description`, `gmNotes`, `partyNotes`, `img_src`, `visible`) VALUES
(1, 2, 'Wilhelm Riquet', 'human', 'thief', 'Passion for animals', 'Played by Steve', NULL, 0),
(2, 4, 'Commodore Stephahk', 'human', 'Recruitment officer at Fort Hranic', 'Rat who let attackers into fort', 'Weirdly chill guy', NULL, 0),
(3, 4, 'Renly Gokel', 'human', 'Recruit specializing in alchemy', 'Harmless?', 'He is an evil terrorist', NULL, 0),
(4, 2, 'Horacio Garzon', 'human', 'wine artisan', 'Passion for grapes', 'Played by George', 'imgs/horacio.png', 0),
(5, 2, 'Jovi', 'Ka’Tavin', 'Animal sold in Siwanilua', 'Will be used to train ‘Ride’ skill', 'Bought by Wilhelm', NULL, 0),
(6, 4, 'Olver Thumbless', 'human', 'Recruit who has no thumbs', 'Dies in Hranic Raid', 'His name is now ‘Nubs’', NULL, 0),
(7, 5, 'Xiarkydoth', 'spider', 'Spider in Myrantahl Forests', 'Beast Aliyra encounters?', 'Hostile forest creature, dangerous but avoidable', NULL, 0),
(8, 2, 'Leon Septar', 'human', 'Bouncer, detective', 'Passion for sneaking', 'Played by Henry', NULL, 0),
(9, 5, 'Aliyra Maastehr', 'ghord', 'Apothecary from Ghordeiol', 'Wife to Ephram and Mother to Obram and Ilen', 'Helpful apothecary, potential ally for potions and healing', NULL, 0),
(10, 4, 'Chef Mya', 'human', 'Chef at Fort Hranic', 'Head chef', 'Falls in love with Wilhelm', NULL, 0),
(11, 2, '', '', '', '', 'Cool cat that we must obtain', NULL, 0),
(12, 2, 'Healer Mam', 'elf', 'Decorated in trinkets and baubles', 'All poisons', 'Healer', NULL, 0),
(13, 2, 'Bram Ironfist', 'dwarf', 'Blacksmith in Fort Hranic', 'Provides weapons to guards', 'Gruff but reliable', NULL, 0),
(14, 3, 'Lysa Windmere', 'human', 'Traveling merchant', 'Knows trade routes', 'Often visits taverns', NULL, 0),
(15, 5, 'Torren Vale', 'elf', 'Forest scout', 'Watches for threats in Myrantahl Forest', 'Quiet and observant', NULL, 0),
(16, 9, 'Velcifer', 'Herdazian', 'Agent/Hunter', 'Serial Looter', 'Has not spoken an oath, mysterious backstory about Herdazian navy', NULL, 0),
(17, 9, 'Ylt', 'Iriali', 'Tall man with golden skin', 'wields a stolen honor blade', 'Killed Brad Pitt', NULL, 0),
(18, 2, 'Johny Long-Legged', 'human', 'A humble human with muscly legs contributing to 90% of his height', 'Orphan of a giant and halfling', 'Silly guy', NULL, 0),
(19, 10, 'Sanjal', 'Kua Lorantene', 'Leader of the Syndicate of Siwanilua', 'Working to take down slaveowners', '', 'imgs/npc_default.png', 1),
(20, 3, 'Priest Alquiv', '', 'The tender of the gardens and worshipper of Azkenilua', '', '', 'imgs/npc_default.png', 1),
(21, 11, 'Nili', '', 'Member of Syndicate of Siwanilua', '', 'Hold', 'imgs/npc_default.png', 0),
(22, 12, 'Nij\'Gama', '', 'Massive tortoise that buries in the sand dunes', '', '', 'imgs/creature_default.png', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Contains`
--

CREATE TABLE `Contains` (
  `container` int(11) NOT NULL,
  `containee` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Contains`
--

INSERT INTO `Contains` (`container`, `containee`) VALUES
(1, 2),
(1, 4),
(1, 11),
(1, 12),
(2, 3),
(2, 10),
(4, 7),
(6, 1),
(6, 5);

-- --------------------------------------------------------

--
-- Table structure for table `Creatures`
--

CREATE TABLE `Creatures` (
  `ID` int(11) NOT NULL,
  `population` int(11) NOT NULL,
  `ability` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Creatures`
--

INSERT INTO `Creatures` (`ID`, `population`, `ability`) VALUES
(5, 1, 'Pincer Tail Attack'),
(7, 1, 'Sticky Web Grab'),
(11, 1000, 'Jumping Claw Grab'),
(15, 30, 'Bite Attack'),
(16, 10, 'Venom Strike'),
(17, 60, 'Camouflage'),
(22, 0, '');

-- --------------------------------------------------------

--
-- Stand-in structure for view `dm_inventory_view`
-- (See below for the actual view)
--
CREATE TABLE `dm_inventory_view` (
`OwnerName` varchar(50)
,`ItemName` varchar(50)
,`ItemType` varchar(50)
,`ItemDescription` varchar(1000)
,`ItemNotes` varchar(1000)
,`QuantityOwned` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `Locations`
--

CREATE TABLE `Locations` (
  `ID` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `gmNotes` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `partyNotes` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `img_src` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `visible` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Locations`
--

INSERT INTO `Locations` (`ID`, `name`, `description`, `gmNotes`, `partyNotes`, `img_src`, `visible`) VALUES
(1, 'Kua Loranta', 'Desert nation ruled by the Kua Lorantene peoples', 'Under control of the foreign power Hrace', 'People have venomous spikes', 'imgs/kua_loranta.png', 1),
(2, 'Siwanilua', 'Wealthy city ruled by Queen Yttrilyna', 'Hrace has soft control over the Queen', 'The royalty are rich but the people are poor', NULL, 1),
(3, 'Hanging Gardens of Azkenilua', 'Wonder built to the Goddess Azkenilua', 'Pilgrimage site', 'They do not grow grapes here', NULL, 1),
(4, 'Hranic Fort', 'Military island off the coast of Kua Loranta', 'Session start. Raided by Kua Lorantene attackers', 'Renly is here and he must be dangerous…', NULL, 1),
(5, 'Ghordeiol', 'Northern country also soft controlled by Hrace', 'Potential site for future campaigns', '', NULL, 0),
(6, 'The World', 'This is the big world that contains everything', 'The good and evil happens here', '', NULL, 0),
(7, 'Barracks', 'Barracks in a fort, weapons and stuff', '', '', NULL, 0),
(8, 'Completely Unrelated World', 'This world doesn\'t have anything yet', '', '', NULL, 0),
(9, 'Roshar', 'World where Stormlight Archives takes place', 'World for Stormlight tabletop RPG', 'Populations range from different kinds of Humans to crab people called Parshendi', NULL, 0),
(10, 'The Back of the Stage Tavern', 'Poor tavern in the Slums District of Siwanilua', '', '', 'imgs/location_default.png', 1),
(11, 'Ahn Janil', 'Small village northwest of Siwanilua', '', '', 'imgs/location_default.png', 0),
(12, 'Kalikiv Kalin Dunes', 'Sand dunes home of the Nij\'Gama', '', '', 'imgs/location_default.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `NPCs`
--

CREATE TABLE `NPCs` (
  `ID` int(11) NOT NULL,
  `opinions` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `occupation` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `gold` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `NPCs`
--

INSERT INTO `NPCs` (`ID`, `opinions`, `occupation`, `gold`) VALUES
(2, 'Cooperative with Hrace, indifferent to recruits', 'Military Recruit Commander', 0),
(3, 'Friendly facade, secretly plotting destruction', 'Alchemist in Training', 0),
(6, 'Eager to prove himself despite disability', 'Chef in Training', 0),
(9, 'Caring and knowledgeable, misses her family', 'Apothecary', 0),
(10, 'Warm and welcoming, develops feelings for Wilhelm', 'Chef', 50),
(11, 'Strict but fair leader of the guards', 'Guard Captain', 0),
(12, 'Kind healer who helps the poor', 'Healer', 0),
(19, '', '', 50),
(20, '', '', 0),
(21, '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Players`
--

CREATE TABLE `Players` (
  `ID` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `playedBy` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `combat_style` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Players`
--

INSERT INTO `Players` (`ID`, `level`, `playedBy`, `combat_style`) VALUES
(1, 3, 'Steve', 'Hracian Street Rat'),
(4, 3, 'George', 'Hracian Recruit'),
(8, 3, 'Henry', 'Hracian Bad Cop');

-- --------------------------------------------------------

--
-- Stand-in structure for view `player_NPC_view`
-- (See below for the actual view)
--
CREATE TABLE `player_NPC_view` (
`Name` varchar(50)
,`Race` varchar(50)
,`Description` varchar(1000)
,`Notes` varchar(1000)
,`Location` varchar(50)
);

-- --------------------------------------------------------

--
-- Table structure for table `PlayingIn`
--

CREATE TABLE `PlayingIn` (
  `user` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `plays` int(11) DEFAULT NULL,
  `world` int(11) NOT NULL,
  `role` enum('gm','player') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `PlayingIn`
--

INSERT INTO `PlayingIn` (`user`, `plays`, `world`, `role`) VALUES
('Steve', 1, 6, 'player'),
('George', 4, 6, 'player'),
('Henry', 8, 6, 'player'),
('George', NULL, 1, 'gm'),
('GM Individual', NULL, 6, 'gm');

-- --------------------------------------------------------

--
-- Table structure for table `Props`
--

CREATE TABLE `Props` (
  `ID` int(11) NOT NULL,
  `isIn` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `gmNotes` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `partyNotes` varchar(1000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `itemType` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `rarity` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `owner` int(11) DEFAULT NULL,
  `img_src` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `visible` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Props`
--

INSERT INTO `Props` (`ID`, `isIn`, `name`, `description`, `gmNotes`, `partyNotes`, `itemType`, `rarity`, `quantity`, `owner`, `img_src`, `visible`) VALUES
(1, 2, 'Venom Spike', 'A spike harvested from a Kua Lorantene warrior', 'Can be used as a poison weapon', 'Found in Siwanilua market', 'Weapon', 'Uncommon', 3, 1, NULL, 0),
(2, 4, 'Alchemy Kit', 'A set of tools for brewing potions', 'Belongs to Renly', 'Confiscated after the raid', 'Tool', 'Common', 1, 3, NULL, 0),
(3, 2, 'Grape Wine', 'A fine bottle of Siwaniluan wine', 'Horacio made this himself', 'Worth good money', 'Consumable', 'Common', 5, 4, NULL, 0),
(4, 4, 'Fort Manifest', 'A list of all recruits at Hranic Fort', 'Contains evidence against Stephahk', 'We need to get this', 'Document', 'Rare', 1, 2, NULL, 0),
(5, 1, 'Desert Cloak', 'A cloak that blends into sand', 'Useful for desert travel', 'Bought in Kua Loranta', 'Armor', 'Common', 1, 8, NULL, 1),
(6, 4, 'Iron Sword', 'Standard weapon used by guards', 'Standard issue weapon kept in the fort armory', 'Useful if we need extra weapons', 'Weapon', 'Common', 1, 1, NULL, 0),
(7, 4, 'Healing Potion', 'Restores minor injuries', 'Stored by the fort medic', 'Can help after a fight', 'Consumable', 'Common', 1, 8, NULL, 0),
(8, 2, 'Travel Cloak', 'Protects against weather', 'Common travel gear sold by merchants', 'Good for long trips', 'Armor', 'Common', 1, NULL, NULL, 0),
(9, 4, 'Lantern', 'Provides light in dark areas', 'Used by guards and scouts at night', 'Helps light dark areas', 'Tool', 'Common', 2, NULL, NULL, 0),
(10, 4, 'Rope', 'Useful for climbing or tying', 'Stored with other travel and climbing gear', 'Could be useful for climbing or tying things down', 'Tool', 'Common', 1, NULL, NULL, 0),
(11, 5, 'Herbal Remedy Kit', 'Basic kit used to treat wounds and poison', 'Can stabilize characters without magic', 'Useful for travel and emergencies', 'Tool', 'Common', 1, NULL, NULL, 0),
(12, 1, 'Rock', 'Just a normal rock', 'Has a evil demon inside', '', 'Miscellaneous', 'Common', 1, 8, NULL, 1),
(13, 1, 'Grass', 'A single blade of grass', '', '', 'Miscellaneous', 'Common', 75, 8, NULL, 0),
(14, 1, 'Stale Sandwich', 'It doesn\'t taste great but it still edible', 'Spoiled and poisonous', 'May be spoiled', 'Consumable', 'Common', 3, 8, NULL, 0),
(15, 1, 'Bucket Hat', 'Protects from the sun', '', 'Same color as desert cloak', 'Armor', 'Uncommon', 1, 8, NULL, 0),
(16, 1, 'Rope', '1 foot of hempen rope', '', '', 'Tool', 'Common', 50, 8, NULL, 1),
(17, 1, 'Ancient Sword', 'There is myth of an ancient sword somewhere lost in this nation', 'Buried under a tree', '', 'Weapon', 'Rare', 1, NULL, NULL, 0),
(18, 1, 'Glasswork', 'Glassblowing is a prized art here, so it can be quite valuable when done well.', '', '', 'Miscellaneous', 'Uncommon', 4, NULL, NULL, 0),
(19, 10, 'Mr. Bones', 'A skeleton sitting on a chair', '', '', '', '', 1, NULL, 'imgs/props_default.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Users`
--
INSERT INTO `Users` (`username`, `password`) VALUES
('George', '$2y$12$.LGrUxPef5ynxKll25SAGOdWO5gtvL109p34ShGItQWaVcaOTpBfq'),
('GM Individual', '$2y$12$SpzkhrJh8sdTfnX7dOtlNeMjM7FZIcueFsCFUoqDeiX33Mp0JkjYu'),
('Henry', '$2y$12$2eFy.SQ9qEwoBRbmYZUfauTRN39raP1vTfjmDXmdC6m0Q0IHReDIS'),
('No Friends Larry', '$2y$12$C27ZHCkDCDswp9bVVAqXZeBLfIex3rqrziz8973RF6srjGopsT/.e'),
('Steve', '$2y$12$SpzkhrJh8sdTfnX7dOtlNeMjM7FZIcueFsCFUoqDeiX33Mp0JkjYu');



-- --------------------------------------------------------

--
-- Table structure for table `WorldSecurity`
--

CREATE TABLE `WorldSecurity` (
  `worldID` int(11) NOT NULL,
  `securityMeasure` enum('plain','hash') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'plain'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `WorldSecurity`
--

INSERT INTO `WorldSecurity` (`worldID`, `securityMeasure`) VALUES
(6, 'plain');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Characters`
--
ALTER TABLE `Characters`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `isAt` (`isAt`);

--
-- Indexes for table `Contains`
--
ALTER TABLE `Contains`
  ADD PRIMARY KEY (`containee`),
  ADD KEY `container` (`container`);

--
-- Indexes for table `Creatures`
--
ALTER TABLE `Creatures`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Locations`
--
ALTER TABLE `Locations`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `NPCs`
--
ALTER TABLE `NPCs`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Players`
--
ALTER TABLE `Players`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `playedBy` (`playedBy`);

--
-- Indexes for table `PlayingIn`
--
ALTER TABLE `PlayingIn`
  ADD KEY `user` (`user`),
  ADD KEY `plays` (`plays`),
  ADD KEY `inWorld` (`world`);

--
-- Indexes for table `Props`
--
ALTER TABLE `Props`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `isIn` (`isIn`),
  ADD KEY `ownedBy` (`owner`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `WorldSecurity`
--
ALTER TABLE `WorldSecurity`
  ADD PRIMARY KEY (`worldID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Characters`
--
ALTER TABLE `Characters`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `Locations`
--
ALTER TABLE `Locations`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `Props`
--
ALTER TABLE `Props`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

-- --------------------------------------------------------

--
-- Structure for view `dm_inventory_view`
--
DROP TABLE IF EXISTS `dm_inventory_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`loganmccue`@`localhost` SQL SECURITY DEFINER VIEW `dm_inventory_view`  AS SELECT `Characters`.`name` AS `OwnerName`, `Props`.`name` AS `ItemName`, `Props`.`itemType` AS `ItemType`, `Props`.`description` AS `ItemDescription`, `Props`.`gmNotes` AS `ItemNotes`, `Props`.`quantity` AS `QuantityOwned` FROM (`Characters` join `Props`) WHERE (`Props`.`owner` = `Characters`.`ID`) ORDER BY `Characters`.`name` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `player_NPC_view`
--
DROP TABLE IF EXISTS `player_NPC_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`loganmccue`@`localhost` SQL SECURITY DEFINER VIEW `player_NPC_view`  AS SELECT `Characters`.`name` AS `Name`, `Characters`.`race` AS `Race`, `Characters`.`description` AS `Description`, `Characters`.`partyNotes` AS `Notes`, `Locations`.`name` AS `Location` FROM (`Characters` join `Locations`) WHERE ((`Characters`.`isAt` = `Locations`.`ID`) AND (not(`Characters`.`ID` in (select `Players`.`ID` from `Players`)))) ORDER BY `Locations`.`name` ASC ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Characters`
--
ALTER TABLE `Characters`
  ADD CONSTRAINT `isAt` FOREIGN KEY (`isAt`) REFERENCES `Locations` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Contains`
--
ALTER TABLE `Contains`
  ADD CONSTRAINT `containee` FOREIGN KEY (`containee`) REFERENCES `Locations` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `container` FOREIGN KEY (`container`) REFERENCES `Locations` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Creatures`
--
ALTER TABLE `Creatures`
  ADD CONSTRAINT `isCreature` FOREIGN KEY (`ID`) REFERENCES `Characters` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `NPCs`
--
ALTER TABLE `NPCs`
  ADD CONSTRAINT `isNPC` FOREIGN KEY (`ID`) REFERENCES `Characters` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Players`
--
ALTER TABLE `Players`
  ADD CONSTRAINT `isPlayer` FOREIGN KEY (`ID`) REFERENCES `Characters` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `playedBy` FOREIGN KEY (`playedBy`) REFERENCES `Users` (`username`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `PlayingIn`
--
ALTER TABLE `PlayingIn`
  ADD CONSTRAINT `inWorld` FOREIGN KEY (`world`) REFERENCES `Locations` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `user` FOREIGN KEY (`user`) REFERENCES `Users` (`username`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Props`
--
ALTER TABLE `Props`
  ADD CONSTRAINT `isIn` FOREIGN KEY (`isIn`) REFERENCES `Locations` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `ownedBy` FOREIGN KEY (`owner`) REFERENCES `Characters` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `WorldSecurity`
--
ALTER TABLE `WorldSecurity`
  ADD CONSTRAINT `isWorld` FOREIGN KEY (`worldID`) REFERENCES `PlayingIn` (`world`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
