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
    <title>Admin // Programming Language Lookup</title>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:         #0d1117;
            --surface:    #161b22;
            --border-lit: #363d47;
            --green:      #3fb950;
            --green-dim:  #2ea043;
            --green-mute: #1a4a25;
            --amber:      #e3b341;
            --red:        #f85149;
            --text:       #e6edf3;
            --muted:      #8b949e;
            --dim:        #6e7681;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            background: var(--bg);
            color: var(--text);
            font-family: 'JetBrains Mono', monospace;
            font-size: 17px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrap {
            width: 100%;
            max-width: 400px;
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
            color: var(--dim);
            font-size: 0.75rem;
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
            background: #1f2937;
            border-bottom: 1px solid var(--border-lit);
            color: var(--green);
            font-size: 0.75rem;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .panel-title::before { content: '> '; color: var(--green-mute); }

        .panel-body { padding: 20px 18px; }

        .field { margin-bottom: 16px; }

        label {
            display: block;
            color: var(--muted);
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 7px;
        }
        label::before { content: '// '; color: var(--green-mute); }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            background: var(--bg);
            color: var(--green);
            border: 1px solid var(--border-lit);
            padding: 9px 12px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.9rem;
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
            font-size: 0.9rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.15s;
            margin-top: 4px;
        }
        .btn:hover { background: #1a5c2a; }

        .error {
            background: #3d0000;
            border: 1px solid var(--red);
            color: var(--red);
            padding: 8px 12px;
            font-size: 0.88rem;
            margin-bottom: 16px;
        }
        .error::before { content: 'ERR: '; }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 14px;
            color: var(--dim);
            font-size: 0.82rem;
            text-decoration: none;
            letter-spacing: 1px;
        }
        .back-link:hover { color: var(--text); }
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="login-header">
        <div class="brand">Programming Language Lookup</div>
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