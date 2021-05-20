

--

CREATE TABLE `meeting` (
  `id` int(11) NOT NULL,
  `id_tutor` int(11) NOT NULL,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `description` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT '',
  `topics` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date DEFAULT NULL,
  `start` time DEFAULT NULL,
  `end` time DEFAULT NULL,
  `responsable` int(11) DEFAULT NULL,
  `file_act` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rules`
--

CREATE TABLE `rules` (
  `id` int(11) NOT NULL,
  `id_educator` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `consequences` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `img_consequences` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rules_children`
--

CREATE TABLE `rules_children` (
  `id` int(11) NOT NULL,
  `id_rule` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stop`
--

CREATE TABLE `stop` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `type` enum('youtube','video','image','audio') COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `link` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `position` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `task`
--

CREATE TABLE `task` (
  `id_task` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT '',
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `date_modification` date NOT NULL,
  `daily` tinyint(1) NOT NULL DEFAULT '0',
  `weekly` tinyint(1) NOT NULL DEFAULT '0',
  `monthly` tinyint(1) NOT NULL DEFAULT '0',
  `monday` tinyint(1) NOT NULL DEFAULT '0',
  `thursday` tinyint(1) NOT NULL DEFAULT '0',
  `wenesday` tinyint(1) NOT NULL DEFAULT '0',
  `tuesday` tinyint(1) NOT NULL DEFAULT '0',
  `friday` tinyint(1) NOT NULL DEFAULT '0',
  `saturday` tinyint(1) NOT NULL DEFAULT '0',
  `sunday` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `task_children`
--

CREATE TABLE `task_children` (
  `id` int(11) NOT NULL,
  `id_task` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `position` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tutors`
--

CREATE TABLE `tutors` (
  `id` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `child` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `surnames` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT '',
  `user` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `password` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `educator` tinyint(1) NOT NULL DEFAULT '1',
  `image` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `age` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wheel`
--

CREATE TABLE `wheel` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `meeting`
--
ALTER TABLE `meeting`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `meeting`
  ADD KEY `meeting_user` (`responsable`);

--
-- Indices de la tabla `rules`
--
ALTER TABLE `rules`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `rules`
  ADD KEY `rule_user` (`id_educator`);

--
-- Indices de la tabla `rules_children`
--
ALTER TABLE `rules_children`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `rules_children`
  ADD KEY `learner_rule` (`id_user`);
ALTER TABLE `rules_children`
  ADD KEY `rule` (`id_rule`);

--
-- Indices de la tabla `stop`
--
ALTER TABLE `stop`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `stop`
  ADD KEY `stop_user` (`id_user`);

--
-- Indices de la tabla `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id_task`);
ALTER TABLE `task`
  ADD KEY `parent_task` (`parent`);

--
-- Indices de la tabla `task_children`
--
ALTER TABLE `task_children`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `task_children`
  ADD KEY `task` (`id_task`);
ALTER TABLE `task_children`
  ADD KEY `learner_task` (`id_user`);

--
-- Indices de la tabla `tutors`
--
ALTER TABLE `tutors`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `tutors`
  ADD KEY `child` (`child`);
ALTER TABLE `tutors`
  ADD KEY `tutor` (`parent`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wheel`
--
ALTER TABLE `wheel`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `wheel`
  ADD KEY `wheel_user` (`id_user`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `meeting`
--
ALTER TABLE `meeting`
  ADD CONSTRAINT `meeting_user` FOREIGN KEY (`responsable`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Filtros para la tabla `rules`
--
ALTER TABLE `rules`
  ADD CONSTRAINT `rule_user` FOREIGN KEY (`id_educator`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rules_children`
--
ALTER TABLE `rules_children`
  ADD CONSTRAINT `learner_rule` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `rules_children`
  ADD CONSTRAINT `rules` FOREIGN KEY (`id_rule`) REFERENCES `rules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `stop`
--
ALTER TABLE `stop`
  ADD CONSTRAINT `stop_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `parent_task` FOREIGN KEY (`parent`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `task_children`
--
ALTER TABLE `task_children`
  ADD CONSTRAINT `learner_task` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `task_children`
  ADD CONSTRAINT `task` FOREIGN KEY (`id_task`) REFERENCES `task` (`id_task`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tutors`
--
ALTER TABLE `tutors`
  ADD CONSTRAINT `child` FOREIGN KEY (`child`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `tutors`
  ADD CONSTRAINT `tutor` FOREIGN KEY (`parent`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `wheel`
--
ALTER TABLE `wheel`
  ADD CONSTRAINT `wheel_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
