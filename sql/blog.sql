-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : lun. 19 déc. 2022 à 09:44
-- Version du serveur : 5.7.36
-- Version de PHP : 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `content` mediumtext CHARACTER SET utf8 NOT NULL,
  `createdAt` datetime NOT NULL,
  `isValid` tinyint(4) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `content`, `createdAt`, `isValid`, `post_id`, `user_id`) VALUES
(1, 'Ut officiis blanditiis qui voluptatum aliquam est facere mollitia qui adipisci necessitatibus sit internos nesciunt non praesentium harum ut fugit natus!', '2022-12-19 10:36:26', 0, 7, 13),
(3, 'Ut magnam fugiat et fugit quidem qui asperiores magni et amet quisquam est obcaecati velit ad rerum galisum! Eum soluta tenetur et dolorum assumenda et magni voluptatem eos galisum magnam sit voluptas eligendi!', '2022-12-19 10:38:28', 1, 7, 14),
(4, 'Aut omnis tempora ab esse labore id ratione velit non perferendis laborum. Non voluptatum minus aut ducimus officia et galisum porro eos sunt aliquam et cupiditate enim rem voluptas tenetur.', '2022-12-19 10:38:48', 1, 6, 14),
(5, 'Et velit autem qui fugiat dignissimos est necessitatibus aliquam cum officiis odio ut odit consequatur?', '2022-12-19 10:40:20', 1, 7, 15),
(6, 'Qui voluptate galisum est nulla eaque sed labore enim?', '2022-12-19 10:40:44', 1, 6, 15);

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `featuredImage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `updateAt` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id`, `title`, `excerpt`, `content`, `featuredImage`, `createdAt`, `updateAt`, `user_id`) VALUES
(1, 'Sed rutrum quis mauris viverra cursus.', 'Nullam cursus tellus quam, in aliquet mauris fermentum dapibus. Morbi nec tellus leo. Maecenas et arcu a leo scelerisque elementum. Vestibulum maximus risus at sem vestibulum, a placerat tellus tincidunt. Phasellus bibendum, mauris nec pretium ullamcorper, nibh nibh rhoncus turpis, at tempor ipsum tellus scelerisque lacus.', '<p>Curabitur quis varius magna, at lacinia ipsum. Sed sed mi sem. Praesent quis justo in purus commodo consequat in sit amet diam. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Ut sit amet lectus aliquet, sodales purus eget, rhoncus felis. Aliquam congue elit molestie, vestibulum nunc id, tristique arcu. Nunc pellentesque id nunc id dapibus. Aliquam erat volutpat. Suspendisse euismod dui eleifend lorem ultricies, eu faucibus leo ullamcorper. Donec condimentum augue ac placerat lacinia. Cras vel nisi mi. Vivamus tristique volutpat mi id aliquet. Mauris gravida nibh ut tortor tristique placerat.</p>\r\n<p><code>Aenean vel varius sapien. Morbi egestas vel elit vel lobortis. </code></p>\r\n<p>Duis quis suscipit leo. Vestibulum purus nisl, aliquet in sagittis id, posuere eu lacus. In eleifend tempus tortor, vitae rutrum sem faucibus nec. Duis vel leo dignissim, malesuada dolor vel, placerat enim. Etiam vitae ex at mi facilisis efficitur. Integer placerat accumsan nibh, nec ultrices orci finibus in. Nunc pulvinar arcu ex, et dignissim turpis pharetra id. Pellentesque efficitur est ac nisl dignissim vulputate. Integer nibh risus, blandit id consectetur in, semper sed mi. Aenean sed massa id nunc dapibus blandit. Proin aliquet pellentesque neque.</p>\r\n<p><code>Nunc sodales eros vitae ligula malesuada laoreet in sed eros. </code></p>\r\n<p>Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec faucibus sed nisl eu fringilla. Aenean a sem non metus scelerisque laoreet ac id justo. In bibendum quam quis nunc feugiat convallis. Donec congue nisl dignissim tortor mattis placerat. Integer vestibulum sapien elit, nec elementum augue convallis vel. Aenean convallis, turpis sed suscipit mollis, dui libero blandit quam, non consequat orci dolor nec erat. Suspendisse cursus malesuada dui non venenatis. Etiam vehicula faucibus sapien vel sodales. Integer at eros lectus. Donec non ipsum pretium, eleifend nisl quis, auctor urna.</p>\r\n<p>Vivamus vitae velit porta, porttitor velit venenatis, luctus augue. Nulla venenatis lorem finibus orci facilisis ultricies. Donec in risus risus. Fusce dictum ante vulputate eros venenatis, sit amet finibus ex maximus. Donec gravida felis quis felis imperdiet auctor. Proin vitae placerat nunc. Nunc ut elit et ligula feugiat porttitor. Donec id tristique odio. Phasellus sit amet lorem vulputate, dictum orci ac, blandit ipsum. Etiam mollis ex ac diam malesuada, et mollis orci tempor.</p>', 'img_639f51e9e9d33_1671385577.jpg', '2022-12-18 18:46:17', NULL, 11),
(2, 'Aut reiciendis tempore qui voluptate dolore.', 'Sit maiores esse ut aperiam quis sit molestiae omnis non consequatur omnis nam quia suscipit aut repellat explicabo. Rem minima quidem et voluptas Quis qui quos reprehenderit non maxime corrupti est sapiente deleniti ad quia officia! 33 culpa laboriosam in rerum facere vel repudiandae enim aut recusandae assumenda et facere molestias.', '<h1>Aut nisi magni aut voluptatem temporibus.</h1>\n<p>Et sequi voluptate est nihil laboriosam eum iste nulla et distinctio veniam sed nostrum tenetur ut deleniti rerum. Ad nostrum expedita a consequatur illum aut corrupti minus vel obcaecati fuga aut quia nostrum est earum voluptas et illum nesciunt. Eos itaque sunt id doloremque facilis ut provident libero et animi officiis. Aut illum temporibus qui perferendis minus non laborum molestias.</p>\n<h2>Ab quisquam magni qui nostrum magnam qui molestias magni!</h2>\n<p>Sed quas porro ab ipsa facilis et dolorem dicta et libero eius vel mollitia quae? Aut dignissimos omnis et quia nulla qui nihil voluptas vel inventore dolor sit reiciendis galisum sit error vitae.</p>\n<h3>Et asperiores minima eum eius aliquid.</h3>\n<p>Est iste soluta qui vero recusandae a rerum consequuntur. Et quia accusamus eum quis sequi sed nostrum eligendi qui perferendis fuga ut dicta veritatis sed veritatis autem qui galisum aliquam. Qui officia Quis et iusto rerum et sequi modi aut molestias quam id voluptatem eveniet ea suscipit perspiciatis.</p>\n<blockquote cite=\"https://www.loremipzum.com\">Vel illum itaque sit placeat Quis id fugit aliquam et nobis soluta.</blockquote>\n<h4>Aut nihil minus ut voluptate adipisci.</h4>\n<p>Rem quaerat autem non repudiandae voluptatum ut expedita Quis ut distinctio aliquid. Ea neque voluptatem sit atque velit cum temporibus architecto sed cumque repudiandae eos facilis numquam. Aut quaerat voluptatibus qui possimus pariatur rem nisi debitis et omnis tempore eum rerum natus aut inventore incidunt qui fugiat distinctio. Sit iste velit ut laboriosam minima ex molestiae voluptatum et beatae alias eos nostrum blanditiis non veritatis illum et asperiores exercitationem.</p>\n<dl>\n<dt><dfn>Eos quia animi At explicabo reprehenderit. </dfn></dt>\n<dd>Non quasi culpa et dolor ipsum in dignissimos recusandae?</dd>\n<dt><dfn>Non quia quia. </dfn></dt>\n<dd>Qui excepturi eveniet qui aliquam dolorem est minima veniam.</dd>\n</dl>', 'img_639f5502ddd5a_1671386370.jpg', '2022-12-18 18:59:30', NULL, 11),
(3, 'Ut veniam voluptatem sit necessitatibus internos!', 'Eos facilis molestiae quo obcaecati minus est quibusdam iure est repellendus sint aut voluptatem tempore et error suscipit et saepe dolorem. Eum placeat placeat quo quos dicta et quis magni qui sunt eaque.', '<h1>Et rerum obcaecati aut aliquam voluptatem sed autem voluptate.</h1>\r\n<p>Sit nemo facilis aut quos sunt qui error consectetur. Non totam quisquam est tempora veniam 33 dolor consequatur ut voluptatum distinctio et ipsam dicta ut explicabo deleniti eos nobis asperiores.</p>\r\n<h2>Sed nobis molestiae sit quasi temporibus eum fuga accusantium.</h2>\r\n<p>At aliquid laudantium et dicta maiores qui quibusdam laboriosam et illo tempore nam maxime amet ut nobis illum et quae perspiciatis. Et architecto optio ut maxime perferendis qui minima fugit et eligendi nihil est necessitatibus veniam qui dignissimos facere. Ea doloremque perferendis 33 voluptas consequatur quo excepturi dolorum. Qui beatae eius qui fugit corporis ea debitis dolores aut molestias voluptas sit magni eaque ea omnis temporibus hic dignissimos libero.</p>\r\n<dl>\r\n<dt><dfn>Ut iusto beatae. </dfn></dt>\r\n<dd>Et sint nemo et illo aliquid et voluptates iure vel rerum voluptatem!</dd>\r\n<dt><dfn>Sit molestiae galisum ex tenetur porro. </dfn></dt>\r\n<dd>Ut quos omnis hic animi repellendus sit enim natus sit fugiat amet.</dd>\r\n<dt><dfn>In officiis unde. </dfn></dt>\r\n<dd>Et rerum autem non aliquid voluptatem et itaque laboriosam quo animi accusantium.</dd>\r\n</dl>\r\n<blockquote cite=\"https://www.loremipzum.com\">Aut fuga ipsum ut incidunt consectetur aut commodi earum ut dolorem voluptatem est eveniet dolore.</blockquote>\r\n<h3>Ab eius tempore ut voluptates adipisci.</h3>\r\n<p>Ut dolores consectetur aut deleniti tempora a voluptatem culpa a aliquid perferendis aut nulla labore qui assumenda nihil ut quas dolore. Est ipsum voluptas ut dolores dicta sed delectus eaque. Et reiciendis quia et officiis accusantium sit quia cupiditate!</p>\r\n<pre><code>&lt;!-- Aut voluptas explicabo vel veniam dolor sit rerum fugiat. --&gt;<br>&lt;hic&gt;Qui tempora iure.&lt;/hic&gt;<br>&lt;commodi&gt;Sed ratione sequi.&lt;/commodi&gt;<br>&lt;et&gt;Eos vitae similique.&lt;/et&gt;<br></code></pre>\r\n<h4>Id labore eius eum eligendi sapiente in animi dignissimos.</h4>\r\n<p>Nam quia facilis id facere placeat aut itaque recusandae ut voluptate quia a voluptatibus laboriosam. Et quos suscipit eos aliquam rerum rem veritatis sequi aut odio neque sit impedit tempora. Et culpa sunt est cupiditate repellendus sed dolor necessitatibus. Et libero galisum non eveniet doloribus aut fuga rerum in voluptatibus reiciendis.</p>', 'img_639f55445779c_1671386436.jpg', '2022-12-18 19:00:36', NULL, 11),
(4, 'Nam architecto beatae ut eius facere.', '33 expedita quae et pariatur eius id incidunt eius nam fugit porro est cumque dolorem! Sed reiciendis assumenda 33 possimus necessitatibus ut voluptatibus alias non soluta animi. Id enim rerum nam iure voluptas ut corrupti molestiae aut repellat quia. Vel porro fugit non officia velit aut labore neque.', '<h1>Quo reiciendis neque ut perspiciatis porro.</h1>\r\n<p>Qui doloremque architecto sit aliquam voluptate est expedita omnis. Aut sapiente voluptas cum unde voluptatum ea officia similique et impedit quia quo temporibus aperiam. In quod distinctio qui voluptate illum et rerum Quis.</p>\r\n<blockquote cite=\"https://www.loremipzum.com\">Eum voluptates provident ut voluptatibus nihil ex quisquam aliquam et voluptas aliquam sit cumque voluptatem et eveniet excepturi.</blockquote>\r\n<h2>Et quia consequatur sit mollitia illo.</h2>\r\n<p>Qui rerum ullam et sunt dolorem aut voluptas sunt. Ut voluptatem rerum aut modi reprehenderit et mollitia neque. Et porro voluptas qui officia quasi qui corporis ducimus hic dolores velit eos animi ipsum est repudiandae illum eos consequuntur obcaecati! Eum dolores cupiditate hic debitis molestiae cum ratione voluptatibus sit molestias beatae?</p>\r\n<dl>\r\n<dt><dfn>Sed ullam adipisci! </dfn></dt>\r\n<dd>Sed eius quibusdam et iure aliquam.</dd>\r\n<dt><dfn>Et debitis voluptas in fugit voluptatum. </dfn></dt>\r\n<dd>Et maiores optio ab porro autem ut labore suscipit!</dd>\r\n<dt><dfn>Et beatae dolorem et rerum dicta. </dfn></dt>\r\n<dd>Ex laudantium expedita aut incidunt voluptas vel temporibus voluptate.</dd>\r\n<dt><dfn>Est explicabo voluptates et voluptatibus Quis. </dfn></dt>\r\n<dd>Sit magni enim et quasi maxime.</dd>\r\n</dl>\r\n<h3>Aut sapiente unde ea commodi culpa.</h3>\r\n<p>Quo pariatur aliquam et corporis culpa et ratione sapiente. Est itaque odit vel corporis molestiae eum quis vitae ut aperiam nobis qui corrupti sint! Est facilis dolores est accusantium excepturi vel quis obcaecati. Non voluptatem odit quo enim sapiente ex facere quos et illo autem ut laboriosam facilis sed libero rerum.</p>\r\n<pre class=\"language-php\"><code>&lt;html&gt;\r\n &lt;head&gt;\r\n  &lt;title&gt;PHP&lt;/title&gt;\r\n &lt;/head&gt;\r\n &lt;body&gt;\r\n  &lt;?php echo \'&lt;p&gt;Bonjour le monde&lt;/p&gt;\'; ?&gt;\r\n &lt;/body&gt;\r\n&lt;/html&gt;</code></pre>\r\n<pre><code>&nbsp;</code></pre>\r\n<h4>Aut quos aspernatur rem soluta velit At obcaecati eius.</h4>\r\n<p>Qui suscipit delectus et similique sunt est tempora voluptatem 33 voluptas voluptate est magnam iste sit Quis quia et galisum assumenda. Ut iste voluptatem et quas mollitia sed deserunt magnam et possimus ratione et saepe neque ut provident deleniti.</p>', 'img_639f5574e6c97_1671386484.jpg', '2022-12-18 19:01:24', '2022-12-19 09:56:26', 11),
(5, 'Est fugit dolorem cum dolores amet.', 'Aut earum sapiente ea fugit doloremque qui Quis laudantium vel pariatur explicabo eos maiores cupiditate. Non maxime consequatur qui ipsa iusto aut voluptatem laborum ut necessitatibus galisum et eveniet dolor. Sed dolores quaerat et quia placeat ut mollitia quia. Ut placeat dolore quo nobis nobis non nostrum neque.', '<h1>Sit accusantium reiciendis est voluptas voluptas.</h1>\r\n<p>Ut modi velit est deserunt veritatis cum nostrum enim. Quo pariatur magnam ea obcaecati harum aut quae labore qui velit possimus sit unde quos? Rem adipisci animi ex facilis molestiae sit temporibus maxime et blanditiis omnis eos facilis cumque non deserunt quia est quis dolor? Qui rerum itaque et amet accusamus qui fugiat facilis non dolorem atque id quisquam obcaecati ut quibusdam voluptas et provident tempore?</p>\r\n<h2>Ex consequatur rerum est atque officia.</h2>\r\n<p>Aut consequatur consequatur eum esse autem a reiciendis natus in expedita nisi nam dolorem beatae aut beatae asperiores sed voluptatibus laudantium. Vel deleniti facere id adipisci mollitia ad cupiditate culpa qui voluptate fugiat vel nostrum consequuntur et cupiditate libero rem nulla eveniet. Et reiciendis vitae ex neque aliquid eos nobis omnis ab amet voluptatem id corporis iusto ut eaque officia ut culpa beatae.</p>\r\n<pre class=\"language-javascript\"><code>&lt;button onclick=\"myFunction()\"&gt;Post&lt;/button&gt;\r\n\r\n&lt;script&gt;\r\nfunction myFunction() {\r\n    document.write(5 + 6);\r\n}\r\n&lt;/script&gt;</code></pre>\r\n<p>Et maiores reiciendis aut mollitia aliquid est eligendi animi.</p>\r\n<p>Cum sint optio est accusamus voluptates eos fugit soluta ex incidunt rerum id excepturi nesciunt. 33 consectetur consequatur aut ipsa recusandae et nulla modi nam distinctio voluptas aut mollitia Quis. Eos labore maxime et placeat impedit et omnis dolorem et quia iure et consequuntur impedit nam libero alias.</p>\r\n<dl>\r\n<dt><dfn>Ut repudiandae ipsa! </dfn></dt>\r\n<dd>Sed alias quaerat sit repudiandae accusantium non natus optio et distinctio commodi?</dd>\r\n<dt><dfn>A soluta voluptatem eos distinctio ipsa. </dfn></dt>\r\n<dd>Ut nobis quidem et repudiandae repellat eum fugiat placeat ad aliquid dignissimos.</dd>\r\n</dl>\r\n<h4>Eum internos quidem qui exercitationem eius est voluptatem autem.</h4>\r\n<p>Et itaque vero ut debitis perspiciatis sit inventore beatae eum internos deleniti. Et quasi odit 33 nostrum earum sit quia quasi ab quasi repudiandae qui omnis rerum. Ut cupiditate quae et accusamus sint qui sequi quas non dolor distinctio qui nulla perferendis sit nesciunt fuga.</p>', 'img_639f559954071_1671386521.jpg', '2022-12-18 19:02:01', '2022-12-19 09:49:50', 11),
(6, 'Est nemo nemo et minus aliquid rem quis sapiente?', 'Vel porro fugiat cum suscipit autem aut tempore labore in enim incidunt est repellat aspernatur. Quo eligendi cumque et debitis excepturi ea ullam illum sit aperiam internos sit provident cupiditate. Qui illum placeat ut debitis sunt et laborum modi ea optio molestias sed praesentium reprehenderit a praesentium ipsa vel atque magni.', '<h1>Qui nobis iste hic corrupti illum et illum molestiae.</h1>\r\n<p>Qui officia temporibus eos sunt impedit non tempora asperiores est quaerat esse At tempora debitis. Eum maiores officiis ea velit quia et maiores aliquam.</p>\r\n<dl>\r\n<dt><dfn>Et optio minima ut animi sequi. </dfn></dt>\r\n<dd>Eum amet eveniet est fugiat obcaecati ea impedit quisquam quo incidunt enim!</dd>\r\n<dt><dfn>Non optio magnam aut rerum nihil? </dfn></dt>\r\n<dd>Et voluptatem omnis et quisquam quisquam!</dd>\r\n<dt><dfn>Et eius iure? </dfn></dt>\r\n<dd>Et illo natus qui eligendi nulla id architecto omnis ut tempora galisum.</dd>\r\n<dt><dfn>Et voluptatem voluptatem id voluptatem galisum? </dfn></dt>\r\n<dd>Qui sint voluptate aut similique maiores qui tempore laborum.</dd>\r\n</dl>\r\n<pre class=\"language-css\"><code>button {\r\n  width:20px;\r\n  height:28px;\r\n  color:#fff;\r\n  font-size:28px;\r\n  padding:11px 15px;\r\n  border-radius:5px;\r\n  background:#14ADE5;\r\n}</code></pre>\r\n<p>Qui officia temporibus aut quia maiores non distinctio vero ea nisi exercitationem et omnis quis. Rem doloribus quae et iure eveniet At quisquam iure At quisquam error a earum magnam. In inventore nemo ea sint perferendis qui quis dolore. Ea delectus Quis quo facere nihil et amet quidem et voluptas porro eos quam rerum sed aspernatur maiores ea dolorem distinctio!</p>\r\n<h3>Est numquam delectus et dicta ducimus.</h3>\r\n<p>Cum possimus distinctio est numquam ducimus cum officiis quia. Et voluptatibus sint a aliquid debitis sit porro beatae et quia maxime ea repellat nisi.</p>\r\n<h4>Ab ipsam repellendus et nostrum quaerat sed possimus debitis?</h4>\r\n<p>Qui vero animi et reiciendis dolorum aut totam modi aut rerum enim nam accusamus omnis. Aut animi consequatur et tempora similique qui deleniti reprehenderit vel deleniti autem.</p>', 'img_639f55afd4595_1671386543.jpg', '2022-12-18 19:02:23', '2022-12-19 09:49:04', 11),
(7, 'Qui mollitia incidunt ab suscipit sequi et fuga quod.', 'Et voluptatem maiores et similique saepe ut officiis rerum ab tempora illum ut voluptatibus earum. Hic sequi sint ut quia alias et reiciendis natus est cumque dolore id nemo dicta eos facilis laborum et accusantium tempora! Eos rerum earum et omnis Quis et aliquid excepturi. Ut internos reiciendis sed fugit quae et explicabo amet rem velit autem hic nulla laboriosam.', '<h1>Vel nisi repellat vel earum repudiandae eum voluptas velit.</h1>\r\n<p>Lorem ipsum dolor sit amet. Et molestiae deleniti est tempora maiores ut alias aliquid sit deserunt molestiae. Nam culpa architecto non dicta excepturi id expedita odit sit deserunt harum et consequatur voluptates. Ex nihil laborum et architecto tempore et fugit quam sed autem itaque.</p>\r\n<h2>Et debitis voluptas ut voluptas perferendis.</h2>\r\n<p>Ad tenetur illum est eaque iste sed accusamus dolorum vel quidem fugiat vel consequuntur aperiam est quasi deleniti. Ad dignissimos aspernatur et assumenda illo qui impedit ducimus non veniam sint.</p>\r\n<blockquote cite=\"https://www.loremipzum.com\">At eveniet inventore sed dignissimos dolores ut ipsa laborum ut quidem voluptatibus?</blockquote>\r\n<h3>Aut velit doloribus non consequatur facilis!</h3>\r\n<p>Aut consequatur inventore ut nobis perferendis At recusandae vitae est vitae perferendis non alias itaque cum tempore blanditiis. Aut recusandae suscipit qui excepturi rerum ut libero omnis et explicabo quis.</p>\r\n<h4>Sit ipsa earum nam doloribus reprehenderit.</h4>\r\n<p>33 excepturi enim qui doloribus perferendis est consequatur dolorem in error molestiae. Ut facere perferendis et consequuntur enim ut inventore fuga eum rerum nulla. Ut provident quia ab numquam dicta est sequi vero id expedita natus eum saepe explicabo aut dolores veritatis rem minus enim? Et explicabo repellat ut eaque consequatur eum officiis voluptatem.</p>\r\n<pre class=\"language-markup\"><code>&lt;!DOCTYPE html&gt;\r\n&lt;html lang=\"en\"&gt;\r\n&lt;head&gt;\r\n	&lt;meta charset=\"utf-8\" /&gt;\r\n	&lt;title&gt;I can haz embedded CSS and JS&lt;/title&gt;\r\n	&lt;style&gt;\r\n		@media print {\r\n			p { color: red !important; }\r\n		}\r\n	&lt;/style&gt;\r\n&lt;/head&gt;\r\n&lt;body&gt;\r\n	&lt;h1&gt;I can haz embedded CSS and JS&lt;/h1&gt;\r\n	&lt;script&gt;\r\n	if (true) {\r\n		console.log(\'foo\');\r\n	}\r\n	&lt;/script&gt;\r\n\r\n&lt;/body&gt;\r\n&lt;/html&gt;</code></pre>\r\n<h5>Est dolor voluptatem ut iste earum ad earum odio.</h5>\r\n<p>Nam voluptatem possimus sit voluptatem perspiciatis est dolorem distinctio. Vel expedita cupiditate ex maiores eligendi ut autem expedita eum odio deserunt aut quod aspernatur aut labore sint et consequatur rerum. Est vero sequi nam maxime assumenda et nemo voluptas ut consectetur vitae hic dolore enim et suscipit accusamus.</p>', 'img_639f55cb5f939_1671386571.jpg', '2022-12-18 19:02:51', '2022-12-19 10:03:07', 11);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(45) CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `firstname` varchar(45) CHARACTER SET utf8 NOT NULL,
  `lastname` varchar(45) CHARACTER SET utf8 NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `createdAt` datetime NOT NULL,
  `isAdmin` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `firstname`, `lastname`, `password`, `createdAt`, `isAdmin`) VALUES
(11, 'Admin', 'admin@admin.com', 'Démo', 'Admin', '$2y$10$ocbS.8Uz4jp9sYuiJ7VR8uYFQGQY4tGuJtbzu120Xi7JfTBjv4N9u', '2022-12-18 17:14:39', 1),
(12, 'User', 'user@user.com', 'Démo', 'User', '$2y$10$Sl2RmIjJhps8ajZr0OE0J.ZUUzysV62XwDdYhCNYONAgDfhqkusju', '2022-12-18 17:15:16', 0),
(13, 'F.Dumont', 'fabrice@user.com', 'Fabrice', 'Dumont', '$2y$10$F7Cq6wCM/PpKrk8e9meiGONCKnpRiutX5INP/rjzASd1xUIz4up/6', '2022-12-19 10:35:29', 0),
(14, 'A.Guibord', 'alice@user.com', 'Alice', 'Guibord', '$2y$10$cRL9Lu98rrfBfYZYtgqqOeLKvA2qpOxsk9b3oGJDk/i2b72WwR3za', '2022-12-19 10:37:55', 0),
(15, 'D.Petrie', 'david@user.com', 'David', 'Petrie', '$2y$10$G4F7pjK7.WCsOvpQUVp.GeGgBNDX5A4Yhe1w1wbL3OVaC5dxqaUvO', '2022-12-19 10:39:48', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `fk_comment_post_idx` (`post_id`) USING BTREE,
  ADD KEY `fk_comment_user_idx` (`user_id`) USING BTREE;

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `fk_post_user_idx` (`user_id`) USING BTREE;

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_comment_post1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comment_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_post_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
