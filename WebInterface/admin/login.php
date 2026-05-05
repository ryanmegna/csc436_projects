<?php
session_start();


// Redirect if already logged in
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

// Load env credentials
$env = parse_ini_file(__DIR__ . '/../.env');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === $env['ADMIN_USERNAME'] && $password === $env['ADMIN_PASSWORD']) {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user']      = $username;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid credentials.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin // LangRef</title>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:         #000000;
            --surface:    #080808;
            --border-lit: #252525;
            --green:      #00ff88;
            --green-dim:  #00cc6a;
            --green-mute: #003d20;
            --amber:      #ffb800;
            --red:        #ff4444;
            --text:       #c8c8c8;
            --muted:      #3a3a3a;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            background: var(--bg);
            color: var(--text);
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrap {
            width: 100%;
            max-width: 380px;
            padding: 20px;
        }

        .login-header {
            margin-bottom: 28px;
        }

        .login-header .brand {
            color: var(--green);
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 3px;
        }

        .login-header .sub {
            color: var(--muted);
            font-size: 0.7rem;
            letter-spacing: 2px;
            margin-top: 4px;
        }

        .login-header .sub::before { content: '// '; color: var(--green-mute); }

        .panel {
            background: var(--surface);
            border: 1px solid var(--border-lit);
        }

        .panel-title {
            padding: 8px 16px;
            background: #050505;
            border-bottom: 1px solid var(--border-lit);
            color: var(--green);
            font-size: 0.68rem;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .panel-title::before { content: '> '; color: var(--green-mute); }

        .panel-body { padding: 20px 18px; }

        .field { margin-bottom: 16px; }

        label {
            display: block;
            color: var(--muted);
            font-size: 0.67rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 7px;
        }
        label::before { content: '// '; color: var(--green-mute); }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            background: #030303;
            color: var(--green);
            border: 1px solid var(--border-lit);
            padding: 9px 12px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.82rem;
            transition: border-color 0.15s;
        }

        input:focus {
            outline: none;
            border-color: var(--green);
            box-shadow: 0 0 0 1px var(--green-mute);
        }

        .btn {
            width: 100%;
            padding: 10px;
            background: var(--green-mute);
            color: var(--green);
            border: 1px solid var(--green-dim);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.15s;
            margin-top: 4px;
        }
        .btn:hover { background: #005a30; }

        .error {
            background: #1a0000;
            border: 1px solid var(--red);
            color: var(--red);
            padding: 8px 12px;
            font-size: 0.78rem;
            margin-bottom: 16px;
        }
        .error::before { content: 'ERR: '; }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 14px;
            color: var(--muted);
            font-size: 0.72rem;
            text-decoration: none;
            letter-spacing: 1px;
        }
        .back-link:hover { color: var(--text); }
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="login-header">
        <div class="brand">LANGREF</div>
        <div class="sub">admin access</div>
    </div>

    <div class="panel">
        <div class="panel-title">authenticate</div>
        <div class="panel-body">
            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="field">
                    <label>username</label>
                    <input type="text" name="username" maxlength="100" autocomplete="off" required>
                </div>
                <div class="field">
                    <label>password</label>
                    <input type="password" name="password" maxlength="100" required>
                </div>
                <button type="submit" class="btn">// login</button>
            </form>
        </div>
    </div>

    <a class="back-link" href="../index.php">&larr; back to public site</a>
</div>
</body>
</html>