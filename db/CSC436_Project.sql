-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 22, 2026 at 04:49 PM
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
-- Database: `mbarbrac_CSC436_Project`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_structure`
--

CREATE TABLE `data_structure` (
  `structure_id` int(11) NOT NULL,
  `structure_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `data_structure`
--

INSERT INTO `data_structure` (`structure_id`, `structure_name`, `category`, `description`) VALUES
(1, 'Array', 'Linear', 'Fixed-size sequential collection'),
(2, 'List', 'Linear', 'Dynamic sequential collection'),
(3, 'Dictionary', 'Associative', 'Key-value pair collection'),
(4, 'Set', 'Collection', 'Unordered unique elements'),
(5, 'Stack', 'Linear', 'LIFO data structure'),
(6, 'Queue', 'Linear', 'FIFO data structure'),
(7, 'Linked List', 'Linear', 'Pointer-based sequential collection'),
(8, 'Tree', 'Hierarchical', 'Hierarchical node structure'),
(9, 'Graph', 'Non-Linear', 'Nodes connected by edges'),
(10, 'Hash Table', 'Associative', 'Hash function indexed structure');

-- --------------------------------------------------------

--
-- Table structure for table `function_implementation`
--

CREATE TABLE `function_implementation` (
  `implementation_id` int(11) NOT NULL,
  `function_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `function_implementation`
--

INSERT INTO `function_implementation` (`implementation_id`, `function_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 2),
(6, 2),
(7, 2),
(8, 3),
(9, 3),
(10, 3),
(11, 4),
(12, 4),
(13, 4),
(14, 5),
(15, 5),
(16, 6),
(17, 6),
(18, 9),
(19, 9),
(20, 10),
(21, 10);

-- --------------------------------------------------------

--
-- Table structure for table `function_table`
--

CREATE TABLE `function_table` (
  `function_id` int(11) NOT NULL,
  `function_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `function_table`
--

INSERT INTO `function_table` (`function_id`, `function_name`, `category`, `description`) VALUES
(1, 'print', 'I/O', 'Outputs text or values to standard output'),
(2, 'input', 'I/O', 'Reads input from standard input'),
(3, 'len', 'Utility', 'Returns the length of a collection or string'),
(4, 'sort', 'Sorting', 'Sorts elements in a collection'),
(5, 'map', 'Functional', 'Applies a function to each element'),
(6, 'filter', 'Functional', 'Filters elements based on a condition'),
(7, 'reduce', 'Functional', 'Reduces a collection to a single value'),
(8, 'open', 'File I/O', 'Opens a file for reading or writing'),
(9, 'split', 'String', 'Splits a string into an array/list'),
(10, 'join', 'String', 'Joins elements into a string');

-- --------------------------------------------------------

--
-- Table structure for table `implementation`
--

CREATE TABLE `implementation` (
  `implementation_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `version` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_added` date DEFAULT NULL,
  `example` text COLLATE utf8_unicode_ci,
  `result` text COLLATE utf8_unicode_ci,
  `notes` text COLLATE utf8_unicode_ci,
  `syntax` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `implementation`
--

INSERT INTO `implementation` (`implementation_id`, `language_id`, `version`, `date_added`, `example`, `result`, `notes`, `syntax`) VALUES
(1, 1, '3.x', '2026-02-22', 'print(\"Score:\", 95, sep=\" \")', 'Score: 95', 'Supports multiple arguments with customizable separator', 'print(value, sep=\" \", end=\"\\n\")'),
(2, 2, 'C++11', '2026-02-22', 'std::cout << \"Temperature: \" << 72 << \"F\" << std::endl;', 'Temperature: 72F', 'Chain multiple values with << operator', 'std::cout << value << std::endl;'),
(3, 3, 'Java 8+', '2026-02-22', 'System.out.println(\"Items in cart: \" + 5);', 'Items in cart: 5', 'Use + for string concatenation', 'System.out.println(value);'),
(4, 4, 'ES6+', '2026-02-22', 'console.log(`User ${name} logged in`);', 'User John logged in', 'Template literals use backticks and ${} for interpolation', 'console.log(value);'),
(5, 1, '3.x', '2026-02-22', 'age = int(input(\"Enter your age: \"))', 'User enters: 25 -> age = 25', 'Always returns string; cast to int/float as needed', 'variable = input(prompt)'),
(6, 2, 'C++11', '2026-02-22', 'int age;\nstd::cout << \"Enter age: \";\nstd::cin >> age;', 'User enters: 25 -> age = 25', 'Use getline(cin, str) for strings with spaces', 'std::cin >> variable;'),
(7, 3, 'Java 8+', '2026-02-22', 'Scanner sc = new Scanner(System.in);\nString name = sc.nextLine();', 'User enters: Alice -> name = \"Alice\"', 'Use nextInt(), nextDouble() for other types', 'Scanner sc = new Scanner(System.in); sc.nextLine();'),
(8, 1, '3.x', '2026-02-22', 'words = [\"apple\", \"banana\", \"cherry\"]\nlen(words)', '3', 'Works on strings, lists, tuples, dicts, sets', 'len(sequence)'),
(9, 2, 'C++11', '2026-02-22', 'std::string name = \"Alice\";\nname.size();', '5', 'Returns size_t type; use .length() for strings too', 'container.size()'),
(10, 3, 'Java 8+', '2026-02-22', 'int[] nums = {10, 20, 30, 40};\nnums.length;', '4', 'Arrays use .length (no parens), Strings use .length(), Collections use .size()', 'array.length or string.length() or list.size()'),
(11, 1, '3.x', '2026-02-22', 'prices = [29.99, 9.99, 49.99, 19.99]\nprices.sort()', '[9.99, 19.99, 29.99, 49.99]', '.sort() modifies in place; sorted() returns new list', 'list.sort() or sorted(iterable)'),
(12, 2, 'C++11', '2026-02-22', 'std::vector<int> v = {5, 2, 8, 1};\nstd::sort(v.begin(), v.end());', '{1, 2, 5, 8}', 'Requires #include <algorithm>; average O(n log n)', 'std::sort(begin, end)'),
(13, 3, 'Java 8+', '2026-02-22', 'List<String> names = Arrays.asList(\"Zoe\", \"Amy\", \"Max\");\nCollections.sort(names);', '[Amy, Max, Zoe]', 'Use Comparator for custom sorting', 'Arrays.sort(array) or Collections.sort(list)'),
(14, 1, '3.x', '2026-02-22', 'nums = [1, 2, 3, 4]\nsquared = list(map(lambda x: x**2, nums))', '[1, 4, 9, 16]', 'Returns iterator; wrap in list() to see results', 'map(function, iterable)'),
(15, 4, 'ES6+', '2026-02-22', 'const prices = [10, 20, 30];\nconst withTax = prices.map(p => p * 1.08);', '[10.8, 21.6, 32.4]', 'Arrow functions make this concise', 'array.map(callback)'),
(16, 1, '3.x', '2026-02-22', 'ages = [12, 18, 25, 8, 30]\nadults = list(filter(lambda x: x >= 18, ages))', '[18, 25, 30]', 'Returns elements where function returns True', 'filter(function, iterable)'),
(17, 4, 'ES6+', '2026-02-22', 'const scores = [85, 42, 93, 67, 55];\nconst passing = scores.filter(s => s >= 60);', '[85, 93, 67]', 'Returns new array with elements that pass the test', 'array.filter(callback)'),
(18, 1, '3.x', '2026-02-22', 'csv_line = \"John,25,Engineer\"\nfields = csv_line.split(\",\")', '[\"John\", \"25\", \"Engineer\"]', 'Default separator is whitespace', 'string.split(separator)'),
(19, 4, 'ES6+', '2026-02-22', 'const path = \"home/user/documents\";\nconst parts = path.split(\"/\");', '[\"home\", \"user\", \"documents\"]', 'Can use regex as separator', 'string.split(separator)'),
(20, 1, '3.x', '2026-02-22', 'words = [\"Hello\", \"World\", \"2024\"]\n\" \".join(words)', 'Hello World 2024', 'Separator is the string you call join on', 'separator.join(iterable)'),
(21, 4, 'ES6+', '2026-02-22', 'const tags = [\"python\", \"javascript\", \"sql\"];\ntags.join(\", \");', 'python, javascript, sql', 'Default separator is comma', 'array.join(separator)'),
(22, 1, '3.x', '2026-02-22', 'shopping = [\"milk\", \"eggs\", \"bread\"]\nshopping.append(\"butter\")\nshopping[0] = \"almond milk\"', '[\"almond milk\", \"eggs\", \"bread\", \"butter\"]', 'Mutable, ordered, allows duplicates, mixed types OK', 'my_list = [item1, item2, ...]'),
(23, 2, 'C++11', '2026-02-22', 'std::vector<double> temps = {72.5, 68.0, 75.3};\ntemps.push_back(71.2);', '{72.5, 68.0, 75.3, 71.2}', 'Dynamic size, contiguous memory, #include <vector>', 'std::vector<type> name = {values};'),
(24, 3, 'Java 8+', '2026-02-22', 'ArrayList<String> colors = new ArrayList<>();\ncolors.add(\"red\");\ncolors.add(\"blue\");', '[red, blue]', 'Resizable array; use LinkedList for frequent insertions', 'ArrayList<Type> list = new ArrayList<>();'),
(25, 4, 'ES6+', '2026-02-22', 'const fruits = [\"apple\", \"mango\"];\nfruits.push(\"kiwi\");\nfruits.unshift(\"grape\");', '[\"grape\", \"apple\", \"mango\", \"kiwi\"]', 'push=end, unshift=start, pop=remove end, shift=remove start', 'const arr = [item1, item2];'),
(26, 1, '3.x', '2026-02-22', 'student = {\"name\": \"Emma\", \"gpa\": 3.8, \"year\": 2}\nstudent[\"major\"] = \"CS\"', '{\"name\": \"Emma\", \"gpa\": 3.8, \"year\": 2, \"major\": \"CS\"}', 'Keys must be immutable (str, int, tuple)', 'my_dict = {key: value, ...}'),
(27, 3, 'Java 8+', '2026-02-22', 'HashMap<String, Double> prices = new HashMap<>();\nprices.put(\"apple\", 1.50);\nprices.put(\"banana\", 0.75);', '{apple=1.5, banana=0.75}', 'O(1) average lookup; use TreeMap for sorted keys', 'HashMap<K, V> map = new HashMap<>();'),
(28, 4, 'ES6+', '2026-02-22', 'const config = {theme: \"dark\", fontSize: 14};\nconfig.language = \"en\";', '{theme: \"dark\", fontSize: 14, language: \"en\"}', 'Use Map for non-string keys or frequent add/delete', 'const obj = {key: value}; or new Map()'),
(29, 1, '3.x', '2026-02-22', 'tags = {\"python\", \"coding\", \"python\", \"tutorial\"}\ntags.add(\"beginner\")', '{\"python\", \"coding\", \"tutorial\", \"beginner\"}', 'Duplicates automatically removed; unordered', 'my_set = {item1, item2, ...}'),
(30, 3, 'Java 8+', '2026-02-22', 'HashSet<Integer> lottery = new HashSet<>();\nlottery.add(7);\nlottery.add(14);\nlottery.add(7);', '[7, 14]', 'No duplicates, no guaranteed order; use TreeSet for sorted', 'HashSet<Type> set = new HashSet<>();'),
(31, 1, '3.x', '2026-02-22', 'undo_stack = []\nundo_stack.append(\"typed A\")\nundo_stack.append(\"typed B\")\nlast = undo_stack.pop()', 'last = \"typed B\", stack = [\"typed A\"]', 'Lists work as stacks; collections.deque is faster', 'stack = [] # use append() and pop()'),
(32, 3, 'Java 8+', '2026-02-22', 'Stack<String> history = new Stack<>();\nhistory.push(\"page1\");\nhistory.push(\"page2\");\nString back = history.pop();', 'back = \"page2\", stack = [page1]', 'Prefer Deque interface: ArrayDeque for better performance', 'Stack<Type> stack = new Stack<>();'),
(33, 1, '3.x', '2026-02-22', 'from collections import deque\nprinter_queue = deque([\"doc1\", \"doc2\"])\nprinter_queue.append(\"doc3\")\nnext_job = printer_queue.popleft()', 'next_job = \"doc1\", queue = [\"doc2\", \"doc3\"]', 'Use deque for O(1) operations on both ends', 'from collections import deque; q = deque()'),
(34, 3, 'Java 8+', '2026-02-22', 'Queue<String> tickets = new LinkedList<>();\ntickets.offer(\"customer1\");\ntickets.offer(\"customer2\");\nString next = tickets.poll();', 'next = \"customer1\", queue = [customer2]', 'offer/poll preferred over add/remove (no exceptions)', 'Queue<Type> q = new LinkedList<>();'),
(35, 1, '3.x', '2026-02-22', 'total = 15.99 + 4.50 + 2.00', '22.49', 'Also concatenates strings and lists', 'a + b'),
(36, 3, 'Java 8+', '2026-02-22', 'int hoursWorked = 8 + 6 + 9;', '23', 'String + int auto-converts int to String', 'a + b'),
(37, 1, '3.x', '2026-02-22', 'balance = 1000.00\nbalance = balance - 250.75', '749.25', 'Also used for set difference: set1 - set2', 'a - b'),
(38, 1, '3.x', '2026-02-22', 'weekly_pay = 25.50 * 40', '1020.0', 'Also repeats strings: \"ha\" * 3 = \"hahaha\"', 'a * b'),
(39, 2, 'C++11', '2026-02-22', 'double area = 3.14159 * radius * radius;', 'area of circle calculation', 'Watch for integer overflow with large numbers', 'a * b'),
(40, 1, '3.x', '2026-02-22', 'avg = 285 / 3\nwhole_avg = 285 // 3', 'avg = 95.0, whole_avg = 95', '/ always returns float; // truncates toward negative infinity', 'a / b (float) or a // b (integer)'),
(41, 3, 'Java 8+', '2026-02-22', 'int pages = 100 / 3;\ndouble exactPages = 100.0 / 3;', 'pages = 33, exactPages = 33.333...', 'int/int = int (truncated); use doubles for decimals', 'a / b'),
(42, 1, '3.x', '2026-02-22', 'is_even = 42 % 2\nremainder = 17 % 5', 'is_even = 0, remainder = 2', 'Common for checking even/odd or cycling through indices', 'a % b'),
(43, 4, 'ES6+', '2026-02-22', 'const hour24 = 27 % 24;', '3', 'Useful for wrapping values (clock, array index cycling)', 'a % b'),
(44, 1, '3.x', '2026-02-22', 'password = \"secret123\"\nis_correct = password == \"secret123\"', 'True', 'Compares value; use \"is\" to compare identity (same object)', 'a == b'),
(45, 4, 'ES6+', '2026-02-22', '\"5\" === 5\n\"5\" == 5', 'false, true', 'Always prefer === to avoid type coercion bugs', 'a === b (strict) or a == b (loose)'),
(46, 1, '3.x', '2026-02-22', 'status = \"active\"\nneeds_attention = status != \"active\"', 'False', 'Returns True if values are different', 'a != b'),
(47, 1, '3.x', '2026-02-22', 'age = 21\ncan_drink = age > 20', 'True', 'Works with numbers and strings (lexicographic)', 'a > b'),
(48, 3, 'Java 8+', '2026-02-22', 'int temperature = 32;\nboolean freezing = temperature < 33;', 'true', 'Returns boolean primitive', 'a < b'),
(49, 1, '3.x', '2026-02-22', 'age = 25\nhas_license = True\ncan_drive = age >= 16 and has_license', 'True', 'Short-circuits: if first is False, second not evaluated', 'a and b'),
(50, 2, 'C++11', '2026-02-22', 'bool inStock = true;\nbool hasCredit = true;\nbool canBuy = inStock && hasCredit;', 'true', 'Keyword \"and\" also works in C++', 'a && b'),
(51, 1, '3.x', '2026-02-22', 'is_weekend = day == \"Saturday\" or day == \"Sunday\"', 'True if day is Saturday', 'Short-circuits: if first is True, second not evaluated', 'a or b'),
(52, 4, 'ES6+', '2026-02-22', 'const username = inputName || \"Guest\";', '\"Guest\" if inputName is empty/null', 'Often used for default values; consider ?? for nullish only', 'a || b'),
(53, 1, '3.x', '2026-02-22', 'logged_in = False\nshow_login_button = not logged_in', 'True', 'Inverts boolean value', 'not a'),
(54, 3, 'Java 8+', '2026-02-22', 'boolean isEmpty = list.isEmpty();\nboolean hasItems = !isEmpty;', 'true if list has items', 'Prefix operator, highest precedence among logical ops', '!a'),
(55, 1, '3.x', '2026-02-22', 'name = \"Alice\"\ncount = 0\ncount += 1  # augmented assignment', 'name=\"Alice\", count=1', 'Also: +=, -=, *=, /=, //=, **=, %=', 'variable = value');

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `language_id` int(11) NOT NULL,
  `language_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`language_id`, `language_name`) VALUES
(5, 'C'),
(2, 'C++'),
(7, 'Go'),
(3, 'Java'),
(4, 'JavaScript'),
(1, 'Python'),
(6, 'Ruby'),
(8, 'Rust');

-- --------------------------------------------------------

--
-- Table structure for table `operator`
--

CREATE TABLE `operator` (
  `operator_id` int(11) NOT NULL,
  `operator_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `operator`
--

INSERT INTO `operator` (`operator_id`, `operator_name`, `category`, `description`) VALUES
(1, 'Addition', 'Arithmetic', 'Adds two operands'),
(2, 'Subtraction', 'Arithmetic', 'Subtracts operands'),
(3, 'Multiplication', 'Arithmetic', 'Multiplies operands'),
(4, 'Division', 'Arithmetic', 'Divides operands'),
(5, 'Modulus', 'Arithmetic', 'Returns remainder'),
(6, 'Equal', 'Comparison', 'Checks equality'),
(7, 'Not Equal', 'Comparison', 'Checks inequality'),
(8, 'Greater Than', 'Comparison', 'Checks if greater'),
(9, 'Less Than', 'Comparison', 'Checks if less'),
(10, 'Logical AND', 'Logical', 'Both must be true'),
(11, 'Logical OR', 'Logical', 'One must be true'),
(12, 'Logical NOT', 'Logical', 'Inverts boolean'),
(13, 'Assignment', 'Assignment', 'Assigns value'),
(14, 'Bitwise AND', 'Bitwise', 'Bitwise AND operation'),
(15, 'Bitwise OR', 'Bitwise', 'Bitwise OR operation');

-- --------------------------------------------------------

--
-- Table structure for table `operator_implementation`
--

CREATE TABLE `operator_implementation` (
  `implementation_id` int(11) NOT NULL,
  `operator_id` int(11) NOT NULL,
  `symbol` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `operator_implementation`
--

INSERT INTO `operator_implementation` (`implementation_id`, `operator_id`, `symbol`) VALUES
(35, 1, '+'),
(36, 1, '+'),
(37, 2, '-'),
(38, 3, '*'),
(39, 3, '*'),
(40, 4, '/ or //'),
(41, 4, '/'),
(42, 5, '%'),
(43, 5, '%'),
(44, 6, '=='),
(45, 6, '=== or =='),
(46, 7, '!='),
(47, 8, '>'),
(48, 9, '<'),
(49, 10, 'and'),
(50, 10, '&&'),
(51, 11, 'or'),
(52, 11, '||'),
(53, 12, 'not'),
(54, 12, '!'),
(55, 13, '=');

-- --------------------------------------------------------

--
-- Table structure for table `structure_implementation`
--

CREATE TABLE `structure_implementation` (
  `implementation_id` int(11) NOT NULL,
  `structure_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `structure_implementation`
--

INSERT INTO `structure_implementation` (`implementation_id`, `structure_id`) VALUES
(22, 2),
(23, 2),
(24, 2),
(25, 2),
(26, 3),
(27, 3),
(28, 3),
(29, 4),
(30, 4),
(31, 5),
(32, 5),
(33, 6),
(34, 6);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_function_lookup`
-- (See below for the actual view)
--
CREATE TABLE `vw_function_lookup` (
`language_name` varchar(100)
,`function_name` varchar(100)
,`category` varchar(100)
,`version` varchar(50)
,`syntax` text
,`example` text
,`result` text
,`notes` text
,`date_added` date
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_operator_lookup`
-- (See below for the actual view)
--
CREATE TABLE `vw_operator_lookup` (
`language_name` varchar(100)
,`operator_name` varchar(100)
,`category` varchar(100)
,`version` varchar(50)
,`symbol` varchar(20)
,`syntax` text
,`example` text
,`result` text
,`notes` text
,`date_added` date
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_structure_lookup`
-- (See below for the actual view)
--
CREATE TABLE `vw_structure_lookup` (
`language_name` varchar(100)
,`structure_name` varchar(100)
,`category` varchar(100)
,`version` varchar(50)
,`syntax` text
,`example` text
,`result` text
,`notes` text
,`date_added` date
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_structure`
--
ALTER TABLE `data_structure`
  ADD PRIMARY KEY (`structure_id`);

--
-- Indexes for table `function_implementation`
--
ALTER TABLE `function_implementation`
  ADD PRIMARY KEY (`implementation_id`),
  ADD KEY `idx_func_impl_function` (`function_id`);

--
-- Indexes for table `function_table`
--
ALTER TABLE `function_table`
  ADD PRIMARY KEY (`function_id`);

--
-- Indexes for table `implementation`
--
ALTER TABLE `implementation`
  ADD PRIMARY KEY (`implementation_id`),
  ADD KEY `idx_impl_language` (`language_id`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`language_id`),
  ADD UNIQUE KEY `language_name` (`language_name`);

--
-- Indexes for table `operator`
--
ALTER TABLE `operator`
  ADD PRIMARY KEY (`operator_id`);

--
-- Indexes for table `operator_implementation`
--
ALTER TABLE `operator_implementation`
  ADD PRIMARY KEY (`implementation_id`),
  ADD KEY `idx_op_impl_operator` (`operator_id`);

--
-- Indexes for table `structure_implementation`
--
ALTER TABLE `structure_implementation`
  ADD PRIMARY KEY (`implementation_id`),
  ADD KEY `idx_struct_impl_structure` (`structure_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_structure`
--
ALTER TABLE `data_structure`
  MODIFY `structure_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `function_table`
--
ALTER TABLE `function_table`
  MODIFY `function_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `implementation`
--
ALTER TABLE `implementation`
  MODIFY `implementation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `language_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `operator`
--
ALTER TABLE `operator`
  MODIFY `operator_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

-- --------------------------------------------------------

--
-- Structure for view `vw_function_lookup`
--
DROP TABLE IF EXISTS `vw_function_lookup`;

CREATE ALGORITHM=UNDEFINED DEFINER=`mbarbrack`@`localhost` SQL SECURITY DEFINER VIEW `vw_function_lookup`  AS SELECT `l`.`language_name` AS `language_name`, `f`.`function_name` AS `function_name`, `f`.`category` AS `category`, `i`.`version` AS `version`, `i`.`syntax` AS `syntax`, `i`.`example` AS `example`, `i`.`result` AS `result`, `i`.`notes` AS `notes`, `i`.`date_added` AS `date_added` FROM (((`implementation` `i` join `function_implementation` `fi` on((`i`.`implementation_id` = `fi`.`implementation_id`))) join `language` `l` on((`i`.`language_id` = `l`.`language_id`))) join `function_table` `f` on((`fi`.`function_id` = `f`.`function_id`))) ORDER BY `f`.`function_name` ASC, `l`.`language_name` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `vw_operator_lookup`
--
DROP TABLE IF EXISTS `vw_operator_lookup`;

CREATE ALGORITHM=UNDEFINED DEFINER=`mbarbrack`@`localhost` SQL SECURITY DEFINER VIEW `vw_operator_lookup`  AS SELECT `l`.`language_name` AS `language_name`, `o`.`operator_name` AS `operator_name`, `o`.`category` AS `category`, `i`.`version` AS `version`, `oi`.`symbol` AS `symbol`, `i`.`syntax` AS `syntax`, `i`.`example` AS `example`, `i`.`result` AS `result`, `i`.`notes` AS `notes`, `i`.`date_added` AS `date_added` FROM (((`implementation` `i` join `operator_implementation` `oi` on((`i`.`implementation_id` = `oi`.`implementation_id`))) join `language` `l` on((`i`.`language_id` = `l`.`language_id`))) join `operator` `o` on((`oi`.`operator_id` = `o`.`operator_id`))) ORDER BY `o`.`operator_name` ASC, `l`.`language_name` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `vw_structure_lookup`
--
DROP TABLE IF EXISTS `vw_structure_lookup`;

CREATE ALGORITHM=UNDEFINED DEFINER=`mbarbrack`@`localhost` SQL SECURITY DEFINER VIEW `vw_structure_lookup`  AS SELECT `l`.`language_name` AS `language_name`, `ds`.`structure_name` AS `structure_name`, `ds`.`category` AS `category`, `i`.`version` AS `version`, `i`.`syntax` AS `syntax`, `i`.`example` AS `example`, `i`.`result` AS `result`, `i`.`notes` AS `notes`, `i`.`date_added` AS `date_added` FROM (((`implementation` `i` join `structure_implementation` `si` on((`i`.`implementation_id` = `si`.`implementation_id`))) join `language` `l` on((`i`.`language_id` = `l`.`language_id`))) join `data_structure` `ds` on((`si`.`structure_id` = `ds`.`structure_id`))) ORDER BY `ds`.`structure_name` ASC, `l`.`language_name` ASC ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `function_implementation`
--
ALTER TABLE `function_implementation`
  ADD CONSTRAINT `fk_func_impl_function` FOREIGN KEY (`function_id`) REFERENCES `function_table` (`function_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_func_impl_parent` FOREIGN KEY (`implementation_id`) REFERENCES `implementation` (`implementation_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `implementation`
--
ALTER TABLE `implementation`
  ADD CONSTRAINT `fk_impl_language` FOREIGN KEY (`language_id`) REFERENCES `language` (`language_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `operator_implementation`
--
ALTER TABLE `operator_implementation`
  ADD CONSTRAINT `fk_op_impl_operator` FOREIGN KEY (`operator_id`) REFERENCES `operator` (`operator_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_op_impl_parent` FOREIGN KEY (`implementation_id`) REFERENCES `implementation` (`implementation_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `structure_implementation`
--
ALTER TABLE `structure_implementation`
  ADD CONSTRAINT `fk_struct_impl_parent` FOREIGN KEY (`implementation_id`) REFERENCES `implementation` (`implementation_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_struct_impl_structure` FOREIGN KEY (`structure_id`) REFERENCES `data_structure` (`structure_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
