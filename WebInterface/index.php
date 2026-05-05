<?php
require_once 'includes/database-connection.php';


// Languages
$languages = $pdo->query("
    SELECT language_id, language_name, version
    FROM language ORDER BY language_name ASC
")->fetchAll();

// Functions with full implementation data per language
$functions = $pdo->query("
    SELECT DISTINCT f.function_id, f.function_name, f.category, f.description,
           i.language_id, i.syntax, i.example, i.result, i.notes
    FROM function_table f
    JOIN function_implementation fi ON f.function_id = fi.function_id
    JOIN implementation i ON fi.implementation_id = i.implementation_id
    ORDER BY f.function_name ASC
")->fetchAll();

// Operators with full implementation data per language
$operators = $pdo->query("
    SELECT DISTINCT o.operator_id, o.operator_name, o.symbol, o.category, o.description,
           i.language_id, i.syntax, i.example, i.result, i.notes
    FROM operator o
    JOIN operator_implementation oi ON o.operator_id = oi.operator_id
    JOIN implementation i ON oi.implementation_id = i.implementation_id
    ORDER BY o.operator_name ASC
")->fetchAll();

// Data structures
$structures = $pdo->query("
    SELECT DISTINCT ds.structure_id, ds.structure_name, ds.category, ds.description,
           i.language_id, i.syntax, i.example, i.result, i.notes
    FROM data_structure ds
    JOIN structure_implementation si ON ds.structure_id = si.structure_id
    JOIN implementation i ON si.implementation_id = i.implementation_id
    ORDER BY ds.structure_name ASC
")->fetchAll();

// Categories
$categories = $pdo->query("
    SELECT DISTINCT category FROM function_table  WHERE category IS NOT NULL
    UNION
    SELECT DISTINCT category FROM operator        WHERE category IS NOT NULL
    UNION
    SELECT DISTINCT category FROM data_structure  WHERE category IS NOT NULL
    ORDER BY category ASC
")->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programming Language Lookup // Terminal</title>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:         #0d1117;
            --surface:    #161b22;
            --border:     #2a3038;
            --border-lit: #363d47;
            --green:      #3fb950;
            --green-dim:  #2ea043;
            --green-mute: #1a4a25;
            --amber:      #e3b341;
            --cyan:       #39c5cf;
            --red:        #f85149;
            --purple:     #d2a8ff;
            --text:       #e6edf3;
            --muted:      #8b949e;
            --dim:        #6e7681;
        }

        body.light {
            --bg:         #ffffff;
            --surface:    #f6f8fa;
            --border:     #d0d7de;
            --border-lit: #c6ccd2;
            --green:      #1a7f37;
            --green-dim:  #116329;
            --green-mute: #dafbe1;
            --amber:      #9a6700;
            --cyan:       #0550ae;
            --red:        #cf222e;
            --purple:     #6639ba;
            --text:       #1f2328;
            --muted:      #1f2328;
            --dim:        #1f2328;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            min-height: 100vh;
            background: var(--bg);
            color: var(--text);
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 17px;
            line-height: 1.7;
            display: flex;
            flex-direction: column;
        }

        .shell { max-width: 1200px; margin: 0 auto; padding: 28px 32px 48px; flex: 1; }
        header {
            background: var(--surface);
            border-bottom: 1px solid var(--border-lit);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .brand { display: flex; align-items: baseline; gap: 10px; }
        .brand-name { color: var(--green); font-size: 1.1rem; font-weight: 700; letter-spacing: 3px; }
        .brand-sep  { color: var(--muted); }
        .brand-sub  { color: var(--dim); font-size: 0.75rem; letter-spacing: 1px; }
        .header-right { text-align: right; color: var(--dim); font-size: 0.7rem; line-height: 2; }
        .header-right .val { color: var(--green-dim); }

        /* TABS */
        .tabs {
            display: flex;
            border-bottom: 1px solid var(--border-lit);
            background: var(--surface);
            padding: 0 32px;
        }
        .tab {
            padding: 10px 20px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            color: var(--muted);
            border-bottom: 2px solid transparent;
            background: none;
            border-top: none;
            border-left: none;
            border-right: none;
            transition: color 0.15s, border-color 0.15s;
        }
        .tab:hover { color: var(--text); }
        .tab.active { color: var(--green); border-bottom-color: var(--green); }

        /* PROMPT */
        .prompt-line {
            color: var(--dim);
            font-size: 0.82rem;
            margin-bottom: 22px;
        }
        .p-user { color: var(--green); }
        .p-host { color: var(--cyan); }
        .cursor {
            display: inline-block;
            width: 8px; height: 13px;
            background: var(--green);
            vertical-align: middle;
            margin-left: 4px;
            animation: blink 1.1s step-end infinite;
        }
        @keyframes blink { 50% { opacity: 0; } }

        /* PANELS */
        .panel {
            background: var(--surface);
            border: 1px solid var(--border-lit);
            margin-bottom: 20px;
        }
        .panel-title {
            padding: 8px 16px;
            background: var(--border);
            border-bottom: 1px solid var(--border-lit);
            color: var(--green);
            font-size: 0.68rem;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .panel-title::before { content: '> '; color: var(--green-mute); }
        .panel-title.compare-title { color: var(--purple); }
        .panel-title.compare-title::before { color: #3a1a5a; }

        /* DROPDOWNS */
        .dropdowns {
            padding: 18px 16px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }
        @media (max-width: 780px) { .dropdowns { grid-template-columns: repeat(2, 1fr); } }

        .dd-group label {
            display: block;
            color: var(--muted);
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 7px;
        }
        .dd-group label::before { content: '// '; color: var(--green-mute); }

        select {
            width: 100%;
            background: var(--surface);
            color: var(--green);
            border: 1px solid var(--border-lit);
            padding: 9px 28px 9px 10px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.9rem;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath fill='%233fb950' d='M5 6L0 0h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            transition: border-color 0.15s;
        }
        select:focus { outline: none; border-color: var(--green); box-shadow: 0 0 0 1px var(--green-mute); }
        select option { background: var(--surface); color: var(--green); }

        /* compare dropdown */
        .compare-select { color: var(--purple) !important; }
        .compare-select:focus { border-color: var(--purple) !important; box-shadow: 0 0 0 1px #3a1a5a !important; }
        .compare-select option { color: var(--purple); }
        .compare-dd label::before { color: #3a1a5a !important; }

        /* OUTPUT BAR */
        .output-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .output-label { color: var(--cyan); font-size: 0.68rem; letter-spacing: 3px; text-transform: uppercase; }
        .output-label::before { content: '$ '; color: var(--green-dim); }
        .row-count { color: var(--dim); font-size: 0.8rem; }
        .row-count .n { color: var(--amber); }

        /* BROWSE TABLE */
        .table-wrap { border: 1px solid var(--border-lit); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: var(--border); border-bottom: 1px solid var(--border-lit); }
        thead th {
            padding: 10px 14px;
            text-align: left;
            color: var(--amber);
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        thead th::before { content: '#'; color: var(--green-mute); margin-right: 3px; }
        tbody tr { border-bottom: 1px solid var(--border); transition: background 0.08s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--surface); }
        td { padding: 10px 14px; font-size: 0.9rem; vertical-align: top; }
        .f-name   { color: var(--green); font-weight: 700; }
        .f-symbol { color: var(--amber); font-weight: 700; }
        .f-cat    { color: var(--cyan); font-size: 0.85rem; }
        .f-lang   { color: var(--amber); font-size: 0.85rem; }
        .f-kind   { color: var(--dim); font-size: 0.82rem; font-style: italic; }
        .f-code   {
            color: var(--green-dim);
            font-size: 0.82rem;
            background: var(--bg);
            padding: 2px 6px;
            border: 1px solid var(--border-lit);
            display: inline-block;
            max-width: 260px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .f-null { color: var(--dim); font-style: italic; }

        /* COMPARE CARDS GRID */
        .compare-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            padding: 16px;
        }

        .lang-card {
            background: var(--bg);
            border: 1px solid var(--border-lit);
            padding: 14px 16px;
        }

        .card-lang {
            color: var(--amber);
            font-size: 0.8rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border);
        }
        .card-lang::before { content: '> '; color: var(--green-mute); }

        .card-row { margin-bottom: 8px; }
        .card-label {
            color: var(--muted);
            font-size: 0.72rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .card-value { color: var(--text); font-size: 0.88rem; }
        .card-code {
            color: var(--green-dim);
            font-size: 0.85rem;
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 6px 8px;
            display: block;
            white-space: pre-wrap;
            word-break: break-all;
        }
        .card-example { color: var(--cyan); }
        .card-result  { color: var(--purple); font-size: 0.85rem; }
        .card-notes   { color: var(--muted); font-size: 0.82rem; font-style: italic; }

        .compare-empty {
            padding: 36px;
            text-align: center;
            color: var(--muted);
            font-size: 0.9rem;
        }
        .compare-empty span { color: var(--purple); }

        /* NO RESULTS */
        #no-results {
            display: none;
            padding: 36px;
            text-align: center;
            color: var(--muted);
            border: 1px solid var(--border-lit);
            font-size: 0.9rem;
        }
        #no-results::before { content: 'ERR: '; color: var(--red); }

        /* LIGHT MODE TOGGLE */
        #theme-toggle {
            background: none;
            border: 1px solid var(--border-lit);
            color: var(--muted);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            letter-spacing: 1px;
            padding: 4px 10px;
            cursor: pointer;
            transition: color 0.15s, border-color 0.15s;
        }
        #theme-toggle:hover { color: var(--text); border-color: var(--green); }

        /* SECTION VISIBILITY */
        .view-section { display: none; }
        .view-section.active { display: block; }

        /* FOOTER */
        footer {
            border-top: 1px solid var(--border);
            padding: 14px 32px;
            color: var(--dim);
            font-size: 0.75rem;
            letter-spacing: 1px;
            display: flex;
            justify-content: space-between;
        }
        footer .val { color: var(--green-dim); }
    </style>
</head>
<body>

<header>
    <div class="brand">
        <span class="brand-name">Programming Language Lookup</span>
        <span class="brand-sep">//</span>
        <span class="brand-sub">programming language reference database</span>
    </div>
    <button id="theme-toggle" onclick="document.body.classList.toggle('light')">[ light mode ]</button>
</header>

<!-- TABS -->
<div class="tabs">
    <button class="tab active" onclick="switchTab('compare')">// compare</button>
    <button class="tab" onclick="switchTab('browse')">// browse</button>
</div>

<div class="shell">

    <div class="prompt-line">
        <span class="p-user">user</span>@<span class="p-host">Programming Language Lookup</span>:~$
        SELECT * FROM database --interactive
    </div>

    <!--COMPARE TAB-->
    <div id="section-compare" class="view-section active">

        <div class="panel">
            <div class="panel-title compare-title">comparison parameters</div>
            <div class="dropdowns">

                <div class="dd-group compare-dd">
                    <label>data type</label>
                    <select id="cmp-datatype" class="compare-select">
                        <option value="functions">functions</option>
                        <option value="operators">operators</option>
                        <option value="structures">data structures</option>
                    </select>
                </div>

                <div class="dd-group compare-dd">
                    <label>item</label>
                    <select id="cmp-item" class="compare-select">
                        <option value="">select an item</option>
                    </select>
                </div>

                <div class="dd-group compare-dd">
                    <label>language A</label>
                    <select id="cmp-lang-a" class="compare-select">
                        <option value="">select language</option>
                        <?php foreach ($languages as $lang): ?>
                            <option value="<?= (int)$lang['language_id'] ?>">
                                <?= htmlspecialchars($lang['language_name'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="dd-group compare-dd">
                    <label>language B</label>
                    <select id="cmp-lang-b" class="compare-select">
                        <option value="">select language</option>
                        <?php foreach ($languages as $lang): ?>
                            <option value="<?= (int)$lang['language_id'] ?>">
                                <?= htmlspecialchars($lang['language_name'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>
        </div>

        <div class="output-bar">
            <div class="output-label">comparison output</div>
            <div class="row-count">languages found: <span class="n" id="cmp-count">0</span></div>
        </div>

        <div id="cmp-output">
            <div class="compare-empty">select a <span>data type</span> and <span>item</span> to compare across languages</div>
        </div>

    </div>
        <!-- BROWSE TAB-->
    <div id="section-browse" class="view-section">

        <div class="panel">
            <div class="panel-title">query parameters</div>
            <div class="dropdowns">

                <div class="dd-group">
                    <label>language</label>
                    <select id="dd-language">
                        <option value="">all languages</option>
                        <?php foreach ($languages as $lang): ?>
                            <option value="<?= (int)$lang['language_id'] ?>">
                                <?= htmlspecialchars($lang['language_name'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="dd-group">
                    <label>data type</label>
                    <select id="dd-datatype">
                        <option value="all">all data</option>
                        <option value="functions">functions</option>
                        <option value="operators">operators</option>
                        <option value="structures">data structures</option>
                    </select>
                </div>

                <div class="dd-group">
                    <label>category</label>
                    <select id="dd-category">
                        <option value="">all categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="dd-group">
                    <label>sort by</label>
                    <select id="dd-sort">
                        <option value="name_asc">name A &rarr; Z</option>
                        <option value="name_desc">name Z &rarr; A</option>
                        <option value="lang_asc">language A &rarr; Z</option>
                        <option value="cat_asc">category A &rarr; Z</option>
                    </select>
                </div>

            </div>
        </div>

        <div class="output-bar">
            <div class="output-label">output</div>
            <div class="row-count">rows returned: <span class="n" id="row-count">0</span></div>
        </div>

        <div class="table-wrap" id="table-wrap">
            <table>
                <thead id="tbl-head"></thead>
                <tbody id="tbl-body"></tbody>
            </table>
        </div>
        <div id="no-results">no records match selected parameters</div>

    </div>


</div>

<footer>
    <div>CSC 436 // <span class="val">Group Project</span> // mbarbrack.rhody.dev</div>
    <div>access: <span class="val">READ-ONLY</span> // SELECT only</div>
    <div>access: <span class="val">READ-ONLY</span> // SELECT only &nbsp;|&nbsp; <a href="admin/login.php" style="color:var(--amber); text-decoration:none; letter-spacing:1px;">// admin</a></div>

</footer>

<script>
const LANGUAGES  = <?= json_encode($languages)  ?>;
const FUNCTIONS  = <?= json_encode($functions)  ?>;
const OPERATORS  = <?= json_encode($operators)  ?>;
const STRUCTURES = <?= json_encode($structures) ?>;

const LANG_MAP = {};
LANGUAGES.forEach(l => { LANG_MAP[String(l.language_id)] = l.language_name; });

//  TAB SWITCHING 
function switchTab(tab) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.view-section').forEach(s => s.classList.remove('active'));
    document.querySelector(`[onclick="switchTab('${tab}')"]`).classList.add('active');
    document.getElementById(`section-${tab}`).classList.add('active');
}


// BROWSE

const ddLanguage = document.getElementById('dd-language');
const ddDatatype = document.getElementById('dd-datatype');
const ddCategory = document.getElementById('dd-category');
const ddSort     = document.getElementById('dd-sort');
const tblHead    = document.getElementById('tbl-head');
const tblBody    = document.getElementById('tbl-body');
const noResults  = document.getElementById('no-results');
const tableWrap  = document.getElementById('table-wrap');
const rowCount   = document.getElementById('row-count');

[ddLanguage, ddDatatype, ddCategory, ddSort].forEach(dd => dd.addEventListener('change', renderBrowse));

function langName(id) { return LANG_MAP[String(id)] || 'unknown'; }

function esc(v) {
    if (v === null || v === undefined || v === '')
        return '<span class="f-null">--</span>';
    return String(v).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function renderBrowse() {
    const lFilter   = ddLanguage.value;
    const dtype     = ddDatatype.value;
    const catFilter = ddCategory.value;
    const sort      = ddSort.value;

    let rows = [];

    function collect(data, buildRow) {
        data.forEach(item => {
            if (lFilter   && String(item.language_id) !== lFilter) return;
            if (catFilter && item.category !== catFilter)           return;
            rows.push(buildRow(item));
        });
    }

    if (dtype === 'all' || dtype === 'functions') {
        collect(FUNCTIONS, item => ({
            kind: 'function', name: item.function_name, symbol: null,
            cat: item.category, desc: item.description,
            code: item.syntax, lang: langName(item.language_id)
        }));
    }
    if (dtype === 'all' || dtype === 'operators') {
        collect(OPERATORS, item => ({
            kind: 'operator', name: item.operator_name, symbol: item.symbol,
            cat: item.category, desc: item.description,
            code: null, lang: langName(item.language_id)
        }));
    }
    if (dtype === 'all' || dtype === 'structures') {
        collect(STRUCTURES, item => ({
            kind: 'structure', name: item.structure_name, symbol: null,
            cat: item.category, desc: item.description,
            code: null, lang: langName(item.language_id)
        }));
    }

    rows.sort((a, b) => {
        if (sort === 'name_asc')  return a.name.localeCompare(b.name);
        if (sort === 'name_desc') return b.name.localeCompare(a.name);
        if (sort === 'lang_asc')  return a.lang.localeCompare(b.lang);
        if (sort === 'cat_asc')   return (a.cat||'').localeCompare(b.cat||'');
        return 0;
    });

    rowCount.textContent = rows.length;

    if (rows.length === 0) {
        tableWrap.style.display = 'none';
        noResults.style.display = 'block';
        return;
    }

    tableWrap.style.display = 'block';
    noResults.style.display = 'none';

    const showKind   = dtype === 'all';
    const showSymbol = dtype === 'all' || dtype === 'operators';
    const showCode   = dtype === 'all' || dtype === 'functions';

    let headCols = '';
    if (showKind)   headCols += '<th>type</th>';
    headCols += '<th>name</th>';
    if (showSymbol) headCols += '<th>symbol</th>';
    headCols += '<th>category</th><th>description</th>';
    if (showCode)   headCols += '<th>syntax</th>';
    headCols += '<th>language</th>';
    tblHead.innerHTML = `<tr>${headCols}</tr>`;

    tblBody.innerHTML = rows.map(r => {
        let cols = '';
        if (showKind)   cols += `<td class="f-kind">${esc(r.kind)}</td>`;
        cols += `<td class="f-name">${esc(r.name)}</td>`;
        if (showSymbol) cols += `<td class="f-symbol">${esc(r.symbol)}</td>`;
        cols += `<td class="f-cat">${esc(r.cat)}</td><td>${esc(r.desc)}</td>`;
        if (showCode)   cols += r.code
            ? `<td><span class="f-code">${esc(r.code)}</span></td>`
            : `<td><span class="f-null">--</span></td>`;
        cols += `<td class="f-lang">${esc(r.lang)}</td>`;
        return `<tr>${cols}</tr>`;
    }).join('');
}


// COMPARE

const cmpDatatype = document.getElementById('cmp-datatype');
const cmpItem     = document.getElementById('cmp-item');
const cmpLangA    = document.getElementById('cmp-lang-a');
const cmpLangB    = document.getElementById('cmp-lang-b');
const cmpOutput   = document.getElementById('cmp-output');
const cmpCount    = document.getElementById('cmp-count');

// Populate item dropdown when data type changes
cmpDatatype.addEventListener('change', function () {
    const dtype = this.value;
    let source = dtype === 'functions' ? FUNCTIONS
               : dtype === 'operators' ? OPERATORS
               : STRUCTURES;

    const seen = new Set();
    const names = [];
    source.forEach(item => {
        const name = item.function_name || item.operator_name || item.structure_name;
        if (!seen.has(name)) { seen.add(name); names.push(name); }
    });
    names.sort();

    cmpItem.innerHTML = '<option value="">select an item</option>'
        + names.map(n => `<option value="${esc(n)}">${esc(n)}</option>`).join('');

    renderCompare();
});

[cmpItem, cmpLangA, cmpLangB].forEach(dd => dd.addEventListener('change', renderCompare));

function renderCompare() {
    const dtype    = cmpDatatype.value;
    const itemName = cmpItem.value;
    const langA    = cmpLangA.value;
    const langB    = cmpLangB.value;

    if (!itemName || !langA || !langB) {
        cmpCount.textContent = '0';
        cmpOutput.innerHTML = '<div class="compare-empty">select a <span>data type</span>, <span>item</span>, and <span>two languages</span> to compare</div>';
        return;
    }

    if (langA === langB) {
        cmpOutput.innerHTML = '<div class="compare-empty"><span>select two different languages</span> to compare</div>';
        return;
    }

    let source = dtype === 'functions' ? FUNCTIONS
               : dtype === 'operators' ? OPERATORS
               : STRUCTURES;

    // Find one entry per selected language for this item
    const results = {};
    source.forEach(item => {
        const name = item.function_name || item.operator_name || item.structure_name;
        const lid  = String(item.language_id);
        if (name === itemName && (lid === langA || lid === langB) && !results[lid]) {
            results[lid] = item;
        }
    });

    const entries = [langA, langB].map(lid => ({
        data: results[lid] || null,
        langId: lid
    }));

    cmpCount.textContent = 2;

    if (entries.length === 0) {
        cmpOutput.innerHTML = '<div class="compare-empty">no implementations found for this combination</div>';
        return;
    }

    const firstItem = entries[0];
    const desc      = firstItem.description;
    const category  = firstItem.category;

    let html = `<div style="padding:12px 16px 4px; color:var(--dim); font-size:0.82rem;">
        <span style="color:var(--cyan)">${esc(itemName)}</span>
        ${category ? `&nbsp;//&nbsp;<span style="color:var(--amber)">${esc(category)}</span>` : ''}
        ${desc     ? `&nbsp;&mdash;&nbsp;${esc(desc)}` : ''}
    </div>`;

    html += '<div class="compare-grid">';

    entries.forEach(({ data, langId }) => {
        const lname = langName(langId);
        html += `<div class="lang-card">
            <div class="card-lang">${esc(lname)}</div>`;

        if (!data) {
            html += `<div class="card-row">
                <div style="color:var(--muted); font-size:0.85rem; font-style:italic;">
                    no implementation found for ${esc(lname)}
                </div>
            </div>`;
        } else {
            if (data.syntax) {
                html += `<div class="card-row">
                    <div class="card-label">syntax</div>
                    <code class="card-code">${esc(data.syntax)}</code>
                </div>`;
            }
            if (data.example) {
                html += `<div class="card-row">
                    <div class="card-label">example</div>
                    <code class="card-code card-example">${esc(data.example)}</code>
                </div>`;
            }
            if (data.result) {
                html += `<div class="card-row">
                    <div class="card-label">output</div>
                    <div class="card-result">${esc(data.result)}</div>
                </div>`;
            }
            if (data.notes) {
                html += `<div class="card-row">
                    <div class="card-label">notes</div>
                    <div class="card-notes">${esc(data.notes)}</div>
                </div>`;
            }
        }

        html += '</div>';
    });
    html += '</div>';
    cmpOutput.innerHTML = html;
}

// Populate item dropdown on load
cmpDatatype.dispatchEvent(new Event('change'));

// Initial browse render
renderBrowse();
</script>
</body>
</html>