<?php
session_start();


if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$env = parse_ini_file(__DIR__ . '/../.env');

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$dsn = "{$env['DB_TYPE']}:host={$env['DB_SERVER']};dbname={$env['DB_NAME']};port={$env['DB_PORT']};charset={$env['DB_CHARSET']}";

try {
    $pdo = new PDO($dsn, $env['ADMIN_DB_USERNAME'], $env['ADMIN_DB_PASSWORD'], $options);
} catch (PDOException $e) {
    die("Database connection failed.");
}

$languages  = $pdo->query("SELECT language_id, language_name FROM language ORDER BY language_name")->fetchAll();
$functions  = $pdo->query("SELECT function_id, function_name FROM function_table ORDER BY function_name")->fetchAll();
$operators  = $pdo->query("SELECT operator_id, operator_name FROM operator ORDER BY operator_name")->fetchAll();
$structures = $pdo->query("SELECT structure_id, structure_name FROM data_structure ORDER BY structure_name")->fetchAll();

$success = '';
$error   = '';
$active  = $_POST['active_tab'] ?? $_GET['tab'] ?? 'language';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $active = $_POST['active_tab'] ?? 'language';
    try {
        switch ($active) {
            case 'language':
                $name    = trim($_POST['language_name'] ?? '');
                $version = trim($_POST['version'] ?? '') ?: null;
                if (!$name) throw new Exception("Language name is required.");
                $stmt = $pdo->prepare("INSERT INTO language (language_name, version) VALUES (:name, :version)");
                $stmt->execute([':name' => $name, ':version' => $version]);
                $success = "Language '{$name}' added successfully.";
                $languages = $pdo->query("SELECT language_id, language_name FROM language ORDER BY language_name")->fetchAll();
                break;

            case 'function':
                $fname = trim($_POST['function_name'] ?? '');
                $fcat  = trim($_POST['function_category'] ?? '') ?: null;
                $fdesc = trim($_POST['function_description'] ?? '') ?: null;
                if (!$fname) throw new Exception("Function name is required.");
                $stmt = $pdo->prepare("INSERT INTO function_table (function_name, category, description) VALUES (:name, :cat, :desc)");
                $stmt->execute([':name' => $fname, ':cat' => $fcat, ':desc' => $fdesc]);
                $success = "Function '{$fname}' added successfully.";
                $functions = $pdo->query("SELECT function_id, function_name FROM function_table ORDER BY function_name")->fetchAll();
                break;

            case 'operator':
                $oname   = trim($_POST['operator_name'] ?? '');
                $osymbol = trim($_POST['operator_symbol'] ?? '') ?: null;
                $ocat    = trim($_POST['operator_category'] ?? '') ?: null;
                $odesc   = trim($_POST['operator_description'] ?? '') ?: null;
                if (!$oname) throw new Exception("Operator name is required.");
                $stmt = $pdo->prepare("INSERT INTO operator (operator_name, symbol, category, description) VALUES (:name, :symbol, :cat, :desc)");
                $stmt->execute([':name' => $oname, ':symbol' => $osymbol, ':cat' => $ocat, ':desc' => $odesc]);
                $success = "Operator '{$oname}' added successfully.";
                $operators = $pdo->query("SELECT operator_id, operator_name FROM operator ORDER BY operator_name")->fetchAll();
                break;

            case 'structure':
                $sname = trim($_POST['structure_name'] ?? '');
                $scat  = trim($_POST['structure_category'] ?? '') ?: null;
                $sdesc = trim($_POST['structure_description'] ?? '') ?: null;
                if (!$sname) throw new Exception("Structure name is required.");
                $stmt = $pdo->prepare("INSERT INTO data_structure (structure_name, category, description) VALUES (:name, :cat, :desc)");
                $stmt->execute([':name' => $sname, ':cat' => $scat, ':desc' => $sdesc]);
                $success = "Data structure '{$sname}' added successfully.";
                $structures = $pdo->query("SELECT structure_id, structure_name FROM data_structure ORDER BY structure_name")->fetchAll();
                break;

            case 'implementation':
                $lang_id   = (int)($_POST['impl_language_id'] ?? 0);
                $syntax    = trim($_POST['impl_syntax']   ?? '') ?: null;
                $example   = trim($_POST['impl_example']  ?? '') ?: null;
                $result    = trim($_POST['impl_result']   ?? '') ?: null;
                $notes     = trim($_POST['impl_notes']    ?? '') ?: null;
                $link_type = $_POST['impl_link_type'] ?? '';
                $link_id   = (int)($_POST['impl_link_id'] ?? 0);
                if (!$lang_id)  throw new Exception("Language is required.");
                if (!$link_type || !$link_id) throw new Exception("You must link this implementation to a function, operator, or data structure.");
                $stmt = $pdo->prepare("INSERT INTO implementation (language_id, syntax, example, result, notes, date_added) VALUES (:lang, :syntax, :example, :result, :notes, CURDATE())");
                $stmt->execute([':lang' => $lang_id, ':syntax' => $syntax, ':example' => $example, ':result' => $result, ':notes' => $notes]);
                $impl_id = (int)$pdo->lastInsertId();
                if ($link_type === 'function') {
                    $pdo->prepare("INSERT INTO function_implementation (implementation_id, function_id) VALUES (:iid, :fid)")->execute([':iid' => $impl_id, ':fid' => $link_id]);
                } elseif ($link_type === 'operator') {
                    $pdo->prepare("INSERT INTO operator_implementation (implementation_id, operator_id) VALUES (:iid, :oid)")->execute([':iid' => $impl_id, ':oid' => $link_id]);
                } elseif ($link_type === 'structure') {
                    $pdo->prepare("INSERT INTO structure_implementation (implementation_id, structure_id) VALUES (:iid, :sid)")->execute([':iid' => $impl_id, ':sid' => $link_id]);
                }
                $success = "Implementation added and linked successfully.";
                break;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin // Programming Language Lookup</title>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:#000;--surface:#080808;--border:#1a1a1a;--border-lit:#252525;
            --green:#00ff88;--green-dim:#00cc6a;--green-mute:#003d20;
            --amber:#ffb800;--cyan:#00e5ff;--red:#ff4444;--text:#c8c8c8;--muted:#3a3a3a;
        }
        *{box-sizing:border-box;margin:0;padding:0;}
        html,body{min-height:100%;background:var(--bg);color:var(--text);font-family:'JetBrains Mono',monospace;font-size:13px;line-height:1.6;}
        header{background:var(--surface);border-bottom:1px solid var(--border-lit);padding:16px 32px;display:flex;align-items:center;justify-content:space-between;}
        .brand-name{color:var(--amber);font-size:1.1rem;font-weight:700;letter-spacing:3px;}
        .brand-sub{color:var(--muted);font-size:.75rem;letter-spacing:1px;margin-top:2px;}
        .brand-sub::before{content:'// ';color:var(--green-mute);}
        .header-right{display:flex;align-items:center;gap:20px;font-size:.72rem;color:var(--muted);}
        .user{color:var(--amber);}
        .logout-btn{color:var(--red);text-decoration:none;font-size:.72rem;letter-spacing:1px;border:1px solid #3a0000;padding:4px 10px;}
        .logout-btn:hover{background:#1a0000;}
        .tabs{display:flex;border-bottom:1px solid var(--border-lit);background:var(--surface);padding:0 32px;overflow-x:auto;}
        .tab{padding:10px 18px;font-family:'JetBrains Mono',monospace;font-size:.7rem;letter-spacing:2px;text-transform:uppercase;cursor:pointer;color:var(--muted);border-bottom:2px solid transparent;background:none;border-top:none;border-left:none;border-right:none;white-space:nowrap;}
        .tab:hover{color:var(--text);}
        .tab.active{color:var(--amber);border-bottom-color:var(--amber);}
        .shell{max-width:800px;margin:0 auto;padding:28px 32px 48px;}
        .prompt-line{color:var(--muted);font-size:.75rem;margin-bottom:22px;}
        .p-user{color:var(--amber);}.p-host{color:var(--cyan);}
        .cursor{display:inline-block;width:8px;height:13px;background:var(--amber);vertical-align:middle;margin-left:4px;animation:blink 1.1s step-end infinite;}
        @keyframes blink{50%{opacity:0;}}
        .panel{background:var(--surface);border:1px solid var(--border-lit);}
        .panel-title{padding:8px 16px;background:#050505;border-bottom:1px solid var(--border-lit);color:var(--amber);font-size:.68rem;letter-spacing:3px;text-transform:uppercase;}
        .panel-title::before{content:'> ';color:#3a2000;}
        .panel-body{padding:22px 20px;}
        .field{margin-bottom:18px;}
        .field-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px;}
        label{display:block;color:var(--muted);font-size:.67rem;letter-spacing:2px;text-transform:uppercase;margin-bottom:7px;}
        label::before{content:'// ';color:var(--green-mute);}
        .required{color:var(--amber);margin-left:4px;}
        input[type="text"],textarea,select{width:100%;background:#030303;color:var(--green);border:1px solid var(--border-lit);padding:9px 12px;font-family:'JetBrains Mono',monospace;font-size:.82rem;}
        textarea{resize:vertical;min-height:70px;line-height:1.5;}
        select{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath fill='%2300ff88' d='M5 6L0 0h10z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center;padding-right:28px;}
        input:focus,textarea:focus,select:focus{outline:none;border-color:var(--amber);box-shadow:0 0 0 1px #3a2000;}
        select option{background:#000;color:var(--green);}
        .link-group{background:#050505;border:1px solid var(--border);padding:14px 16px;margin-bottom:18px;}
        .link-group-title{color:var(--cyan);font-size:.67rem;letter-spacing:2px;text-transform:uppercase;margin-bottom:12px;}
        .link-group-title::before{content:'>> ';color:#003a3a;}
        .radio-row{display:flex;gap:20px;margin-bottom:12px;}
        .radio-label{display:flex;align-items:center;gap:8px;color:var(--text);font-size:.78rem;cursor:pointer;}
        .radio-label::before{content:none;}
        input[type="radio"]{accent-color:var(--amber);}
        .btn{padding:10px 24px;background:var(--green-mute);color:var(--green);border:1px solid var(--green-dim);font-family:'JetBrains Mono',monospace;font-size:.78rem;letter-spacing:2px;text-transform:uppercase;cursor:pointer;}
        .btn:hover{background:#005a30;}
        .alert{padding:10px 14px;font-size:.8rem;margin-bottom:20px;border:1px solid;}
        .alert-success{background:#001a0d;border-color:var(--green-dim);color:var(--green);}
        .alert-success::before{content:'OK: ';color:var(--green-dim);}
        .alert-error{background:#1a0000;border-color:var(--red);color:var(--red);}
        .alert-error::before{content:'ERR: ';}
        .tab-section{display:none;}
        .tab-section.active{display:block;}
        .divider{border:none;border-top:1px solid var(--border);margin:20px 0;}
        .hint{color:var(--muted);font-size:.68rem;margin-top:5px;}
        footer{border-top:1px solid var(--border);padding:14px 32px;color:#222;font-size:.68rem;letter-spacing:1px;display:flex;justify-content:space-between;}
        footer .val{color:#333;}
    </style>
</head>
<body>
<header>
    <div>
        <div class="brand-name">Programming Language Lookup // ADMIN</div>
        <div class="brand-sub">database management</div>
    </div>
    <div class="header-right">
        <span>user: <span class="user"><?= htmlspecialchars($_SESSION['admin_user'], ENT_QUOTES, 'UTF-8') ?></span></span>
        <a class="logout-btn" href="logout.php">// logout</a>
    </div>
</header>

<div class="tabs">
    <button class="tab <?= $active==='language'?'active':'' ?>" onclick="switchTab('language')">// language</button>
    <button class="tab <?= $active==='function'?'active':'' ?>" onclick="switchTab('function')">// function</button>
    <button class="tab <?= $active==='operator'?'active':'' ?>" onclick="switchTab('operator')">// operator</button>
    <button class="tab <?= $active==='structure'?'active':'' ?>" onclick="switchTab('structure')">// data structure</button>
    <button class="tab <?= $active==='implementation'?'active':'' ?>" onclick="switchTab('implementation')">// implementation</button>
</div>

<div class="shell">
    <div class="prompt-line">
        <span class="p-user">admin</span>@<span class="p-host">Programming Language Lookup</span>:~$ INSERT INTO database --interactive
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <!-- LANGUAGE -->
    <div id="section-language" class="tab-section <?= $active==='language'?'active':'' ?>">
        <div class="panel"><div class="panel-title">add language</div><div class="panel-body">
        <form method="POST" action="index.php">
            <input type="hidden" name="active_tab" value="language">
            <div class="field-row">
                <div class="field"><label>language name <span class="required">*</span></label>
                    <input type="text" name="language_name" maxlength="100" placeholder="e.g. Python" required></div>
                <div class="field"><label>version</label>
                    <input type="text" name="version" maxlength="50" placeholder="e.g. 3.x"></div>
            </div>
            <button type="submit" class="btn">// insert language</button>
        </form>
        </div></div>
    </div>

    <!-- FUNCTION -->
    <div id="section-function" class="tab-section <?= $active==='function'?'active':'' ?>">
        <div class="panel"><div class="panel-title">add function</div><div class="panel-body">
        <form method="POST" action="index.php">
            <input type="hidden" name="active_tab" value="function">
            <div class="field-row">
                <div class="field"><label>function name <span class="required">*</span></label>
                    <input type="text" name="function_name" maxlength="100" placeholder="e.g. print" required></div>
                <div class="field"><label>category</label>
                    <input type="text" name="function_category" maxlength="100" placeholder="e.g. I/O"></div>
            </div>
            <div class="field"><label>description</label>
                <textarea name="function_description" placeholder="e.g. Outputs text to standard output"></textarea></div>
            <button type="submit" class="btn">// insert function</button>
        </form>
        </div></div>
    </div>

    <!-- OPERATOR -->
    <div id="section-operator" class="tab-section <?= $active==='operator'?'active':'' ?>">
        <div class="panel"><div class="panel-title">add operator</div><div class="panel-body">
        <form method="POST" action="index.php">
            <input type="hidden" name="active_tab" value="operator">
            <div class="field-row">
                <div class="field"><label>operator name <span class="required">*</span></label>
                    <input type="text" name="operator_name" maxlength="100" placeholder="e.g. Addition" required></div>
                <div class="field"><label>symbol</label>
                    <input type="text" name="operator_symbol" maxlength="20" placeholder="e.g. +"></div>
            </div>
            <div class="field-row">
                <div class="field"><label>category</label>
                    <input type="text" name="operator_category" maxlength="100" placeholder="e.g. Arithmetic"></div>
                <div></div>
            </div>
            <div class="field"><label>description</label>
                <textarea name="operator_description" placeholder="e.g. Adds two operands"></textarea></div>
            <button type="submit" class="btn">// insert operator</button>
        </form>
        </div></div>
    </div>

    <!-- STRUCTURE -->
    <div id="section-structure" class="tab-section <?= $active==='structure'?'active':'' ?>">
        <div class="panel"><div class="panel-title">add data structure</div><div class="panel-body">
        <form method="POST" action="index.php">
            <input type="hidden" name="active_tab" value="structure">
            <div class="field-row">
                <div class="field"><label>structure name <span class="required">*</span></label>
                    <input type="text" name="structure_name" maxlength="100" placeholder="e.g. Hash Table" required></div>
                <div class="field"><label>category</label>
                    <input type="text" name="structure_category" maxlength="100" placeholder="e.g. Associative"></div>
            </div>
            <div class="field"><label>description</label>
                <textarea name="structure_description" placeholder="e.g. Hash function indexed structure"></textarea></div>
            <button type="submit" class="btn">// insert structure</button>
        </form>
        </div></div>
    </div>

    <!-- IMPLEMENTATION -->
    <div id="section-implementation" class="tab-section <?= $active==='implementation'?'active':'' ?>">
        <div class="panel"><div class="panel-title">add implementation</div><div class="panel-body">
        <form method="POST" action="index.php">
            <input type="hidden" name="active_tab" value="implementation">
            <div class="field"><label>language <span class="required">*</span></label>
                <select name="impl_language_id" required>
                    <option value="">select language...</option>
                    <?php foreach ($languages as $l): ?>
                        <option value="<?= (int)$l['language_id'] ?>"><?= htmlspecialchars($l['language_name'], ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field-row">
                <div class="field"><label>syntax</label>
                    <input type="text" name="impl_syntax" maxlength="500" placeholder="e.g. print(value)"></div>
                <div class="field"><label>result / output</label>
                    <input type="text" name="impl_result" maxlength="500" placeholder="e.g. Hello World"></div>
            </div>
            <div class="field"><label>example</label>
                <textarea name="impl_example" placeholder="e.g. print('Hello', 42)"></textarea></div>
            <div class="field"><label>notes</label>
                <textarea name="impl_notes" placeholder="e.g. Supports multiple arguments"></textarea></div>
            <hr class="divider">
            <div class="link-group">
                <div class="link-group-title">link to existing item <span style="color:var(--amber)">*</span></div>
                <div class="radio-row">
                    <label class="radio-label"><input type="radio" name="impl_link_type" value="function" onchange="updateLinkDropdown()" checked> function</label>
                    <label class="radio-label"><input type="radio" name="impl_link_type" value="operator" onchange="updateLinkDropdown()"> operator</label>
                    <label class="radio-label"><input type="radio" name="impl_link_type" value="structure" onchange="updateLinkDropdown()"> data structure</label>
                </div>
                <select name="impl_link_id" id="link-dropdown" required>
                    <option value="">select item...</option>
                </select>
                <div class="hint">links this implementation to a function/operator/structure via the junction table</div>
            </div>
            <button type="submit" class="btn">// insert implementation</button>
        </form>
        </div></div>
    </div>
</div>

<footer>
    <div>LANGREF // <span class="val">Admin Dashboard</span></div>
    <div>access: <span class="val">SELECT + INSERT</span></div>
</footer>

<script>
const FUNCTIONS  = <?= json_encode(array_map(fn($f) => ['id' => $f['function_id'],  'name' => $f['function_name']],  $functions))  ?>;
const OPERATORS  = <?= json_encode(array_map(fn($o) => ['id' => $o['operator_id'],  'name' => $o['operator_name']],  $operators))  ?>;
const STRUCTURES = <?= json_encode(array_map(fn($s) => ['id' => $s['structure_id'], 'name' => $s['structure_name']], $structures)) ?>;

function switchTab(tab) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-section').forEach(s => s.classList.remove('active'));
    document.querySelector(`[onclick="switchTab('${tab}')"]`).classList.add('active');
    document.getElementById('section-' + tab).classList.add('active');
}

function updateLinkDropdown() {
    const type = document.querySelector('input[name="impl_link_type"]:checked').value;
    const dd   = document.getElementById('link-dropdown');
    const data = type === 'function' ? FUNCTIONS : type === 'operator' ? OPERATORS : STRUCTURES;
    dd.innerHTML = '<option value="">select item...</option>'
        + data.map(item => '<option value="' + item.id + '">' + item.name + '</option>').join('');
}

updateLinkDropdown();
</script>
</body>
</html>
