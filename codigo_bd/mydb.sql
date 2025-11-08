-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-11-2025 a las 23:48:38
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `restaurante`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `ultima_conexion` time DEFAULT NULL,
  `usuario_id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`admin_id`, `ultima_conexion`, `usuario_id_usuario`) VALUES
(1, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `cliente_id` int(11) NOT NULL,
  `preferencias` varchar(200) DEFAULT NULL,
  `usuario_id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`cliente_id`, `preferencias`, `usuario_id_usuario`) VALUES
(1, 'travestis', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `empleado_id` int(11) NOT NULL,
  `estado` enum('activo','inactivo') NOT NULL,
  `salario` decimal(10,2) NOT NULL,
  `usuario_id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`empleado_id`, `estado`, `salario`, `usuario_id_usuario`) VALUES
(1, 'activo', 10000.00, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial`
--

CREATE TABLE `historial` (
  `historial_id` int(11) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `tipo_movimiento` enum('entrada','salida','ajuste') DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `stock_id stock` int(11) NOT NULL,
  `admin_admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `historial`
--

INSERT INTO `historial` (`historial_id`, `descripcion`, `tipo_movimiento`, `cantidad`, `fecha`, `stock_id stock`, `admin_admin_id`) VALUES
(1, 'es pedro 500', 'salida', 50, '2025-11-05', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `estado` enum('disponible','no_disponible') NOT NULL,
  `admin_admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id_menu`, `tipo`, `estado`, `admin_admin_id`) VALUES
(2, 'prueba', 'disponible', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE `mesa` (
  `mesa_id` int(11) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `estado` enum('disponible','ocupada','reservada','mantenimiento') NOT NULL,
  `fecha_asig` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `mesa`
--

INSERT INTO `mesa` (`mesa_id`, `capacidad`, `numero`, `estado`, `fecha_asig`) VALUES
(1, 20, 1, 'disponible', '2025-11-05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa_cliente`
--

CREATE TABLE `mesa_cliente` (
  `mesa_mesa_id` int(11) NOT NULL,
  `cliente_cliente_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `mesa_cliente`
--

INSERT INTO `mesa_cliente` (`mesa_mesa_id`, `cliente_cliente_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa_empleado`
--

CREATE TABLE `mesa_empleado` (
  `mesa_mesa_id` int(11) NOT NULL,
  `empleado_empleado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `pedido_id` int(11) NOT NULL,
  `estado` enum('recibido','preparacion','listo','entregado','cancelado') NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `mesa_mesa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`pedido_id`, `estado`, `fecha_hora`, `mesa_mesa_id`) VALUES
(1, 'entregado', '2025-11-05 21:26:33', 1),
(2, 'entregado', '2025-11-05 21:59:43', 1),
(3, 'entregado', '2025-11-06 20:04:39', 1),
(4, 'entregado', '2025-11-06 20:17:41', 1),
(5, 'entregado', '2025-11-06 20:18:25', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalle`
--

CREATE TABLE `pedido_detalle` (
  `pedido_pedido_id` int(11) NOT NULL,
  `plato_plato_id` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plato`
--

CREATE TABLE `plato` (
  `plato_id` int(11) NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `menu_id menu` int(11) NOT NULL,
  `imagen` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `plato`
--

INSERT INTO `plato` (`plato_id`, `nombre`, `descripcion`, `precio`, `menu_id menu`, `imagen`) VALUES
(2, 'pedro', 'aaaa', 1000.00, 2, 0xffd8ffe000104a46494600010100000100010000ffdb0084000906071313121513131316161517181d18191817181f1d1e1a1a211f181d1d1e201e1b1f28201d1a251d1d1721312227292b2e2e2e201f3338332d37282d2e2b010a0a0a0e0d0e1b10101b2d2520252d2d2d2d2d2f2d2d2d2d2d2d2d2d2d2d2d2d2f2d2d2d2d2d2d2d2d2d2f2d2d2f2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2dffc000110800a8012c03012200021101031101ffc4001c0000020203010100000000000000000000040503060002070108ffc4004110000201020403060306040503040300000102110003041221310541510613226171813291a11442b1c1d1f0072352e115627292f14382b22434a2c3161733ffc4001a010003010101010000000000000000000001020300040506ffc4002d110002020202010401020603010000000000010211032112314104132251917181233261a1c1e10542f133ffda000c03010002110311003f00e44456b5365ad48a7108eb056f96bc88dc500980d4a594ec2073d66a1ac1598494a01ceb49af41f2af0a8eb40264d6c1aa365af66b18916e0e95b3283150015e1a141b083663ceb193a7bd439cf5a2f097e4650b27af950d8746b6ecce9f4a6581e10aa43b6b1b2d6d66c6483bf9d4af883ce91b6fa1925e435713263a5477ef2a8398c1e7aefd2294dfc791a0a830d64dc327dcd050fb0b992dbb46e3c229249da9ee17850b7ab34b790d3fe6a1c35b541e1d35d49a9ee6247f54fae942526f48318a5b636b4c75d41ebff141f13e28b6f7d34d296b715824205ff574f49a45c4af06225b311e5a508c37b34a7f4363c6a09cb201d490627dba6f50371700693aee7fe2929bd44e0f02f72488006be2d27d3a9f2aa3489ab7d049c5b5c043bb2a8e8b3f98f956983c3068d030242f29d79c033f957b86bd949475f09dc19d28de0c8ab7cb104c4640a24124c099208eba0274a0de86a00c55a40e422b05063c5a127d3fbd40712068353d0536bb783dc7f0c6a669589ce408deb45d8b25e4f53393260796e4d1787c41302604fec56b6ac8e667c8edf2e7ef352dcc406d37f4fd803dc8a2f664e8b6f06e062e5b1ac0e5aebf23a5458ee0a2d8b86417b7059412614edb8d4fa7ce9470ee297ac688f9676139bdff00e3e747dced12bffee50e73a67b71e2036d0c99a8b8b3a39c5a0de177257e15dc4ee40f9e834e74faf6191a590a158d43c913eb1afa4556f0b8dc2302af7af28e5b47b807f2ab470dc560f2aa8c45b257504960df5fd291b32456b11d9a724b85006f941307d088dfa50871a2d0ca2db483b4651fa9f6ae918d2966d8bce4bab4465d8022759d7e9558e337ec5ecb74adb50ba6673e1f78d18f918a319bba62e48c6ad15cb58dbf74e54903a5b103fee6dfdcc5103b3acdabb24ff98963ee406fc68f6e20080103dc80634eeed88e4091b798522a36e2ce3ef5a4e8a13347b96d7d4003caae93f08e739ec564513dc57a30f356b05029515e65e9f2a37ec44edf5a89b0ac37142cd40656bc22a775a88a513181abdca2b4222b6471ce83312022b510244033d7f2add6dcec41ad9d481a8a5181b2d7875add419d288b5696466f97eb58281add89d76152addc9f081e745dfbc00f08a0aedca1d99e86385c5c8198e9ce86c4623318498a0409a36c7862b5246bb25b7803966bcb18883d0d1031c363b74a5989bb24952040dff7ce82b7d99d2e862d8bf0c13fde99a61d0d8424162dae61a05d623ceaae984b8c4000924c0f5deac298622ddb124f840dfef7303f0a59aa1e1bec598fb454cac1ff004d58bb27d9337545fbca32b4e504488d812276df53b6f41712c3e41b6fbf91f9cf9537e11da56b567bb22614411008f393a1e943936b464958bfb5d8380c42e5ca48dc4c69034034de34e9bef48382f16b987b81c6a3983cfe74c78de3dae081b9588e7ef0009f4f7aafdbb677274f2a68ad6c1396ed174ed7f12b789186c4aa95cd99194f542274f3cd517622eda6bf755c10f94b5833a17132847565d8e90479d69c1b811b8a9a41452d0e7424924691a6801f6a1301c21ae16960a4b4660745e7a82048e5a1e94a92ae28777a6c878ce396e5d6744ca1a07a9ea697a5a60c493bf3a9f118420e592246a0ef237faced4db857006b8bde3be548d0449227527c4328e734da4238b6078644fbe0b1e63947a73f79a33117b32aa28800cfc004fa91a903cf4a69fe116f2f86ea9d762c35fa81143626d323c2852601e4e0fa69b797e349c8658e85188b3940218f9c41fc0e95edac0330cca20732c77f6dcfd68c38412334807981afd77af1b0ec84aa3900f38d7db5d28a95f40946b643f600a258931ec07e745616ea9d0166f2403eac48f9893e543dae1ec757ebbb193ec29be0f2a2c2a96d75e5f32361e7a535af2c4df807c5715bd985a5ce5e00ca01b842fab78636d901f3a7180e0b71886b99548fbd718bbc796a481e448a8538a0839591637eee0fd7e127de6a3c5f10ba87e06db566d7d0eb03d8834bbffaa33af2cb0d8e1b6437f30b5c3b959397fdabac7ce8bb3c7b0d6c05408a07245047ff001044fbcd54ed603117c49576591b8859ea068bee0538c27648e5f1de453d0063f59143f560fd115aee139fd47e62b4ee40d447a8fed465fb46262a1c360a496d74e60c7e157a3590322b0ad962002206d47bdb2632888df599a89ecebe291f5a0c64697785230d80f3a5f88eceb7dc606ac185743a4cfefa6f472da1c88f4a9b934554148e718be1ef6cf8948a10ad74ac560f3083557e23c0992586abd28c725f62cb0b5d15c0636a330f70be9bfefad6988b2a3513151e12f430e42a8f68974c3dace5123e7411434fbbb05623d29663563ca91328e3a0067ad0a0debd36893aed51b82a608a7266cefcab3bf3102a12d3b6b445bc230824c7e5f5ac024b56e3e2d7f2af12d29ccd98684787593d63956d7f305fd6bcc5da2a5534cda6de7afe669460dc3620aae7cb24b6e472d418e54d8d965596e6d20efaf2d6a7c162ad7d952d85d46ad2398e7a13e55a637e1015bc3ba8ca44f524933ce7a19a9bd978ad019b4d71c2b6c35f2fdf9d5aad5cb4133b65196db22286cb31a344431620c6b3cf7aa8d95722e3f2ccab20f5d758d7a519c62ea28010e8402d94699b9e9b56a12c53c60e4b85ed011b6b1a4fca965911bc558309834b963319625bc404cac7c3b72acb58250411687b93fad0f752d1bda6f646bda2bc96d94699844c728881ed4b30ccf049ba501d7ac9f407eb56e1c192eaae6b5f17c3e36f63a7bd27c6f014b6e33b6407a99faf5a10cb06e9149e1c8a36fc0bf0385172ea0cccd2c24b6927e66baae138546570a4792903588d74d069b1f9eb5ccb0301a14fc2748304f98ae9384e3b9f0a0b34e53c885dbd235f33ad69b60c54bb1662384b0d5ae05225bc232e627fd2227952e6c3db5008633be53047fe5335adfc4dc66254e864fee688e1b74125890a7a4edefaeff9d25bf23b69bd1e624596510e55e35024cfcc4d078fb6de0034f33cbd6ac0b6d6e13f0b42fc44904469a4083e955bc684b848678546d84ebefbc6d422d5892e885d26097248d04532c270d661255b79198c05f73a8d3a0ad701709d2d2923690b207948d7e7475ac25e6600c49fea61a7a813a7bd593fa441afb66e7076f316b8e0e9f0af2f7fed45e1ae25b50c88175105cfd4173e9a0344607805d6b2d75aeada559883a18ff31f10f9d2b7c1cea0493bb6df53bfd68b4fc9bf44303c5b9979f4063dc983f20d415de37ae88a479e727e6193ff001adb07c2d64b3dc0c0ec8a263ac99e7e9468c16187dd8f5b9fdf4a5f8a06d94fc162986b323e647f6a6231c0ff007d2bcc6f0789bd8592bbb5a3ab2fa7f52fd6a2e198ab6df1003a8e9fa8ae84efa05058c4af98f5d7ea35fa510ae08e47f7ed5edde1a22440e846df4a81b04c39fefd6806ccb98553ca3e95ad9cc9b37b1123ebb56ebde2e9cbe62b67c4003c4b1e634fc6850e9922620f3047a7f7a916f09eb419b81b41edd6b4bed97c32246e39fca91c50ea6d03f692c58168dc36d8eb12bc89da4ec3deaafc37879baeab90f8a359831d408d62ae9c0b14e1bbb5456ef582e57d88066770011cb5ab8717ec961d6eadf46ee5e0f8557c24f3dbc3aceb4233517c58670f717289cbca94b8d6b34e525667e5a7a50b8e48593068bed461cade772c341a988d4691f8526666b855330cc796c069b934d28ec9c674a88f18c0c2a9d224e9cfa505736a77630a2d030d2f044830052c4c313e22349f0e9bd14d0ad33db160afc5a4895a3adab0492a641df68f9eb465b7ce05a73aaf8a40ccc23611cbad4973282aa2db8b644eb0c7a6b1e73d2858e90a3088f76fa5b2034b7b40d7a7951585c18c463322e50b9e227eecc183a72ac40a1af78d46550c3432609194403075e700c509c32e5c5be8d6bc2f3a3000c6867482369e5444f25a789f0eb56efe4b40e40bd660f413d48fa1a597492fddafc6cc14163d60098db5e94f2f623b980f6f316d4b903974034f90a97b1bc06f5f67c5a2165b6d92d956510e7ef1267459e9a9e62a77f67453a48dbfc285a46b6b720b0824a021ce99a25e758dc0e5558e2fa1443f78f2f5898ae8d8fe0451ff00980c9322468741300fc3a9d4091e7546ff0011b698970501cbe1563c80de27f1a0e55d6c0a0dbae8b8707c15ab368e994b40931e211a9d353034aa89e1f7249606418ccc604498df953a3c52cb10e5f6103cbd24e945be1cc25c2f94313035cfcb5008800f59d2b8bdd69f47a38bd02af94bf6468a0b78fc53a00134020002275e542714e046f5a67baecb944ac29cbd2331f88ebca9fe1aea00cc755eac64b37a9d4ebbd5bbb2aa71166e77a81ad182998020c48681ca348a44e4ddad16cd2c38a35c6fef670bc0d95b4c0bbc19d0ebb7330049a1d080188225b946804c88d7d34ab1f6c6dda7bcd6b0e8a96ad992c04966333a927c236006835a5b8ce12967c05a2e1d416232e523781a83246935e963972478738d7cab4fa23c0711cbe126549a658423c4c7e10621873f31bf3a556b10a8c005f08e6adab1d779590277a8df1339a44e76924b13ed1b47b721b450941780c64fe8b7705b81dcdab4173349d4428e7248d00a1f1fc1b0569185c7176ec6aa8496cdcf2eb9575e64fb520c261dcfc0ac491a813b7a0dc6da5156b04c0127400790f620ea0fb508468d3761bc0715752c776f6edb42e55227433249eade7a54caec32bbb367e40691ca34df4f950f81c387ce0978589c924024c004e804f435a5cc23ae7b6e2de69d22e69c8ea0482796dd6b549bdb17947e839b16c4eae4c752491f3d68ec1618bc162403f79b403dfc467feda9b109877b56b25acb701f1408588dc06ad5ad2104660a39e5924fe027d8d1a4bb60b930cc2dac3e496b8b9c6cb1baff00de0a93e8052fc55c39b4bb7001a42dd6503d8102a6b0f8445f10da60b9e7a72d01a776b8de182a83867639774468fa0a129a5d23717e594fe0fc464e872b8f3fc3f4a338970fb788f12c5abfd468ae7cc7dd6f3d8d24c46101d4687ad4986e2a57c177d9bf5ebeb56942b680a57a67986e2372c39b7701107553cfd3a1fa1ab5d8c25ebd6d6eda4050f2240349b1052f265ba3308f0baeebe9d57ca83e1fc52fe05c6b9ed37393047e4d46324f4ccd56d177b1c35340e3d4c4fe14bbb6182fb3aab0336da40d37a6dc3b88262503db791cc692bea07ecd2cb9ff00acc33daba61acdf3dde849208119c0d7299f2d39d2644e324d17c4d4e0d3ed6ca1dcc43281901ef1be1dbc23f363caadfd97e082cdaeff0011743f7ea55f0cc0e767fbbe6083a861046a399a2d6d58b441281f13f08c85b41d323b7f2d7cc1d69e762787b6231071774ff2ec12b6d57ef3f33a725fa9e7a6ab915ebc023495f911f15ecde2b062d621ed8640ead9537b6d3201ca2003b73d79ed56de21c56de270c2f5a66cb24329d974fbc7298f7357b7b61d4ab0d08d4570ff00e3070b385bb9b0e2e5b5be0f79960239f28d9b7241d0ee35999668394a2d7e7fc0714d228fc4ef8c55f2a08f8a0799e6c7f0143e1f044391ba8d09ebfdaa4b56858878820110d20c9dcfa7ad4bc3b0179ce5257c724eb3957ce36ab3fa117760f8a5cce02db3936900c31f5da9b8c225b522e30775da202a7973d7c851787c62da50969b39820b95903c9418103f73558e25733182d3ccff007d6b2417ad8d3825d55372fb910415032c9e5af974f9d6b8562f2fbe900c79fe55b70ab76d0a225a66be353203299f103f14a9008d23f4a25d8e608c1958cb1d0c09e73106958c8ad2da22e5c48962206bb1f4e75e70bbad6afa8ca734e5ca46b27488d0d418a045c7cc664913d699f03b2d72f96401992dbbc12aa3450b3e22008cc0efcaa8fa24bb2cfc271521d59549ca3c4cc4913a13d2600a63d8ce26f877bc2ddd9b60af84b44732472830748d6a886d377ce844b1e49d77ca27581b53be2381b96ec8b9b1ca030d2488db7dfca0f3a9b8ece853756748ed576970ee86e24668885cba9899de7f7d6b8ff692c6554b8489ba49ca3ee83f851163165d7c3a6904ef3ea74a0b88e296e1b1961b26aca751a11a1d7634630517a279249a543fec1f021701c4de0058b7ac933247f7803ce9db5c389bacf06390e400d87a01f3d68bfb45bc560a2c016429045b9caa1c72d06a3534bacf155c35bcb9d331f88ac933d0469e55c2e5ce6dcbf07a5ffca0a307fb915ebd9dcdb371819017485f5d8ed569c0f68060ad0b42f2def32a47b09e5f8d53b01df62dcf72842aead75816c8379d069f8d32c62e1708aa731bd7984825667cc2749e6d03d69a6df490b8d41ff3b36e281b16c0dbc32db1cdc0cb3e7cbf035270dec8d9b2bde620e7e707459de23763e674ad2cf68ee3a050992e188663afac753d0d756e1dc145cc25bb77ce72106624092c7526796bd2a49645a5a453d47a9c3149f1e4ce15c538566bcd9132ab7c2a0447b4cf4a89b0288010731d9a51a01fe90c3c25b63ccfe35d5b8df17c0e1eddcc35bb26fb1cc1998900379bfc463cba45724b787bc54b774a83520e6d23a092cc7d39d76e195aa3cff52b94bdc49c62fa18f0ce2ed871169406264b025246ba123535afdb9b33b364970402019507a1277f38a5785c2dcb8332abc44ceda7bcf96a40df7a6376cada543dd92e4a8218822490237931e5155f91cdf147985c4f76bdda16899893b9e640dcd10b9fc97d74fa6ff4ab4f09c05bb9de25c36ad1b5018080c66658963a28d840a838859b4d9952eb9ca0658b7e024743a1f7ace0fc9b9fd2112dbeae49e836f998fc286c67105b4142db372e31f0a83afa98d23ce0d355b7325b2ae91120fe55a259b624b313e408023f1a5d1be4c93081722e64399a794093bf2e5b0a63c19eda2158fbc76d790a02d711b68202af913a9f493ad4e7893f2b6e6798b6c41f70b5b9d78371fb13dd4206c686baaac35122acdc5b04729216ab587c1b97001024f3ae9b12ac82d5d7b5b194e87f7a5163101c102083ba9fdfd6b7c7605edfc43f7e74aeed88d57e541c530a951bbd87b27bdb2c63ef2f41d08e629ef03e2966f496636ae11e2606093b00ac58c08e4549f3e748531c66762284c4c16cd6c656e606c7f43415f46d765f6c325a90a033193b410a46ecdb926ad7d90c758664b66da1c42291683850aa0724fbc3a9dc9eb1b50fb11da2b7de1b789cde38f113f786d9893b0e5caac5dafc15a6b59b314ba0f81908df700ed3d67715cf9b2f16a2d77e4ebc3879c1ca2f7e51d6eddd9d34dbf7a74ae15fc49e3e31d8a6b599970f615886504cb47c4606d31ed14931b8ac45f8372f5db974016c08d083fd4c080353a92350049aae62505a72b242b1dd8ead1be8ba0527d7d6b4212d72d9174ba037cd39cfc3ca7e94fb0dc526de45940da1f0896f53a7ca9413deb85ce888352ccd0bfbf4a7166f590ec13c48a3c2e1481a6e75155681160d8e85dc8006834d4d0181c235eb8207867c51c946fb0e952f1566bada4e51b4447d00a63d96e32f826306d31b802052a2e10676883049d36d74acff00a1bb7b1ef0ae138bcc585bbbe1695cc45b55064c9ce773d00a1b1d88299bbe452e372a64cf50663e54cffc5f1172ebb64cc42e40ce0854ea449813e83caa4c07679ef596bf785955cac54163246a0113b02761acfbd4ad792ed44e64cb2cce5b73b7507deaf9d9fe1696f875cc6abcb3dccab9d098550065dc480fac8d3459da29060bb1189bc6e30f82d832ecad04800e410a7c707631ed5721c1afe1b0f6b077ef419108aaba6662d94bb365804869831ed546d57642117655b8725ab64dc6399e667a1dce9d4d1bc7f891bf6cda45d499dcc40e7311f2347bf646cbb345d28a84e624962da9d9953228035044d2cb98042e2dd8370ac6a0f89bcb550399e83de83944acaea92115ee1e6da922e4784ce5933a6dcb73a509c2702c6f28b8b0b3e251a9ff008f7abef08ecca33e5b8c50e7035225469bb6d9bca9971cc165b9dc61d0c930cc44b31e6761a479524b3784858e1d5b6563895bbc2d854c3a2224c12017631a891e188d408eba99a4dc030cd8dc4a5953ab1d4c68a399357bc5e0800899f347c51c809f858c2b7a6bbeb1196a9f65ce12f30b6081272bea098eb077d795095f17f656115c97d1d4fb576efe16ca613049796d05f135b4f8d8ee4bc687d08ae6e3875eb773f9965d4b6b9cb063ea7c5a8ab059ed95c750b7ae3911be6335e5cc7615e09b776e18e6ed1f88ae18ca71ed1df1f45292d3427c0d93deacff0050d74f7df4aeb1db3c45dfb3036af46500b2a907e647e46b9fe1b8958b3e24b1690cce7739dbdb36bf235edded35c72195c9235100013c8f9fbd6939c9ae2b45e3e8611779651afa64171dc5823e28d49206613b9cdbc6bccd22c872e51a73d4c7ebe544714c4e26ec96b861f5caa64b113bf2007bd0fc3f81dfbeca0f79058292ba44f9d7562f826db39bd663f76518e35f14a97d05f0fe25f6732b740054ab2c4820f23275d75a8aef12b37184a1bac351206847380227cf7ab0f10ec3a588214b0200d0927379cf3a45c42db59316c90362469edc89a659d37473bff8f928f2b44a97efb7c16089da4059f39689f9d1387e1d8977542c8858c789a7f091f5ad385e36e652c57bc10415d36ff2e9a34c6b34c0e32cdc1299c4af84415653d083a7bd522d4bc9c9931cf1ba68609d82be4fff00d73f565f0a2ffdcc0e63e4bf315977b222d004dc4b8f3f04b37cce8a7d32c799a9705c7ee5bc39b4ef72e7242e5741d0b4927f1f3a0ec768184a000088d04e9f2d68d444a9335c6d8b96066f83978065df730913f2aaef12739fc0987611a9b858b13fed3a6d4d31fc56d3125a64c485d36dbe13401e3b68682c8f78fce6b5a5d038b6741bb616eae80ced0c44fb0aaee23863d96ef546aa675123de9a70ae20971b2c89e94c3885a26d66831315693a34537d09c61971855d1cab7c2d6cc6507a8e71fb8a038ef6645a4ce8e3303050f3ff004f9f9517d93b8062591b4522403b7afad31e39c357399b972e3478525481cf631539b96a8e8c50c74f92ff004736c461a77d0d02ca55b503f2357ec076706218a06082d826edd6923372402401e93a0aade3b079443095330c3668e60d554e2df1f2733c724b95684ac509f1483c986b1ea398a6587c63b5b172e3175b7e14cc7493d39e514bd787b5db82cdb04b36d02481cc98e429c62eedac28ee9d73db9cb9227c406a6645092026c33b2b87371c3e62416f17211d76d403ca9bf1be037c0ceaad76d0925d622368952675e5a50f83c529c3aae0b1b6d4a99eeae154693a78a4cb289e4481562bf88c72f728b85c3ce587b8f714296247c277d4cc03ad271bd9d1ca3c68a1ada58d42861246780bfed8ad787e185e841732b66d440cbea3955bb146d9b8f6f1382b56d233075c484827c9ee652d32771a72a5f86eeed5f0a45bee624852712d33a785402b3afc2c7cf4a571192544dc5fb3362cda8ef59ae36a2002663a8131ef1d6a9d81ecde25b136cd9b6e2e660e8db65208398c88807d6ae23b44b6af35d36bed3fd2ad6d905b1e59ee1fc06d3344e17f8837118e4c35bcc40ca0b839447884fc51e401f5a1c65e047c6b6c8b8e5cc45e54b26e0462ca8c5172bb49019b4f87990093ed577c3f0e6b762da15b32901415d4c00016c82036a3918927a571eed8f686e630a9c8b65d40584307483398e58598f7a11bb4d8c91ffabb8cc09319888d0092c343a7af2a5589d219e48de8ec7da8e2bf634b4916daf0d493d00d4e4560da92006266b9bf6bb8d62f17700ee543831e0b4e099d0025dcea7ca29e709e2f87bd77ba402e5d6f1330b79d9881cb48067596689f5a658ce0b7cff33ec77ee9ca0206caa14f52b6de23524811ad2a4aca4baec45c0bb38e6d0172e1cd989f09d246900cc881a79eb4462f0d6f096dbc614b6e09199cebd072d6a6c3711b572f3dabb6dac621da143e762dac3300c8005e8269d71fc3da5c2fd9d2d5b6b6b19ae3684b0eab965ceb1f169b99d2a5293b192b5a39ae1f18ee59e0301ab658855e7aed3029e765f1eceee1519d090a241732bb082088d672c579c32caae62ab99544145075db532575f33f2a3f8467b4ef973db57399e46566d204f281e95674a2fec118be483f895d77401f35a40488312dcc66caab957d649aaaf12e063122d43f73704f8ce99844c81b9d79cf3aba21c3c06b85012214dc0ce07510a7349eb2373b08ae69c778bb5abacfdd4962423fc2a04ec1620e83fbd3634fb6266692686f87ecd414173105c16cacc1478742675f4fef5e62b076d0bf7574dd0a3458827a4c0d24ced34913b4b75d32b1016790d7ea4fd0579638ab8f0a5d7f1690ac44f28d0ed14d2e3e512c72cbe2545cf82602d5964bcfdd1755964b90e35824c9221c72d450fc46f8c55e192ddb12609023331dc9927e409a4580b26edc08b33f798eb3e42ae4b835c0596bd706a465400e84f598d7daa139b6aa27a5e9fd3461fc4c8ed91e2acdac1aaadd9724160b3b1f498027e750704ed7dd42e6dd95eee21a4c09e5a01bfce2ab5670989c7dd661cf593d0408034d04814c7156db0d874b00cdc939895d75d662292517156bc95c79d659383e92b18f1ced2639ec165bc320d4a776a749eb13a556adf12389f8878874d8fe94f783e0dae5b656cc64111487b2f6b2b5d53c8edf4f9d2a9dc5df82d2870c914b49966ecde040320fb6f51f1cc16475b88002d39a4800111aea75df903ca9af0a324408f415176a6d00518f988ea4c46949864fdc07adc6bd86fe84ccf79d63bcb280ff004a3313eccabf3adaff0065d0a876c57792272a90a41e873668f4a9b042e622c367b96ecb2b9500ead9473cbaccf5048a070bc0ae00cc4deb864c1048017cf346fe5f3aefe3f67cf3949915cb1864616cd93de188ccece0090092159469234d37f5a0f8c5d45b9162d5a29037b6b33cf97e345ff815e99454b40ef9ae49f901acf99269a58e02328ced68b73307f5ada46f913627874f89746e9b4fe94ef83f1d01196f8f08dc1327d279fca81c76285b040f88ee7a0a4471c56e238301751eb559c149530e3c8e0ed04f0ee336571172e1842148558d753e7cf6fd2b7c7e3ee5d2151b3339d001cfdeab56714e5ef5c8cc589fe674d7cb4abef63782941dfdd2033005410651799d48009f3069a85e4d8cb80ddc2ad918560c21b5ef001debee637959fd284ed2a5bcadfca0492140e40f22488fde94c38c5cb09e2b844830834204f293b13bcef4bf1dc4ac35bc97a54c123268c40ea47e06a19316d48efc39fe0e1aebcf473ec05dbb84c4b306ca7215254f2312277e542713bccfdd13a8504852ad24b1927e1d7e6699e152c38c45db8be10a52ca03bdc60c0318d7c3a350d8016903a5fb6ce20158cc235d7c4ae0c44738de69b96d9c6e001f69590f959634d88927ce045176ed0952b6cdbb7b907378cebb1f0823cc53ec3f1144b6530e86d1b82262c87cbcce71643ea3fcc4d3dc359b470e2f4075005b557b7e20c234041198fa493d684b268ca1bd94e7bb6906573958990019723d84fa6fef1519c6a1baab9428983de13758fb39d23fccca69a6170f74e2543addb768b78865eec19d832ac3313b0d49923435b5ee1b656ebe6b6f6731d22e77600e8438ccc7dc0a29792bce4e354bf08138c609d2eb2da7b9704886b5ddd904980006209633cc46fcf7a01b87dd6467b8c120e5cb7310d70fa008049f534c8a117084b736d7c52ef33d2546839eb4bf17c4802de04ca07c368b0f7691afce39d6b77425452b620b968dcb8167ccc7f48db724ea7ceba98ec2da22d65b96d6e8519ad66086ee8000091ab4ee0f3f5ae7f87e236d5813860ec5c3196ca180884d066ca46e7cc9e7579e23c570b8b44bf7b0ec988b6c0e5ccd9184c8cac3c61b6f43d6a796ed6f4362da7ad8c78f761b87d9b6c3ed0f65e330cd9641f2c804c74dfce9af64f89a9428311719540033b38613ccac981b013348f07da8b67317c383a18efe5988264a8632a748e9b73af31f7dad14c46156ddb120b3241257a434c1f315cd296eace951a5b2c38dc6b5801cd9578526de785c867e39613b4f4e835a6f7b0166eae5ceefa0d566402019d77df5f7ae49c4fb68efe06724729504c19ccacdb9156bfe1df693158db9794422222cb788e9e20a0062794fece8725c2368116af4c338970f386fe65c3324c69124ec40d8003988d6abc38c2f881bb68b9d813cb680769f51f2ab6719c6612e1cb88bb70fdd3cc69cc81afb0a569d98c1b12f876b17db29f0b78f4f3b6e67e42a2b3c7b959d3c2555a4ff0075fe28afa5d5cade253032f84b7cff00e2aabc6312b71d11d8f7418131b8123311fe6227ca7951bda3b66d3c5db2be5dd92887a6839f94d57edb80c1e751cff7bd76c649ab472658c93e3254c61c5783f758ab96ec0176d21846ca0b368a759db700d5830b805b3611de45c2082a50486ea1b3911ac6d55aff001501b3e6cadd57c3bebf7604f9d5c786277b854bce5ae162dab3163130049d634d8cc7952e6c8943a29e87d3b9665bbad8e3b29855b645c2ba91b920183cf4e755ced471b5c562025d70962c9d402733f503283ae91ca051f738ddbb68c91e383b1d0795736c433172729927998a97a74df6767fc8e4a4947b6750b7dafc2db74b896cac0232da50a02c42a8048e7ace9415ebf731574b208d753f97b0a45c07b3f72e10597cc09fc8ebf4ae9bc178020505a446913127d07e35b365e4f8c44f4383da4f24f57f9057c377184b970b6b97780353a015cff87619a0b46acda558bb5fc5fbc6ee1587756db58fbedebcfa7ceb3b35c345c60e5751b69f41151ae313b229e49f27d22c7d93e0ae5658183d3f434176c31b6ec35b5244f9f97e75d0f0caab6598db290a32c911b79127e75ccaf7175bf7dd540244a891d37234abc31f16bf270fa8f54e5095f5d216627b4491a16d7a291f8c0af6cf69aff77956cb95066481b731cf4a3b15894eed650f9933cbcb78f7a8adf02b98839ed2e45d224e8de601931ea457471f0794e77b175a5c65c9eef0ff00fca607a721eb582c6287c4f650f4324fd09ab1e2386be193299633260a92c7cc0a52dc5a3fea283cc644d3cbe214dc49f217ddbc5c996f003a9eb4ab16eb7642b405dcf97cea0c5b37c24cfa50b6adc9093198ea63f735d02d962ecd5a37a501096974072ccb72f3f7d69ffdbefe0bc0e16e5a2771f74f9731e86b6b096ec7736fc4a8ca7c40c44738592493bef455e4b57585d0c1c65ca44c86e848201069252aefa3a71e3b5a7b07e29c76c5c5464860b072c73f94ef550e3fc5dae9eeedaae624cb1811d402da05ea68aed0606ddb62f65848dd0ed3e44efe94bb80605afbbbb10104172340bd17d4f94d2e9ae40c8da6e0d6c2b0010e442c217600fdee649cba8f7229d1c023dc5443aeee46800f40359da9562f8695b83ba92cc7c20f4ea4c00168cb384704da46372e36adddebb44c98023c816f515293f214bc0f309c045cef1c200b3914e6ca606ec399f7229063b84b0b9dcda4660173305782541e63c44af3d872d69bf0bcd6f32bda6500180c3313e7b42fce9471d5cca6e86bd6ee8fbaa85572fb8258732418a10db33d2a340325ceea3900ec198c2f41e2e53f3a618ae0a32dc2f6ad929e15ceee794ea24826083cf9524c0f1122dbe7bb0ec225ada91a6db49d37da87e19c36fe21d58deef515f997009dc0505400635279517f7652124b5c6c638ce2c12c27756d7594ca72c13b18463afca94e13815cbc41bac54319cabbfcf6039455e709d9e77b833acbf331e2df69813ed5271cc65ac2dc1630e82e5e1f130d421e9a6e473a939cabe1f93ab1fa7869e4fd90cf81f66708892d086342df99e54cee76530d7562ddcb4e46a42b027e9ad52f1385c53f8ddae19e4341fda85bfc2895ccb9addc4f12b824191ad2c6517fcc5a509afe5d2fad0db13c3723848dfdfdb6aaa76cf825fb4cad87ef595f436d33183e4179113564c0f13bcfdd35c7cf71d4169fdc09a7fda6bbdcd8377e12ab33fdba4134b07f21f3e28cb1ff005ab387a70dbe1739b4c146999b41f5e75dbff837d93bf615b137fc3de85c880cf875219b4d26741f3ae5fda1ed1b62d6cd9c3c4e4eefbb5d4bb49822564020ed3d66be87e0373ecf83c3a621c2badb543998492001ee7d2bab270d291e245cfc1cf7895a4bfc4af59201ce5d17fcafac37ac8a5f80e1b8de1f884b9dd82762a0839d4fc4b3f9f502ae1da2e11699daf0516d882cae9a127a9e4c49d662a1e1b83c55f369dc067395989202db41aac012733c024729034af3dc3baecf5bde4e2afaaa760fdb4ecc670c42128c248236f2d3a550f86f6530e4b2de2aac355ef2751d019209d398f4aec5da6e32d84b3395af5d2345512046e481f779753f38a47f10540caf932332062b33949d489d36a48dfa69a8a769f8fa2b8abd5c163caaa93e32fd3fc158c2e0ecab1b630c848531009236326390f31cfd2ad1d952976d1b1742f7bb22a428d4c0d8003f13bf9d52b87711c5bdb6c487c8abfcb5c88aa48e7ac6de7bcd02bc42fdf050862379cd0646c4911ad77ce5092a6717a7c19b1c9493effb9d0b1bd82782ddd6aa7689fd8a5b80ec90562cc9e7240fa557f01c77885b192de22e15f53fb3eb455ced2e363298fc6b9a704b5167a90cb27b9a5f82fb82b586b2b9af301be533bfbc556bb4fdb0cebdcda309a8cdf79bcbd2900c3e2310c0dd6246daf2f41ca9cf0dec8be7d493ae931b7ad4f9463a33c729be52d2fea2ce0fc1daf4311a03f09fc4d757eccf6742aabbc08d803bff006ad7827065419f2a9503607e2a9f8c71d012140503aefec29a34be53fd910cd9653fe161ebcb02ed9f18f032ab7f946bb9ae5fc33858b577bd664720108083e19de4cf88fca89e278bc4deb85cd901412143389f5cabac9ad2c58bee42e5b2a4ff00513a7af8a63dabaa0a7dbed9e7e6962a505d236bac8a49cca013272afea4d32e1fdaab96404b77d9940d01b69e1f204acc7a9a10705bdacdfc2023905b8c7ff1343dae1cf718db4c65b3714c32a5920aec75cc83911555672be01d8be38f75b331bac796b1f45005686e5a6d5b02ac7a9ce4fe35ea702ba18aae35988e4161baecb3f8d0adc2dcebf69c474221841ff7d1d82e3f421c632a085dcee693ea488f6a3ee24924ebf3fa5459d1488047283bc7b574912ca789b3584b2aab9d8c0c84181ce74f59d681c4e22e59cb66d9f111274d04eb33ce8be0f85ee84c00cda4b0d117a444fd75a0f8f3894b56e19db4cd1d7d291ed95ba5684f899b84819881b9d356f562009f7f4a79f6436ad0cdde18018e62c77d775111ea69cf63b80596716dac8ba402ccddf9f84119c8b6502ff9649d277e45ee170f631b8eeeb4083c4cacf242ac784418249ca3780263ad24a5e0318d6c496185bc38baf903dcf166cb2513941dd677d28ee1682c90b3371c06f0b15650750be11ee493b9ab071de186ee21d9f20b1642b2045cc091f08d08896df4d23ce6ab216e172c991ae4eb9a10c4ec031df96866a34ee8a3aa1ce3310b6596e5e4bd72c13e2744673a6a55c2830a76988f3aad7f13b8dadcbf61dd996c940542a824ee76661e1db70098d46d11718c1e205ccfdc62195815bb65c35c464ea0e45d7783048d758998b1169f10a05a2f875cca0b02650468a6190e5001da76da74a7692a62c149dd0136247d945fb375944f8415c9de107c5011da00db68274a1f8663af87378f7195068d69533066e53ba9d2498d6ac7881dd61acaa5f6bab71988376eb4b8d840780546da9df6153617036c6018f7595cdf39940226008264f4d6440e94937499d1e9e37915863f691ed612574b8de10dcc4f393ce2bdecc6170f6c66b8c096dda7527d77deab9da269c3151020830290f0ce3ddda05833f80a8a8ba4774b2252765db8d76aaee12e1c8e0db3f15b63e161d3fd47a8d6b4bfc7ece2725bb02e2bdcdd1c7c0a77398ea7d636aab59e35ddbe729de6f0a4902791d3a69a7951fd9fbb886bc0dc8041992066d4c996dcfa4e9b53cf8a86c8e373965d2d17ae17c352c8672410a9a11c801ceab98e37f1cddd392b6cfdd8034f3241f9535e31c654b772af2ba779e7cf2cfac4d597b2584b6ed9fc449d76d2a515ba474ce4945c9f48aef63781d9c05cbaca81ae9320902424691e534276c31ef89b881dce8600034131b79d15fc5ac61b6d65b0ec56fb380186c880eb33b8a7d631186c4da56012e5e4519a6db292d1b86e40f21263ad467e9f2b939277fd0e7f771c6be35afc10f69718061329593942ab1074f9fe549bb37db4b7876546396d810517597e6649d0790a64f715e16f619085d81bfcbd0b7e3159fe1983b4dde59c3d92db80d714c1ea03391a797c8557f88b6a2c97c1fc6c2384768c628de6be8bddab1c8dcfc9794803527a9a51dace1d77136dc3185394dc61ab49f8124e8a009240d751d28dc55dbd7757b965474cff00a03a4d11f6bb71696f1b255092e03b7f318cc1694e5d27e9a50f4d8a529bc935fa14f5338c31f1c6f6feba5ffa0dfc37c058387b98264639496cc54e99a63c5b4ccfec51d7bf8716d67bb3bf5d2a61da6c3da5cb67ba596249cfa6be41758da3c856b7bb5cab052fda2635519c83d4ea800ae9cb8e2d6d7e0e7f4f9f345d275faf5fe85f89eca35b59c9b6e697e1fb3f94e66ff5491faed4f1bb78a44129ae875d3da82c6f696cdcd59d4691a11fb35c392114fe367af8b3e56be7c7f54d1be1f096a41d23cb9d30bb8c00650200da2ab7ff00e4969242e569e646de9a6942623b436da658ebd0081f3fd29638a6fa41c9971ddc9dd7ee59ecf11bacc1164f285fcfa0a32e700bf1de05b7708040b6d3967af991ac55530fdb1b696fbbf140d642a493beb2a7e915bb7f109c464b8fa750867d7c126bbb1618c76ddb3cbf51ea2592e3054bfbb15e36cba1f14ab12667ebe54beda15f113975d07cb9ee451d8eed31bcd9ae33127a651ff8a8d2827c7da6194ab11feaaae8e1f6a41d6c16049b9046c001afbef52613c256ebaf80b492047c891af3d6978e2aaa20078ff59a92e768198416b844ed9c81fed1a5646f6d963c35d0e5afe1f150c924864001074930daf4da83c362f3026f640f267c647e115132d8ee3bcb560673058667db9e8184f5a5cd89277c1db6f36b527e675a2c0a02218e6b632a0583b8613a74335a5c166eea87bbb9fd267293fe56e47c8d6565751234b58f7b63238d06b1faf5a17017b3dfccc575e6d9a075f8066dba57b594b464d9d6fb27c3adbdbbcadddb5e16d7c2968a15425b42614b298d24fb532e13c30e192fe252e839d405551a4f288dccc00046a2b2b2a325e4bc1f48878ae23bac384ef033124dd0a80b66e65bc5e11cb6a4385c67f26f5def955d6f5bccbdd8626d8cb0606a7773035d394835e5650c710b74c1ec71cb18a6fb2bdaba4b928b8854c85509967248612378d76a59daec3358b9953126e1b50b6c3b8636c01a9ca515483a6be3f3acaca67151e829b96c5f8bed1d94b16d6ef094b97483389f0a0632632f756f2931e623a53bfe143a626c62b0a014707beb4092441856130240207fbab2b2b4e29aa62e3c9284b9205e35c21ad9c8c867ace9ed5571c3951a2e0d2743fad7b595c90fa3d49d3f906d97c26ec648e439d196efddba42da4c8b1a311048f23cab2b2b4e291b14dcb43becff00649ae32b3723ed1f99ae938dc55bc2d92042e5512c37f6eb5e5650c6b5c819772e1e16ce37c76c5fe2778dec3c3a5a5009ef54059eb3196a4e1fd99c6dc25156c3b28d57bf4689d0139663deb2b2baf82a3cc79a56c27ffd6bc460009683ee7f9c36e5a659ebad7b6bb078f2c503d9ce2257bf32267905acacadc503dc61387fe1ef1175ccaf6181d0117988d0c724d75d2836ec6e2e48fb4e134394c5e630768316cc199acacadc503dc64f8efe1ee3ed2e67b98651b4f7973f2b55ee13f8718eb9396f61654c302f7641f306d0359595a8dcd87dafe1763c7fd5c37cdcff00f5d797ff008718d50335eb11e42e37e0b59595b8a0b9b24b3fc33c4993dfd9007547fce2b6b3fc33c4b0917ac7fb5ab2b2b38a154d82623b03755ca1c4da5223fe936bef1f856d6fb08d207db6d4f4160b11fa0f3acacadc103dc60389ecc8472a718011a19c33083c86c77a7973f86b885009c62ebfd3641fcc565651e2acdc9d0b5bb30f6c956c7811a6516a7fe2bc7e06d00fdbf28e44d90bf8d65652b8aba329304c361acb5c368f1628d1f7d0a2ff00ba3281a1d669862bb3450807894c8995791f3cd595947820f267ffd9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `precio` int(11) NOT NULL,
  `tipo` varchar(30) DEFAULT NULL,
  `admin_admin_id` int(11) NOT NULL,
  `stock_stock_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`producto_id`, `cantidad`, `nombre`, `precio`, `tipo`, `admin_admin_id`, `stock_stock_id`) VALUES
(1, 150, 'pedro', 100, 'alimento', 1, 1),
(2, 100, 'coca cola', 10, 'bebida', 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocion`
--

CREATE TABLE `promocion` (
  `promocion_id` int(11) NOT NULL,
  `condiciones` varchar(10) DEFAULT NULL,
  `duracion` date DEFAULT NULL,
  `titulo` varchar(30) NOT NULL,
  `estado` varchar(10) DEFAULT NULL,
  `tipo` enum('descuento','2x1','combo','cumpleaños') NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `promocion`
--

INSERT INTO `promocion` (`promocion_id`, `condiciones`, `duracion`, `titulo`, `estado`, `tipo`, `descripcion`) VALUES
(1, 'sabados', '0000-00-00', 'aaaa', 'activa', 'descuento', 'aaaaaaaaa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promo_cliente`
--

CREATE TABLE `promo_cliente` (
  `promocion_promocion_id` int(11) NOT NULL,
  `cliente_cliente_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `promo_cliente`
--

INSERT INTO `promo_cliente` (`promocion_promocion_id`, `cliente_cliente_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro de visita`
--

CREATE TABLE `registro de visita` (
  `id_visita` int(11) NOT NULL,
  `fecha_hora` datetime(3) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `registro de visita`
--

INSERT INTO `registro de visita` (`id_visita`, `fecha_hora`, `cantidad`) VALUES
(1, '2025-11-05 21:26:11.000', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` int(11) NOT NULL,
  `hora_inicio` time NOT NULL,
  `estado` enum('pendiente','confirmada','cancelada','completada') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `cliente_cliente_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`id_reserva`, `hora_inicio`, `estado`, `cantidad`, `fecha`, `descripcion`, `cliente_cliente_id`) VALUES
(1, '13:20:00', 'completada', 15, '2025-11-05', 'aaa', 1),
(2, '21:30:00', 'confirmada', 15, '2025-11-05', 'aaaaaaaaa', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE `stock` (
  `stock_id` int(11) NOT NULL,
  `unidad_medida` varchar(45) DEFAULT NULL,
  `cantidad_total` int(11) DEFAULT NULL,
  `ultima_actu` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `stock`
--

INSERT INTO `stock` (`stock_id`, `unidad_medida`, `cantidad_total`, `ultima_actu`) VALUES
(1, 'kg', 150, '2025-11-05'),
(2, 'litros', 100, '2025-11-05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `telefono`
--

CREATE TABLE `telefono` (
  `id_telefono` int(11) NOT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `cliente_cliente_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `telefono`
--

INSERT INTO `telefono` (`id_telefono`, `telefono`, `cliente_cliente_id`) VALUES
(1, '092274638', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `nombre` varchar(20) NOT NULL,
  `fecha_nac` date DEFAULT NULL,
  `gmail` varchar(100) NOT NULL,
  `contraseña` varchar(100) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `nacionalidad` varchar(45) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`nombre`, `fecha_nac`, `gmail`, `contraseña`, `apellido`, `fecha_registro`, `nacionalidad`, `id_usuario`) VALUES
('admin', '2007-11-25', 'admin@gmail.com', '123456', 'admin1', '2025-11-05 12:36:45', 'peruano', 1),
('cliente', '2000-11-12', 'cliente@gmail.com', '123456', 'cliente1', '2025-11-05 12:38:38', 'nigeria', 2),
('pedro', '2000-05-05', 'cliente1@gmail.com', '123456', 'juan', '2025-11-05 12:40:59', 'Afganistán', 3),
('pedro500', '1900-02-22', 'empleado@gmail.com', '123456', 'juan', '2025-11-05 12:59:56', 'aaa', 4),
('pedro500', '2000-05-05', 'prueba@gmail.com', '123456', 'juan', '2025-11-05 13:47:37', 'uruguay', 5);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`,`usuario_id_usuario`),
  ADD KEY `fk_admin_usuario1_idx` (`usuario_id_usuario`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`cliente_id`,`usuario_id_usuario`),
  ADD KEY `fk_cliente_usuario1_idx` (`usuario_id_usuario`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`empleado_id`,`usuario_id_usuario`),
  ADD KEY `fk_empleado_usuario1_idx` (`usuario_id_usuario`);

--
-- Indices de la tabla `historial`
--
ALTER TABLE `historial`
  ADD PRIMARY KEY (`historial_id`,`stock_id stock`,`admin_admin_id`),
  ADD KEY `fk_historial_stock1_idx` (`stock_id stock`),
  ADD KEY `fk_historial_admin1_idx` (`admin_admin_id`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`,`admin_admin_id`),
  ADD KEY `fk_menu_admin1_idx` (`admin_admin_id`);

--
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`mesa_id`);

--
-- Indices de la tabla `mesa_cliente`
--
ALTER TABLE `mesa_cliente`
  ADD PRIMARY KEY (`mesa_mesa_id`,`cliente_cliente_id`),
  ADD KEY `fk_mesa_has_cliente_cliente1_idx` (`cliente_cliente_id`),
  ADD KEY `fk_mesa_has_cliente_mesa1_idx` (`mesa_mesa_id`);

--
-- Indices de la tabla `mesa_empleado`
--
ALTER TABLE `mesa_empleado`
  ADD PRIMARY KEY (`mesa_mesa_id`,`empleado_empleado_id`),
  ADD KEY `fk_mesa_has_empleado_empleado1_idx` (`empleado_empleado_id`),
  ADD KEY `fk_mesa_has_empleado_mesa1_idx` (`mesa_mesa_id`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`pedido_id`,`mesa_mesa_id`),
  ADD KEY `fk_pedido_mesa1_idx` (`mesa_mesa_id`);

--
-- Indices de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  ADD PRIMARY KEY (`pedido_pedido_id`,`plato_plato_id`),
  ADD KEY `fk_pedido_detalle_plato1_idx` (`plato_plato_id`);

--
-- Indices de la tabla `plato`
--
ALTER TABLE `plato`
  ADD PRIMARY KEY (`plato_id`,`menu_id menu`),
  ADD KEY `fk_plato_menu1_idx` (`menu_id menu`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`producto_id`,`admin_admin_id`,`stock_stock_id`),
  ADD KEY `fk_producto_admin1_idx` (`admin_admin_id`),
  ADD KEY `fk_producto_stock1_idx` (`stock_stock_id`);

--
-- Indices de la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD PRIMARY KEY (`promocion_id`);

--
-- Indices de la tabla `promo_cliente`
--
ALTER TABLE `promo_cliente`
  ADD PRIMARY KEY (`promocion_promocion_id`,`cliente_cliente_id`),
  ADD KEY `fk_promocion_has_cliente_cliente1_idx` (`cliente_cliente_id`),
  ADD KEY `fk_promocion_has_cliente_promocion1_idx` (`promocion_promocion_id`);

--
-- Indices de la tabla `registro de visita`
--
ALTER TABLE `registro de visita`
  ADD PRIMARY KEY (`id_visita`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id_reserva`,`cliente_cliente_id`),
  ADD KEY `fk_reserva_cliente1_idx` (`cliente_cliente_id`);

--
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indices de la tabla `telefono`
--
ALTER TABLE `telefono`
  ADD PRIMARY KEY (`id_telefono`,`cliente_cliente_id`),
  ADD KEY `fk_telefono_cliente1_idx` (`cliente_cliente_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `gmail_UNIQUE` (`gmail`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `cliente_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `empleado_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `historial`
--
ALTER TABLE `historial`
  MODIFY `historial_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `mesa`
--
ALTER TABLE `mesa`
  MODIFY `mesa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `pedido_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `plato`
--
ALTER TABLE `plato`
  MODIFY `plato_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `producto_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `promocion`
--
ALTER TABLE `promocion`
  MODIFY `promocion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `registro de visita`
--
ALTER TABLE `registro de visita`
  MODIFY `id_visita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `telefono`
--
ALTER TABLE `telefono`
  MODIFY `id_telefono` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `fk_admin_usuario1` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `fk_cliente_usuario1` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `fk_empleado_usuario1` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `historial`
--
ALTER TABLE `historial`
  ADD CONSTRAINT `fk_historial_admin1` FOREIGN KEY (`admin_admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_historial_stock1` FOREIGN KEY (`stock_id stock`) REFERENCES `stock` (`stock_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fk_menu_admin1` FOREIGN KEY (`admin_admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mesa_cliente`
--
ALTER TABLE `mesa_cliente`
  ADD CONSTRAINT `fk_mesa_has_cliente_cliente1` FOREIGN KEY (`cliente_cliente_id`) REFERENCES `cliente` (`cliente_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mesa_has_cliente_mesa1` FOREIGN KEY (`mesa_mesa_id`) REFERENCES `mesa` (`mesa_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mesa_empleado`
--
ALTER TABLE `mesa_empleado`
  ADD CONSTRAINT `fk_mesa_has_empleado_empleado1` FOREIGN KEY (`empleado_empleado_id`) REFERENCES `empleado` (`empleado_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mesa_has_empleado_mesa1` FOREIGN KEY (`mesa_mesa_id`) REFERENCES `mesa` (`mesa_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `fk_pedido_mesa1` FOREIGN KEY (`mesa_mesa_id`) REFERENCES `mesa` (`mesa_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  ADD CONSTRAINT `fk_pedido_detalle_pedido1` FOREIGN KEY (`pedido_pedido_id`) REFERENCES `pedido` (`pedido_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pedido_detalle_plato1` FOREIGN KEY (`plato_plato_id`) REFERENCES `plato` (`plato_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `plato`
--
ALTER TABLE `plato`
  ADD CONSTRAINT `fk_plato_menu1` FOREIGN KEY (`menu_id menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_producto_admin1` FOREIGN KEY (`admin_admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_producto_stock1` FOREIGN KEY (`stock_stock_id`) REFERENCES `stock` (`stock_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `promo_cliente`
--
ALTER TABLE `promo_cliente`
  ADD CONSTRAINT `fk_promocion_has_cliente_cliente1` FOREIGN KEY (`cliente_cliente_id`) REFERENCES `cliente` (`cliente_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_promocion_has_cliente_promocion1` FOREIGN KEY (`promocion_promocion_id`) REFERENCES `promocion` (`promocion_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `fk_reserva_cliente1` FOREIGN KEY (`cliente_cliente_id`) REFERENCES `cliente` (`cliente_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `telefono`
--
ALTER TABLE `telefono`
  ADD CONSTRAINT `fk_telefono_cliente1` FOREIGN KEY (`cliente_cliente_id`) REFERENCES `cliente` (`cliente_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
